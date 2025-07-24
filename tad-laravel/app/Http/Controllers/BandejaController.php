<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BandejaController extends Controller
{
    public function index()
    {
        // Más adelante podés cargar solicitudes reales desde DB
        return view('pages.profile.funcionario.bandeja');
    }
}
