<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FuncionarioController;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

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
  Route::get('/pagos', [ProfileController::class, 'pagos'])->name('pagos');
  Route::get('/inspecciones', [ProfileController::class, 'inspecciones'])->name('inspecciones');
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

Route::post('/logout', function (Request $request) {
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect('/');
})->name('logout');