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
  public function ciudadanos()
{
    // Simulación de datos estáticos por ahora
    $ciudadanos = [
        [
            'nombre' => 'Pam Martínez',
            'correo' => 'fernando~1@os.city',
            'fecha' => '16/11/2023 21:04:35'
        ],
        [
            'nombre' => 'Suleyma Mota',
            'correo' => 'suley301194@gmail.com',
            'fecha' => '13/03/2024 13:42:57'
        ],
        [
            'nombre' => 'Evaristo Miguel FAJARDO',
            'correo' => 'evo.familia6@gmail.com',
            'fecha' => '06/11/2024 21:03:09'
        ],
        [
            'nombre' => 'Pascual Mario GOMEZ',
            'correo' => 'mariogom1957@gmail.com',
            'fecha' => '08/11/2024 19:51:50'
        ],
        [
            'nombre' => 'Lucas Agustín Castillo',
            'correo' => 'agustincastillo086@gmail.com',
            'fecha' => '08/11/2024 19:52:35'
        ],
    ];

    return view('pages.profile.funcionario.usuarios.ciudadanos', [
        'ciudadanos' => $ciudadanos,
        'totalCiudadanos' => count($ciudadanos),
        'active' => 'usuarios'
    ]);
    }
    public function permisos()
    {
        return view('pages.profile.funcionario.usuarios.permisos');
    }

    public function config()
    {
        return view('pages.profile.funcionario.usuarios.config');
    }


}
