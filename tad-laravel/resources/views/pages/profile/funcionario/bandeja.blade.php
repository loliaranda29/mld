@extends('layouts.app-funcionario')

@section('title', 'Bandeja de entrada')

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

  <div class="row">
    <!-- Columna izquierda -->
    <div class="col-lg-4 mb-4">
      <!-- Info principal -->
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Folio/Prefolio del Expediente</p>
          <h5 class="fw-bold text-primary">R/009202/08–2025</h5>
          <p class="mb-1">Trámite: <strong>Renovación licencia de conducir</strong></p>
          <p class="mb-1">Fecha de recepción de solicitud: <strong>08/08/2025 08:37:55 hrs</strong></p>
          <p class="mb-1">Operador(es) asignado(s): <strong class="text-danger">Sin asignar</strong></p>
        </div>
      </div>

      <!-- Estado -->
      <div class="card mb-3 shadow-sm">
        <div class="card-body">
          <p class="text-muted mb-1">Estatus</p>
          <span class="badge bg-primary mb-2">En proceso</span>
          <p>Etapa <strong>(1 / 6)</strong>: Verificación</p>
          <div class="d-grid gap-2">
            <button class="btn btn-outline-secondary btn-sm">Ver historial</button>
            <button class="btn btn-outline-dark btn-sm">Asignación de etapas</button>
            <button class="btn btn-outline-dark btn-sm">Asignar responsable</button>
          </div>
        </div>
      </div>

      <!-- Acciones -->
      <div class="card shadow-sm">
        <div class="card-body">
          <p class="text-warning mb-3">
            <i class="bi bi-exclamation-triangle"></i> Prevenciones disponibles: <strong>15</strong>
          </p>
          <div class="d-grid gap-2 mb-2">
            <button class="btn btn-outline-secondary btn-sm" disabled>Prevenir al solicitante</button>
            <button class="btn btn-danger btn-sm">Rechazar trámite</button>
            <button class="btn btn-outline-secondary btn-sm" disabled>Aceptar etapa</button>
            <button class="btn btn-outline-secondary btn-sm" disabled>Guardar</button>
          </div>
          <div class="d-grid gap-2">
            <button class="btn btn-outline-dark btn-sm"><i class="bi bi-download"></i> Área de descargas</button>
            <button class="btn btn-outline-dark btn-sm"><i class="bi bi-upload"></i> Adjuntar documentos de salida</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Columna derecha -->
    <div class="col-lg-8">
      <!-- Tabs -->
      <ul class="nav nav-tabs mb-3" id="tabsDetalle" role="tablist">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabSolicitante">Datos del solicitante</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabInicio">Inicio de Trámite</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabDocumentos">Documento</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabDenuncia">Denuncia</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabJurada">Declaración jurada</button></li>
      </ul>

      <div class="tab-content">
        <!-- Datos del solicitante -->
        <div class="tab-pane fade show active" id="tabSolicitante">
          <div class="card mb-3 shadow-sm">
            <div class="card-body">
              <div class="row g-3">
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
                <div class="col-md-6">
                  <label class="form-label fw-bold">Teléfono celular</label>
                  <input type="text" class="form-control" readonly>
                </div>
              </div>

              <hr class="my-4">

              <h6 class="fw-bold bg-primary text-white px-3 py-2 rounded">Dirección del solicitante</h6>
              <div class="row g-3 mt-2">
                <div class="col-md-6"><input type="text" class="form-control" placeholder="Teléfono fijo"></div>
                <div class="col-md-6"><input type="text" class="form-control" placeholder="Calle"></div>
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Número exterior"></div>
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Depto."></div>
                <div class="col-md-4"><input type="text" class="form-control" placeholder="Barrio"></div>
                <div class="col-md-6"><input type="text" class="form-control" placeholder="Código postal"></div>
                <div class="col-md-6"><input type="text" class="form-control" placeholder="Referencias"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Otros tabs (por ahora placeholders) -->
        <div class="tab-pane fade" id="tabInicio">Contenido de Inicio de Trámite</div>
        <div class="tab-pane fade" id="tabDocumentos">Contenido de Documentos</div>
        <div class="tab-pane fade" id="tabDenuncia">Contenido de Denuncia</div>
        <div class="tab-pane fade" id="tabJurada">Contenido de Declaración Jurada</div>
      </div>
    </div>
  </div>
</div>
@endsection

