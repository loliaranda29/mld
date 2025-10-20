@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-3 col-lg-2 bg-light min-vh-100 py-4 border-end">
      @include('components.menu-funcionario', ['active' => $active ?? ''])
    </div>

    <!-- Contenido principal -->
    <div class="col-md-9 col-lg-10 py-4">
      @if(session('perfil_activo') && in_array(session('perfil_activo'), ['ciudadano', 'funcionario']))
        <div class="text-end pe-4 mb-3">
          <form method="POST" action="{{ route('profile.switch') }}">
            @csrf
            <button class="btn btn-sm btn-outline-secondary">
              Cambiar a perfil {{ session('perfil_activo') === 'ciudadano' ? 'Funcionario' : 'Ciudadano' }}
            </button>
          </form>
        </div>
      @endif

      @yield('profile_content')
    </div>
  </div>
</div>
@endsection

@push('scripts')
  <script>
    // Soporte para tabs Bootstrap en el módulo de trámites
    document.addEventListener('DOMContentLoaded', function () {
      if (!window.bootstrap) return;
      const triggers = document.querySelectorAll('#tabsTramite button[data-bs-toggle="tab"], #tabsTramite button');
      triggers.forEach((el) => {
        const tab = new bootstrap.Tab(el);
        el.addEventListener('click', (e) => { e.preventDefault(); tab.show(); });
      });
    });
  </script>
@endpush
