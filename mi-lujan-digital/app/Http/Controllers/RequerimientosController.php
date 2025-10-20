<?php
namespace App\Http\Controllers;

use App\Models\Requerimiento;
use App\Models\Tramite;
use Illuminate\Http\Request;

class RequerimientosController extends Controller
{
    public function store(Request $request, Tramite $tramite, $instanciaId)
    {
        $data = $request->validate([
            'section_name'   => 'required|string',
            'fecha_limite'   => 'nullable|date',
            'mensaje'        => 'nullable|string',
        ]);

        // Buscar la sección activable por nombre (o por key si usás keys)
        $sec = collect($tramite->seccionesActivables())
                ->firstWhere('name', $data['section_name']);

        abort_if(!$sec, 404, 'Sección no encontrada o no activable');

        $req = Requerimiento::create([
            'tramite_id'        => $tramite->id,
            'instancia_id'      => $instanciaId,
            'section_key'       => $sec['key'] ?? null,
            'section_name'      => $sec['name'],
            'form_schema'       => $sec, // snapshot de la plantilla
            'estado'            => 'pendiente',
            'fecha_limite'      => $data['fecha_limite'] ?? null,
            'mensaje_funcionario'=> $data['mensaje'] ?? null,
            'creado_por'        => $request->user()->id,
            'dirigido_a'        => null, // o el user ciudadano de la instancia
        ]);

        // TODO: notificar al ciudadano
        return back()->with('ok', 'Requerimiento creado.');
    }

        public function responder(Request $request, Requerimiento $requerimiento)
    {
        $schema = $requerimiento->form_schema ?? [];
        $fields = $schema['fields'] ?? [];

        // Armá validación dinámica (ejemplo simple)
        $rules = [];
        foreach ($fields as $f) {
            $r = [];
            if (($f['required'] ?? false) === true) $r[] = 'required';
            if (($f['type'] ?? '') === 'email') $r[] = 'email';
            if (($f['type'] ?? '') === 'number') $r[] = 'numeric';
            $rules[$f['name'] ?? 'campo_'.uniqid()] = implode('|', $r);
        }

        $respuestas = $request->validate($rules);

        $requerimiento->update([
            'respuestas_json' => $respuestas,
            'estado'          => 'respondido',
            'respondido_at'   => now(),
        ]);

        // TODO: notificar a funcionario
        return back()->with('ok','Información enviada.');
    }

}
