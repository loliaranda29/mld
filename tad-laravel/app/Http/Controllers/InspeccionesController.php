<?php


namespace App\Http\Controllers;

use App\Helpers\DataTransformer;
use App\Models\Inspeccion;
use Illuminate\Http\Request;

class InspeccionesController extends Controller
{
  public function index(Request $request)
  {
    $query = Inspeccion::with('inspector')->where('usuario_id', 15104);

    if ($request->filled('search')) {
      $search = $request->input('search');

      $query->where(function ($q) use ($search) {
        $q->where('folio_inspeccion', 'like', "%$search%");
      });
    }

    $inspecciones = $query->paginate(5);

    $inspeccionesPaginados = DataTransformer::paginarTransformados(
      collect($inspecciones->items())->map([DataTransformer::class, 'inspecciones']),
      $inspecciones,
      $request
    );

    return view('pages.profile.ciudadano.inspecciones', [
      'active' => 'inspecciones',
      'inspecciones' => $inspeccionesPaginados,
    ]);
  }
  public function show($id)
  {
    $inspeccion = Inspeccion::with('inspector.superior')->find($id);
    if (!$inspeccion) {
      return response()->json([
        'success' => false,
        'message' => 'Inspección no encontrada',
      ], 404);
    }

    $inspeccionTransformada = DataTransformer::inspecciones($inspeccion);

    return view('pages.profile.ciudadano.details.inspecciones', [
      'active' => 'inspecciones',
      'inspeccion' => $inspeccionTransformada,
    ]);
  }
}
