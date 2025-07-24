@extends('layouts.app-funcionario')

@section('content')
<div class="container">
  <h1>Crear Nuevo Trámite</h1>

  <!-- Nav Tabs -->
  <ul class="nav nav-tabs" id="tabsTramite" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab" aria-controls="tab-general" aria-selected="true">General</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="formulario-tab" data-bs-toggle="tab" data-bs-target="#tab-formulario" type="button" role="tab" aria-controls="tab-formulario" aria-selected="false">Formulario</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="etapas-tab" data-bs-toggle="tab" data-bs-target="#tab-etapas" type="button" role="tab" aria-controls="tab-etapas" aria-selected="false">Etapas</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="documento-tab" data-bs-toggle="tab" data-bs-target="#tab-documento" type="button" role="tab" aria-controls="tab-documento" aria-selected="false">Documento</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="configuracion-tab" data-bs-toggle="tab" data-bs-target="#tab-configuracion" type="button" role="tab" aria-controls="tab-configuracion" aria-selected="false">Configuración</button>
    </li>
  </ul>

  <!-- Tab Contents -->
  <div class="tab-content mt-3" id="tabsTramiteContent">
    <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="general-tab">
      @includeIf('pages.profile.funcionario.tramites.partials.general')
    </div>

    <div class="tab-pane fade" id="tab-formulario" role="tabpanel" aria-labelledby="formulario-tab">
      @includeIf('pages.profile.funcionario.tramites.partials.formulario')
    </div>

    <div class="tab-pane fade" id="tab-etapas" role="tabpanel" aria-labelledby="etapas-tab">
      @includeIf('pages.profile.funcionario.tramites.partials.etapas')
    </div>

    <div class="tab-pane fade" id="tab-documento" role="tabpanel" aria-labelledby="documento-tab">
      @includeIf('pages.profile.funcionario.tramites.partials.documento')
    </div>

    <div class="tab-pane fade" id="tab-configuracion" role="tabpanel" aria-labelledby="configuracion-tab">
      @includeIf('pages.profile.funcionario.tramites.partials.configuracion')
    </div>
  </div>

  <div class="mt-4">
    <button class="btn btn-success">Guardar</button>
    <a href="{{ route('funcionario.tramite_config') }}" class="btn btn-secondary">Cancelar</a>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76A08GgPpmtz8eNt3vDp6u9a6a9T9l9bKjNj+Uj2kr6W1r3/f1cH+XgVnYjJdzK" crossorigin="anonymous"></script>
@endpush