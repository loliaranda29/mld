<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class SolicitudAccionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Aceptar etapa / pasar a en_proceso si estaba iniciado */
    public function aceptar($id)
    {
        $s = Solicitud::findOrFail($id);

        if ($s->estado === 'iniciado') {
            $s->estado = 'en_proceso';
        }

        $s->save();

        return back()->with('success', 'Etapa aceptada. La solicitud está en proceso.');
    }

    /** Rechazar con motivo (guarda en respuestas_json._funcionario) */
    public function rechazar(Request $request, $id)
    {
        $request->validate([
            'motivo' => 'required|string|max:500',
        ]);

        $s = Solicitud::findOrFail($id);
        $s->estado = 'rechazado';

        $meta = is_array($s->respuestas_json)
            ? $s->respuestas_json
            : (json_decode($s->respuestas_json ?? '[]', true) ?: []);

        $meta['_funcionario']['rechazo_motivo'] = $request->string('motivo')->toString();
        $meta['_funcionario']['rechazado_por']  = auth()->id();
        $meta['_funcionario']['rechazado_at']   = now()->toDateTimeString();

        $s->respuestas_json = $meta;
        $s->save();

        return back()->with('success', 'Trámite rechazado.');
    }

    /** Guardar “sin cambios” (touchea timestamps) para forzar actualizado */
    public function guardar(Request $request, $id)
    {
        $s = Solicitud::findOrFail($id);
        $s->touch();

        return back()->with('success', 'Cambios guardados.');
    }

    /** ZIP con todos los adjuntos (campos file) de la solicitud */
    public function descargasZip($id)
    {
        $s = Solicitud::findOrFail($id);

        $schema = is_array($s->datos)
            ? $s->datos
            : (json_decode($s->datos ?? '[]', true) ?: ['sections' => []]);

        $files = [];

        foreach (($schema['sections'] ?? []) as $sec) {
            foreach (($sec['fields'] ?? []) as $f) {
                if (strtolower($f['type'] ?? '') !== 'file') {
                    continue;
                }

                $val = $f['value'] ?? null;
                $arr = [];

                if (is_array($val)) {
                    // Si es assoc -> un solo archivo; si es lista -> múltiples
                    $arr = array_keys($val) !== range(0, count($val) - 1) ? [$val] : $val;
                }

                foreach ($arr as $one) {
                    $path = $one['path'] ?? null;
                    if ($path && Storage::disk('public')->exists($path)) {
                        $files[] = [
                            'path' => $path,
                            'name' => ($one['name'] ?? basename($path)),
                        ];
                    }
                }
            }
        }

        if (!count($files)) {
            return back()->with('info', 'La solicitud no tiene adjuntos.');
        }

        $zipName = 'solicitud-' . $s->id . '-adjuntos.zip';
        $tmpZip  = storage_path('app/' . $zipName);

        $zip = new ZipArchive();
        if ($zip->open($tmpZip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            return back()->with('error', 'No se pudo crear el ZIP.');
        }

        foreach ($files as $f) {
            $absPath = Storage::disk('public')->path($f['path']);
            $zip->addFile($absPath, $f['name']);
        }

        $zip->close();

        return response()->download($tmpZip)->deleteFileAfterSend(true);
    }

    /** Subir documentos de salida generados por el funcionario */
    public function uploadSalida(Request $request, $id)
    {
        $request->validate([
            'salida'   => 'required',
            'salida.*' => 'file|max:10240', // 10 MB c/u
        ]);

        $s = Solicitud::findOrFail($id);
        $saved = [];

        foreach ((array) $request->file('salida', []) as $file) {
            $path = $file->store('solicitudes/' . $s->id . '/salida', 'public');

            $saved[] = [
                'path' => $path,
                'url'  => Storage::disk('public')->url($path),
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getClientMimeType(),
            ];
        }

        $meta = is_array($s->respuestas_json)
            ? $s->respuestas_json
            : (json_decode($s->respuestas_json ?? '[]', true) ?: []);

        $meta['_funcionario']['salida'] = array_merge($meta['_funcionario']['salida'] ?? [], $saved);

        $s->respuestas_json = $meta;
        $s->save();

        return back()->with('success', 'Documentos de salida adjuntados.');
    }

    /** Descarga segura de un adjunto (por nombre de campo o índice de campo file) */
    public function downloadFile($id, $field, $index = null)
    {
        $s = Solicitud::findOrFail($id);

        $schema = is_array($s->datos)
            ? $s->datos
            : (json_decode($s->datos ?? '[]', true) ?: ['sections' => []]);

        $entry   = null;
        $byIndex = ctype_digit((string) $field);
        $fileFieldPos = 0;

        foreach (($schema['sections'] ?? []) as $sec) {
            foreach (($sec['fields'] ?? []) as $f) {
                if (strtolower($f['type'] ?? '') !== 'file') {
                    continue;
                }

                $match = $byIndex
                    ? ((int) $field === $fileFieldPos)
                    : (($f['_name'] ?? ($f['name'] ?? '')) === $field);

                if (!$match) {
                    $fileFieldPos++;
                    continue;
                }

                $val = $f['value'] ?? null;
                if (is_array($val)) {
                    $isAssoc = array_keys($val) !== range(0, count($val) - 1);
                    $entry   = $isAssoc
                        ? $val
                        : ($val[is_null($index) ? 0 : (int) $index] ?? null);
                }
                break 2;
            }
        }

        if (!$entry || !is_array($entry)) {
            abort(404);
        }

        $path = $entry['path'] ?? null;
        if (!$path) {
            abort(404);
        }

        // Evita que descarguen paths fuera de la carpeta de la solicitud
        $expectedPrefix = 'solicitudes/' . $s->id . '/';
        if (strpos($path, $expectedPrefix) !== 0 && strpos($path, 'solicitudes/tmp/') !== 0) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $name = $entry['name'] ?? basename($path);
        $mime = $entry['mime'] ?? (Storage::disk('public')->mimeType($path) ?: 'application/octet-stream');

        return Storage::disk('public')->download($path, $name, [
            'Content-Type' => $mime,
        ]);
    }

    public function historial($id)
    {
        return back()->with('info', 'Historial no implementado aún.');
    }

    public function asignacion($id)
    {
        return back()->with('info', 'Asignación de etapas no implementada aún.');
    }
}
