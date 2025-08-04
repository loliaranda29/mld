<?php

namespace App\Http\Controllers;

use App\Models\Formulario;
use App\Models\Tramite;
use Illuminate\Http\Request;

class FormularioController extends Controller
{
    public function edit($id)
    {
        $tramite = Tramite::findOrFail($id);
        $formulario = $tramite->formulario ?? new Formulario(['estructura' => json_encode([])]);
        return view('pages.profile.funcionario.formulario_edit', compact('tramite', 'formulario'));
    }

    public function update(Request $request, $id)
    {
        $tramite = Tramite::findOrFail($id);

        $validated = $request->validate([
            'estructura' => 'required|json',
        ]);

        $formulario = $tramite->formulario ?? new Formulario(['tramite_id' => $tramite->id]);
        $formulario->estructura = $validated['estructura'];
        $formulario->save();

        return redirect()->route('funcionario.tramite_config')->with('success', 'Formulario actualizado correctamente');
    }
}
