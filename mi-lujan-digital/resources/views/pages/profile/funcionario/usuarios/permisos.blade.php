@extends('layouts.app-funcionario')

@section('title', 'Roles')

@section('profile_content')
<div class="container mt-4">

  {{-- Breadcrumb opcional --}}
  <nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
      <li class="breadcrumb-item">Usuarios</li>
      <li class="breadcrumb-item active" aria-current="page">Roles</li>
    </ol>
  </nav>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Roles</h2>

    <a href="{{ route('usuarios.permisos.create') }}" class="btn btn-primary">
      Nuevo rol
    </a>
  </div>

  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th style="width: 30%;">Nombre</th>
            <th style="width: 30%;">Módulo</th>
            <th style="width: 30%;">Rol base</th>
            <th class="text-center" style="width: 10%;">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($roles as $rol)
            <tr>
              <td>{{ $rol['nombre'] }}</td>
              <td>{{ $rol['modulo'] }}</td>
              <td>{{ $rol['rol_base'] }}</td>
              <td class="text-center">
                <a href="{{ route('usuarios.permisos.edit', $rol['id']) }}" class="text-primary" title="Editar">
                  <i class="bi bi-pencil"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">
                Sin roles cargados.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Footer de tabla (paginación simple como placeholder) --}}
    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center">
        <span class="me-2">Filas por página:</span>
        <select class="form-select d-inline w-auto">
          <option>10</option>
          <option>20</option>
          <option>50</option>
        </select>
      </div>
      <div class="text-muted small">
        1–{{ count($roles) }} de {{ count($roles) }}
        <button class="btn btn-outline-secondary btn-sm ms-2" disabled><i class="bi bi-chevron-left"></i></button>
        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection
