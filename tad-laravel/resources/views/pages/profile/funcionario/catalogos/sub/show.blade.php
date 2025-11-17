@extends('layouts.app-funcionario')

@section('title', 'Detalle')

@section('profile_content')
<div class="container-fluid mt-4">
  {{-- Migas --}}
  <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('catalogos.index') }}">Catálogos</a></li>
      <li class="breadcrumb-item">
        <a href="{{ route('catalogos.subcatalogos', $catalogoId) }}">{{ $catalogoId }}</a>
      </li>
      <li class="breadcrumb-item active" aria-current="page">{{ $opt['id'] }}</li>
    </ol>
  </nav>

  {{-- Avisos --}}
  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">
      <strong>Ups…</strong>
      <ul class="mb-0">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- Card principal --}}
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white fw-semibold">
      Detalle
    </div>

    <div class="card-body">
      {{-- Ayuda superior (como en tu UI) --}}
      <div class="alert alert-light border mb-4">
        Si realizas alguna modificación recuerda volver a la
        <a href="#" class="link-dark text-decoration-underline">página inicial</a>
        para sincronizar tu catálogo.
      </div>

      {{-- FORMULARIO DE DEMO (solo visual) --}}
      <div class="mb-3">
        <label class="form-label">Valor de la opción</label>
        <input type="text" class="form-control" value="{{ strtoupper($opt['nombre'] ?? '') }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Ruta dinámica de la opción</label>
        <input type="text" class="form-control" value="{{ $opt['slug'] ?? '' }}">
      </div>

      <div class="mb-3">
        <label class="form-label">Id</label>
        <div class="input-group">
          <input type="text" class="form-control" value="{{ $opt['id'] ?? '' }}">
          <button class="btn btn-dark" type="button" title="Copiar">
            <i class="bi bi-clipboard"></i>
          </button>
        </div>
      </div>

      <div class="mb-3">
        <label class="form-label">Icono</label>
        <input type="text" class="form-control" value="{{ $opt['icon'] ?? 'mdi-account' }}">
        <div class="form-text">
          <i class="bi bi-info-circle"></i> Lista de iconos
        </div>
      </div>

      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="jerarquico">
        <label class="form-check-label" for="jerarquico">Jerárquico</label>
      </div>
    </div>

    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="d-flex gap-2">
        <a href="{{ route('catalogos.subcatalogos', $catalogoId) }}" class="btn btn-outline-secondary">
          Regresar
        </a>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-primary">Guardar</button>

        {{-- BOTÓN QUE ABRE EL MODAL --}}
        <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalUpload">
          Cargar nuevos datos
        </button>
      </div>
    </div>
  </div>
</div>

{{-- ===== MODAL PARA CARGAR NUEVOS DATOS ===== --}}
<div class="modal fade" id="modalUpload" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title">Subir datos del catálogo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="text-muted mb-3">
          Agregar nuevos términos o subcatálogos en este nivel
        </p>

        {{-- PESTAÑAS --}}
        <ul class="nav nav-tabs" id="uploadTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-term" data-bs-toggle="tab"
                    data-bs-target="#pane-term" type="button" role="tab">
              AGREGAR UN TÉRMINO
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-csv" data-bs-toggle="tab"
                    data-bs-target="#pane-csv" type="button" role="tab">
              SUBIR LISTA DE TÉRMINOS
            </button>
          </li>
        </ul>

        <div class="tab-content pt-3">
          {{-- TAB 1: Agregar un término --}}
          <div class="tab-pane fade show active" id="pane-term" role="tabpanel">
            <form method="POST"
                  action="{{ route('catalogos.sub.upload', [$catalogoId, $opt['id']]) }}">
              @csrf

              <div class="mb-3">
                <label class="form-label">Valor de la opción <span class="text-danger">*</span></label>
                <input type="text" name="nombre" class="form-control" required
                       placeholder="Ej. CONEXIÓN ÚNICA">
              </div>

              <div class="mb-3">
                <label class="form-label">Ruta dinámica de la opción <span class="text-danger">*</span></label>
                <input type="text" name="slug" class="form-control" required
                       placeholder="Ej. conexionUnica">
              </div>

              <div class="mb-3">
                <label class="form-label">Icono</label>
                <input type="text" name="icon" class="form-control" placeholder="mdi-account">
                <div class="form-text"><i class="bi bi-info-circle"></i> Lista de iconos</div>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>

          {{-- TAB 2: Subir lista de términos (.csv) --}}
          <div class="tab-pane fade" id="pane-csv" role="tabpanel">
            <div class="alert alert-light border">
              <strong>Instrucciones</strong>
              <ul class="mb-0">
                <li>El archivo debe ser de tipo <code>csv</code>.</li>
                <li>Debe tener dos columnas, sin espacios en los encabezados y con este orden: <b>Nombre</b>, <b>Icono</b>, <b>IdPersonalizado</b>.</li>
                <li>El campo “Nombre” es obligatorio para todas las filas; “Icono” e “IdPersonalizado” son opcionales.</li>
              </ul>
            </div>

            <form method="POST" enctype="multipart/form-data"
                  action="{{ route('catalogos.sub.upload', [$catalogoId, $opt['id']]) }}">
              @csrf
              <input type="hidden" name="mode" value="csv">

              <div class="mb-3">
                <label class="form-label">Subir archivo <span class="text-danger">*</span></label>
                <input type="file" class="form-control" name="csv" accept=".csv" required>
              </div>

              <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-outline-secondary"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
              </div>
            </form>
          </div>
        </div> {{-- /tab-content --}}
      </div>
    </div>
  </div>
</div>
@endsection
