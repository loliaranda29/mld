@extends('layouts.profile')

@section('profile_content')
<div class="card shadow-lg rounded-4 p-4 mb-4">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
    <h4 class="mb-0" style="color: #298e8c;">
      <i class="mdi mdi-calendar-check-outline"></i> Detalle de Inspección
    </h4>
    <button class="btn btn-sm btn-outline-secondary" onclick="history.back()">
      <i class="mdi mdi-arrow-left"></i> Volver
    </button>
  </div>

  {{-- Logo y folio --}}
  <div class="row align-items-center mb-4">
    <div class="col-md-6 text-center mb-3 mb-md-0">
      <img src="{{ asset('assets/img/logo-lujan.png') }}" alt="Logo" width="100">
    </div>
    <div class="col-md-6 text-center">
      <p class="mb-1 text-muted">Folio del trámite</p>
      <div class="text-white rounded-pill py-2 px-4 d-inline-block fs-5 fw-semibold" style="background-color:#298e8c;">
        {{ $inspeccion['folio_inspeccion'] }}
      </div>
    </div>
  </div>

  {{-- Sección título --}}
  <h5 class="text-uppercase fw-bold text-secondary">Permisos Eléctricos</h5>
  <hr>
  <div class="row g-4 align-items-stretch">
    {{-- Inspector --}}
    <div class="col-md-4 text-center d-flex flex-column justify-content-center">
      <div class="text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
        style="width: 100px; height: 100px; font-size: 2rem; background-color:#298e8c;">
        {{ strtoupper(substr($inspeccion['inspector']['nombre'], 0, 1)) }}{{ strtoupper(substr($inspeccion['inspector']['apellido'], 0, 1)) }}
      </div>
      <h5 style="color: #298e8c;">{{ $inspeccion['inspector']['nombre'] }} {{ $inspeccion['inspector']['apellido'] }}</h5>
      <p class="text-muted mb-0">Puesto: {{ $inspeccion['inspector']['puesto'] ?? 'Puesto no definido' }}</p>
    </div>

    {{-- Línea vertical --}}
    <div class="col-md-1 d-none d-md-flex justify-content-center">
      <div class="vr" style="height: 100%; width: 2px; background-color: #ccc;"></div>
    </div>

    {{-- Detalles --}}
    <div class="col-md-7">
      <div class="mb-3">
        <h6 style="color: #298e8c;"><i class="mdi mdi-calendar-badge-outline"></i> Fecha & Hora</h6>
        <p class="ms-3 mb-2">{{ \Carbon\Carbon::parse($inspeccion['fecha_inspeccion'])->format('d M Y, h:i A') }}</p>
      </div>
      <div class="mb-3">
        <h6 style="color: #298e8c;"><i class="mdi mdi-map-marker-outline"></i> Dirección</h6>
        <p class="ms-3 mb-2">{{ $inspeccion['direccion'] }}</p>
      </div>

      <hr>

      <div class="mb-3">
        <h6 style="color: #298e8c;"><i class="mdi mdi-map-marker-outline"></i> Tipo de inspección</h6>
        <p class="ms-3 mb-2">{{ $inspeccion['tipo'] ?? 'Sin especificar' }}</p>
      </div>

      <div class="mb-3">
        <h6 style="color: #298e8c;"><i class="mdi mdi-map-marker-outline"></i>Contacto del inspector</h6>
        <ul class="ms-3 mb-0 list-unstyled">
          <li><strong>Teléfonos:</strong> {{ $inspeccion['inspector']['telefono'] ?? 'N/D' }}</li>
          <li><strong>Email:</strong> {{ $inspeccion['inspector']['email'] ?? 'N/D' }}</li>
        </ul>
      </div>

      <div>
        <h6 style="color: #298e8c;"><i class="mdi mdi-map-marker-outline"></i>Superior jerárquico</h6>
        <ul class="ms-3 mb-0 list-unstyled">
          <li><strong>Nombre:</strong> {{ $inspeccion['inspector']['superior']['nombre'] ?? 'Sin asignar' }}</li>
          <li><strong>Cargo:</strong> {{ $inspeccion['inspector']['superior']['cargo'] ?? 'N/D' }}</li>
          <li><strong>Teléfonos:</strong> {{ $inspeccion['inspector']['superior']['telefono'] ?? 'N/D' }}</li>
          <li><strong>Email:</strong> {{ $inspeccion['inspector']['superior']['email'] ?? 'N/D' }}</li>
        </ul>
      </div>
    </div>
  </div>

</div>
@endsection