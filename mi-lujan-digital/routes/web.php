<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CitasController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\InspeccionesController;
use App\Http\Controllers\PagosController;
// ✅ usar el controlador real que existe en tu app (sin subnamespace Profile)
use App\Http\Controllers\TramitesController;
use Illuminate\Auth\Events\Login;
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
use App\Http\Controllers\CiudadanoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\Funcionario\TramiteConfigController;
use App\Http\Controllers\Funcionario\TramiteController;
use App\Http\Controllers\Funcionario\TramiteRelacionesController;
use App\Http\Controllers\SolicitudesController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::get('/', [HomeController::class, 'index'])->name('home.index');


Route::middleware(['auth', 'verified'])
  ->prefix('profile')
  ->name('profile.')
  ->group(function () {

    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::get('/documentos', [ProfileController::class, 'documentos'])->name('documentos');

    // Catálogo y ficha
    Route::get('/catalogo', [TramitesController::class, 'catalogo'])->name('catalogo');
    Route::get('/tramites/{tramite}', [TramitesController::class, 'ficha'])->name('tramites.ficha');

    // Formulario de inicio de trámite
    Route::get('/tramites/{tramite}/iniciar', [SolicitudesController::class, 'create'])
      ->name('tramites.iniciar');

    // Guardado definitivo de solicitud
    Route::post('/solicitudes', [SolicitudesController::class, 'store'])->name('solicitudes.store');

    // Mis solicitudes
    Route::prefix('solicitudes')->name('solicitudes.')->group(function () {
      Route::get('/', [SolicitudesController::class, 'index'])->name('index');
      Route::get('/{id}', [SolicitudesController::class, 'show'])->name('show');
    });

    // Alias “Mis trámites”
    Route::get('/mis-tramites', [SolicitudesController::class, 'index'])->name('tramites');
    Route::get('/mis-tramites/{id}', [SolicitudesController::class, 'show'])->name('tramites.detail');

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

    // Citas
    Route::get('/citas', [CitasController::class, 'index'])->name('citas');
  });


/* ========= NUEVA RUTA PARA EL WIZARD DEL CIUDADANO =========
   Nombre EXACTO: tramite.update (sin prefijo "profile.")
   Recibe el PUT del formulario por pasos y lo procesa con SolicitudesController@store
   (si luego querés un método específico, cambiás el controlador/método aquí) */
Route::put('/profile/tramites/{tramite}', [SolicitudesController::class, 'store'])
  ->middleware('auth')
  ->name('tramite.update');
// ============================================================

Route::put('/solicitudes/{id}', [SolicitudesController::class, 'update'])
  ->name('solicitudes.update');


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
  Route::delete('/tramites/{id}', [Tramite_configController::class, 'destroy'])->name('funcionario.tramites.destroy');
  Route::post('/tramites/media-upload', [\App\Http\Controllers\Tramite_configController::class, 'mediaUpload'])
    ->name('tramites.media.upload')
    ->middleware('auth'); // ajusta al guard que uses
  Route::post('/funcionario/tramites/media', [Tramite_configController::class, 'mediaUpload'])
    ->name('tramites.media');
});

// Legacy: tu listado anterior
Route::get('/tramite_config', [Tramite_configController::class, 'indexFuncionario'])->name('funcionario.tramite_config');

// Bandeja (funcionario)
Route::get('/bandeja',      [BandejaController::class, 'index'])->name('funcionario.bandeja');
Route::get('/bandeja/{id}', [BandejaController::class, 'show'])->name('funcionario.bandeja.show');

// Inspectores / Pagos / Citas (módulos aparte)
Route::get('/inspectores', [InspectorController::class, 'index'])->name('inspectores.index');

Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');


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
  Route::get('/crear',   [CatalogosAdminController::class, 'create'])->name('create');
  Route::post('/',       [CatalogosAdminController::class, 'store'])->name('store');
  Route::delete('{id}',  [CatalogosAdminController::class, 'destroy'])->name('destroy');
  Route::get('{id}',     [CatalogosAdminController::class, 'show'])->name('show');

  // Subcatálogos (ítems)
  Route::get('{id}/subcatalogos',            [CatalogosAdminController::class, 'subcatalogos'])->name('subcatalogos');
  Route::post('{id}/subcatalogos',           [CatalogosAdminController::class, 'subStore'])->name('sub.store');     // 👈 NUEVA
  Route::post('{id}/subcatalogos/upload',    [CatalogosAdminController::class, 'subUpload'])->name('sub.upload');   // 👈 SIN optId
  Route::get('{id}/subcatalogos/{optId}',    [CatalogosAdminController::class, 'subShow'])->name('sub.show');
  Route::delete('{id}/subcatalogos/{optId}', [CatalogosAdminController::class, 'subDestroy'])->name('sub.destroy');
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

Route::prefix('funcionario/tramites/{tramite}/config')
  ->name('funcionario.tramites.config.')
  ->middleware(['web', 'auth'])
  ->group(function () {
    Route::post('folio/preview', [TramiteConfigController::class, 'previewFolio'])->name('folio.preview');
    Route::post('folio/generar', [TramiteConfigController::class, 'generarFolio'])->name('folio.generar');
    Route::post('folio/reset',   [TramiteConfigController::class, 'resetFolio'])->name('folio.reset');
  });

Route::prefix('funcionario/tramites')
  ->name('funcionario.tramites.')
  ->middleware(['web', 'auth'])
  ->group(function () {
    // solo actualizar la pestaña Relaciones
    Route::post('{tramite}/relaciones', [TramiteRelacionesController::class, 'update'])
      ->name('relaciones.update');
  });


////////////////////// Tincho ///////////////////

Route::get('/', function () {
  return Inertia::render('Welcome', [
    'canLogin' => Route::has('login'),
    'canRegister' => Route::has('register'),
    'laravelVersion' => Application::VERSION,
    'phpVersion' => PHP_VERSION,
  ]);
});

Route::middleware([
  'auth:sanctum',
  config('jetstream.auth_session'),
  'verified',
])->group(function () {
  Route::get('/ciudadano', function () {
    return redirect()->route('ciudadano.index');
  });
});


// 👔 Ruta home de funcionario
Route::get('/funcionario', [FuncionarioController::class, 'home'])->name('funcionario.home');


Route::prefix('ciudadano')->name('ciudadano.')->group(
  function () {
    Route::get('/', [CiudadanoController::class, 'index'])->name('index');
    Route::get('/perfil', [CiudadanoController::class, 'perfil'])->name('perfil');
    Route::prefix('citas')->name('citas.')->group(
      function () {
        Route::get('/citas', [CitaController::class, 'index'])->name('index');
        Route::get('/edit', [CitaController::class, 'edit'])->name('edit');
        Route::get('/edit/{id}', [CitaController::class, 'show'])->name('show');
        Route::put('/edit/{id}', [CitaController::class, 'update'])->name('update');
      }
    );

    Route::prefix('tramites')->name('tramites.')->group(
      function () {
        Route::get('/solicitudes', [TramitesController::class, 'solicitudes'])->name('solicitudes');
        Route::get('/catalogo', [TramitesController::class, 'catalogo'])->name('catalogo');
        Route::get('/edit', [CitaController::class, 'edit'])->name('edit');
        Route::get('/edit/{id}', [CitaController::class, 'show'])->name('show');
        Route::put('/edit/{id}', [CitaController::class, 'update'])->name('update');
        Route::get('/{id}/iniciar', [SolicitudesController::class, 'catalogo'])->name('iniciar');
      }
    );
  }
);
Route::prefix('citas')->name('citas.')->group(
  function () {
    Route::get('/', [CitaController::class, 'index'])->name('index');
    Route::get('/create', [CitaController::class, 'create'])->name('create');
    Route::post('/create', [CitaController::class, 'store'])->name('store');
    Route::get('/edit', [CitaController::class, 'edit'])->name('edit');
    Route::get('/edit/{id}', [CitaController::class, 'show'])->name('show');
    Route::put('/edit/{id}', [CitaController::class, 'update'])->name('update');
  }
);
