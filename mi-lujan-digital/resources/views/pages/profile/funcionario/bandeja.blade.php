@extends('layouts.app-funcionario')

@section('title', 'Bandeja de entrada')

@section('profile_content')
<div class="container mt-4">
  <h2 class="h5 mb-4">Bandeja de entrada</h2>

  <!-- Filtros superiores -->
  <div class="card p-3 mb-3">
    <div class="row g-2 align-items-end mb-3">
      <div class="col-md-4">
        <label class="form-label">Tareas</label>
        <select class="form-select">
          <option selected>Mis tareas</option>
          <option>Todos los trámites</option>
          <option>Trámites asignados</option>
        </select>
      </div>
      <div class="col-md-8 d-flex flex-wrap justify-content-end align-items-end gap-2">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarFolio" checked>
          <label class="form-check-label" for="buscarFolio">Buscar por folio</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarPrefolio">
          <label class="form-check-label" for="buscarPrefolio">Buscar por prefolio</label>
        </div>
        <input type="text" class="form-control w-auto" placeholder="Buscar por folio">
        <button class="btn btn-dark"><i class="bi bi-search"></i></button>
        <button class="btn btn-outline-secondary"><i class="bi bi-sliders"></i></button>
      </div>
    </div>

    <!-- Filtros adicionales -->
    <div class="row g-2">
      <div class="col-md-5">
        <input type="text" class="form-control" placeholder="Buscar un trámite, servicio, ...">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Rango de fechas">
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Buscar por CUIL del solicitante">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-dark w-100"><i class="bi bi-download me-1"></i>Buscar</button>
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" href="#">Todos <span class="badge bg-secondary">146</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="#">Abiertos <span class="badge bg-secondary">146</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Cerrados <span class="badge bg-secondary">146</span></a>
    </li>
  </ul>
<div class="card p-3">
  @if(isset($solicitudes) && $solicitudes->count())
    @foreach($solicitudes as $s)
      <div class="d-flex justify-content-between align-items-center border-bottom py-2">
        <div>
          <div class="small text-muted">Expediente</div>
          <div class="fw-bold">{{ $s->expediente }}</div>
          <div class="small mt-1">
            Trámite: <span class="fw-semibold">{{ $s->tramite->nombre ?? '—' }}</span>
            · Ciudadano: <span class="text-muted">{{ $s->usuario->name ?? $s->usuario->email }}</span>
          </div>
          <div class="small text-muted">Estado: <span class="badge bg-light text-dark">{{ $s->estado }}</span></div>
        </div>
        <a class="btn btn-outline-primary btn-sm" href="{{ route('funcionario.bandeja.show', $s->id) }}">Abrir</a>
      </div>
    @endforeach

    <div class="mt-3">
      {{ $solicitudes->links() }}
    </div>
  @else
    <div class="alert alert-info mb-0">No hay solicitudes para mostrar.</div>
  @endif
</div>
 
<div class="card-footer d-flex justify-content-between align-items-center">
      <div>
        Filas por página:
        <select class="form-select d-inline w-auto ms-2">
          <option>5</option>
          <option>10</option>
          <option>20</option>
        </select>
      </div>
      <div>
        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-chevron-left"></i></button>
        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection
