<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SolicitudValidacionesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'key'   => 'required|string|max:100',
            'ok'    => 'required|boolean',
            'motivo'=> 'nullable|string|max:2000',
        ]);

        $s = \App\Models\Solicitud::findOrFail($id);

        $meta = is_array($s->respuestas_json)
            ? $s->respuestas_json
            : (json_decode($s->respuestas_json ?? '[]', true) ?: []);

        $key = (string) $request->input('key');
        $entry = [
            'ok'         => (bool)$request->boolean('ok'),
            'motivo'     => (string)($request->input('motivo') ?? ''),
            'by'         => auth()->id(),
            'updated_at' => now()->toDateTimeString(),
        ];

        $meta['_funcionario']['validaciones'][$key] = $entry;
        $s->respuestas_json = $meta;
        $s->save();

        return response()->json(['success' => true, 'data' => $entry]);
    }
}

