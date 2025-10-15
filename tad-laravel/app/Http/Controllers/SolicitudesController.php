<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
    $expediente = $this->generarExpediente($tramite);

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

        foreach (($data['sections'] ?? []) as &$sec) {
            foreach (($sec['fields'] ?? []) as &$f) {
                if (($f['type'] ?? null) !== 'file' || empty($f['value'])) continue;

                $vals = is_array($f['value']) ? $f['value'] : [$f['value']];
                $new  = [];

                foreach ($vals as $v) {
                    if (!empty($v['tmp']) && !empty($v['path'])) {
                        $newPath = "solicitudes/{$solicitud->id}/".basename($v['path']);
                        \Storage::disk('public')->move($v['path'], $newPath);
                        $v['path'] = $newPath;
                        $v['url']  = \Storage::disk('public')->url($newPath);
                        unset($v['tmp']);
                        $changed = true;
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

        foreach (($schema['sections'] ?? []) as $si => &$sec) {
            foreach (($sec['fields'] ?? []) as $fi => &$f) {
                $name       = $f['_name'] ?? ($f['name'] ?? "s{$si}_f{$fi}");
                $f['value'] = $posted[$name] ?? ($f['value'] ?? null);
            }
        }
        unset($sec, $f);

        $solicitud->datos = $schema; // gracias al cast, se guarda JSON
        $solicitud->save();

        return back()->with('success', 'Datos guardados.');
    }

    /**
     * Genera un código de expediente simple: TRAM-{id}-{Ymd}-{seq}
     * (Si luego querés, lo reemplazamos por tu lógica/tabla de folios).
     */
    protected function generarExpediente(Tramite $tramite): string
    {
        $prefix = 'TRAM-' . $tramite->id . '-' . now()->format('Ymd');
        $countHoy = Solicitud::where('tramite_id', $tramite->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

        $seq = str_pad((string)($countHoy + 1), 3, '0', STR_PAD_LEFT);
        return $prefix . '-' . $seq;
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
