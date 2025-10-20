<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EtapaTramite;
use App\Models\Tramite;

class EtapaTramiteController extends Controller
{
    public function index($tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        $etapas = $tramite->etapas()->orderBy('orden')->get();

        return view('pages.profile.funcionario.etapas.index', compact('tramite', 'etapas'));
    }

    public function create($tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        return view('pages.profile.funcionario.etapas.create', compact('tramite'));
    }

    public function store(Request $request, $tramite_id)
    {
        $tramite = Tramite::findOrFail($tramite_id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer',
            'oficina_id' => 'nullable|integer',
            'requiere_firma' => 'boolean',
            'requiere_documentacion' => 'boolean',
        ]);

        $validated['tramite_id'] = $tramite->id;

        EtapaTramite::create($validated);

        return redirect()->route('etapas.index', $tramite->id)->with('success', 'Etapa creada correctamente.');
    }

    public function edit($tramite_id, $id)
    {
        $tramite = Tramite::findOrFail($tramite_id);
        $etapa = EtapaTramite::findOrFail($id);

        return view('pages.profile.funcionario.etapas.edit', compact('tramite', 'etapa'));
    }

    public function update(Request $request, $tramite_id, $id)
    {
        $etapa = EtapaTramite::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'orden' => 'nullable|integer',
            'oficina_id' => 'nullable|integer',
            'requiere_firma' => 'boolean',
            'requiere_documentacion' => 'boolean',
        ]);

        $etapa->update($validated);

        return redirect()->route('etapas.index', $tramite_id)->with('success', 'Etapa actualizada.');
    }

    public function destroy($tramite_id, $id)
    {
        $etapa = EtapaTramite::findOrFail($id);
        $etapa->delete();

        return redirect()->route('etapas.index', $tramite_id)->with('success', 'Etapa eliminada.');
    }
}
