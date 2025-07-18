<?php


namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Usuario;


class ProfileController extends Controller
{
  protected $userData;
  protected $user;
  protected $tramites;
  protected $pagos;
  protected $inspecciones;
  protected $citas;
  protected $documentos;

  public function __construct()
  {

    // Por ejemplo, obtenemos el usuario autenticado y lo guardamos como propiedad
    $this->userData = json_decode(file_get_contents(public_path('/json/user.json')), true);

    $this->user = $this->userData['userData'];
    //$this->documentos = $this->paginator($this->userData['documentos']);
  }

  public function index()
  {
    $perfilActivo = session('perfil_activo', 'ciudadano');
    return view('pages.profile.ciudadano.home', [
      'active' => 'perfil',
      'user' => $this->user,
      'perfilActivo' => $perfilActivo,
    ]);
  }
  // public function documentos()
  // {
  //   return view('pages.profile.ciudadano.documentos', [
  //     'active' => 'documentos',
  //     'documentos' => $this->documentos,
  //   ]);
  // }
}
