@extends('layouts.app-funcionario')

@section('title','Crear catálogo')

@section('profile_content')
<div class="container-fluid mt-4">

  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-3">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('catalogos.index') }}">Catálogos</a></li>
      <li class="breadcrumb-item active" aria-current="page">Crear catálogo</li>
    </ol>
  </nav>

  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">
      <strong>GENERAL</strong>
    </div>

    <form action="{{ route('catalogos.store') }}" method="POST">
      @csrf
      <div class="card-body">

        <div class="mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" placeholder="" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Ruta dinámica</label>
          <input type="text" name="ruta" class="form-control" placeholder="">
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="1" id="chkJerarquico" name="jerarquico">
          <label class="form-check-label" for="chkJerarquico">
            Jerárquico
          </label>
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="1" id="chkMultinivel" name="multinivel">
          <label class="form-check-label" for="chkMultinivel">
            Catálogo Multinivel con Búsqueda
          </label>
        </div>

        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" value="1" id="chkIdsPersonalizados" name="ids_personalizados">
          <label class="form-check-label" for="chkIdsPersonalizados">
            Catálogo con ids personalizados
          </label>
        </div>

      </div>

      <div class="card-footer d-flex justify-content-end gap-2">
        <a href="{{ route('catalogos.index') }}" class="btn btn-outline-secondary">Regresar</a>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection
