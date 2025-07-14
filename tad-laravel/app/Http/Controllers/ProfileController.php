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
  protected $documentos;

  public function __construct()
  {

    // Por ejemplo, obtenemos el usuario autenticado y lo guardamos como propiedad
    $this->userData = json_decode(file_get_contents(public_path('/json/user.json')), true);

    $this->user = $this->userData['userData'];
    $this->tramites = $this->paginator($this->userData['tramites']);
    $this->pagos = $this->paginator($this->userData['pagos']);
    $this->inspecciones = $this->paginator($this->userData['inspecciones']);
    $this->citas = $this->paginator($this->userData['citas']);
    $this->documentos = $this->paginator($this->userData['documentos']);
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
  public function documentos()
  {
    return view('pages.profile.ciudadano.documentos', [
      'active' => 'documentos',
      'documentos' => $this->documentos,
    ]);
  }

  public function tramites()
  {
    return view('pages.profile.ciudadano.tramites', [
      'active' => 'tramites',
      'tramites' => $this->tramites,
    ]);
  }

  public function tramitesShow($id)
  {
    // Buscar el trámite por ID
    $tramite = array_values(array_filter($this->userData['tramites'], function ($tramite) use ($id) {
      return $tramite['id'] == $id;
    }))[0] ?? null;
    // Retornar la vista con el trámite
    return view('pages.profile.ciudadano.details.tramites', [
      'active' => 'tramites',
      'tramite' => $tramite,
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
