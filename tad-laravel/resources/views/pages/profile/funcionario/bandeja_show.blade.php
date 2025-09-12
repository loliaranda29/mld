@extends('layouts.app-funcionario')

@section('title', 'Detalle de solicitud')

@section('profile_content')
<div class="container mt-4">
  <!-- Breadcrumb -->
  <nav class="mb-3" aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="#">Inicio</a></li>
      <li class="breadcrumb-item">Ventanilla Digital</li>
      <li class="breadcrumb-item"><a href="{{ route('funcionario.bandeja') }}">Bandeja de entrada</a></li>
      <li class="breadcrumb-item active" aria-current="page">Detalle de solicitud</li>
    </ol>
  </nav>

<!-- Información principal -->
<div class="card shadow-sm p-4 mb-4">
  <div class="row align-items-start">
    <!-- Columna izquierda: Info del trámite -->
    <div class="col-md-4 border-end">
      <p class="text-muted mb-1 small">Folio/Prefolio del Expediente</p>
      <h5 class="fw-bold text-primary mb-2">R/009202/08–2025</h5>

      <p class="mb-1 small text-muted">Trámite:</p>
      <p class="fw-bold">Renovación licencia de conducir</p>

      <p class="mb-1 small text-muted">Fecha de recepción de solicitud:</p>
      <p class="fw-bold">08/08/2025 08:37:55 hrs</p>

      <p class="mb-1 small text-muted">Operador(es) asignado(s):</p>
      <p class="fw-bold text-danger">Sin asignar</p>
    </div>

    <!-- Columna centro: Estatus y botones -->
    <div class="col-md-4 border-end px-4">
      <p class="text-muted mb-1 small">Estatus</p>
      <span class="badge bg-primary rounded-pill mb-2">Citado</span>

      <p class="text-muted mb-1 small">Etapa <strong>(3 / 6)</strong></p>
      <p class="small mb-3">Turno</p>

      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary btn-sm">Ver historial</button>
        <button class="btn btn-outline-secondary btn-sm">Historial de asignación</button>
        <button class="btn btn-primary btn-sm fw-bold">Asignación de etapas</button>
      </div>
    </div>

    <!-- Columna derecha: Acciones -->
    <div class="col-md-4 px-4">
      <div class="d-grid gap-2 mb-3">
        <button class="btn btn-danger btn-sm fw-bold">Rechazar trámite</button>
        <button class="btn btn-outline-secondary btn-sm" disabled>Aceptar etapa</button>
        <button class="btn btn-outline-secondary btn-sm" disabled>Guardar</button>
      </div>
      <div class="d-grid gap-2">
        <button class="btn btn-outline-dark btn-sm"><i class="bi bi-download me-1"></i> Área de descargas</button>
        <button class="btn btn-outline-dark btn-sm"><i class="bi bi-upload me-1"></i> Adjuntar documentos de salida</button>
      </div>
    </div>
  </div>
</div>

 <div class="row">
  <!-- Panel izquierdo: Documentos -->
  <div class="col-md-5">
    <!-- Card Documentos -->
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        <i class="bi bi-journal-text me-2"></i> Documentos
      </div>
      <div class="card-body p-0">
        @foreach ($documentos as $documento)
          <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2">
            <div class="d-flex align-items-center">
              <i class="bi bi-check-circle text-success me-2"></i>
              <span>{{ $documento }}</span>
            </div>
            <span class="badge bg-light text-muted small">Requerido</span>
          </div>
        @endforeach
      </div>
    </div>
  </div>

  <!-- Panel derecho: Tabs de contenido -->
{{-- Reemplazá los textos fijos por: --}}
<h5 class="fw-bold text-primary mb-2">{{ $solicitud->expediente }}</h5>
<p class="small text-muted mb-1">Trámite:</p>
<p class="fw-bold">{{ $solicitud->tramite->nombre ?? '—' }}</p>
<p class="small text-muted mb-1">Estado:</p>
<span class="badge bg-secondary">{{ $solicitud->estado }}</span>

<pre class="small mt-3 bg-light p-3 rounded">{{ json_encode($solicitud->datos, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }}</pre>


</div>
@endsection