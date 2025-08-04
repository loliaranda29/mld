<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CampoTramite;
use App\Models\Tramite;

class CampoTramiteController extends Controller
{
    // Lista todos los campos de un trámite
    public function index($tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        $campos = $tramite->campos()->orderBy('orden')->get();

        return view('pages.profile.funcionario.campos.index', compact('tramite', 'campos'));
    }

    // Formulario para crear un nuevo campo
    public function create($tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        return view('pages.profile.funcionario.campos.create', compact('tramite'));
    }

    // Guardar campo
    public function store(Request $request, $tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'etiqueta' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'requerido' => 'boolean',
            'orden' => 'integer',
            'valores' => 'nullable|json',
            'condicional' => 'nullable|json',
        ]);

        $validated['tramite_id'] = $tramite->id;

        CampoTramite::create($validated);

        return redirect()->route('campos.index', $tramite->id)->with('success', 'Campo creado correctamente.');
    }

    // Formulario de edición
    public function edit($tramite_id, $id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        $campo = CampoTramite::findOrFail($id);

        return view('pages.profile.funcionario.campos.edit', compact('tramite', 'campo'));
    }

    // Actualizar campo
    public function update(Request $request, $tramite_id, $id)
    {
        $campo = CampoTramite::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'etiqueta' => 'required|string|max:255',
            'tipo' => 'required|string|max:100',
            'requerido' => 'boolean',
            'orden' => 'integer',
            'valores' => 'nullable|json',
            'condicional' => 'nullable|json',
        ]);

        $campo->update($validated);

        return redirect()->route('campos.index', $tramite_id)->with('success', 'Campo actualizado.');
    }

    // Eliminar campo
    public function destroy($tramite_id, $id)
    {
        $campo = CampoTramite::findOrFail($id);
        $campo->delete();

        return redirect()->route('campos.index', $tramite_id)->with('success', 'Campo eliminado.');
    }
}
