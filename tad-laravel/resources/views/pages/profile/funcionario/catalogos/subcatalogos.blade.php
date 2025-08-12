@extends('layouts.app-funcionario')

@section('title', 'Subcatálogos')

@section('profile_content')
<div class="container-fluid mt-4">

  <div class="card shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
            <li class="breadcrumb-item">
              <a href="{{ route('catalogos.index') }}">Catálogos</a>
            </li>
            <li class="breadcrumb-item">
              <a href="{{ route('catalogos.show', $catalogoId) }}">{{ $catalogoId }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Subcatálogos</li>
          </ol>
        </nav>
        <h5 class="mt-2 mb-0">Subcatálogos</h5>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('catalogos.show', $catalogoId) }}" class="btn btn-outline-secondary">Regresar</a>
        <button class="btn btn-primary">Cargar nuevos datos</button>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th style="width:220px;">Slug</th>
            <th style="width:420px;">Id</th>
            <th style="width:130px;" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subcatalogos as $row)
            <tr>
              <td>{{ $row['nombre'] }}</td>
              <td>{{ $row['slug'] }}</td>
              <td class="font-monospace small">{{ $row['id'] }}</td>
              <td class="text-center">
                {{-- Ver (demo) --}}
                <a href="#" class="text-primary me-3" title="Ver">
                  <i class="bi bi-eye"></i>
                </a>
                {{-- Copiar Id --}}
                <a href="#" class="text-primary me-3"
                   onclick="navigator.clipboard.writeText('{{ $row['id'] }}'); return false;"
                   title="Copiar Id">
                  <i class="bi bi-clipboard"></i>
                </a>
                {{-- Eliminar (demo) --}}
                <a href="#" class="text-danger" title="Eliminar"
                   onclick="alert('Demo: no elimina.'); return false;">
                  <i class="bi bi-trash"></i>
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Sin registros</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @php
      $from = $total ? (($page-1)*$perPage)+1 : 0;
      $to   = min($page*$perPage, $total);
      $prev = max(1, $page-1);
      $next = $total > $page*$perPage ? $page+1 : $page;
    @endphp

    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">Filas por página:</span>
        <select class="form-select form-select-sm" style="width: 70px"
                onchange="location.href='?per_page='+this.value+'&page=1'">
          @foreach([10,20,50] as $n)
            <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ $from }}–{{ $to }} de {{ $total }}</span>
        <div class="btn-group">
          <a class="btn btn-outline-secondary btn-sm" href="?per_page={{ $perPage }}&page={{ $prev }}" @disabled($page==1)>
            <i class="bi bi-chevron-left"></i>
          </a>
          <a class="btn btn-outline-secondary btn-sm" href="?per_page={{ $perPage }}&page={{ $next }}" @disabled($page==$next)>
            <i class="bi bi-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
