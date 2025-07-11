<?php


namespace App\Http\Controllers;


class HomeController extends Controller
{
  public function index()
  {
    $homeJson = json_decode(file_get_contents(public_path('/json/home.json')), true);
    $tramitesMasBuscados = $homeJson['tramitesMasBuscados'];
    $oficinas = $homeJson['oficinas'];
    $categorias = $homeJson['categorias'];
    return view('pages.home', compact('tramitesMasBuscados', 'oficinas', 'categorias'));
  }
}
