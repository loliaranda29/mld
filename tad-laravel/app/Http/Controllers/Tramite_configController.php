<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramite;

class Tramite_configController extends Controller
{
    // Mostrar listado de trámites
    public function indexFuncionario()
    {
        $tramites = Tramite::orderBy('created_at', 'desc')->get();
        return view('pages.profile.funcionario.tramite_config', compact('tramites'));
    }

    // Mostrar formulario de creación
   public function create()
{
    $tramite = null;
    $etapas = [];

    return view('pages.profile.funcionario.tramite_create', compact('tramite', 'etapas'));
}


    // Guardar un nuevo trámite
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'publicado' => 'boolean',
            'disponible' => 'boolean',
            'mostrar_inicio' => 'boolean',
            'tipo' => 'nullable|string|max:100',
            'estatus' => 'nullable|string|max:100',
            'etapas' => 'nullable|json',
            'mensaje' => 'nullable|string',
        ]);

        Tramite::create($validated);

        return redirect()->route('funcionario.tramite_config')->with('success', 'Trámite creado con éxito.');
    }

    // Mostrar formulario de edición
   public function edit($id)
{
    $tramite = Tramite::with('etapas')->findOrFail($id);
    $etapas = $tramite->etapas;

    return view('pages.profile.funcionario.tramite_create', compact('tramite', 'etapas'));
}



    // Actualizar un trámite existente
    public function update(Request $request, $id)
    {
        $tramite = Tramite::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'publicado' => 'boolean',
            'disponible' => 'boolean',
            'mostrar_inicio' => 'boolean',
            'tipo' => 'nullable|string|max:100',
            'estatus' => 'nullable|string|max:100',
            'etapas' => 'nullable|json',
            'mensaje' => 'nullable|string',
        ]);

        $tramite->update($validated);

        return redirect()->route('funcionario.tramite_config')->with('success', 'Trámite actualizado correctamente.');
    }

    // Eliminar un trámite
    public function destroy($id)
    {
        $tramite = Tramite::findOrFail($id);
        $tramite->delete();

        return redirect()->route('funcionario.tramite_config')->with('success', 'Trámite eliminado.');
    }
    public function etapas()
{
    return $this->hasMany(Etapa::class)->orderBy('orden');
}

}
