@extends('layouts.app-funcionario')

@section('title', 'SEO')

@section('profile_content')
<div class="container-fluid mt-3">

  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('configuracion.index') }}">Configuración</a></li>
      <li class="breadcrumb-item active">SEO</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">SEO</h5>
      <button form="form-seo" class="btn btn-primary">Guardar cambios</button>
    </div>

    <div class="card-body">
      <form id="form-seo" action="{{ route('configuracion.seo.guardar') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
          <label class="form-label">Título <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" required
                 value="{{ old('title', $seo['title'] ?? '') }}"
                 placeholder="Mi Luján">
        </div>

        <div class="mb-4">
          <label class="form-label">Descripción <span class="text-danger">*</span></label>
          <input type="text" name="description" class="form-control" required
                 value="{{ old('description', $seo['description'] ?? '') }}"
                 placeholder="Mi Luján Digital">
        </div>

        <div class="mb-2 d-flex align-items-center gap-3">
          <label class="form-label m-0">Favicon</label>
          @if($faviconUrl)
            <a href="{{ $faviconUrl }}" target="_blank">Ver favicon actual</a>
          @endif
        </div>
        <div class="mb-4">
          <input type="file" name="favicon" class="form-control" accept=".ico,.png,.svg">
          <small class="text-muted">Formatos permitidos: .ico, .png, .svg — máx 512 KB.</small>
        </div>

      </form>
    </div>
  </div>
</div>
@endsection
