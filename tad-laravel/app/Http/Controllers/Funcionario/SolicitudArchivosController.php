<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudArchivosController extends Controller
{
    /**
     * Normaliza los campos de tipo file en el schema de la solicitud:
     * - Si quedaron como string (ej. "multa.pdf"), intenta localizar el archivo
     *   en public/solicitudes/{id} o en public/solicitudes/tmp/{usuario_id}.
     * - Si está en tmp y matchea, lo mueve a la carpeta definitiva
     *   public/solicitudes/{id} y actualiza el valor a {path,url,name,size,mime}.
     */
    public function normalize($id)
    {
        $s = Solicitud::findOrFail($id);

        $schema = is_array($s->datos) ? $s->datos : (json_decode($s->datos ?? '[]', true) ?: ['sections'=>[]]);
        $sections = $schema['sections'] ?? [];
        if (!$sections) return back();

        $listFiles = function(string $dir){
            try { return Storage::disk('public')->files($dir); } catch (\Throwable $e) { return []; }
        };
        $sanitize = function(string $s){
            $s = strtolower($s);
            $s = preg_replace('/\.[^.]+$/','',$s); // sin extensión
            $s = preg_replace('/[^a-z0-9]+/','', $s); // alfanumérico
            return $s ?: '';
        };
        $findExact = function(string $dir, string $needleLower){
            foreach ($listFiles($dir) as $p) {
                if (strtolower(basename($p)) === $needleLower) return $p;
            }
            return null;
        };
        $makeEntry = function(string $path){
            $url  = Storage::disk('public')->url($path);
            $name = basename($path);
            $size = null; $mime = null;
            try { $size = Storage::disk('public')->size($path); } catch (\Throwable $e) {}
            try { $mime = Storage::disk('public')->mimeType($path) ?: 'application/octet-stream'; } catch (\Throwable $e) { $mime = 'application/octet-stream'; }
            return compact('path','url','name','size','mime');
        };

        $dirDef = 'solicitudes/'.$s->id;
        $dirTmp = !empty($s->usuario_id) ? 'solicitudes/tmp/'.$s->usuario_id : null;

        $normalizeOne = function(string $name) use ($dirDef, $dirTmp, $findExact, $listFiles, $sanitize, $makeEntry){
            $needle = strtolower(basename($name));
            if ($needle === '') return null;
            $extNeedle = pathinfo($needle, PATHINFO_EXTENSION);

            // 1) Exacto en definitivo
            if ($hit = $findExact($dirDef, $needle)) return $makeEntry($hit);

            // 2) Exacto en tmp, mover
            if ($dirTmp) {
                if ($hit = $findExact($dirTmp, $needle)) {
                    $dest = $dirDef.'/'.basename($hit);
                    try { Storage::disk('public')->makeDirectory($dirDef); } catch (\Throwable $e) {}
                    try { Storage::disk('public')->move($hit, $dest); $hit = $dest; } catch (\Throwable $e) {}
                    return $makeEntry($hit);
                }
                // 2b) Fuzzy en tmp
                $needleSan = $sanitize($needle);
                foreach ($listFiles($dirTmp) as $p) {
                    if ($extNeedle && strtolower(pathinfo($p, PATHINFO_EXTENSION)) !== $extNeedle) continue;
                    if ($needleSan && strpos($sanitize(basename($p)), $needleSan) !== false) {
                        $dest = $dirDef.'/'.basename($p);
                        try { Storage::disk('public')->makeDirectory($dirDef); } catch (\Throwable $e) {}
                        try { Storage::disk('public')->move($p, $dest); $p = $dest; } catch (\Throwable $e) {}
                        return $makeEntry($p);
                    }
                }
            }
            // 3) Fuzzy en definitivo
            $needleSan = $sanitize($needle);
            foreach ($listFiles($dirDef) as $p) {
                if ($extNeedle && strtolower(pathinfo($p, PATHINFO_EXTENSION)) !== $extNeedle) continue;
                if ($needleSan && strpos($sanitize(basename($p)), $needleSan) !== false) return $makeEntry($p);
            }
            return null;
        };

        $changed = false;
        foreach ($sections as $si => $sec) {
            foreach (($sec['fields'] ?? []) as $fi => $f) {
                if (strtolower($f['type'] ?? 'text') !== 'file') continue;
                $val = $f['value'] ?? null;
                if (is_string($val) && trim($val) !== '') {
                    if ($entry = $normalizeOne($val)) {
                        $schema['sections'][$si]['fields'][$fi]['value'] = $entry;
                        $changed = true;
                    }
                } elseif (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val)-1);
                    if ($isAssoc) {
                        if (empty($val['path']) && !empty($val['name'])) {
                            if ($entry = $normalizeOne($val['name'])) {
                                $schema['sections'][$si]['fields'][$fi]['value'] = $entry;
                                $changed = true;
                            }
                        }
                    } else {
                        $newArr = [];
                        foreach ($val as $v) {
                            if (is_string($v) && trim($v) !== '') {
                                $entry = $normalizeOne($v);
                                $newArr[] = $entry ?: $v;
                                if ($entry) $changed = true;
                            } elseif (is_array($v) && empty($v['path']) && !empty($v['name'])) {
                                $entry = $normalizeOne($v['name']);
                                $newArr[] = $entry ?: $v;
                                if ($entry) $changed = true;
                            } else {
                                $newArr[] = $v;
                            }
                        }
                        $schema['sections'][$si]['fields'][$fi]['value'] = $newArr;
                    }
                }
            }
        }

        if ($changed) {
            $s->datos = $schema;
            $s->save();
        }

        return back()->with('success', $changed ? 'Adjuntos normalizados.' : 'Sin cambios en adjuntos.');
    }
}

