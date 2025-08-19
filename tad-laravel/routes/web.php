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
use App\Http\Controllers\PagosAdminController;
use App\Http\Controllers\CatalogosAdminController;
use App\Http\Controllers\FiltrosAdminController;
use App\Http\Controllers\ConfiguracionAdminController;
use App\Http\Controllers\EstadisticasAdminController;
use App\Http\Controllers\ChangeLogAdminController;

// Agregados porque los usás en rutas más abajo
use App\Http\Controllers\InspectorController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\RegistroController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout'); // único logout

// Password reset
Route::get('forgot-password', function () {
  return view('auth.forgot-password');
})->name('password.request');

Route::post('forgot-password', [LoginController::class, 'sendResetLinkEmail'])->name('password.email');

Route::get('reset-password/{token}', function ($token) {
  return view('auth.reset-password', ['token' => $token]);
})->name('password.reset');

Route::post('reset-password', [LoginController::class, 'reset'])->name('password.update');

// Perfil (ciudadano)
Route::prefix('profile')->name('profile.')->group(function () {
  Route::get('/', [ProfileController::class, 'index'])->name('index');
  Route::get('/documentos', [ProfileController::class, 'documentos'])->name('documentos');

  // Pagos (perfil ciudadano)
  Route::prefix('pagos')->name('pagos')->group(function () {
    Route::get('/', [PagosController::class, 'index'])->name('');
    Route::get('/{id}', [PagosController::class, 'show'])->name('.detail');
  });

  // Inspecciones (perfil ciudadano)
  Route::prefix('inspecciones')->name('inspecciones')->group(function () {
    Route::get('/', [InspeccionesController::class, 'index'])->name('');
    Route::get('/{id}', [InspeccionesController::class, 'show'])->name('.detail');
  });

  // Trámites (perfil ciudadano)
  Route::prefix('tramites')->name('tramites')->group(function () {
    Route::get('/', [tramitesController::class, 'index'])->name('');
    Route::get('/{id}', [tramitesController::class, 'show'])->name('.detail');
  });

  Route::get('/citas', [CitasController::class, 'index'])->name('citas');
});

// 👔 Ruta home de funcionario
Route::get('/funcionario', [FuncionarioController::class, 'home'])->name('funcionario.home');

// 🔁 Cambiar entre perfiles (fix: usa profile.index)
Route::post('/profile/switch', function () {
  $actual = session('perfil_activo', 'ciudadano');
  $nuevo  = $actual === 'ciudadano' ? 'funcionario' : 'ciudadano';
  session(['perfil_activo' => $nuevo]);

  return redirect()->route($nuevo === 'ciudadano' ? 'profile.index' : 'funcionario.home');
})->name('profile.switch');

/* ===========================
   Ventanilla Digital - TRÁMITES (FUNCIONARIO)
   CRUD bajo /funcionario/tramites/* con nombres funcionario.tramites.*
   Mantengo además alias legacy funcionario.tramite.* (singular)
   y la ruta /tramite_config que ya usabas.
   =========================== */

Route::prefix('funcionario/tramites')->name('funcionario.tramites.')->group(function () {
  // listado (antes: indexFuncionario)
  Route::get('/', [Tramite_configController::class, 'indexFuncionario'])->name('index');

  // crear
  Route::get('/crear', [Tramite_configController::class, 'create'])->name('create');
  Route::post('/',     [Tramite_configController::class, 'store'])->name('store');

  // editar / actualizar
  Route::get('/{tramite}/editar', [Tramite_configController::class, 'edit'])->name('edit');
  Route::put('/{tramite}',        [Tramite_configController::class, 'update'])->name('update');

  // ver detalle
  Route::get('/{tramite}', [Tramite_configController::class, 'show'])->name('show');
});

// Alias legacy para no tocar vistas que usan funcionario.tramite.*
Route::prefix('funcionario')->group(function () {
  Route::get('/tramite/crear',            [Tramite_configController::class, 'create'])->name('funcionario.tramite.create');
  Route::post('/tramite',                 [Tramite_configController::class, 'store'])->name('funcionario.tramite.store');
  Route::get('/tramite/{tramite}/editar', [Tramite_configController::class, 'edit'])->name('funcionario.tramite.edit');
  Route::put('/tramite/{tramite}',        [Tramite_configController::class, 'update'])->name('funcionario.tramite.update');
  Route::get('/tramite/{tramite}',        [Tramite_configController::class, 'show'])->name('funcionario.tramite.show');
});

// Legacy: tu listado anterior
Route::get('/tramite_config', [Tramite_configController::class, 'indexFuncionario'])->name('funcionario.tramite_config');

// Bandeja (funcionario)
Route::get('/bandeja',      [BandejaController::class, 'index'])->name('funcionario.bandeja');
Route::get('/bandeja/{id}', [BandejaController::class, 'show'])->name('funcionario.bandeja.show');

// Inspectores / Pagos / Citas (módulos aparte)
Route::get('/inspectores', [InspectorController::class, 'index'])->name('inspectores.index');

Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');

Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');

// Usuarios (funcionario)
Route::prefix('usuarios')->name('usuarios.')->group(function () {
  // Listado (ciudadanos)
  Route::get('/ciudadanos', [UsuariosController::class, 'ciudadanos'])->name('ciudadanos');

  // Permisos
  Route::get('/permisos', [UsuariosController::class, 'permisos'])->name('permisos');

  // Configuración
  Route::get('/configuracion', [UsuariosController::class, 'config'])->name('config');

  // Detalle
  Route::get('/{id}', [UsuariosController::class, 'show'])->name('show');
});

Route::prefix('usuarios')->name('usuarios.')->group(function () {
  Route::post('{id}/deactivate', [UsuariosController::class, 'deactivate'])->name('deactivate');
  Route::post('{id}/password',   [UsuariosController::class, 'updatePassword'])->name('password');
  Route::post('{id}/email',      [UsuariosController::class, 'updateEmail'])->name('email');
});

// Ciudadanos -> Detalle
Route::get('/usuarios/ciudadanos/{id}', [UsuariosController::class, 'ciudadanoShow'])
  ->name('funcionario.usuarios.ciudadanos.show');

/* --- Permisos / Roles --- */
Route::get('/usuarios/permisos',                 [UsuariosController::class, 'permisos'])->name('usuarios.permisos');
Route::get('/usuarios/permisos/crear',           [UsuariosController::class, 'crearRol'])->name('usuarios.permisos.create');
Route::get('/usuarios/permisos/{id}/editar',     [UsuariosController::class, 'editarRol'])->name('usuarios.permisos.edit');

/* --- Pagos (funcionario/admin) --- */
Route::prefix('funcionario/pagos')->name('pagos.')->group(function () {
  Route::get('/',               [PagosAdminController::class, 'index'])->name('index');
  Route::get('/conceptos',      [PagosAdminController::class, 'conceptos'])->name('conceptos');
  Route::get('/configuracion',  [PagosAdminController::class, 'config'])->name('config');

  // CRUD valor de la UT
  Route::post('/ut',            [PagosAdminController::class, 'utStore'])->name('ut.store');
  Route::put('/ut/{id}',        [PagosAdminController::class, 'utUpdate'])->name('ut.update');
  Route::delete('/ut/{id}',     [PagosAdminController::class, 'utDestroy'])->name('ut.destroy');
});

/* --- Catálogos (tal cual) --- */
Route::prefix('funcionario/catalogos')->name('catalogos.')->group(function () {
  Route::get('/',        [CatalogosAdminController::class, 'index'])->name('index');
  Route::get('/crear',   [CatalogosAdminController::class, 'create'])->name('create'); // ⬅️ NUEVO
  Route::post('/',       [CatalogosAdminController::class, 'store'])->name('store');
  Route::delete('{id}',  [CatalogosAdminController::class, 'destroy'])->name('destroy');
  Route::get('{id}',     [CatalogosAdminController::class, 'show'])->name('show');

  // Subcatálogos
  Route::get('{id}/subcatalogos',                 [CatalogosAdminController::class, 'subcatalogos'])->name('subcatalogos');
  Route::get('{id}/subcatalogos/{optId}',         [CatalogosAdminController::class, 'subShow'])->name('sub.show');
  Route::post('{id}/subcatalogos/{optId}/upload', [CatalogosAdminController::class, 'subUpload'])->name('sub.upload');
  Route::delete('{id}/subcatalogos/{optId}',      [CatalogosAdminController::class, 'subDestroy'])->name('sub.destroy');
});

/* --- Filtros (funcionario) --- */
Route::prefix('funcionario/filtros')->name('filtros.')->group(function () {
  Route::get('/',        [FiltrosAdminController::class, 'index'])->name('index');
  Route::post('/toggle', [FiltrosAdminController::class, 'toggle'])->name('toggle');
  Route::post('/store',  [FiltrosAdminController::class, 'store'])->name('store');
  Route::delete('/{id}', [FiltrosAdminController::class, 'destroy'])->name('destroy');
});

/* --- Estadísticas (funcionario) --- */
Route::prefix('funcionario')->group(function () {
  Route::get('/estadisticas', [EstadisticasAdminController::class, 'index'])->name('estadisticas');
});

/* --- Registro de cambios --- */
Route::get('/registro-cambios', [RegistroController::class, 'index'])->name('registro.cambios');

Route::prefix('funcionario')->group(function () {
  Route::get('/registro-cambios',        [ChangeLogAdminController::class, 'index'])->name('registro.cambios');
  Route::get('/registro-cambios/export', [ChangeLogAdminController::class, 'export'])->name('registro.cambios.export');
});

/* --- Rutas del Súper Admin (si existen en archivo separado) --- */
if (file_exists(__DIR__ . '/superadmin_tramites.php')) {
  require __DIR__ . '/superadmin_tramites.php';
}



Route::prefix('funcionario/configuracion')->name('configuracion.')->group(function () {
    Route::get('/',                     [ConfiguracionAdminController::class, 'index'])->name('index');
    Route::post('/guardar',             [ConfiguracionAdminController::class, 'guardar'])->name('guardar');

    Route::post('/inhabiles',           [ConfiguracionAdminController::class, 'agregarInhabil'])->name('inhabiles.add');
    Route::delete('/inhabiles/{dia}',   [ConfiguracionAdminController::class, 'eliminarInhabil'])->name('inhabiles.del');

    Route::get('/apariencia',           [ConfiguracionAdminController::class, 'aparienciaIndex'])->name('apariencia.index');
    Route::post('/apariencia/guardar',  [ConfiguracionAdminController::class, 'aparienciaGuardar'])->name('apariencia.guardar');

    Route::get('/seo',                  [ConfiguracionAdminController::class, 'seoIndex'])->name('seo.index');
    Route::post('/seo/guardar',         [ConfiguracionAdminController::class, 'seoGuardar'])->name('seo.guardar');

    Route::get('/mapa',                 [ConfiguracionAdminController::class, 'mapaIndex'])->name('mapa.index');
    Route::post('/mapa/guardar',        [ConfiguracionAdminController::class, 'mapaGuardar'])->name('mapa.guardar');
});
