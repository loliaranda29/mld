<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Catalogo;
use App\Models\CatalogoItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CatalogosAdminController extends Controller
{
    /* ───────────── Catálogos (padre) ───────────── */

    public function index(Request $request)
    {
        $perPage   = (int) $request->get('per_page', 10);

        $catalogos = Catalogo::query()
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.profile.funcionario.catalogos.index', [
            'active'    => 'catalogos',
            'catalogos' => $catalogos,            // ← ahora es LengthAwarePaginator
            'page'      => $catalogos->currentPage(),
            'perPage'   => $catalogos->perPage(),
            'total'     => $catalogos->total(),
        ]);
    }

    public function create()
    {
        return view('pages.profile.funcionario.catalogos.create', [
            'active' => 'catalogos',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'      => ['required','string','max:200'],
            'slug'        => ['nullable','string','max:200', 'unique:catalogos,slug'],
            'descripcion' => ['nullable','string','max:255'],
            'icono'       => ['nullable','string','max:255'],
            'orden'       => ['nullable','integer'],
            'activo'      => ['nullable','boolean'],
        ]);

        // si no mandan slug, lo generamos
        $data['slug'] = $data['slug'] ?? Str::slug($data['nombre']);
        $data['activo'] = (bool)($data['activo'] ?? true);

        Catalogo::create($data);

        return redirect()->route('catalogos.index')->with('ok','Catálogo creado');
    }

    public function show($id)
    {
        $catalogo = Catalogo::findOrFail($id);

        // Listado de items del catálogo (paginado)
        $items = $catalogo->items()
            ->orderByRaw('COALESCE(orden, 999999) asc')
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();

        return view('pages.profile.funcionario.catalogos.show', [
            'active'   => 'catalogos',
            'catalogo' => $catalogo,
            'items'    => $items,
        ]);
    }

    public function destroy($id)
    {
        Catalogo::findOrFail($id)->delete(); // cascade a items por FK
        return back()->with('ok','Catálogo eliminado.');
    }

    /* ───────────── Subcatálogos / Ítems ───────────── */

    // Listado “subcatálogos” (ítems) de un catálogo
    public function subcatalogos(Request $request, $id)
{
    $catalogo = Catalogo::findOrFail($id);
    $items = $catalogo->items()
        ->orderByRaw('COALESCE(orden, 999999) asc')->orderBy('nombre')
        ->paginate((int)$request->get('per_page', 10))->withQueryString();

    return view('pages.profile.funcionario.catalogos.subcatalogos', [
        'active'       => 'catalogos',
        'catalogoId'   => $catalogo->id,
        'catalogo'     => $catalogo,      // si querés usar nombre en el breadcrumb
        'subcatalogos' => $items,
        'page'         => $items->currentPage(),
        'perPage'      => $items->perPage(),
        'total'        => $items->total(),
    ]);
}

    // Si tenés vistas “sub.index / sub.show” mantenemos equivalentes:
    public function subIndex($catalogoId, Request $request)
    {
        $catalogo = Catalogo::findOrFail($catalogoId);
        $perPage  = (int)$request->get('per_page', 10);

        $items = $catalogo->items()
            ->orderByRaw('COALESCE(orden, 999999) asc')
            ->orderBy('nombre')
            ->paginate($perPage)
            ->withQueryString();

        return view('pages.profile.funcionario.catalogos.sub.index', [
            'active'     => 'catalogos',
            'catalogoId' => $catalogo->id,
            'items'      => $items,
            'page'       => $items->currentPage(),
            'perPage'    => $items->perPage(),
            'total'      => $items->total(),
        ]);
    }

    public function subShow($catalogoId, $optId)
    {
        $catalogo = Catalogo::findOrFail($catalogoId);
        $opt      = $catalogo->items()->whereKey($optId)->firstOrFail();

        return view('pages.profile.funcionario.catalogos.sub.show', [
            'active'     => 'catalogos',
            'catalogoId' => $catalogo->id,
            'opt'        => $opt,
        ]);
    }

    // Alta de ítem o importación CSV
// Alta manual
public function subStore(Request $request, $id)
{
    $catalogo = \App\Models\Catalogo::findOrFail($id);

    $data = $request->validate([
        'nombre'     => ['required','string','max:255'],
        'slug'       => ['nullable','string','max:255'],
        'icono'      => ['nullable','string','max:255'],
        'orden'      => ['nullable','integer'],
        'activo'     => ['nullable','in:0,1'],       // ⬅️ acepta 0/1
        'jerarquico' => ['nullable','in:0,1'],       // ⬅️ acepta 0/1
    ]);

    $activo     = $request->boolean('activo');       // ⬅️ castea on/yes/1 a true
    $jerarquico = $request->boolean('jerarquico');

    \App\Models\CatalogoItem::create([
        'catalogo_id' => $catalogo->id,
        'nombre'      => $data['nombre'],
        'codigo'      => null,
        'orden'       => $data['orden'] ?? null,
        'activo'      => $activo,
        'meta'        => [
            'slug'       => $data['slug'] ?: \Illuminate\Support\Str::slug($data['nombre']),
            'icono'      => $data['icono'] ?? null,
            'jerarquico' => $jerarquico,
        ],
    ]);

    return back()->with('ok','Término agregado.');
}



// Upload CSV (con o sin cabecera)
public function subUpload(Request $request, $id)
{
    $catalogo = Catalogo::findOrFail($id);
    $request->validate(['csv' => ['required','file','mimes:csv,txt']]);

    $h = fopen($request->file('csv')->getRealPath(), 'r');
    $count = 0; $header = null;

    while (($row = fgetcsv($h, 0, ',')) !== false) {
        if ($header === null) {
            $header = array_map('mb_strtolower', $row);
            if (!in_array('nombre', $header)) { // sin cabecera
                $header = ['nombre','slug','icono','orden','activo','jerarquico'];
                // re-procesamos esta fila como datos
                $row = array_pad($row, count($header), null);
            } else {
                continue; // ya seteamos header, próxima vuelta trae datos
            }
        }
        $row = array_pad($row, count($header), null);
        $data = array_combine($header, $row);

        $nombre = trim($data['nombre'] ?? '');
        if ($nombre === '') continue;

        CatalogoItem::create([
            'catalogo_id' => $catalogo->id,
            'nombre'      => $nombre,
            'orden'       => isset($data['orden']) ? (int)$data['orden'] : null,
            'activo'      => isset($data['activo']) ? (bool)$data['activo'] : true,
            'meta'        => [
                'slug'       => ($data['slug'] ?? null) ?: Str::slug($nombre),
                'icono'      => $data['icono'] ?? null,
                'jerarquico' => isset($data['jerarquico']) ? (bool)$data['jerarquico'] : false,
            ],
        ]);
        $count++;
    }
    fclose($h);

    return back()->with('ok', "Se importaron {$count} términos.");
}


    public function subDestroy($catalogoId, $optId)
    {
        $catalogo = Catalogo::findOrFail($catalogoId);
        $catalogo->items()->whereKey($optId)->firstOrFail()->delete();

        return back()->with('ok', 'Término eliminado correctamente.');
    }
}
