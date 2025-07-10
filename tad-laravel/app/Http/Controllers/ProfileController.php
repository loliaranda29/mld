<?php


namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;

class ProfileController extends Controller
{
  protected $userData;
  protected $user;
  protected $tramites;
  protected $pagos;
  protected $inspecciones;
  protected $citas;

  public function __construct()
  {

    // Por ejemplo, obtenemos el usuario autenticado y lo guardamos como propiedad
    $this->userData = json_decode(file_get_contents(public_path('/json/user.json')), true);

    $this->user = $this->paginator($this->userData['userData']);
    $this->tramites = $this->paginator($this->userData['tramites']);
    $this->pagos = $this->paginator($this->userData['pagos']);
    $this->inspecciones = $this->paginator($this->userData['inspecciones']);
    $this->citas = $this->paginator($this->userData['citas']);
  }

  public function index()
  {
    return view('pages.profile.ciudadano.home', [
      'active' => 'perfil',
      'user' => $this->user
    ]);
  }
  public function documentos()
  {
    return view('profile.show', ['active' => 'documentos']);
  }

  public function tramites()
  {
    return view('pages.profile.ciudadano.tramites', [
      'active' => 'tramites',
      'tramites' => $this->tramites,
    ]);
  }

  public function pagos()
  {
    return view('pages.profile.ciudadano.pagos', [
      'active' => 'pagos',
      'pagos' => $this->pagos,
    ]);
  }

  public function inspecciones()
  {
    return view('pages.profile.ciudadano.inspecciones', [
      'active' => 'inspecciones',
      'inspecciones' => $this->inspecciones,
    ]);
  }

  public function citas()
  {
    return view('pages.profile.ciudadano.citas', [
      'active' => 'citas',
      'citas' => $this->citas,
    ]);
  }
  public function paginator($dataArray)
  {
    // Obtener página actual, default 1
    $page = request()->get('page', 1);
    // Definir elementos por página
    $perPage = 3;
    // Calcular offset
    $offset = ($page - 1) * $perPage;

    // Extraer solo los elementos para la página actual
    $itemsForCurrentPage = array_slice($dataArray, $offset, $perPage);

    // Crear paginador manualmente
    $data = new LengthAwarePaginator(
      $itemsForCurrentPage,
      count($dataArray), // total de elementos
      $perPage,
      $page,
      [
        'path' => request()->url(), // para que mantenga la URL actual
        'query' => request()->query(), // para mantener query params como ?buscar=xxx
      ]
    );
    return $data;
  }
}
