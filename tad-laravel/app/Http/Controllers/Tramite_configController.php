<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramite;
use Illuminate\Support\Facades\Schema;


class Tramite_configController extends Controller
{
    // Listado
    public function indexFuncionario()
    {
        $tramites = \App\Models\Tramite::with(['parent','hijos','relacionados'])
            ->orderBy('created_at','desc')
            ->get();

        return view('pages.profile.funcionario.tramite_config', compact('tramites'));
    }

    // Crear
    public function create()
    {
        $tramite = new \App\Models\Tramite();

        $tramitesListado = \App\Models\Tramite::orderBy('nombre')->pluck('nombre','id');
        $relacionadosSeleccionados = [];
        $hijosSeleccionados = [];
        $etapas = []; // si usás esto en la vista

        return view('pages.profile.funcionario.tramite_create', compact(
            'tramite','tramitesListado','relacionadosSeleccionados','hijosSeleccionados','etapas'
        ));
    }


    // Guardar
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data = $this->normalizeBooleans($request, $data);
        $data = $this->stripMissingColumns($data);

        \App\Models\Tramite::create($data);
        return redirect()->route('funcionario.tramite_config')->with('success', 'Trámite creado con éxito.');
    }
    // Editar
    public function edit($id)
    {
        $tramite = \App\Models\Tramite::with(['parent','hijos','relacionados'])->findOrFail($id);

        $tramitesListado = \App\Models\Tramite::orderBy('nombre')->pluck('nombre','id');
        $relacionadosSeleccionados = $tramite->relacionados->pluck('id')->all();
        $hijosSeleccionados        = $tramite->hijos->pluck('id')->all();
        $etapas = $tramite->etapas_json ?? [];

        return view('pages.profile.funcionario.tramite_create', compact(
            'tramite','tramitesListado','relacionadosSeleccionados','hijosSeleccionados','etapas'
        ));
    }

    // Actualizar

    public function update(Request $request, $id)
{
    $tramite = Tramite::findOrFail($id);

    $data = $request->validate([
        'nombre'           => 'required|string|max:255',
        'descripcion'      => 'nullable|string',
        // … el resto de tus campos simples …
        'formulario_json'  => 'nullable|string', // llega del hidden
        'etapas_json'      => 'nullable|string',
        'documento_json'   => 'nullable|string',
        'config_json'      => 'nullable|string',
        // …
    ]);

    // No inventes estructuras nuevas acá: si el hidden existe, usarlo tal cual.
    // (Opcional) Blindaje básico para booleanos/toggles:
    $data['publicado']      = (bool) ($data['publicado']      ?? $tramite->publicado);
    $data['disponible']     = (bool) ($data['disponible']     ?? $tramite->disponible);
    $data['mostrar_inicio'] = (bool) ($data['mostrar_inicio'] ?? $tramite->mostrar_inicio);

    $tramite->update($data);

    return redirect()
        ->route('funcionario.tramite_config')
        ->with('success', 'Trámite actualizado correctamente.');
}


    // Eliminar
    // App\Http\Controllers\Tramite_configController.php

public function destroy($id)
{
    $tramite = \App\Models\Tramite::findOrFail($id);

    // 1) Quitar relación padre de los hijos
    \App\Models\Tramite::where('parent_id', $tramite->id)->update(['parent_id' => null]);

    // 2) Opcional: limpiar vínculos N:M en ambos sentidos
    if (method_exists($tramite, 'relacionados')) {
        $tramite->relacionados()->detach();
    }
    if (method_exists($tramite, 'relacionadosComoDestino')) {
        $tramite->relacionadosComoDestino()->detach();
    }

    // 3) Borrar
    $tramite->delete();

    return redirect()->route('funcionario.tramite_config')
        ->with('success', 'Trámite eliminado.');
}


    /* ----------------- Helpers ----------------- */

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nombre'          => 'required|string|max:255',
            'descripcion'     => 'nullable|string',
            'tipo'            => 'nullable|string|max:100',
            'estatus'         => 'nullable|string|max:100',
            'mensaje'         => 'nullable|string',

            // JSONs de pestañas
            'general_json'    => 'nullable',
            'formulario_json' => 'nullable',
            'etapas_json'     => 'nullable',
            'documento_json'  => 'nullable',
            'config_json'     => 'nullable',

            // switches
            'publicado'           => 'nullable',
            'disponible'          => 'nullable',
            'mostrar_inicio'      => 'nullable',
            'acepta_solicitudes'  => 'nullable',
            'acepta_pruebas'      => 'nullable',
            'modulo_citas'        => 'nullable',
            'modulo_inspectores'  => 'nullable',
        ]);
    }

    private function sanitizeTramiteData(array $data): array
    {
        foreach ([
            'publicado','disponible','mostrar_inicio',
            'acepta_solicitudes','acepta_pruebas',
            'modulo_citas','modulo_inspectores'
        ] as $k) {
            if (array_key_exists($k, $data)) {
                $data[$k] = isset($data[$k]) && in_array($data[$k], [1, '1', true, 'on', 'yes'], true);
            }
        }
        return $data;
    }
        private function stripMissingColumns(array $data): array
        {
            $existing = Schema::getColumnListing('tramites');
            return array_intersect_key($data, array_flip($existing));
        }

        private function normalizeBooleans(Request $request, array $data): array
        {
            foreach ([
                'publicado','disponible','mostrar_inicio',
                'acepta_solicitudes','acepta_pruebas',
                'modulo_citas','modulo_inspectores'
            ] as $k) {
                // true si viene tildado; false si no vino
                $data[$k] = $request->boolean($k);
            }
            return $data;
        }


}
