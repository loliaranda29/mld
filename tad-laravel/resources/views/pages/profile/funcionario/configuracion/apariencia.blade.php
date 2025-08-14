@extends('layouts.app-funcionario')

@section('title', 'Apariencia')

@section('profile_content')
<div class="container-fluid mt-3">

  {{-- Breadcrumb --}}
  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('configuracion.index') }}">Configuración</a></li>
      <li class="breadcrumb-item active">Apariencia</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Editar apariencia</h5>
      <button form="form-apariencia" class="btn btn-primary">Guardar cambios</button>
    </div>

    <div class="card-body">
      <form id="form-apariencia" action="{{ route('configuracion.apariencia.guardar') }}" method="POST">
        @csrf

        {{-- ==== Colores base ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Colores</h6>
        <div class="row g-3">
          @php
            $base = [
              'primary' => 'Primary',
              'secondary' => 'Secondary',
              'accent' => 'Accent',
              'error' => 'Error',
              'info' => 'Info',
              'success' => 'Success',
              'warning' => 'Warning',
            ];
          @endphp
          @foreach($base as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}" placeholder="#RRGGBB o #RRGGBBAA">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Textos ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Textos</h6>
        <div class="row g-3">
          @foreach([
            'text_title' => 'Títulos',
            'text_subtitle' => 'Subtítulos',
            'text_body' => 'Text body',
          ] as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Botones / Chips ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Botones</h6>
        <div class="row g-3">
          @foreach([
            'btn_primary' => 'Botón principal',
            'btn_secondary' => 'Botón secundario',
            'chips' => 'Chips',
          ] as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Home ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Home</h6>
        <div class="row g-3">
          @foreach([
            'home_card_title' => 'Títulos cards',
            'home_tabs'       => 'Tabs',
            'home_lists'      => 'Listas',
          ] as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Ficha de trámite / Cards / Modal ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Ficha de trámite</h6>
        <div class="row g-3">
          @foreach([
            'doc_icons'        => 'Íconos',
            'doc_cards_border' => 'Bordes de cards',
            'doc_section'      => 'Sección de documentos',
          ] as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <div class="row g-3 mt-2">
          @foreach([
            'card_title_bg' => 'Cards: background títulos',
            'modal_toolbar' => 'Modal toolbar',
          ] as $key => $label)
            <div class="col-md-6">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Tabs ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Tabs</h6>
        <div class="row g-3">
          @foreach([
            'tabs_border'   => 'Borde',
            'tabs_text'     => 'Texto',
            'tabs_card_bg'  => 'Tabs tipo card: background',
            'tabs_active'   => 'Tabs active',
            'tabs_disabled' => 'Tab disabled',
          ] as $key => $label)
            <div class="col-md-4">
              <label class="form-label">{{ $label }}</label>
              <div class="input-group color-row" data-key="{{ $key }}">
                <span class="input-group-text p-0">
                  <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta[$key] ?? '' }}">
                </span>
                <input type="text" name="{{ $key }}" class="form-control" value="{{ $paleta[$key] ?? '' }}">
              </div>
            </div>
          @endforeach
        </div>

        <hr class="my-4">

        {{-- ==== Íconos generales ==== --}}
        <h6 class="text-uppercase text-muted mb-3">Iconos</h6>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Iconos</label>
            <div class="input-group color-row" data-key="icons">
              <span class="input-group-text p-0">
                <input type="color" class="form-control form-control-color rounded-0 border-0" value="{{ $paleta['icons'] ?? '' }}">
              </span>
              <input type="text" name="icons" class="form-control" value="{{ $paleta['icons'] ?? '' }}">
            </div>
          </div>
        </div>

      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  // Sincroniza color input <-> texto hex
  document.querySelectorAll('.color-row').forEach(row => {
    const color = row.querySelector('input[type="color"]');
    const text  = row.querySelector('input[type="text"]');

    const normalize = (v) => {
      v = (v || '').trim();
      if (!v) return v;
      if (v[0] !== '#') v = '#'+v;
      return v.toUpperCase();
    };

    color.addEventListener('input', () => {
      text.value = color.value.toUpperCase();
    });

    text.addEventListener('change', () => {
      const v = normalize(text.value);
      text.value = v;
      try { color.value = v; } catch(e){}
    });
  });
});
</script>
@endpush
@endsection
