<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;


class FuncionarioController extends Controller
{
    public function home()
{
    $funcionario = Funcionario::get();
    return view('pages.profile.funcionario.home', [
        'funcionario' => $funcionario,
        'active' => 'perfil', // o el nombre de la sección activa
    ]);
}

public function listadoTramites()
{
    return view('pages.profile.funcionario.listado-tramites');
}


}
