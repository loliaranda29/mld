@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Nuevo trámite</h5>
    <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary btn-sm">Mis solicitudes</a>
  </div>

  @forelse($plantillas as $t)
    <div class="card mb-3 border-0 shadow-sm rounded-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="fw-bold">{{ $t->nombre }}</div>
          @if(!empty($t->descripcion))
            <div class="small text-muted">{{ $t->descripcion }}</div>
          @endif
        </div>
        <form method="POST" action="{{ route('profile.tramites.iniciar', $t->id) }}">
          @csrf
          <button class="btn btn-primary btn-sm">Iniciar trámite</button>
        </form>
      </div>
    </div>
  @empty
    <div class="alert alert-info">No hay trámites disponibles para iniciar.</div>
  @endforelse

  <div class="mt-3">{{ $plantillas->links() }}</div>
</div>
@endsection
