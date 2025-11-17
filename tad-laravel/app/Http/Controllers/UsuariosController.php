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

    public function config()
    {
        return view('pages.profile.funcionario.usuarios.config');
    }
    public function ciudadanoShow($id)
{
    // Datos de ejemplo (reemplazá por la consulta real)
    $ciudadano = [
        'id' => $id,
        'cuil' => '7654321',
        'nombre' => 'Pam',
        'apellido' => 'Martínez',
        'fecha_nac' => '1992-05-06',
        'email' => 'fernando~1@os.city',
        'telefono_fijo' => '',
        'telefono_celular' => '',
        'direccion' => [
            'cp' => '',
            'barrio' => '',
            'calle' => '',
            'numero' => '',
            'depto' => '',
            'referencias' => '',
        ],
        // Solo para avatar iniciales
        'iniciales' => 'PM',
    ];

    return view('pages.profile.funcionario.usuarios.show', [
        'c'      => $ciudadano,
        'active' => 'usuarios',
    ]);
    }
    public function deactivate($id): RedirectResponse
      {
          $u = Usuario::findOrFail($id);
          // Si tu tabla es `users`, asegurate de que el modelo `Usuario` apunte a esa tabla.
          // Ej: protected $table = 'users';

          // Suponemos que hay un boolean `active` (o `activo`). Cambiá el nombre si hace falta.
          $flag = $u->active ?? $u->activo ?? 1;
          if (property_exists($u, 'active')) {
              $u->active = !$flag;
          } else {
              $u->activo = !$flag;
          }
          $u->save();

          return back()->with('ok', 'Usuario ' . ($flag ? 'desactivado' : 'activado') . ' correctamente.');
      }

      public function updatePassword(Request $request, $id): RedirectResponse
      {
          $request->validate([
              'password' => ['required', 'min:8', 'confirmed'],
          ]);

          $u = Usuario::findOrFail($id);
          $u->password = Hash::make($request->password);
          $u->save();

          return back()->with('ok', 'Contraseña actualizada.');
      }

      public function updateEmail(Request $request, $id): RedirectResponse
      {
          // Si tu tabla real es `users`, usá 'users' en la regla unique
          $request->validate([
              'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($id)],
          ]);

          $u = Usuario::findOrFail($id);
          $u->email = $request->email;
          $u->save();

          return back()->with('ok', 'Correo electrónico actualizado.');
      }
       public function permisos()
        {
            // Demo: datos estáticos para clonar la UI
            $roles = [
                ['id' => 1, 'nombre' => 'Seguimiento',   'modulo' => 'Trámites', 'rol_base' => 'Operador'],
                ['id' => 2, 'nombre' => 'Director',      'modulo' => 'Trámites', 'rol_base' => 'Administrador'],
                ['id' => 3, 'nombre' => 'Auditor',       'modulo' => 'Trámites', 'rol_base' => 'Administrador'],
                ['id' => 4, 'nombre' => 'control',       'modulo' => 'Trámites', 'rol_base' => 'Operador'],
                ['id' => 5, 'nombre' => 'Visualizador',  'modulo' => 'Trámites', 'rol_base' => 'Editor'],
            ];

            return view('pages.profile.funcionario.usuarios.permisos', [
                'roles'  => $roles,
                'active' => 'permisos',
            ]);
        }

        public function crearRol()
        {
            // Más adelante podés renderizar un form real.
            return back()->with('status', 'Abrir modal/forma de "Nuevo rol" (placeholder).');
        }

        public function editarRol($id)
        {
            // Placeholder; en real traerías el rol por $id y mostrarías el form de edición
            return back()->with('status', "Editar rol ID {$id} (placeholder).");
        }
        

}
