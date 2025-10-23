<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Storage;

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

        // 1) Preferir el schema guardado en la solicitud (ya con values y archivos)
        $schema = is_array($solicitud->datos)
            ? $solicitud->datos
            : (json_decode($solicitud->datos ?? '[]', true) ?: []);
        $sections = is_array($schema['sections'] ?? null) ? $schema['sections'] : [];

        // Fallback: si no hay sections en datos, usar el formulario del trámite e hidratar SOLO campos NO file
        if (!$sections) {
            $base = is_array($tramite?->formulario_json)
                ? $tramite->formulario_json
                : (json_decode($tramite?->formulario_json ?? '[]', true) ?: []);
            $sections = $base['sections'] ?? [];
            $answers = is_array($solicitud->respuestas_json)
                ? $solicitud->respuestas_json
                : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
            foreach ($sections as $si => $sec) {
                foreach (($sec['fields'] ?? []) as $fi => $f) {
                    $name = $f['_name'] ?? ($f['name'] ?? null);
                    if (!$name) continue;
                    $type = strtolower($f['type'] ?? 'text');
                    if ($type === 'file') continue; // no pisar archivos con placeholders
                    if (array_key_exists($name, $answers)) {
                        $sections[$si]['fields'][$fi]['value'] = $answers[$name];
                    }
                }
            }
        }

        // 2) Documentos planos con fallback de descarga protegida
        $documentos = [];
        $fileFieldPos = 0;
        foreach ($sections as $sec) {
            foreach (($sec['fields'] ?? []) as $f) {
                if (strtolower($f['type'] ?? '') !== 'file') continue;

                $campo = $f['_name'] ?? ($f['name'] ?? 'archivo');
                $val   = $f['value'] ?? null;

                $items = [];
                if (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val) - 1);
                    $items = $isAssoc ? [$val] : $val;
                } elseif ($val) {
                    $items = [$val];
                }

                $idx = 0;
                foreach ($items as $it) {
                    $name = 'Archivo'; $mime = null; $url = null; $path = null;

                    if (is_array($it)) {
                        $name = $it['name'] ?? $name;
                        $mime = $it['mime'] ?? null;
                        $url  = $it['url']  ?? null;
                        $path = $it['path'] ?? null;
                    } elseif (is_string($it)) {
                        $path = $it;
                        if (preg_match('~^https?://~i', $it)) $url = $it;
                    }

                    if (!$url && $path) {
                        try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; }
                    }
                    if (!$name && $path) $name = basename($path);

                    // Fallback: ruta segura si no hay URL pública
                    if (!$url) {
                        try {
                            $url = route('funcionario.bandeja.file', [$solicitud->id, $campo ?: (string)$fileFieldPos, $idx]);
                        } catch (\Throwable $e) {
                            $url = null;
                        }
                    }

                    $documentos[] = compact('campo','name','mime','url');
                    $idx++;
                }
                $fileFieldPos++;
            }
        }

        // 2.b) Fallback a respuestas_json si no se detectaron en sections/value
        if (empty($documentos)) {
            $answers = is_array($solicitud->respuestas_json)
                ? $solicitud->respuestas_json
                : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
            foreach ($answers as $campo => $val) {
                $items = [];
                if (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val) - 1);
                    $items = $isAssoc ? [$val] : $val;
                }
                foreach ($items as $ix => $it) {
                    if (!is_array($it)) continue;
                    $name = $it['name'] ?? 'Archivo';
                    $mime = $it['mime'] ?? null;
                    $url  = $it['url']  ?? null;
                    $path = $it['path'] ?? null;
                    if (!$url && $path) {
                        try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; }
                    }
                    if (!$url) {
                        try { $url = route('funcionario.bandeja.file', [$solicitud->id, $campo, $ix]); } catch (\Throwable $e) { $url = null; }
                    }
                    $documentos[] = ['campo' => $campo, 'name' => $name, 'mime' => $mime, 'url' => $url];
                }
            }
        }

        // 3) Etapas (igual que antes)
        // Fallback conservador: solo listar definitivos de esta solicitud
        $scanFallback = function() use($solicitud) {
            $out = [];
            try {
                $defDir = 'solicitudes/' . ($solicitud->id ?? 0);
                foreach (\Storage::disk('public')->files($defDir) as $p) {
                    $url = null; try { $url = \Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                    $out[] = ['campo' => 'Adjuntos', 'name' => basename($p), 'mime' => null, 'url' => $url];
                }
            } catch (\Throwable $e) { /* noop */ }
            return $out;
        };

        if (empty($documentos)) {
            $documentos = $scanFallback();
        }

        $etapas = [];
        try { $etapas = json_decode($tramite->etapas_json ?? '[]', true) ?: []; } catch (\Throwable $e) { $etapas = []; }
        $totalEtapas = is_array($etapas) ? count($etapas) : 0;
        $estado = strtolower((string)($solicitud->estado ?? ''));
        $map = [
            'iniciada'=>1,'iniciado'=>1,
            'en_proceso'=>max(1,min(2,$totalEtapas?:2)),
            'en_revision'=>max(1,min(2,$totalEtapas?:2)),
            'observado'=>max(1,min(2,$totalEtapas?:2)),
            'aprobado'=>max(1,$totalEtapas?:1),
            'finalizado'=>max(1,$totalEtapas?:1),
            'rechazado'=>max(1,$totalEtapas?:1),
        ];
        $etapaActual = $map[$estado] ?? 1;

        // Pasamos respuestas_json como fallback para no-file en la vista
        $answersForView = is_array($solicitud->respuestas_json)
            ? $solicitud->respuestas_json
            : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);

        return view('pages.profile.funcionario.bandeja_show', [
            'active'      => 'bandeja',
            'solicitud'   => $solicitud,
            'tramite'     => $tramite,
            'sections'    => $sections,
            'answers'     => $answersForView,
            'documentos'  => $documentos,
            'totalEtapas' => $totalEtapas,
            'etapaActual' => $etapaActual,
        ]);
    }

    /**
     * Descarga segura para funcionario
     * Ruta sugerida (en routes/web.php):
     *   Route::get('funcionario/bandeja/{id}/archivo/{field}/{index?}',
     *     [BandejaController::class, 'downloadFile'])->name('funcionario.bandeja.file');
     */
    public function downloadFile($id, string $field, $index = null)
    {
        $solicitud = Solicitud::with('tramite')->findOrFail($id);

        // 1) Buscar en schema (sections/value)
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

        // 2) Si no está en schema, buscar en respuestas_json
        if (!$entry) {
            $answers = is_array($solicitud->respuestas_json)
                ? $solicitud->respuestas_json
                : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
            if (isset($answers[$field])) {
                $val = $answers[$field];
                if (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val) - 1);
                    if ($isAssoc) {
                        $entry = $val;
                    } else {
                        $i = is_null($index) ? 0 : (int)$index;
                        $entry = $val[$i] ?? null;
                    }
                }
            }
        }

        if (!$entry || !is_array($entry)) abort(404);

        $path = $entry['path'] ?? null;
        if (!$path && !empty($entry['url'])) {
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
