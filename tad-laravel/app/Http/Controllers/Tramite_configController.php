<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tramite;

class Tramite_configController extends Controller
{
    // Listado
    public function indexFuncionario()
    {
        $tramites = Tramite::orderBy('created_at', 'desc')->get();
        return view('pages.profile.funcionario.tramite_config', compact('tramites'));
    }

    // Crear
    public function create()
    {
        $tramite = null;
        $etapas  = [];
        return view('pages.profile.funcionario.tramite_create', compact('tramite', 'etapas'));
    }

    // Guardar
    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data = $this->sanitizeTramiteData($data);

        Tramite::create($data);

        return redirect()->route('funcionario.tramite_config')
                         ->with('success', 'Trámite creado con éxito.');
    }

    // Editar
    public function edit($id)
    {
        $tramite = Tramite::findOrFail($id);
        $etapas  = $tramite->etapas_json ?? [];
        return view('pages.profile.funcionario.tramite_create', compact('tramite', 'etapas'));
    }

    // Actualizar
    public function update(Request $request, $id)
    {
        $tramite = Tramite::findOrFail($id);

        $data = $this->validateData($request);
        $data = $this->sanitizeTramiteData($data);

        $tramite->update($data);

        return redirect()->route('funcionario.tramite_config')
                         ->with('success', 'Trámite actualizado correctamente.');
    }

    // Eliminar
    public function destroy($id)
    {
        $tramite = Tramite::findOrFail($id);
        $tramite->delete();

        return redirect()->route('funcionario.tramite_config')
                         ->with('success', 'Trámite eliminado.');
    }

    /* ----------------- Helpers ----------------- */

    private function validateData(Request $request): array
    {
        return $request->validate([
            'nombre'              => 'required|string|max:255',
            'descripcion'         => 'nullable|string',
            'publicado'           => 'nullable',
            'disponible'          => 'nullable',
            'mostrar_inicio'      => 'nullable',
            'acepta_solicitudes'  => 'nullable',
            'acepta_pruebas'      => 'nullable',
            'modulo_citas'        => 'nullable',
            'modulo_inspectores'  => 'nullable',
            'tipo'                => 'nullable|string|max:100',
            'estatus'             => 'nullable|string|max:100',
            'mensaje'             => 'nullable|string',

            // pestañas (vendrán como JSON string en hidden inputs)
            'general_json'        => 'nullable',
            'formulario_json'     => 'nullable',
            'etapas_json'         => 'nullable',
            'documento_json'      => 'nullable',
            'config_json'         => 'nullable',
        ]);
    }

    private function sanitizeTramiteData(array $data): array
    {
        // normalizo booleans por si vienen 'on'
        foreach ([
            'publicado','disponible','mostrar_inicio',
            'acepta_solicitudes','acepta_pruebas','modulo_citas','modulo_inspectores'
        ] as $b) {
            $data[$b] = !empty($data[$b]);
        }

        // decodifico JSONs si vienen como string
        foreach (['general_json','formulario_json','etapas_json','documento_json','config_json'] as $key) {
            if (array_key_exists($key, $data) && is_string($data[$key]) && $data[$key] !== '') {
                $decoded = json_decode($data[$key], true);
                $data[$key] = json_last_error() === JSON_ERROR_NONE ? $decoded : null;
            }
        }

        return $data;
    }
}
