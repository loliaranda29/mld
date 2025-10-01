<?php

namespace App\Http\Controllers;

use App\Models\Tramite;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

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

        $posted      = $request->input('form', []);
        $filesByName = $request->file('files', []);

        // Volcamos valores y archivos al schema
        foreach (($schema['sections'] ?? []) as $si => &$sec) {
            foreach (($sec['fields'] ?? []) as $fi => &$f) {
                $name     = $f['name'] ?? "s{$si}_f{$fi}";
                $type     = $f['type'] ?? 'text';
                $multiple = !empty($f['multiple']);

                if ($type === 'file') {
                    $uploads = $filesByName[$name] ?? null;
                    $stored  = [];

                    if ($uploads) {
                        if ($multiple && is_array($uploads)) {
                            foreach ($uploads as $u) {
                                if ($u) $stored[] = $this->storeSolicitudFileTemp($u); // temporal
                            }
                        } else {
                            $stored[] = $this->storeSolicitudFileTemp($uploads);
                        }
                    }
                    $f['value'] = $multiple ? $stored : ($stored[0] ?? null);
                } else {
                    $f['value'] = $posted[$name] ?? null;
                }
            }
        }
        unset($sec, $f);

        // Generamos expediente
        $expediente = $this->generarExpediente($tramite);

        // Creamos la solicitud
        $solicitud = Solicitud::create([
            'tramite_id' => $tramite->id,
            'usuario_id' => $user->id,
            'expediente' => $expediente,
            'estado'     => 'iniciado',
            'datos'      => $schema,
        ]);

        // Movemos archivos del “temp” a carpeta definitiva (y actualizamos las URLs)
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

        $schema = is_array($solicitud->datos)
            ? $solicitud->datos
            : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections' => []]);

        return view('pages.profile.ciudadano.details.solicitud', compact('solicitud','schema'));
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
                $name       = $f['name'] ?? "s{$si}_f{$fi}";
                $f['value'] = $posted[$name] ?? ($f['value'] ?? null);
            }
        }
        unset($sec, $f);

        $solicitud->datos = $schema; // gracias al cast, se guarda JSON
        $solicitud->save();

        // ✅ Redirige al detalle, para cerrar el flujo del wizard
        return redirect()
            ->route('profile.solicitudes.show', $solicitud->id)
            ->with('success', 'Datos guardados.');
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

    public function create(\App\Models\Tramite $tramite)
    {
        // 1) Traigo el JSON del builder
        $raw = $tramite->formulario_json;

        // 2) Lo decodifico con tolerancia (puede venir array o string)
        if (is_string($raw)) {
            $data = json_decode($raw, true);
        } elseif (is_array($raw)) {
            $data = $raw;
        } else {
            $data = [];
        }

        // 3) Obtengo las secciones; si no hay, dejo array vacío
        $sections = is_array($data['sections'] ?? null) ? $data['sections'] : [];

        return view('pages.profile.ciudadano.solicitud_nueva', [
            'tramite'  => $tramite,
            'sections' => $sections,
        ]);
    }
}
