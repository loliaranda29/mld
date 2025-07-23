<?php


namespace App\Http\Controllers;

use App\Helpers\DataTransformer;
use App\Models\Pago;
use Illuminate\Http\Request;

class PagosController extends Controller
{
  protected $user;
  public function __construct()
  {
    $this->user = auth()->user();
  }
  public function index(Request $request)
  {
    $query = Pago::where('usuario_id', $this->user->id);

    if ($request->filled('search')) {
      $search = $request->input('search');

      $query->where(function ($q) use ($search) {
        $q->where('numero_folio', 'like', "%$search%");
      });
    }

    $pagos = $query->paginate(5);

    $pagosPaginados = DataTransformer::paginarTransformados(
      collect($pagos->items())->map([DataTransformer::class, 'pagos']),
      $pagos,
      $request
    );

    return view('pages.profile.ciudadano.pagos', [
      'active' => 'pagos',
      'pagos' => $pagosPaginados,
    ]);
  }
  public function show($id)
  {
    $pago = Pago::find($id);

    if (!$pago) {
      return response()->json([
        'success' => false,
        'message' => 'Pago no encontrado',
      ], 404);
    }

    $pagoTransformado = DataTransformer::pagos($pago);

    return view('pages.profile.ciudadano.details.pagos', [
      'active' => 'pagos',
      'pago' => $pagoTransformado,
    ]);
  }
}
