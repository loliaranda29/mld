<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


class RepresentantesController extends Controller
{
  public function index()
  {
    return Inertia::render('Ciudadano/Representantes/Representantes');
  }
}
