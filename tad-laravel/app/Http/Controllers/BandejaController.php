<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;

class BandejaController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->input('search', ''));

        $q = Solicitud::with(['tramite', 'usuario'])->latest();

        if ($search !== '') {
            $q->where(function ($qq) use ($search) {
                $qq->where('expediente', 'like', "%{$search}%")
                   ->orWhereHas('tramite', function ($tq) use ($search) {
                       $tq->where('nombre', 'like', "%{$search}%");
                   })
                   ->orWhereHas('usuario', function ($uq) use ($search) {
                       $uq->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%");
                   });
            });
        }

        $solicitudes = $q->paginate(10);

        return view('pages.profile.funcionario.bandeja', [
            'active'      => 'bandeja',
            'solicitudes' => $solicitudes,
        ]);
    }

   public function show($id)
{
    // Traemos la solicitud con su trámite y (si existe) el usuario
    $solicitud = \App\Models\Solicitud::with(['tramite', 'usuario'])->findOrFail($id);
    $tramite   = $solicitud->tramite;

    // === 1) Obtenemos el schema base del trámite (para labels, tipos, secciones) ===
    // Acepta que venga en string JSON o ya casteado
    $schemaBase = [];
    try {
        if (is_array($tramite?->formulario_json)) {
            $schemaBase = $tramite->formulario_json;
        } else {
            $schemaBase = json_decode($tramite?->formulario_json ?? '[]', true) ?: [];
        }
    } catch (\Throwable $e) {
        $schemaBase = [];
    }
    $sections = $schemaBase['sections'] ?? [];

    // === 2) Valores cargados por el ciudadano ===
    // 'datos' suele ser un map [fieldName => value]; 'respuestas_json' puede venir como apoyo
    $values = is_array($solicitud->datos) ? $solicitud->datos : (json_decode($solicitud->datos ?? '[]', true) ?: []);
    $answers = is_array($solicitud->respuestas_json) ? $solicitud->respuestas_json : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
    // Lo que vino en 'datos' pisa a 'answers' (porque es lo efectivamente guardado)
    $values = array_replace($answers, $values);

    // === 3) Hidratamos el schema con los valores (matcheando por 'name') ===
    foreach ($sections as $si => $sec) {
        foreach (($sec['fields'] ?? []) as $fi => $f) {
            $name = $f['_name'] ?? ($f['name'] ?? null);
            if (!$name) continue;
            if (array_key_exists($name, $values)) {
                $sections[$si]['fields'][$fi]['value'] = $values[$name];
            }
        }
    }

    // === 4) Armamos "documentos" a partir de los fields type=file ===
    $documentos = [];
    foreach ($sections as $sec) {
        foreach (($sec['fields'] ?? []) as $f) {
            if (strtolower($f['type'] ?? '') !== 'file') continue;

            $label = $f['label'] ?? ($f['name'] ?? 'Archivo');
            $val   = $f['value'] ?? null;

            // Normalizamos posibles formas de guardar archivos:
            // - string URL/path
            // - array de strings
            // - objeto {path,url,name} o array de esos
            $items = [];
            if (is_array($val)) {
                // ¿array indexado o asociativo?
                $items = array_keys($val) !== range(0, count($val) - 1) ? [$val] : $val;
            } elseif ($val) {
                $items = [$val];
            }

            $urls = [];
            foreach ($items as $it) {
                // Si es string: intentamos mostrarlo como link si parece URL
                if (!is_array($it)) {
                    $url = (is_string($it) && preg_match('~^https?://~i', $it)) ? $it : null;
                    $name = $url ? (basename(parse_url($url, PHP_URL_PATH)) ?: 'Archivo') : (is_string($it) ? basename($it) : 'Archivo');
                    if (!$url && is_string($it)) {
                        // Si es un path relativo en "public", armamos URL pública
                        try { $url = \Storage::disk('public')->url($it); } catch (\Throwable $e) { $url = null; }
                    }
                    if ($url) $urls[] = compact('name','url');
                    continue;
                }

                // Si es objeto/array con path/url/name
                $url  = $it['url']  ?? null;
                $path = $it['path'] ?? null;
                $name = $it['name'] ?? ($path ? basename($path) : 'Archivo');

                if (!$url && $path) {
                    try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; }
                }
                if ($url) $urls[] = compact('name','url');
            }

            $documentos[] = [
                'label'    => $label,
                'required' => !empty($f['required']),
                'urls'     => $urls,
            ];
        }
    }

    // === 5) Etapas (para el indicador X/N) ===
    $etapas = [];
    try {
        $etapas = json_decode($tramite->etapas_json ?? '[]', true) ?: [];
    } catch (\Throwable $e) {
        $etapas = [];
    }
    $totalEtapas = is_array($etapas) ? count($etapas) : 0;
    $estado = strtolower((string)($solicitud->estado ?? ''));
    // Heurística simple por ahora (si luego agregan etapa_actual/historial, lo uso)
    $map = [
        'iniciada'   => 1, 'iniciado'   => 1,
        'en_proceso' => max(1, min(2, $totalEtapas ?: 2)),
        'en_revision'=> max(1, min(2, $totalEtapas ?: 2)),
        'observado'  => max(1, min(2, $totalEtapas ?: 2)),
        'aprobado'   => max(1, $totalEtapas ?: 1),
        'finalizado' => max(1, $totalEtapas ?: 1),
        'rechazado'  => max(1, $totalEtapas ?: 1),
    ];
    $etapaActual = $map[$estado] ?? 1;

    // Enviamos todo a la vista
    return view('pages.profile.funcionario.bandeja_show', [
        'active'       => 'bandeja',
        'solicitud'    => $solicitud,
        'tramite'      => $tramite,
        'sections'     => $sections,     // schema hidratado con values
        'documentos'   => $documentos,   // lista para el card "Documentos"
        'totalEtapas'  => $totalEtapas,
        'etapaActual'  => $etapaActual,
    ]);
}

}

