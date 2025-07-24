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

<!-- Scripts necesarios para funcionamiento de pestañas -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
  integrity="sha384-QF1c+IAyT5Gyy+K3X5J82tFe0UeUYR5o4rwrXq8lLt1RbB2RCvC1q90ShxhEYMPn"
  crossorigin="anonymous"></script>

<!-- Alpine.js -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const triggerTabList = [].slice.call(document.querySelectorAll('#tabsTramite button'));
    triggerTabList.forEach(function(triggerEl) {
      const tabTrigger = new bootstrap.Tab(triggerEl);
      triggerEl.addEventListener('click', function(event) {
        event.preventDefault();
        tabTrigger.show();
      });
    });
  });
</script>
@endsection