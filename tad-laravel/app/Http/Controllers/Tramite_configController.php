<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Tramite_configController extends Controller
{
    public function indexFuncionario()
    {
        $tramites = [
            [
                'id' => 1,
                'nombre' => 'Asistencia presencial para Licencias',
                'descripcion' => 'Te ayudamos a cargar tu trámite online...',
                'fecha' => '21/07/2025 08:40:05 hrs',
                'disponible' => true,
                'publicado' => true,
                'acepta_solicitudes' => false,
                'mostrar_inicio' => true,
            ],
            // ... más trámites simulados
        ];

        return view('pages.profile.funcionario.tramite_config', compact('tramites'));
    }
    public function create()
    {
        return view('pages.profile.funcionario.tramite_create');
    }
    public function store(Request $request)
{
    // Validar campos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        // Otros campos a validar
    ]);

    // Guardar en la base de datos
    Trámite::create($validated); // O el modelo correspondiente

    return redirect()->route('funcionario.tramite_config')->with('success', 'Trámite creado con éxito');
}


}
