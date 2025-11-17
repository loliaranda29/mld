@extends('layouts.app-funcionario')

@section('title','Catálogos')

@section('profile_content')
<div class="container-fluid mt-4">

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif

  <div class="card shadow-sm">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Catálogos</li>
          </ol>
        </nav>
        <h5 class="mt-2 mb-0">Catálogos</h5>
      </div>

      <a href="{{ route('catalogos.create') }}" class="btn btn-primary">
        Agregar nuevo registro
      </a>
    </div>

    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Catálogos</th>
            <th style="width: 230px;">Fecha de creación</th>
            <th style="width: 120px;" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($catalogos as $cat)
            <tr>
              <td>{{ $cat['nombre'] }}</td>
              <td>{{ \Carbon\Carbon::parse($cat['created_at'])->format('d/m/Y H:i:s') }}</td>
              <td class="text-center">
                <a href="{{ route('catalogos.show', $cat['id']) }}" class="text-primary me-3" title="Ver">
                  <i class="bi bi-eye"></i>
                </a>
                <form action="{{ route('catalogos.destroy', $cat['id']) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar este catálogo?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-link p-0 m-0 text-danger" title="Eliminar">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-muted py-4">Sin registros</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación simple (clon visual) --}}
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

{{-- Modal agregar --}}
<div class="modal fade" id="modalNuevoCatalogo" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" action="{{ route('catalogos.store') }}" method="POST">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Nuevo catálogo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" required maxlength="200" placeholder="Ej. Barrios">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">Guardar</button>
      </div>
    </form>
  </div>
</div>
@endsection
