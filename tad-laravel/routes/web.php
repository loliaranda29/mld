<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\InspeccionesController;
use App\Http\Controllers\PagosController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;

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

Route::prefix('profile')->name('profile.')->group(function () {
  Route::get('/', [ProfileController::class, 'index'])->name('perfil'); // perfil por defecto
  Route::get('/documentos', [ProfileController::class, 'documentos'])->name('documentos');
  Route::prefix('tramites')->name('tramites')->group(function () {
    Route::get('/', [ProfileController::class, 'tramites'])->name('');
    Route::get('/{id}', [ProfileController::class, 'tramitesShow'])->name('.detail');
  });

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

  Route::get('/citas', [ProfileController::class, 'citas'])->name('citas');
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
