<?php


namespace App\Http\Controllers;

use App\Helpers\DataTransformer;
use App\Models\Cita;
use Illuminate\Http\Request;

class CitasController extends Controller
{
  protected $user;
  public function __construct()
  {
    $this->user = auth()->user();
  }
  public function index(Request $request)
  {
    $query = Cita::with('tramite')->where('usuario_id', $this->user->id);

    if ($request->filled('search')) {
      $search = $request->input('search');

      $query->where(function ($q) use ($search) {
        $q->where('numero_folio', 'like', "%$search%");
      });
    }

    $citas = $query->paginate(5);


    $citasPaginados = DataTransformer::paginarTransformados(
      collect($citas->items())->map([DataTransformer::class, 'citas']),
      $citas,
      $request
    );
    return view('pages.profile.ciudadano.citas', [
      'active' => 'citas',
      'citas' => $citasPaginados,
    ]);
  }
  public function show($id)
  {
    $cita = Cita::find($id);

    if (!$cita) {
      return response()->json([
        'success' => false,
        'message' => 'Cita no encontrada',
      ], 404);
    }

    $citaTransformado = DataTransformer::citas($cita);

    return view('pages.profile.ciudadano.details.citas', [
      'active' => 'citas',
      'cita' => $citaTransformado,
    ]);
  }
}
