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
  <div class="row mb-4">
    <div class="col-md-8">
      <p class="mb-1 text-muted">Folio/Prefolio del Expediente</p>
      <h5 class="fw-bold text-primary">R/009202/08–2025</h5>
      <p class="mb-1">Trámite: <strong>Renovación licencia de conducir</strong></p>
      <p class="mb-1">Fecha de recepción de solicitud: <strong>08/08/2025 08:37:55 hrs</strong></p>
      <p class="mb-1">Operador(es) asignado(s): <strong class="text-danger">Sin asignar</strong></p>
    </div>
    <div class="col-md-4">
      <p class="mb-1 text-muted">Estatus</p>
      <span class="badge bg-primary mb-2">En proceso</span>
      <p>Etapa <strong>(1 / 6)</strong>: Verificación</p>

      <div class="d-grid gap-2">
        <button class="btn btn-outline-secondary btn-sm">Ver historial</button>
        <button class="btn btn-outline-dark btn-sm">Asignación de etapas</button>
        <button class="btn btn-outline-dark btn-sm">Asignar responsable</button>
      </div>
    </div>
  </div>

  <!-- Acciones adicionales -->
  <div class="d-flex justify-content-between flex-wrap gap-2 mb-4">
    <div class="text-danger">
      <i class="bi bi-exclamation-triangle"></i> Prevenciones disponibles: <strong>15</strong>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm" disabled>Prevenir al solicitante</button>
      <button class="btn btn-danger btn-sm">Rechazar trámite</button>
      <button class="btn btn-outline-secondary btn-sm" disabled>Aceptar etapa</button>
      <button class="btn btn-outline-secondary btn-sm" disabled>Guardar</button>
    </div>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-dark btn-sm"><i class="bi bi-download"></i> Área de descargas</button>
      <button class="btn btn-outline-dark btn-sm"><i class="bi bi-upload"></i> Adjuntar documentos de salida</button>
    </div>
  </div>

  <div class="row">
    <!-- Columna izquierda: Documentos -->
    <div class="col-md-5">
      <div class="card shadow-sm border-0">
        <div class="card-header text-white fw-semibold" style="background-color: #0a2540;">
          <i class="bi bi-journal-text me-2"></i> Documentos
        </div>
        <div class="card-body p-0">
          @foreach ($documentos as $documento)
          <div class="d-flex justify-content-between align-items-center border-bottom px-3 py-2">
            <div class="d-flex align-items-center gap-2">
              <i class="bi bi-check-circle text-success"></i>
              <span>{{ $documento }}</span>
            </div>
            <span class="badge bg-light text-muted small">Requerido</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- Columna derecha: Tabs y contenido -->
    <div class="col-md-7">
      <ul class="nav nav-tabs mt-0 mb-3 bg-white border rounded shadow-sm">
        <li class="nav-item">
          <button class="nav-link active fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#tabSolicitante">
            <i class="bi bi-person-lines-fill me-1"></i> Datos del solicitante
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#tabInicio">
            <i class="bi bi-hourglass-split me-1"></i> Inicio de Trámite
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#tabDocumentos">
            <i class="bi bi-file-earmark-text me-1"></i> Documento
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#tabDenuncia">
            <i class="bi bi-exclamation-circle me-1"></i> Denuncia
          </button>
        </li>
        <li class="nav-item">
          <button class="nav-link fw-semibold text-dark" data-bs-toggle="tab" data-bs-target="#tabJurada">
            <i class="bi bi-file-earmark-lock me-1"></i> Declaración jurada
          </button>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane fade show active" id="tabSolicitante">
          <!-- Información del solicitante -->
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

          <h6 class="fw-bold text-white px-3 py-2 mt-3 mb-2" style="background-color: #0a2540; border-radius: .25rem;">Dirección del solicitante</h6>
          <div class="row g-3">
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Teléfono fijo"></div>
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Calle"></div>
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Número exterior"></div>
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Depto."></div>
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Barrio"></div>
            <div class="col-md-3"><input type="text" class="form-control" placeholder="Código postal"></div>
            <div class="col-md-6"><input type="text" class="form-control" placeholder="Referencias"></div>
          </div>
        </div>

        <!-- Pestañas placeholder -->
        <div class="tab-pane fade" id="tabInicio">Contenido de Inicio de Trámite</div>
        <div class="tab-pane fade" id="tabDocumentos">Contenido de Documentos</div>
        <div class="tab-pane fade" id="tabDenuncia">Contenido de Denuncia</div>
        <div class="tab-pane fade" id="tabJurada">Contenido de Declaración Jurada</div>
      </div>
    </div>
  </div>
</div>
@endsection