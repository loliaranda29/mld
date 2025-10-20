{{-- Header --}}
<nav class="navbar navbar-expand-lg shadow-sm" style="height: 80px; position:fixed; background-color:rgb(255, 215, 0); z-index:1000; width:100%;">
  <div class="container-fluid px-3">
    <a class="navbar-brand d-flex align-items-center col-md-2" href="/">
      <img src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/brand%2FuserImages%2Flogo_fondo_boton3_time1723479741442.png?alt=media&token=8c0166a1-a82d-412b-abcf-b8ad7a61ad12"
        alt="Logo Luján Digital"
        class="img-fluid"
        style="height: 40px; object-fit: contain;">
    </a>

    <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end col-md-10" id="navbarContent">
      <div class="d-flex align-items-center border-end pe-3 me-3">
        <a href="#" class="btn btn-link text-dark text-decoration-none d-flex align-items-center">
          <i class="mdi mdi-view-module me-1"></i>
          <span>Servicios digitales</span>
        </a>
      </div>

      {{-- ✅ Mostrar login o usuario --}}
      @guest
      <div>
        <a href="{{ route('login') }}" class="btn btn-link text-dark text-decoration-none d-flex align-items-center">
          <i class="mdi mdi-account-outline me-1"></i>
          <span>Iniciá sesión / Registrarse</span>
        </a>
      </div>
      @else
      {{-- Usuario logueado --}}
      <div class="dropdown">
        <a class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" href="#" role="button" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=298e8c&color=fff&rounded=true&size=40' }}"
            alt="Avatar"
            class="rounded-circle me-2"
            style="width: 40px; height: 40px; object-fit: cover;">
          <span>{{ Auth::user()->nombre }} {{ Auth::user()->apellido }}</span>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser">
          <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="mdi mdi-account-circle-outline me-1"></i> Mi perfil</a></li>
          <hr>
          <li>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="dropdown-item"><i class="mdi mdi-logout me-1"></i> Cerrar sesión</button>
            </form>
          </li>
        </ul>
      </div>
      @endguest

    </div>
  </div>
</nav>