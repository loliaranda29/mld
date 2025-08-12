<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CatalogosAdminController extends Controller
{
    // Para clonar la UI usamos datos en memoria
    protected function seed()
    {
        // ⚠️ En producción: reemplazar por modelo Catalogo::query()->latest()->get()
        return collect([
            ['id'=>1,'nombre'=>'DestinoPermiso',             'created_at'=>'2025-03-20 09:37:54'],
            ['id'=>2,'nombre'=>'Corresponde',                'created_at'=>'2025-03-20 09:33:26'],
            ['id'=>3,'nombre'=>'Construcción Destino',       'created_at'=>'2025-03-20 09:24:55'],
            ['id'=>4,'nombre'=>'Catalogo de prueba',         'created_at'=>'2024-07-01 10:57:37'],
            ['id'=>5,'nombre'=>'Categorías Particulares',    'created_at'=>'2024-07-01 09:06:54'],
            ['id'=>6,'nombre'=>'Barrios',                    'created_at'=>'2023-12-19 11:44:53'],
            ['id'=>7,'nombre'=>'Área',                       'created_at'=>'2023-12-18 11:54:20'],
            ['id'=>8,'nombre'=>'Material Techo',             'created_at'=>'2023-06-02 10:02:29'],
            ['id'=>9,'nombre'=>'Tipo de DNI',                'created_at'=>'2023-05-22 11:25:22'],
            ['id'=>10,'nombre'=>'Estado Civil',              'created_at'=>'2023-05-22 11:24:26'],
        ]);
    }

    public function index(Request $request)
    {
        // Paginar manualmente la colección para clonar la UI
        $perPage   = (int)($request->get('per_page', 10));
        $page      = (int)($request->get('page', 1));
        $all       = $this->seed()->sortByDesc('created_at')->values();
        $total     = $all->count();
        $slice     = $all->slice(($page-1)*$perPage, $perPage)->values();

        return view('pages.profile.funcionario.catalogos.index', [
            'active'      => 'catalogos',
            'catalogos'   => $slice,
            'page'        => $page,
            'perPage'     => $perPage,
            'total'       => $total,
        ]);
    }

    public function store(Request $request)
    {
        // En la demo no persistimos; validamos y “simulamos éxito”
        $request->validate([
            'nombre' => ['required','string','max:200'],
        ]);

        // Aquí guardarías el registro en BD (Catalogo::create([...]))
        return back()->with('ok', 'Catálogo agregado correctamente.');
    }

    public function show($id)
    {
        // En demo: respondemos 404 si no existe
        $item = $this->seed()->firstWhere('id', (int)$id);
        if (!$item) {
            abort(404);
        }

        return view('pages.profile.funcionario.catalogos.show', [
            'active'  => 'catalogos',
            'catalogo'=> $item,
        ]);
    }

    public function destroy($id)
    {
        // En la demo no eliminamos nada. En BD: Catalogo::findOrFail($id)->delete();
        return back()->with('ok', 'Catálogo eliminado.');
    }
    public function create()
{
    // Solo renderiza la pantalla de alta (clon visual)
    return view('pages.profile.funcionario.catalogos.create', [
        'active' => 'catalogos',
    ]);
}

public function subcatalogosSeed()
{
    // Demo (clona la UI). En prod, traer desde BD.
    return collect([
        ['id' => '1SmNbPMRvt3hE8hSJ6A3', 'nombre' => 'CONEXIÓN ÚNICA', 'slug' => 'conexionUnica'],
        ['id' => 'fqsLvhiDF4xLTwULRvV3', 'nombre' => 'CASA Nº',          'slug' => 'casaN'],
        ['id' => '3gCKU82ZScKomPPSI2Z7q','nombre' => 'DPTO Nº',          'slug' => 'dptoN'],
        ['id' => 'Fq3717BlI2WqhkOTSJ',   'nombre' => 'LOCAL Nº',         'slug' => 'localN'],
        ['id' => 'H5IFGXMd19YrwrEONagf', 'nombre' => 'ESP.COM. Nº',      'slug' => 'espComN'],
        ['id' => '3wnjKoyTc8yyKT4CDvKM', 'nombre' => 'DEPÓSITO Nº',      'slug' => 'depositoN'],
        ['id' => 'W4Vqawsw88S8AuxAyfDp', 'nombre' => 'GALPÓN Nº',        'slug' => 'galponN'],
        ['id' => 'Oh9MQijQm2jE5lGW7X1M', 'nombre' => 'OFICINA Nº',       'slug' => 'oficinaN'],
        ['id' => 'DgnKSbyiKHP2whH7dxIDV','nombre' => 'PORTAL ING. Nº',    'slug' => 'portalIngN'],
    ]);
}

public function subcatalogos(Request $request, $id)
{
    // En demo ignoramos $id; en prod usalo para filtrar subcatálogos de ese catálogo.
    $perPage = (int)($request->get('per_page', 10));
    $page    = (int)($request->get('page', 1));

    $all   = $this->subcatalogosSeed()->values();
    $total = $all->count();
    $slice = $all->slice(($page-1)*$perPage, $perPage)->values();

    return view('pages.profile.funcionario.catalogos.subcatalogos', [
        'active'       => 'catalogos',
        'catalogoId'   => $id,
        'subcatalogos' => $slice,
        'page'         => $page,
        'perPage'      => $perPage,
        'total'        => $total,
    ]);
}


}

