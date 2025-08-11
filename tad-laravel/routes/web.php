<?php

use App\Http\Controllers\CitasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\InspeccionesController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\tramitesController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Tramite_configController;
use App\Http\Controllers\BandejaController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('forgot-password', function () {
  return view('auth.forgot-password');
})->name('password.request');

Route::post('forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', function ($token) {
  return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('reset-password', [LoginController::class, 'reset'])->name('password.update');

Route::prefix('profile')->name('profile.')->group(function () {
  Route::get('/', [ProfileController::class, 'index'])->name('index'); // perfil por defecto
  Route::get('/documentos', [ProfileController::class, 'documentos'])->name('documentos');

  // Pagos
  Route::prefix('pagos')->name('pagos')->group(function () {
    Route::get('/', [PagosController::class, 'index'])->name('');
    Route::get('/{id}', [PagosController::class, 'show'])->name('.detail');
  });
  // Inspecciones
  Route::prefix('inspecciones')->name('inspecciones')->group(function () {
    Route::get('/', [InspeccionesController::class, 'index'])->name('');
    Route::get('/{id}', [InspeccionesController::class, 'show'])->name('.detail');
  });
  // tramites
  Route::prefix('tramites')->name('tramites')->group(function () {
    Route::get('/', [tramitesController::class, 'index'])->name('');
    Route::get('/{id}', [tramitesController::class, 'show'])->name('.detail');
  });

  Route::get('/citas', [CitasController::class, 'index'])->name('citas');
});

// 👔 Ruta para funcionario
Route::get('/funcionario', [FuncionarioController::class, 'home'])->name('funcionario.home');

// 🔁 Ruta para cambiar entre perfiles
Route::post('/profile/switch', function () {
    $actual = session('perfil_activo', 'ciudadano');
    $nuevo = $actual === 'ciudadano' ? 'funcionario' : 'ciudadano';
    session(['perfil_activo' => $nuevo]);

    return redirect()->route($nuevo === 'ciudadano' ? 'perfil.index' : 'funcionario.home');
})->name('profile.switch');

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');

// Ventanilla Digital
Route::get('/tramite_config', [Tramite_configController::class, 'indexFuncionario'])->name('funcionario.tramite_config');
Route::get('/tramites/crear', [Tramite_configController::class, 'create'])->name('funcionario.tramite.create');
Route::post('/tramites', [Tramite_configController::class, 'store'])->name('funcionario.tramite.store');
Route::get('/bandeja', [BandejaController::class, 'index'])->name('funcionario.bandeja');
Route::get('/bandeja/{id}', [BandejaController::class, 'show'])->name('funcionario.bandeja.show');


// Inspectores
Route::get('/inspectores', [InspectorController::class, 'index'])->name('inspectores.index');

// Pagos
Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');

// Citas
Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');

// Usuarios
Route::prefix('usuarios')->name('usuarios.')->group(function () {
    // Listado (ciudadanos)
    Route::get('/ciudadanos', [UsuariosController::class, 'ciudadanos'])->name('ciudadanos');

    // Permisos
    Route::get('/permisos', [UsuariosController::class, 'permisos'])->name('permisos');

    // Configuración
    Route::get('/configuracion', [UsuariosController::class, 'config'])->name('config');

    // Detalle (mostrar ficha del ciudadano) — si querés que sea /usuarios/{id}
    Route::get('/{id}', [UsuariosController::class, 'show'])->name('show');
});

Route::prefix('usuarios')->name('usuarios.')->group(function () {
    Route::post('{id}/deactivate', [UsuariosController::class, 'deactivate'])->name('deactivate');
    Route::post('{id}/password',   [UsuariosController::class, 'updatePassword'])->name('password');
    Route::post('{id}/email',      [UsuariosController::class, 'updateEmail'])->name('email');
});

// Ciudadanos -> Detalle
Route::get(
    '/usuarios/ciudadanos/{id}',
    [UsuariosController::class, 'ciudadanoShow']
)->name('funcionario.usuarios.ciudadanos.show');




// Catálogos
Route::get('/catalogos', [CatalogoController::class, 'index'])->name('catalogos.index');

// Filtros
Route::get('/filtros', [FiltroController::class, 'index'])->name('filtros.index');

// Estadísticas
Route::get('/estadisticas', [EstadisticaController::class, 'index'])->name('estadisticas');

// Registro de cambios
Route::get('/registro-cambios', [RegistroController::class, 'index'])->name('registro.cambios');

if (file_exists(__DIR__.'/superadmin_tramites.php')) {
    require __DIR__.'/superadmin_tramites.php';
}




