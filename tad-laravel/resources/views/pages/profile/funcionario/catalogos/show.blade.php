@extends('layouts.app-funcionario')

@section('title', 'Detalle del catálogo')

@section('profile_content')
<div class="container-fluid mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catalogos.index') }}">Catálogos</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $catalogo['nombre'] }}</li>
      </ol>
    </nav>

    <div class="d-flex gap-2">
      <button type="button" class="btn btn-dark">
        Sincronizar <i class="bi bi-arrow-repeat ms-1"></i>
      </button>
      <a href="{{ route('catalogos.subcatalogos', $catalogo['id']) }}" class="btn btn-dark">
        Ver Listado
      </a>
    </div>
  </div>

  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white fw-semibold">
      GENERAL
    </div>

    <div class="card-body">
      <div class="mb-3">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" value="{{ $catalogo['nombre'] }}" disabled>
      </div>

      <div class="mb-3">
        <label class="form-label">Ruta dinámica</label>
        <input type="text" class="form-control"
               value="{{ \Illuminate\Support\Str::of($catalogo['nombre'])->slug('') }}" disabled>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="chk1" checked>
        <label class="form-check-label" for="chk1">Jerárquico</label>
      </div>

      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" id="chk2" checked>
        <label class="form-check-label" for="chk2">Catálogo Multinivel con Búsqueda</label>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="chk3">
        <label class="form-check-label" for="chk3">Catálogo con ids personalizados</label>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-end gap-2">
      <a href="{{ route('catalogos.index') }}" class="btn btn-outline-secondary">Regresar</a>
      <button class="btn btn-primary" disabled>Guardar</button>
    </div>
  </div>
</div>
@endsection
