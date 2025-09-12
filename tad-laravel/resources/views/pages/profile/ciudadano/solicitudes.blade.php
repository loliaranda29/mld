@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">Mis solicitudes</h5>
    {{-- antes decía "Ver plantillas de trámites" y apuntaba a la misma ruta --}}
    <a href="{{ route('profile.catalogo') }}" class="btn btn-outline-secondary btn-sm">
      Nuevo trámite
    </a>
  </div>

  <form method="GET" action="" class="mb-4">
    <div class="input-group">
      <input type="text" name="search" class="form-control" placeholder="Buscar por expediente" value="{{ request('search') }}">
      <button class="btn btn-outline-secondary" type="submit">Buscar</button>
    </div>
  </form>

  @forelse ($solicitudes as $s)
    <div class="card mb-3 border-0 shadow-sm rounded-4">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <div class="small text-muted">Expediente</div>
          <div class="fw-bold">{{ $s->expediente }}</div>
          <div class="small mt-2">
            Trámite: <span class="fw-semibold">{{ $s->tramite->nombre ?? '—' }}</span>
          </div>
          <div class="small text-muted">Estado: <span class="badge bg-light text-dark">{{ $s->estado }}</span></div>
        </div>
        <a class="btn btn-primary" href="{{ route('profile.solicitudes.show', $s->id) }}">Abrir</a>
      </div>
    </div>
  @empty
    <div class="alert alert-info mb-0">Aún no tenés solicitudes creadas.</div>
  @endforelse

  <div class="mt-3">
    {{ $solicitudes->links() }}
  </div>
</div>
@endsection
