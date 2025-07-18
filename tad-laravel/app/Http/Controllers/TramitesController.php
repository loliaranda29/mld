<?php


namespace App\Http\Controllers;

use App\Helpers\DataTransformer;
use App\Models\Tramite;
use Illuminate\Http\Request;

class tramitesController extends Controller
{
  public function index(Request $request)
  {
    $query = Tramite::where('usuario_id', 15104);

    if ($request->filled('search')) {
      $search = $request->input('search');

      $query->where(function ($q) use ($search) {
        $q->where('expediente', 'like', "%$search%");
      });
    }

    $tramites = $query->paginate(5);

    $tramitesPaginados = DataTransformer::paginarTransformados(
      collect($tramites->items())->map([DataTransformer::class, 'tramites']),
      $tramites,
      $request
    );

    return view('pages.profile.ciudadano.tramites', [
      'active' => 'tramites',
      'tramites' => $tramitesPaginados,
    ]);
  }
  public function show($id)
  {
    $tramite = Tramite::find($id);
    //$tramit = Tramite::with('inmueble', 'inicioTramite', 'escribano', 'transmitente', 'adquirente', 'documento')->find($id);
    if (!$tramite) {
      return response()->json([
        'success' => false,
        'message' => 'Trámite no encontrado',
      ], 404);
    }

    $tramiteTransformada = DataTransformer::tramites($tramite);

    return view('pages.profile.ciudadano.details.tramites', [
      'active' => 'tramites',
      'tramite' => $tramiteTransformada,
    ]);
  }
}
