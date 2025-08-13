{{-- resources/views/pages/profile/funcionario/catalogos/subcatalogos.blade.php --}}
@extends('layouts.app-funcionario')

@section('title', 'Subcatálogos')

@section('profile_content')
<div class="container-fluid mt-4">
  @if(session('ok'))
  <div class="alert alert-success">{{ session('ok') }}</div>
@endif
@if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div>
@endif

  {{-- Breadcrumbs + título --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
          <a href="{{ route('home.index') }}">Inicio</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('catalogos.index') }}">Catálogos</a>
        </li>
        <li class="breadcrumb-item">
          <a href="{{ route('catalogos.show', $catalogoId) }}">
            {{ $catalogo['nombre'] ?? $catalogoId }}
          </a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Subcatálogos</li>
      </ol>
    </nav>

    <div class="d-flex gap-2">
      <a href="{{ route('catalogos.show', $catalogoId) }}" class="btn btn-outline-secondary">
        Regresar
      </a>
      <button type="button" class="btn btn-dark" disabled>
        Cargar nuevos datos
      </button>
    </div>
  </div>

  {{-- Mensaje info (como en la referencia) --}}
  <div class="alert alert-light border mb-3">
    Si realizás alguna modificación recordá volver a la
    <a href="{{ route('catalogos.show', $catalogoId) }}">página inicial</a>
    para sincronizar tu catálogo.
  </div>

  {{-- Tabla --}}
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th style="width: 220px;">Slug</th>
            <th style="width: 280px;">Id</th>
            <th style="width: 120px;" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subcatalogos as $it)
            <tr>
              <td>{{ $it['nombre'] ?? '-' }}</td>
              <td class="text-muted">{{ $it['slug'] ?? '-' }}</td>
              <td class="text-muted">
                <span class="small">{{ $it['id'] ?? '-' }}</span>
              </td>
              <td class="text-center">
  {{-- Ver detalle del subcatálogo --}}
  <a
    href="{{ route('catalogos.sub.show', [$catalogoId, $it['id']]) }}"
    class="btn btn-link p-0 m-0 text-primary me-3"
    title="Ver"
  >
    <i class="bi bi-eye"></i>
  </a>

  {{-- Copiar ID al portapapeles --}}
  <button
    type="button"
    class="btn btn-link p-0 m-0 text-primary me-3"
    title="Copiar ID"
    onclick="copyToClipboard('{{ $it['id'] }}')"
  >
    <i class="bi bi-clipboard"></i>
  </button>

  {{-- 🗑️ Eliminar --}}
  <form
    action="{{ route('catalogos.sub.destroy', [$catalogoId, $it['id']]) }}"
    method="POST"
    class="d-inline"
    onsubmit="return confirm('¿Eliminar «{{ $it['nombre'] ?? 'este subcatálogo' }}»?')"
  >
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-link p-0 m-0 text-danger" title="Eliminar">
      <i class="bi bi-trash"></i>
    </button>
  </form>
</td>

            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Sin subcatálogos</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación simple (opcional, como en tus otras pantallas) --}}
    @php
      $total   = $total   ?? (is_countable($subcatalogos) ? count($subcatalogos) : 0);
      $page    = $page    ?? 1;
      $perPage = $perPage ?? max(10, $total); // por si no llega de controlador
      $from    = $total ? (($page - 1) * $perPage) + 1 : 0;
      $to      = min($page * $perPage, $total);
      $prev    = max(1, $page - 1);
      $next    = $total > $page * $perPage ? $page + 1 : $page;
    @endphp

    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">Filas por página:</span>
        <select class="form-select form-select-sm" style="width: 70px"
                onchange="location.href='?per_page='+this.value+'&page=1'">
          @foreach([10,20,50] as $n)
            <option value="{{ $n }}" @selected(($perPage ?? 10) == $n)>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ $from }}–{{ $to }} de {{ $total }}</span>
        <div class="btn-group">
          <a class="btn btn-outline-secondary btn-sm"
             href="?per_page={{ $perPage }}&page={{ $prev }}"
             @disabled($page == 1)
          >
            <i class="bi bi-chevron-left"></i>
          </a>
          <a class="btn btn-outline-secondary btn-sm"
             href="?per_page={{ $perPage }}&page={{ $next }}"
             @disabled($page == $next)
          >
            <i class="bi bi-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- utilitario para copiar --}}
<script>
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function () {
      // Podés reemplazar alert por un toast si tenés uno
      alert('ID copiado al portapapeles');
    }).catch(function () {
      alert('No se pudo copiar');
    });
  }
</script>
@endsection
