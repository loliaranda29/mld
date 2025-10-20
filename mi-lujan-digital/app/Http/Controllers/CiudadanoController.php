<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class CiudadanoController extends Controller
{
  public function index()
  {
    return Inertia::render('Ciudadano/Ciudadano');
  }
  public function perfil()
  {
    return Inertia::render('Ciudadano/Perfil');
  }
}
