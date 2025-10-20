@extends('layouts.app-funcionario')

@section('title','Control de cambios')

@section('profile_content')
<div class="container-fluid mt-3">
  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item active">Control de cambios</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-body">
      <h5 class="mb-3">Control de cambios</h5>

      {{-- Tabs --}}
      @php
        $tabs = [
          'todos' => 'TODOS',
          'configuraciones' => 'CONFIGURACIONES',
          'tramites' => 'TRÁMITES',
          'inspectores' => 'INSPECTORES',
          'pagos' => 'PAGOS',
          'citas' => 'CITAS',
        ];
      @endphp
      <ul class="nav nav-tabs mb-3">
        @foreach($tabs as $key => $label)
          <li class="nav-item">
            <a class="nav-link {{ $tab===$key ? 'active' : '' }}"
               href="{{ request()->fullUrlWithQuery(['tab'=>$key,'page'=>1]) }}">{{ $label }}</a>
          </li>
        @endforeach
      </ul>

      {{-- Filtros --}}
      <form class="row g-2 align-items-center mb-3" method="GET">
        <input type="hidden" name="tab" value="{{ $tab }}">
        <div class="col-md-4">
          <div class="input-group">
            <select name="modulo" class="form-select">
              <option value="">Seleccionar módulo</option>
              @foreach($modulos as $m)
                <option value="{{ $m }}" @selected($filters['modulo']===$m)>{{ $m }}</option>
              @endforeach
            </select>
            <button class="btn btn-outline-secondary" title="Buscar"><i class="bi bi-search"></i></button>
          </div>
        </div>
        <div class="col-md-4">
          <input type="text" name="email" class="form-control"
                 placeholder="Correo electrónico" value="{{ $filters['email'] }}">
        </div>
        <div class="col-md-3">
          <input type="date" name="fecha" class="form-control" value="{{ $filters['fecha'] }}">
        </div>
        <div class="col-md-1 text-end">
          <a class="btn btn-outline-primary"
             href="{{ route('registro.cambios.export', request()->query()) }}"
             title="Exportar CSV">
             <i class="bi bi-upload"></i>
          </a>
        </div>
      </form>

      {{-- Tabla --}}
      <div class="table-responsive">
        <table class="table">
          <thead class="table-light">
            <tr>
              <th>Módulo</th>
              <th>Sección</th>
              <th>Acción</th>
              <th>Fecha</th>
              <th>Nombre del usuario</th>
              <th>Correo del usuario</th>
            </tr>
          </thead>
          <tbody>
            @forelse($items as $r)
              <tr>
                <td>{{ $r['modulo'] }}</td>
                <td>{{ $r['seccion'] }}</td>
                <td>{{ $r['accion'] }}</td>
                <td>{{ $r['fecha'] }}</td>
                <td>{{ $r['user'] }}</td>
                <td>{{ $r['email'] }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-4">Sin resultados</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{-- Paginación simple (filas por página y flechas) --}}
      @php
        $pages = max(1, (int) ceil($total / $perPage));
        $prev = max(1, $page - 1);
        $next = min($pages, $page + 1);
      @endphp

      <div class="d-flex justify-content-end align-items-center gap-3">
        <div class="small text-muted">Filas por página:</div>
        <form method="GET">
          @foreach(request()->except(['per_page']) as $k => $v)
            <input type="hidden" name="{{ $k }}" value="{{ $v }}">
          @endforeach
          <select name="per_page" class="form-select form-select-sm" onchange="this.form.submit()">
            @foreach([5,10,20,50] as $n)
              <option value="{{ $n }}" @selected($perPage==$n)>{{ $n }}</option>
            @endforeach
          </select>
        </form>
        <div class="small">{{ ($items->isEmpty()?0:(($page-1)*$perPage+1)) }}–{{ min($page*$perPage, $total) }} de {{ $total }}</div>
        <div class="btn-group">
          <a class="btn btn-sm btn-outline-secondary {{ $page<=1?'disabled':'' }}"
             href="{{ request()->fullUrlWithQuery(['page'=>$prev]) }}">
            <i class="bi bi-chevron-left"></i>
          </a>
          <a class="btn btn-sm btn-outline-secondary {{ $page>=$pages?'disabled':'' }}"
             href="{{ request()->fullUrlWithQuery(['page'=>$next]) }}">
            <i class="bi bi-chevron-right"></i>
          </a>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
