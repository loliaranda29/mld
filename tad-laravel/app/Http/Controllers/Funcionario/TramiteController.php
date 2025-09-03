<?php
namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Http\Requests\TramiteRequest;
use App\Models\Tramite;

class TramiteController extends Controller
{
    public function store(TramiteRequest $request)
    {
        $payload = $request->getNormalized();

        $tramite = Tramite::create($payload);

        return redirect()
            ->route('funcionario.tramites.index')
            ->with('success', 'Trámite creado correctamente.');
    }

    public function update(TramiteRequest $request, Tramite $tramite)
    {
        $payload = $request->getNormalized();

        $tramite->update($payload);

        return redirect()
            ->route('funcionario.tramites.index')
            ->with('success', 'Trámite actualizado correctamente.');
    }

    public function edit(Tramite $tramite)
    {
        // Pasamos a la vista los JSON ya “casted” a array (por $casts del modelo)
        return view('pages.profile.funcionario.tramites.create', [
            'tramite' => $tramite,
            // si necesitás variables auxiliares:
            'etapas'  => $tramite->etapas_json ?? [],
            'todos'   => collect(), // relaciones etc.
        ]);
    }
}
