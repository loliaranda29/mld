<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Funcionario;


class FuncionarioController extends Controller
{
    public function index()
    {
        $funcionario = Funcionario::get();
        return view('pages.profile.funcionario.index', compact('funcionario'));
    }

}
