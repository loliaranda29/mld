<?php

use App\Http\Controllers\InspeccionesController;
use App\Http\Controllers\PagosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuariosController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/usuarios', [UsuariosController::class, 'index']);
Route::get('/usuarios/{id}', [UsuariosController::class, 'show']);
Route::get('/pagos', [PagosController::class, 'index']);
Route::get('/inspecciones/{id}', [InspeccionesController::class, 'show']);
