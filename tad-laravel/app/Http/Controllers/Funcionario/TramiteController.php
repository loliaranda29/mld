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
    // Excluir el propio trámite
    $base = Tramite::query()->where('id', '!=', $tramite->id);

    // Posibles padres: excluimos descendientes para no crear ciclos
    $descendientesIds = $this->descendantsIds($tramite); // helper simple abajo
    $tramitesPosiblesPadre = (clone $base)
        ->whereNotIn('id', $descendientesIds)
        ->orderBy('nombre')
        ->get();

    // Posibles hijos: todos menos el propio y su padre (no obligatorio)
    $tramitesPosiblesHijo = (clone $base)
        ->orderBy('nombre')
        ->get();

    // Para vínculos: todos menos el propio
    $tramitesParaVincular = (clone $base)
        ->orderBy('nombre')
        ->get();

    return view('pages.profile.funcionario.tramites.editar', [
        'tramite' => $tramite->load(['parent','children','vinculos']),
        'tramitesPosiblesPadre'  => $tramitesPosiblesPadre,
        'tramitesPosiblesHijo'   => $tramitesPosiblesHijo,
        'tramitesParaVincular'   => $tramitesParaVincular,
        // ...otros datos que ya mandás (tabs, etc.)
    ]);
}

/** Retorna IDs de todos los descendientes del trámite (para evitar ciclos). */
private function descendantsIds(Tramite $root): array
{
    $ids = [];
    $queue = [$root];
    while ($node = array_shift($queue)) {
        foreach ($node->children as $child) {
            if (!in_array($child->id, $ids, true)) {
                $ids[] = $child->id;
                $queue[] = $child->loadMissing('children');
            }
        }
    }
    return $ids;
}
}
