<!-- Perfil del funcionario (mock) -->
<div class="d-flex align-items-center p-3 border-bottom">
  <div class="me-3">
    <img src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/users%2FEL7ME2E54uccdpCK6xnC7znfFBd2%2FprofilePicture.jpg?alt=media&token=94664911-a337-4c9f-bab7-f0e0c180bf40"
      alt="Foto de perfil"
      class="rounded-circle"
      style="width: 40px; height: 40px; object-fit: cover;">
  </div>
  <div>
    <div class="fw-bold">Alicia Aranda</div>
    <div class="text-muted small">
      Nivel 1<br>
      Funcionario
    </div>
  </div>
</div>

<!-- Menú lateral del funcionario -->
<ul class="nav flex-column nav-pills gap-1">

  <!-- Sección: Ventanilla Digital -->
  <li class="nav-item">
    <div class="fw-bold text-uppercase small px-3 pt-3">Ventanilla Digital</div>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'tramite_config' ? 'active' : '' }}" href="{{ route('funcionario.tramite_config') }}">
      <i class="bi bi-list-task me-2"></i> Listado de trámites
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'bandeja' ? 'active' : '' }}" href="{{ route('funcionario.bandeja') }}">
      <i class="bi bi-inbox me-2"></i> Bandeja de entrada
    </a>
  </li>

  <!-- Sección: Inspectores -->
  <li class="nav-item">
    <div class="fw-bold text-uppercase small px-3 pt-4">Inspectores</div>
    <a class="nav-link {{ $active === 'inspectores' ? 'active' : '' }}" href="{{ route('inspectores.index') }}">
      <i class="bi bi-person-badge me-2"></i> Gestión de Inspectores
    </a>
  </li>

  <!-- Sección: Pagos -->
  <li class="nav-item">
    <div class="fw-bold text-uppercase small px-3 pt-4">Pagos</div>
    <a class="nav-link {{ $active === 'pagos' ? 'active' : '' }}" href="{{ route('pagos.index') }}">
      <i class="bi bi-cash-coin me-2"></i> Administración de pagos
    </a>
  </li>

  <!-- Sección: Citas -->
  <li class="nav-item">
    <div class="fw-bold text-uppercase small px-3 pt-4">Citas</div>
    <a class="nav-link {{ $active === 'citas' ? 'active' : '' }}" href="{{ route('citas.index') }}">
      <i class="bi bi-calendar-check me-2"></i> Gestión de citas
    </a>
  </li>

  <hr>

  <!-- Otros enlaces -->
  <li class="nav-item">
    <a class="nav-link {{ $active === 'usuarios' ? 'active' : '' }}" href="{{ route('usuarios.index') }}">
      <i class="bi bi-people me-2"></i> Usuarios
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'catalogos' ? 'active' : '' }}" href="{{ route('catalogos.index') }}">
      <i class="bi bi-collection me-2"></i> Catálogos
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'filtros' ? 'active' : '' }}" href="{{ route('filtros.index') }}">
      <i class="bi bi-filter me-2"></i> Filtros
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'estadisticas' ? 'active' : '' }}" href="{{ route('estadisticas') }}">
      <i class="bi bi-bar-chart-line me-2"></i> Estadísticas
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link {{ $active === 'registro' ? 'active' : '' }}" href="{{ route('registro.cambios') }}">
      <i class="bi bi-clock-history me-2"></i> Registro de cambios
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="https://www.os.city" target="_blank">
      <i class="bi bi-box-arrow-up-right me-2"></i> Centro de ayuda +
    </a>
  </li>

  <div class="mt-auto border-t px-4 py-4">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-700">
        <i class="mdi mdi-logout-variant text-lg"></i>
        <span class="text-sm">Cerrar sesión</span>
      </button>
    </form>
  </div>
  </nav>
  </div>