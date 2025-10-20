@extends('layouts.app-funcionario')

@section('title', 'Datos del ciudadano')

@section('profile_content')
<div class="container mt-4">

  {{-- Breadcrumb / Volver --}}
  <nav class="mb-3" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('/') }}">Inicio</a></li>
      <li class="breadcrumb-item">
        <a href="{{ route('usuarios.ciudadanos') }}">Usuarios</a>
        </li>

      <li class="breadcrumb-item active" aria-current="page">Datos del ciudadano</li>
    </ol>
  </nav>

  <div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
      <div class="h5 mb-0">Datos del ciudadano</div>
      <a href="#" class="btn btn-sm btn-dark">
        <i class="bi bi-pencil-square me-1"></i> Editar perfil
      </a>
    </div>

    <div class="card-body">

      <div class="row">
        {{-- Columna Avatar y correo --}}
        <div class="col-12 col-md-3 text-center mb-4">
          <div class="d-inline-flex align-items-center justify-content-center rounded-circle"
               style="width:110px;height:110px;background:#0b2e4e;color:#fff;font-size:36px;">
            {{ $c['iniciales'] ?? 'AA' }}
          </div>
          <div class="mt-3 small text-muted">Correo electrónico</div>
          <div class="fw-semibold">{{ $c['email'] }}</div>
        </div>

        {{-- Columna formulario --}}
        <div class="col-12 col-md-9">

          <div class="row g-3">
            <div class="col-md-12">
              <label class="form-label small">CUIL</label>
              <input type="text" class="form-control" value="{{ $c['cuil'] }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small">Nombre</label>
              <input type="text" class="form-control" value="{{ $c['nombre'] }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Apellido paterno</label>
              <input type="text" class="form-control" value="{{ $c['apellido'] }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small">Fecha de nacimiento</label>
              <div class="input-group">
                <input type="text" class="form-control" value="{{ $c['fecha_nac'] }}" readonly>
                <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Teléfono celular</label>
              <div class="input-group">
                <input type="text" class="form-control" value="{{ $c['telefono_celular'] }}" readonly>
                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
              </div>
            </div>

            <div class="col-md-12">
              <h6 class="mt-3 mb-2">Dirección</h6>
            </div>

            <div class="col-md-6">
              <label class="form-label small">Código postal</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['cp'] }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Barrio</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['barrio'] }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small">Calle</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['calle'] }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Número exterior</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['numero'] }}" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label small">Depto.</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['depto'] }}" readonly>
            </div>
            <div class="col-md-6">
              <label class="form-label small">Referencias</label>
              <input type="text" class="form-control" value="{{ $c['direccion']['referencias'] }}" readonly>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>

</div>
@endsection
