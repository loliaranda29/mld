<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Usuario;

class SolicitudesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // ciudadano logueado
    }

    /**
     * Listado de solicitudes del ciudadano autenticado.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $q = Solicitud::with('tramite')
            ->where('usuario_id', $user->id)
            ->latest();

        if ($search = $request->input('search')) {
            $q->where('expediente', 'like', "%{$search}%");
        }

        $solicitudes = $q->paginate(12)->withQueryString();

        return view('pages.profile.ciudadano.solicitudes', [
            'active' => 'tramites',
            'solicitudes' => $solicitudes,
        ]);
    }

    /**
     * Crea una solicitud nueva para un trámite (plantilla).
     * POST /profile/solicitudes/{tramite}
     */
   public function store(Request $request)
{
    $user    = auth()->user();
    $tramite = Tramite::findOrFail($request->input('tramite_id'));

    if (!$tramite->acepta_solicitudes || !$tramite->disponible) {
        return back()->with('error', 'Este trámite no acepta solicitudes actualmente.');
    }

    // Schema base
    $schema = is_array($tramite->formulario_json)
        ? $tramite->formulario_json
        : (json_decode($tramite->formulario_json ?? '[]', true) ?: ['sections' => []]);

    // 1) Lo que haya en form[] (último paso)
    $posted = $request->input('form', []);

    // 2) Merge con answers_json (tiene TODOS los pasos del wizard)
    if ($request->filled('answers_json')) {
        try {
            $fromJson = json_decode($request->input('answers_json'), true) ?: [];
            if (is_array($fromJson)) {
                // Lo del DOM tiene prioridad
                $posted = array_merge($fromJson, $posted);
            }
        } catch (\Throwable $e) {
            // noop
        }
    }

    // 3) Archivos: aceptar tanto files[...] (vista nueva) como form[...]
    $allFiles    = $request->allFiles();
    $filesByName = $allFiles['files'] ?? ($allFiles['form'] ?? []);

    // 4) Volcar valores/archivos dentro del schema (en ->datos)
    $respuestas = [];
        foreach (($schema['sections'] ?? []) as $si => &$sec) {
        foreach (($sec['fields'] ?? []) as $fi => &$f) {
            $name     = $f['_name'] ?? ($f['name'] ?? "s{$si}_f{$fi}");
            $type     = strtolower($f['type'] ?? 'text');
            $multiple = !empty($f['multiple']);

            if ($type === 'file') {
                $uploads = $filesByName[$name] ?? null;
                $stored  = [];

                if ($uploads) {
                    if ($multiple && is_array($uploads)) {
                        foreach ($uploads as $u) {
                            if ($u) $stored[] = $this->storeSolicitudFileTemp($u);
                        }
                    } else {
                        $stored[] = $this->storeSolicitudFileTemp($uploads);
                    }
                }
                $f['value'] = $multiple ? $stored : ($stored[0] ?? null);
            } else {
                // Cualquier tipo no-file
                $f['value'] = $posted[$name] ?? null;
            }

            // Acumular respuestas simples en un hash plano
            $respuestas[$name] = $f['value'] ?? null;
        }
    }
    unset($sec, $f);

    // 5) Generar expediente y guardar
  $user       = $request->user();               // devolverá un App\Models\Usuario
$expediente = $this->generarExpediente($user);

    $solicitud = Solicitud::create([
        'tramite_id' => $tramite->id,
        'usuario_id' => $user->id,
        'expediente' => $expediente,
        'estado'     => 'iniciado',
        'datos'      => $schema, // <-- con los values ya volcados
        // Si existe la columna en la DB, guardamos también el hash plano
        'respuestas_json' => $respuestas,
    ]);

    // 6) Mover archivos de tmp => definitivos y actualizar URLs
    $this->finalizarArchivosEnSchema($solicitud);

    return redirect()
        ->route('profile.solicitudes.show', $solicitud->id)
        ->with('success', 'Tu solicitud fue creada correctamente.');
}



    /**
     * Guarda archivo temporalmente (aún sin id de solicitud).
     */
    protected function storeSolicitudFileTemp(\Illuminate\Http\UploadedFile $file): array
    {
        $path = $file->store("solicitudes/tmp/".auth()->id(), 'public');

        return [
            'tmp'   => true,
            'path'  => $path,
            'url'   => \Storage::disk('public')->url($path),
            'name'  => $file->getClientOriginalName(),
            'size'  => $file->getSize(),
            'mime'  => $file->getClientMimeType(),
        ];
    }

    /**
     * Recorre el JSON, mueve archivos tmp => definitivos en /solicitudes/{id}
     * y actualiza paths/urls.
     */
    protected function finalizarArchivosEnSchema(Solicitud $solicitud): void
    {
        $data = is_array($solicitud->datos) ? $solicitud->datos : json_decode($solicitud->datos ?? '[]', true);
        $changed = false;
        $destDir = 'solicitudes/' . $solicitud->id;

        // Asegurar el directorio destino para evitar fallos de move()
        try { \Storage::disk('public')->makeDirectory($destDir); } catch (\Throwable $e) { /* noop */ }

        foreach (($data['sections'] ?? []) as &$sec) {
            foreach (($sec['fields'] ?? []) as &$f) {
                if (($f['type'] ?? null) !== 'file' || empty($f['value'])) continue;

                $vals = is_array($f['value']) ? $f['value'] : [$f['value']];
                $new  = [];

                foreach ($vals as $v) {
                    if (!empty($v['tmp']) && !empty($v['path'])) {
                        $newPath = $destDir . '/' . basename($v['path']);
                        try {
                            // intentar mover, si falla, dejar el tmp pero mantener visible
                            \Storage::disk('public')->move($v['path'], $newPath);
                            $v['path'] = $newPath;
                            $v['url']  = \Storage::disk('public')->url($newPath);
                            unset($v['tmp']);
                            $changed = true;
                        } catch (\Throwable $e) {
                            // Si no se pudo mover, al menos exponer URL temporal
                            try { $v['url'] = $v['url'] ?? \Storage::disk('public')->url($v['path']); } catch (\Throwable $ee) { /* noop */ }
                        }
                    }
                    $new[] = $v;
                }

                $f['value'] = is_array($f['value']) ? $new : ($new[0] ?? null);
            }
        }
        unset($sec, $f);

        if ($changed) {
            $solicitud->datos = $data;
            $solicitud->save();
        }
    }

    /**
     * Detalle de una solicitud del ciudadano (valida pertenencia).
     */
    public function show($id)
{
    $user = auth()->user();

    $solicitud = Solicitud::with('tramite')
        ->where('usuario_id', $user->id)
        ->findOrFail($id);

    // el detalle necesita el schema para pintar secciones/valores
    $schema = is_array($solicitud->datos)
        ? $solicitud->datos
        : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections' => []]);

    // Hidratar con respuestas_json si faltan values en el schema
    $answers = is_array($solicitud->respuestas_json ?? null)
        ? $solicitud->respuestas_json
        : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
    if (!empty($answers) && isset($schema['sections']) && is_array($schema['sections'])) {
        foreach ($schema['sections'] as $si => $sec) {
            foreach (($sec['fields'] ?? []) as $fi => $f) {
                $name = $f['_name'] ?? ($f['name'] ?? null);
                if (!$name) continue;
                $curr = $f['value'] ?? null;
                if ((($curr === null) || ($curr === '') || (is_array($curr) && !count($curr))) && array_key_exists($name, $answers)) {
                    $ansVal = $answers[$name];
                    $type   = strtolower($f['type'] ?? 'text');
                    // Evitar pisar FILE con el placeholder textual de answers_json
                    if ($type === 'file' && !is_array($ansVal)) {
                        continue;
                    }
                    $schema['sections'][$si]['fields'][$fi]['value'] = $ansVal;
                }
            }
        }
    }

    // 👇 Renderizar la vista de **detalle** (lectura), NO la del wizard
    return view()->first([
        'pages.profile.ciudadano.solicitudes.show',          // principal (lectura)
        'pages.profile.ciudadano.details.solicitud_show',    // fallback si la tenés
    ], compact('solicitud', 'schema'));
}

    public function update(Request $request, $id)
    {
        $solicitud = Solicitud::where('usuario_id', auth()->id())->findOrFail($id);

        $schema  = is_array($solicitud->datos)
            ? $solicitud->datos
            : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections' => []]);

        $posted = $request->input('form', []);
        $allFiles    = $request->allFiles();
        $filesByName = $allFiles['files'] ?? ($allFiles['form'] ?? []);

        foreach (($schema['sections'] ?? []) as $si => &$sec) {
            foreach (($sec['fields'] ?? []) as $fi => &$f) {
                $name       = $f['_name'] ?? ($f['name'] ?? "s{$si}_f{$fi}");
                $type       = strtolower($f['type'] ?? 'text');
                $multiple   = !empty($f['multiple']);

                if ($type === 'file') {
                    $uploads = $filesByName[$name] ?? null;
                    $stored  = [];
                    if ($uploads) {
                        if ($multiple && is_array($uploads)) {
                            foreach ($uploads as $u) { if ($u) $stored[] = $this->storeSolicitudFileTemp($u); }
                        } else {
                            $stored[] = $this->storeSolicitudFileTemp($uploads);
                        }
                        $f['value'] = $multiple ? $stored : ($stored[0] ?? null);
                    } else {
                        // si no se sube nada, mantener el valor actual
                        $f['value'] = $f['value'] ?? null;
                    }
                } else {
                    $f['value'] = $posted[$name] ?? ($f['value'] ?? null);
                }
            }
        }
        unset($sec, $f);

        $solicitud->datos = $schema; // gracias al cast, se guarda JSON
        $solicitud->save();

        return back()->with('success', 'Datos guardados.');
    }

    // Responder observaciones: vuelve a revisión para el funcionario
    public function responderObservaciones(Request $request, $id)
    {
        $solicitud = Solicitud::where('usuario_id', auth()->id())->findOrFail($id);

        $solicitud->estado = 'en_revision';

        $meta = is_array($solicitud->respuestas_json)
            ? $solicitud->respuestas_json
            : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);

        $wf = (array)($meta['_wf'] ?? []);
        $hist = (array)($wf['history'] ?? []);
        $hist[] = [
            'at' => now()->toDateTimeString(),
            'by' => auth()->id(),
            'action' => 'responder',
        ];
        $wf['history'] = $hist;
        $meta['_wf'] = $wf;

        $solicitud->respuestas_json = $meta;
        $solicitud->save();

        return redirect()->route('profile.solicitudes.show', $solicitud->id)
            ->with('success', 'Observaciones respondidas. En revisión.');
    }

  /**
 * EXP-(aleatorio + últimos 3 del CUIL/CUIT)-(mes/año).
 * Solo acepta CUIL/CUIT de 11 dígitos. Si no lo encuentra ⇒ '000'.
 */
private function generarExpediente(Usuario $user): string
{
    $rand    = random_int(10000, 99999);
    $last3   = $this->ultimos3Cuil($user);
    $mesAnio = now()->format('m/Y');

    return "EXP-{$rand}{$last3}-{$mesAnio}";
}

/** Devuelve los últimos 3 dígitos del CUIL/CUIT (11 dígitos) o '000' si no lo encuentra. */
private function ultimos3Cuil(Usuario $user): string
{
    // 1) Claves comunes en el propio modelo
    $candidatos = [
        $user->cuil  ?? null, $user->CUIL  ?? null,
        $user->cuit  ?? null, $user->CUIT  ?? null,
        $user->cuil_cuit ?? null, $user->CUIL_CUIT ?? null,
    ];
    foreach ($candidatos as $v) {
        if (!$v) continue;
        $digits = preg_replace('/\D+/', '', (string)$v);
        if (strlen($digits) === 11) {
            return substr($digits, -3);
        }
    }

    // 2) Atributos “crudos” del modelo (por si viene de casts/alias)
    if ($user instanceof \Illuminate\Database\Eloquent\Model) {
        foreach ($user->getAttributes() as $k => $v) {
            if (!is_scalar($v) || $v === null) continue;
            $digits = preg_replace('/\D+/', '', (string)$v);
            if (strlen($digits) === 11) {
                Log::info('CUIL detectado en atributo', ['attr' => $k]);
                return substr($digits, -3);
            }
        }
    }

    // 3) Búsqueda recursiva en toArray() (incluye relaciones/JSON del usuario)
    $arr = method_exists($user, 'toArray') ? $user->toArray() : [];
    $found = $this->buscar11DigitosRecursivo($arr);
    if ($found) {
        Log::info('CUIL detectado recursivo', ['hint' => 'toArray']);
        return substr($found, -3);
    }

    // 4) Nada: devolvemos '000' (evita romper flujo)
    Log::warning('No se encontró CUIL/CUIT de 11 dígitos para el usuario', ['id' => $user->id ?? null]);
    return '000';
}

/** Devuelve el primer string con exactamente 11 dígitos dentro de un array (recursivo). */
private function buscar11DigitosRecursivo($data): ?string
{
    if (is_array($data)) {
        // priorizar claves evidentes
        foreach (['cuil','CUIL','cuit','CUIT','cuil_cuit','CUIL_CUIT'] as $k) {
            if (array_key_exists($k, $data) && $data[$k]) {
                $digits = preg_replace('/\D+/', '', (string)$data[$k]);
                if (strlen($digits) === 11) return $digits;
            }
        }
        foreach ($data as $v) {
            $res = $this->buscar11DigitosRecursivo($v);
            if ($res) return $res;
        }
        return null;
    }
    if (is_scalar($data) && $data !== null) {
        $digits = preg_replace('/\D+/', '', (string)$data);
        if (strlen($digits) === 11) return $digits;
    }
    return null;
}


    public function create(Tramite $tramite)
    {
        // Tomamos el schema del trámite (formulario_json o como lo guardes)
        $schema = is_array($tramite->formulario_json)
            ? $tramite->formulario_json
            : (json_decode($tramite->formulario_json ?? '[]', true) ?: ['sections' => []]);

        // Render de una vista “nueva solicitud” (sin id y sin DB)
        return view('pages.profile.ciudadano.solicitud_nueva', [
            'active'   => 'tramites',
            'tramite'  => $tramite,
            'schema'   => $schema,
        ]);
    }

    /**
     * Descarga segura de un archivo subido en un campo de tipo file.
     * GET /profile/solicitudes/{id}/archivo/{field}/{index?}
     */
    public function downloadFile($id, string $field, $index = null)
    {
        $user = auth()->user();
        $solicitud = Solicitud::where('usuario_id', $user->id)->findOrFail($id);

        $schema = is_array($solicitud->datos)
            ? $solicitud->datos
            : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections' => []]);

        $entry = null;
        $targetByIndex = ctype_digit((string)$field);
        $fileFieldPos  = 0;
        foreach (($schema['sections'] ?? []) as $sec) {
            foreach (($sec['fields'] ?? []) as $f) {
                if (($f['type'] ?? '') !== 'file') continue;

                $match = false;
                if ($targetByIndex) {
                    $match = ((int)$field === $fileFieldPos);
                } else {
                    $fname = $f['_name'] ?? ($f['name'] ?? '');
                    $match = ($fname === $field);
                }

                if (!$match) { $fileFieldPos++; continue; }

                $val = $f['value'] ?? null;
                if (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val) - 1);
                    if ($isAssoc) {
                        $entry = $val;
                    } else {
                        $i = is_null($index) ? 0 : (int)$index;
                        $entry = $val[$i] ?? null;
                    }
                }
                break 2;
            }
        }

        if (!$entry || !is_array($entry)) abort(404);

        $path = $entry['path'] ?? null;
        if (!$path && !empty($entry['url'])) {
            // Fallback: derivar path desde /storage/{path}
            $pos = strpos($entry['url'], '/storage/');
            if ($pos !== false) {
                $maybe = substr($entry['url'], $pos + 9);
                if ($maybe) $path = $maybe;
            }
        }
        if (!$path) abort(404);

        // Seguridad: el archivo debe pertenecer a la solicitud
        $expectedPrefix = 'solicitudes/' . $solicitud->id . '/';
        if (strpos($path, $expectedPrefix) !== 0) abort(403);

        if (!Storage::disk('public')->exists($path)) abort(404);

        $name = $entry['name'] ?? basename($path);
        $mime = $entry['mime'] ?? (Storage::disk('public')->mimeType($path) ?: 'application/octet-stream');

        return Storage::disk('public')->download($path, $name, [
            'Content-Type' => $mime,
        ]);
    }
}
