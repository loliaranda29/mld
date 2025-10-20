@extends('layouts.app-funcionario')

@section('title', 'Filtros de trámites')

@section('profile_content')
<div class="container-fluid mt-4">
    {{-- Breadcrumbs + título --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item">
                    <a href="{{ route('home.index') }}">Inicio</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Filtros de trámites</li>
            </ol>
        </nav>

        {{-- ⬇️ Trigger correcto del modal (Bootstrap) --}}
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAgregarFiltro">
            Agregar filtro
        </button>
    </div>

    {{-- Card principal --}}
    <div class="card">
        <div class="card-body">

            {{-- Conjunción en los filtros --}}
            <div class="mb-3">
                <h5 class="mb-2">Conjunción en los filtros</h5>
                <div class="d-flex align-items-center gap-3">
                    <span>No</span>

                    <form action="{{ route('filtros.toggle') }}" method="POST">
                        @csrf
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   role="switch"
                                   id="switchConjuncion"
                                   onChange="this.form.submit()"
                                   {{ $conjuncion ? 'checked' : '' }}>
                        </div>
                    </form>

                    <span>Sí</span>
                </div>
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Nombre</th>
                            <th style="width: 40%">Fecha de creación</th>
                            <th class="text-end" style="width: 20%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($items as $i)
                            <tr>
                                <td>{{ $i['nombre'] }}</td>
                                <td>{{ \Carbon\Carbon::parse($i['created_at'])->format('d/m/Y H:i:s') }}</td>
                                <td class="text-end">
                                    <form action="{{ route('filtros.destroy', $i['id']) }}" method="POST"
                                          onsubmit="return confirm('¿Eliminar filtro «{{ $i['nombre'] }}»?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-link text-decoration-none" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">Sin filtros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación simple tipo "Mostrar más" --}}
            @php
                $nextPerPage = $perPage + 10;
                $mostrarMas  = $total > $perPage;
            @endphp

            <div class="d-flex justify-content-center">
                @if ($mostrarMas)
                    <a class="btn btn-outline-secondary"
                       href="{{ request()->fullUrlWithQuery(['per_page' => $nextPerPage, 'page' => 1]) }}">
                        Mostrar más
                    </a>
                @else
                    <button class="btn btn-outline-secondary" disabled>Mostrar más</button>
                @endif
            </div>
        </div>
    </div>

    {{-- Modal: Agregar filtros (Bootstrap puro) --}}
    <div class="modal fade" id="modalAgregarFiltro" tabindex="-1" aria-hidden="true" aria-labelledby="tituloModalAgregarFiltro">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow">
          <div class="modal-header bg-dark text-white">
            <h5 class="modal-title" id="tituloModalAgregarFiltro">Agregar filtros</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
          </div>

          <form action="{{ route('filtros.store') }}" method="POST">
            @csrf
            <div class="modal-body">
              <label class="form-label">Elige un filtro</label>
              <div class="border rounded" style="max-height: 360px; overflow:auto;">
                <ul class="list-group list-group-flush">
                  @foreach ($available as $id => $nombre)
                    <li class="list-group-item d-flex align-items-center">
                      <input class="form-check-input me-2" type="checkbox"
                             id="chk_{{ $id }}" name="filtros[]" value="{{ $id }}">
                      <label class="form-check-label" for="chk_{{ $id }}">{{ $nombre }}</label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
</div>

{{-- Ajustes menores --}}
<style>
    .breadcrumb a { font-weight: 600; color:#000; }
</style>
@endsection
