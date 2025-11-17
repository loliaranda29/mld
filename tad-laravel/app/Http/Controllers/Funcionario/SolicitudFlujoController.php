<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class SolicitudFlujoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Devolver con observaciones al ciudadano para corregir
    public function devolver(Request $request, $id)
    {
        $request->validate([
            'mensaje' => 'nullable|string|max:2000',
        ]);

        $s = Solicitud::findOrFail($id);

        // Cambiar estado
        $s->estado = 'observado';

        // Meta workflow + marca de devolución
        $meta = is_array($s->respuestas_json)
            ? $s->respuestas_json
            : (json_decode($s->respuestas_json ?? '[]', true) ?: []);

        $meta['_funcionario']['observado_at'] = now()->toDateTimeString();
        $meta['_funcionario']['observado_por'] = auth()->id();
        if ($request->filled('mensaje')) {
            $meta['_mensajes'][] = [
                'from' => 'funcionario',
                'body' => (string)$request->input('mensaje'),
                'created_at' => now()->toDateTimeString(),
                'require_reply' => true,
            ];
        }

        // Workflow simple en _wf
        $wf = (array)($meta['_wf'] ?? []);
        $hist = (array)($wf['history'] ?? []);
        $hist[] = [
            'at' => now()->toDateTimeString(),
            'by' => auth()->id(),
            'action' => 'devolver',
            'detalle' => (string)$request->input('mensaje', ''),
        ];
        $wf['history'] = $hist;
        $meta['_wf'] = $wf;

        $s->respuestas_json = $meta;
        $s->save();

        return back()->with('success', 'Solicitud devuelta al ciudadano con observaciones.');
    }
}

