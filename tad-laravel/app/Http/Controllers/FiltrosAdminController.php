<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class FiltrosAdminController extends Controller
{
    /** Catálogo disponible para el modal (clona lo que mostrás en la UI). */
    protected function available(): array
    {
        // id => nombre (usamos ids fijos para poder eliminar sin confusión)
        return [
            1  => 'Dependencias',
            2  => 'Oficinas',
            3  => 'Modalidad',
            4  => 'Categorías',
            5  => 'Ubicaciones',
            6  => 'Implica costo',
            7  => 'Vivienda',
            8  => 'Área',
            9  => 'Catálogo de prueba',
            10 => 'Tipo de DNI',
            11 => 'Departamentos',
        ];
    }

    /** Dataset inicial para clonar la UI (solo demo). */
    protected function seed()
    {
        return collect([
            ['id' => 1, 'nombre' => 'Dependencias', 'created_at' => '2025-07-01 03:29:14'],
            ['id' => 2, 'nombre' => 'Oficinas',     'created_at' => '2025-06-02 17:22:30'],
            ['id' => 3, 'nombre' => 'Modalidad',    'created_at' => '2025-05-14 20:29:46'],
            ['id' => 4, 'nombre' => 'Categorías',   'created_at' => '2025-06-12 12:36:05'],
            ['id' => 5, 'nombre' => 'Ubicaciones',  'created_at' => '2022-11-09 13:00:13'],
            ['id' => 6, 'nombre' => 'Implica costo','created_at' => '2022-11-09 13:00:13'],
        ]);
    }

    public function index(Request $request)
    {
        // Items “persistidos” en sesión (si no hay, usamos seed)
        $items = collect($request->session()->get('filtros_items'));
        if ($items->isEmpty()) {
            $items = $this->seed();
            $request->session()->put('filtros_items', $items->values()->all());
        }

        // Orden + paginado simple
        $all     = $items->sortBy('nombre')->values();
        $perPage = (int) $request->get('per_page', 10);
        $page    = max(1, (int) $request->get('page', 1));

        $total = $all->count();
        $slice = $all->slice(($page - 1) * $perPage, $perPage)->values();

        $conjuncion = (bool) $request->session()->get('filtros_conjuncion', false);

        return view('pages.profile.funcionario.filtros.index', [
            'active'     => 'filtros',
            'items'      => $slice,
            'page'       => $page,
            'perPage'    => $perPage,
            'total'      => $total,
            'conjuncion' => $conjuncion,
            'available'  => $this->available(), // ⬅️ para pintar el modal
        ]);
    }

    public function toggle(Request $request)
    {
        $current = (bool) $request->session()->get('filtros_conjuncion', false);
        $request->session()->put('filtros_conjuncion', ! $current);
        return back();
    }

    /** Guarda múltiples filtros elegidos en el modal (demo: sesión). */
    public function store(Request $request)
    {
        $request->validate([
            'filtros'   => ['array', 'min:1'],
            'filtros.*' => ['integer'],
        ]);

        $available = $this->available();
        $now       = Carbon::now()->format('Y-m-d H:i:s');

        // Cargamos los actuales
        $items = collect($request->session()->get('filtros_items', []));

        // Normalizamos a mapa por id para deduplicar
        $byId = $items->keyBy('id');

        foreach ($request->input('filtros', []) as $id) {
            if (! array_key_exists($id, $available)) continue; // ignora ids no válidos
            if (! $byId->has($id)) {
                $byId->put($id, [
                    'id'         => (int) $id,
                    'nombre'     => $available[$id],
                    'created_at' => $now,
                ]);
            }
        }

        // Guardamos
        $request->session()->put('filtros_items', $byId->values()->all());

        return redirect()->route('filtros.index')
            ->with('ok', 'Filtros agregados correctamente.');
    }

    public function destroy(Request $request, $id)
    {
        $items = collect($request->session()->get('filtros_items', []))
            ->reject(fn ($row) => (int) $row['id'] === (int) $id)
            ->values();

        $request->session()->put('filtros_items', $items->all());

        return back()->with('ok', 'Filtro eliminado.');
    }
}
