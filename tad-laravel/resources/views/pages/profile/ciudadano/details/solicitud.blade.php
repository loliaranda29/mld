@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 p-4 mb-4">
  <div class="row gy-4 align-items-start">
    <div class="col-12 col-md-5">
      <div class="d-flex flex-column h-100 justify-content-between">
        <div>
          <p class="text-muted mb-1">Folio/Prefolio del Expediente</p>
          <h5 class="fw-bold">{{ $solicitud->expediente }}</h5>

          <p class="text-muted mt-4 mb-1">Trámite</p>
          <p class="fw-semibold">{{ $solicitud->tramite->nombre ?? '—' }}</p>

          <p class="text-muted mt-4 mb-1">Estado</p>
          <span class="badge bg-secondary">{{ $solicitud->estado }}</span>
        </div>
        <div class="mt-4">
          <a href="{{ route('profile.solicitudes.index') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-7">
      <div class="border rounded-4 p-4 h-100 bg-light">
        <h6 class="fw-semibold text-secondary mb-3">
          <i class="mdi mdi-information-outline text-primary me-2"></i> Datos del formulario (JSON)
        </h6>
        <pre class="small mb-0" style="white-space: pre-wrap;">{{ json_encode($solicitud->datos, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }}</pre>
      </div>
    </div>
  </div>
</div>
@endsection
