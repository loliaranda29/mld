@extends('layouts.app')

@section('content')


<div class="position-relative text-center w-100" style="max-width: 100%;">
  <img
    src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/brand%2FuserImages%2Fmld_12%20(1)_time1749138951205.jpg?alt=media&token=06ec261a-793c-41f6-9430-e3bb7bab1fd9"
    alt="lujan-digital-logo"
    class="img-fluid w-100"
    style="height: auto;">

  <a target="_blank"
    href="https://api.whatsapp.com/send/?phone=5492615656170&amp;text&amp;type=phone_number&amp;app_absent=0"
    class="btn btn-outline-custom position-absolute top-50 start-50 translate-middle px-3 py-1 fs-6 fs-md-5 px-md-4 py-md-2">
    Chatea con Luji
  </a>
</div>

{{-- Trámites más buscados --}}
<div class="box-2">
  <div class="container">
    <h2>Trámites y servicios más buscados</h2>
    <p class="intro">
      Conocé cómo registrarte, iniciar tus trámites, darles seguimiento y cómo conectar tu Wallet, haciendo clic en el botón "Tutoriales"
      <a href="#">Tutoriales</a>
    </p>

    {{-- DESKTOP --}}
    <div class="row g-3 d-none d-md-flex">
      @foreach ($tramitesMasBuscados as $tramite)
      <div class="col-md-6 col-lg-4">
        <a href="{{ $tramite['href'] }}">
          <div class="tramite-card">
            <div class="tramite-icon">{!! $tramite['icono'] !!}</div>
            <div>
              <div class="div-title">{{ $tramite['titulo'] }}</div>
              <div class="tramite-desc">{{ $tramite['descripcion'] }}</div>
            </div>
          </div>
        </a>
      </div>
      @endforeach
    </div>

    {{-- MOBILE - primeros 4 --}}
    <div class="row g-3 d-md-none">
      @foreach ($tramitesMasBuscados as $index => $tramite)
      @if ($index < 4)
        <div class="col-12">
        <a href="{{ $tramite['href'] }}">
          <div class="tramite-card">
            <div class="tramite-icon">{!! $tramite['icono'] !!}</div>
            <div>
              <div class="div-title">{{ $tramite['titulo'] }}</div>
              <div class="tramite-desc">{{ $tramite['descripcion'] }}</div>
            </div>
          </div>
        </a>
    </div>
    @endif
    @endforeach
  </div>
</div>

{{-- MOBILE - oculto y botón --}}
@if (count($tramitesMasBuscados) > 4)
<div class="collapse d-md-none" id="moreTramitesMobile">
  <div class="row g-3 mt-2">
    @foreach ($tramitesMasBuscados as $index => $tramite)
    @if ($index >= 4)
    <div class="col-12">
      <a href="{{ $tramite['href'] }}">
        <div class="tramite-card">
          <div class="tramite-icon">{!! $tramite['icono'] !!}</div>
          <div>
            <div class="div-title">{{ $tramite['titulo'] }}</div>
            <div class="tramite-desc">{{ $tramite['descripcion'] }}</div>
          </div>
        </div>
      </a>
    </div>
    @endif
    @endforeach
  </div>
</div>

<div class="text-center mt-3 d-md-none">
  <button class="btn btn-outline-custom" data-bs-toggle="collapse" data-bs-target="#moreTramitesMobile" aria-expanded="false" aria-controls="moreTramitesMobile">
    Mostrar más
  </button>
</div>
@endif
</div>

{{-- Lista de trámites --}}
<div class="box-1">
  <div class="container">
    <h2 class="mb-4">Lista de Trámites</h2>
    <div class="row g-4">
      <div class="col-12 col-md-6">
        <div class="tramite-card">
          <div class="tramite-icon">🚗</div>
          <div>
            <div class="div-title">Licencia conducir particular</div>
            <div class="tramite-desc">
              A continuación encontrarás todos los trámites de licencias de conducir particulares.
            </div>
            <a href="#" class="tramite-link">Conocé más</a>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        <div class="tramite-card">
          <div class="tramite-icon">🚚</div>
          <div>
            <div class="div-title">Licencia conducir profesional</div>
            <div class="tramite-desc">
              A continuación encontrarás todos los trámites de licencias de conducir profesionales.
            </div>
            <a href="#" class="tramite-link">Conocé más</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Sección de interés --}}
<div class="box-2">
  <div class="container">
    <h2 class="mb-4">También te puede interesar</h2>

    <!-- Tabs -->
    <ul class="nav nav-tabs custom-tabs justify-content-center flex-wrap mb-4" id="interesTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="oficinas-tab" data-bs-toggle="tab" data-bs-target="#oficinas" type="button" role="tab">Oficinas</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="categorias-tab" data-bs-toggle="tab" data-bs-target="#categorias" type="button" role="tab">Categorías</button>
      </li>
    </ul>

    <!-- Contenido de tabs -->
    <div class="tab-content">
      {{-- OFICINAS --}}
      <div class="tab-pane fade show active" id="oficinas" role="tabpanel">
        {{-- Desktop --}}
        <div class="row g-3 g-md-4 d-none d-md-flex">
          @foreach ($oficinas as $oficina)
          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="{{ $oficina['href'] }}">
              <div class="oficina-card text-center p-3 p-md-4 h-100">
                <div class="oficina-icon mb-2 fs-2">{{ $oficina['icono'] }}</div>
                <div class="div-title fw-semibold">{{ $oficina['titulo'] }}</div>
              </div>
            </a>
          </div>
          @endforeach
        </div>

        {{-- Mobile --}}
        <div class="row g-3 g-md-4 d-md-none">
          @foreach ($oficinas as $index => $oficina)
          @if ($index < 4)
            <div class="col-12 col-sm-6">
            <a href="{{ $oficina['href'] }}">
              <div class="oficina-card text-center p-3 p-md-4 h-100">
                <div class="oficina-icon mb-2 fs-2">{{ $oficina['icono'] }}</div>
                <div class="div-title fw-semibold">{{ $oficina['titulo'] }}</div>
              </div>
            </a>
        </div>
        @endif
        @endforeach
      </div>

      @if (count($oficinas) > 4)
      <div class="collapse d-md-none" id="moreOficinasMobile">
        <div class="row g-3 g-md-4 mt-2">
          @foreach ($oficinas as $index => $oficina)
          @if ($index >= 4)
          <div class="col-12 col-sm-6">
            <a href="{{ $oficina['href'] }}">
              <div class="oficina-card text-center p-3 p-md-4 h-100">
                <div class="oficina-icon mb-2 fs-2">{{ $oficina['icono'] }}</div>
                <div class="div-title fw-semibold">{{ $oficina['titulo'] }}</div>
              </div>
            </a>
          </div>
          @endif
          @endforeach
        </div>
      </div>

      <div class="text-center mt-3 d-md-none">
        <button class="btn btn-outline-custom" data-bs-toggle="collapse" data-bs-target="#moreOficinasMobile" aria-expanded="false" aria-controls="moreOficinasMobile">
          Mostrar más
        </button>
      </div>
      @endif
    </div>

    {{-- CATEGORÍAS --}}
    <div class="tab-pane fade" id="categorias" role="tabpanel">
      {{-- Desktop --}}
      <div class="row g-3 g-md-4 d-none d-md-flex">
        @foreach ($categorias as $categoria)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
          <a href="{{ $categoria['href'] }}">
            <div class="oficina-card text-center p-3 p-md-4 h-100">
              <div class="oficina-icon mb-2 fs-2">{{ $categoria['icono'] }}</div>
              <div class="div-title fw-semibold">{{ $categoria['titulo'] }}</div>
            </div>
          </a>
        </div>
        @endforeach
      </div>

      {{-- Mobile --}}
      <div class="row g-3 g-md-4 d-md-none">
        @foreach ($categorias as $index => $categoria)
        @if ($index < 4)
          <div class="col-12 col-sm-6">
          <a href="{{ $categoria['href'] }}">
            <div class="oficina-card text-center p-3 p-md-4 h-100">
              <div class="oficina-icon mb-2 fs-2">{{ $categoria['icono'] }}</div>
              <div class="div-title fw-semibold">{{ $categoria['titulo'] }}</div>
            </div>
          </a>
      </div>
      @endif
      @endforeach
    </div>

    @if (count($categorias) > 4)
    <div class="collapse d-md-none" id="moreCategoriasMobile">
      <div class="row g-3 g-md-4 mt-2">
        @foreach ($categorias as $index => $categoria)
        @if ($index >= 4)
        <div class="col-12 col-sm-6">
          <a href="{{ $categoria['href'] }}">
            <div class="oficina-card text-center p-3 p-md-4 h-100">
              <div class="oficina-icon mb-2 fs-2">{{ $categoria['icono'] }}</div>
              <div class="div-title fw-semibold">{{ $categoria['titulo'] }}</div>
            </div>
          </a>
        </div>
        @endif
        @endforeach
      </div>
    </div>

    <div class="text-center mt-3 d-md-none">
      <button class="btn btn-outline-custom" data-bs-toggle="collapse" data-bs-target="#moreCategoriasMobile" aria-expanded="false" aria-controls="moreCategoriasMobile">
        Mostrar más
      </button>
    </div>
    @endif
  </div>
</div> <!-- end tab-content -->
</div>
</div>
<div class="box-1">
  <div class="container">
    <div class="row align-items-center">
      <!-- Columna de texto -->
      <div class="col-12 col-md-6 text-center text-md-start mb-4 mb-md-0">
        <h2>Tutoriales y Preguntas Frecuentes</h2>
        <p class="intro">
          Aquí podrás encontrar toda la información necesaria.
        </p>
        <a href="https://lujandecuyo.gob.ar/instructivos-mld/" class="btn btn-outline-custom">
          Ver instructivos
        </a>
      </div>

      <!-- Columna de imagen -->
      <div class="col-12 col-md-6">
        <a href="https://lujandecuyo.gob.ar/instructivos-mld/">
          <img
            src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/brand%2FuserImages%2Ffaq_mld_time1729165211535.png?alt=media&token=fd91549a-e46f-4913-9a86-7b42d0d1499f"
            alt="lujan-digital-logo"
            class="img-fluid mx-auto d-block"
            style="width: 100%; height: auto;">
        </a>
      </div>
    </div>
  </div>
</div>



<script>
  document.addEventListener('DOMContentLoaded', function() {
    const botones = document.querySelectorAll('[data-bs-toggle="collapse"]');

    botones.forEach(boton => {
      const targetSelector = boton.getAttribute('data-bs-target');
      const target = document.querySelector(targetSelector);

      if (!target) return;

      const collapseInstance = bootstrap.Collapse.getOrCreateInstance(target, {
        toggle: false
      });

      target.addEventListener('show.bs.collapse', function() {
        boton.textContent = 'Mostrar menos';
      });

      target.addEventListener('hide.bs.collapse', function() {
        boton.textContent = 'Mostrar más';
      });
    });
  });
</script>

@endsection