<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Helpers\DataTransformer;

class UsuariosController extends Controller
{
  /**
   * Muestra una lista paginada de usuarios.
   */
  public function index(Request $request)
  {
    $usuarios = Usuario::with('permiso')->paginate(10);

    // Transformar colección con helper
    $usuariosPaginados = DataTransformer::paginarTransformados(
      collect($usuarios->items())->map([DataTransformer::class, 'usuarios']),
      $usuarios,
      $request
    );


    return response()->json($usuariosPaginados);
  }

  /**
   * Muestra un usuario específico por ID.
   */
  public function show($id)
  {
    $usuario = Usuario::with('permiso', 'pago')->find($id);

    if (!$usuario) {
      return response()->json([
        'success' => false,
        'message' => 'Usuario no encontrado',
      ], 404);
    }

    $usuarioTransformado = DataTransformer::usuarios($usuario);

    return response()->json([
      'success' => true,
      'data' => $usuarioTransformado,
    ]);
  }
}
