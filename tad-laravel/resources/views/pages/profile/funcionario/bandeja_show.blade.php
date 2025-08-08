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
  <div class="col-md-7">
    <div class="card shadow-sm">
      <div class="card-body p-0">
        <!-- Tabs -->
        <ul class="nav nav-tabs px-3 pt-3" id="tabsDetalle" role="tablist">
          <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabSolicitante">Datos del solicitante</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabInicio">Inicio de Trámite</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabDocumentos">Documento</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabDenuncia">Denuncia</button></li>
          <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabJurada">Declaración jurada</button></li>
        </ul>

        <div class="tab-content p-3">
          <!-- Tab Activa -->
          <div class="tab-pane fade show active" id="tabSolicitante">
            {{-- FORMULARIO DATOS DEL SOLICITANTE --}}
            <h6 class="fw-bold bg-dark text-white p-2 rounded">Datos del solicitante</h6>
            <div class="row g-3 mb-3">
              <div class="col-md-6">
                <label class="form-label fw-bold">CUIL</label>
                <input type="text" class="form-control" value="20166659834" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Nombre</label>
                <input type="text" class="form-control" value="Alfonso Ignacio" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Apellido paterno</label>
                <input type="text" class="form-control" value="Ibaceta" readonly>
              </div>
              <div class="col-md-6">
                <label class="form-label fw-bold">Fecha de nacimiento</label>
                <input type="text" class="form-control" value="1964-10-04" readonly>
              </div>
              <div class="col-md-12">
                <label class="form-label fw-bold">Teléfono celular</label>
                <input type="text" class="form-control" readonly>
              </div>
            </div>

            <h6 class="fw-bold bg-dark text-white p-2 rounded">Dirección del solicitante</h6>
            <div class="row g-3">
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Teléfono fijo"></div>
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Calle"></div>
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Número exterior"></div>
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Depto."></div>
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Barrio"></div>
              <div class="col-md-6"><input type="text" class="form-control" placeholder="Código postal"></div>
              <div class="col-md-12"><input type="text" class="form-control" placeholder="Referencias"></div>
            </div>
          </div>

          <!-- Otras pestañas -->
          <div class="tab-pane fade" id="tabInicio">Contenido de Inicio de Trámite</div>
          <div class="tab-pane fade" id="tabDocumentos">Contenido de Documentos</div>
          <div class="tab-pane fade" id="tabDenuncia">Contenido de Denuncia</div>
          <div class="tab-pane fade" id="tabJurada">Contenido de Declaración Jurada</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection