<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SolicitudComunicacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Crear una nota/actuación interna entre funcionarios */
    public function storeNota(Request $request, $id)
    {
        $request->validate([
            'titulo' => 'required|string|max:200',
            'descripcion' => 'required|string|max:5000',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $s = Solicitud::findOrFail($id);

        $fileEntry = null;
        if ($request->hasFile('archivo')) {
            $file = $request->file('archivo');
            $path = $file->store('solicitudes/' . $s->id . '/notas', 'public');
            $fileEntry = [
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

        $nota = [
            'titulo' => $request->string('titulo')->toString(),
            'descripcion' => $request->string('descripcion')->toString(),
            'archivo' => $fileEntry,
            'created_by' => auth()->id(),
            'created_at' => now()->toDateTimeString(),
        ];

        $meta['_funcionario']['notas'] = array_values(array_merge($meta['_funcionario']['notas'] ?? [], [$nota]));

        $s->respuestas_json = $meta;
        $s->save();

        return back()->with('success', 'Nota/actuación registrada.');
    }

    /** Enviar mensaje al ciudadano (mensajería interna simple) */
    public function storeMensaje(Request $request, $id)
    {
        $request->validate([
            'mensaje' => 'required|string|max:10000',
            'solicitar_respuesta' => 'nullable|boolean',
        ]);

        $s = Solicitud::findOrFail($id);

        $meta = is_array($s->respuestas_json)
            ? $s->respuestas_json
            : (json_decode($s->respuestas_json ?? '[]', true) ?: []);

        $entry = [
            'from' => 'funcionario',
            'body' => $request->string('mensaje')->toString(),
            'require_reply' => (bool)$request->boolean('solicitar_respuesta'),
            'created_by' => auth()->id(),
            'created_at' => now()->toDateTimeString(),
        ];

        $meta['_mensajes'] = array_values(array_merge($meta['_mensajes'] ?? [], [$entry]));

        $s->respuestas_json = $meta;
        $s->save();

        return back()->with('success', 'Mensaje enviado al ciudadano.');
    }
}

