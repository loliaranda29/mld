<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


class EmpresasController extends Controller
{
  public function index()
  {
    return Inertia::render('Ciudadano/Empresas/Empresas');
  }
}
