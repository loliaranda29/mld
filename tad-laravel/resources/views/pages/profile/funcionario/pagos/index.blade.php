@extends('layouts.app-funcionario')

@section('title','Módulo de pagos')

@section('profile_content')
<div class="container-fluid px-3 mt-3">
  <div class="row">
    {{-- Sidebar del módulo (izquierda) --}}
    <div class="col-12 col-md-3 mb-3">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
          <i class="bi bi-cash-coin me-2"></i> Módulo Pagos
        </div>
        <div class="list-group list-group-flush">
          <a href="{{ route('pagos.index') }}" class="list-group-item list-group-item-action">
                <span class="me-2"><i class="bi bi-sliders"></i></span> Configuración pagos
                <i class="bi bi-chevron-right float-end"></i>
                </a>

                <a href="{{ route('pagos.conceptos') }}" class="list-group-item list-group-item-action">
                <span class="me-2"><i class="bi bi-journal-text"></i></span> Catálogo de conceptos
                <i class="bi bi-chevron-right float-end"></i>
                </a>

                {{-- Si quieres el acceso directo a la pantalla de UT --}}
                <a href="{{ route('pagos.config') }}" class="list-group-item list-group-item-action">
                <span class="me-2"><i class="bi bi-cash-coin"></i></span> Valor de la UT
                <i class="bi bi-chevron-right float-end"></i>
                </a>
        </div>
      </div>
    </div>

    {{-- Contenido principal (derecha) --}}
    <div class="col-12 col-md-9">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
          <h5 class="mb-0">Configuración pagos</h5>
        </div>
        <div class="card-body">

          {{-- Form Valor de la UT --}}
          <div class="mb-3">
            <h6 class="text-muted mb-2">Valor de la UT</h6>
            <form class="row g-2" method="POST" action="{{ route('pagos.ut.store') }}">
              @csrf
              <div class="col-12 col-md-4">
                <input type="text" name="anio" class="form-control" placeholder="Año de la UT">
              </div>
              <div class="col-12 col-md-4">
                <div class="input-group">
                  <span class="input-group-text">$</span>
                  <input type="number" step="0.01" name="valor" class="form-control" placeholder="Valor de la UT">
                </div>
              </div>
              <div class="col-12 col-md-4">
                <button class="btn btn-dark w-100" type="submit">Agregar</button>
              </div>
            </form>
          </div>

          {{-- Historial de la UT --}}
          <div class="mt-4">
            <h6 class="text-muted mb-2">Historial de la UT</h6>
            <div class="table-responsive">
              <table class="table align-middle">
                <thead class="table-light">
                  <tr>
                    <th style="width:120px;">Año</th>
                    <th style="width:120px;">Valor</th>
                    <th class="text-center" style="width:120px;">Acciones</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($historialUT as $row)
                    <tr>
                      <td>{{ $row['anio'] }}</td>
                      <td>${{ number_format($row['valor'],2) }}</td>
                      <td class="text-center">
                        {{-- Editar (modal demo) --}}
                        <button type="button" class="btn btn-sm btn-outline-secondary me-1" data-bs-toggle="modal"
                                data-bs-target="#editarUT{{ $row['id'] }}" title="Editar">
                          <i class="bi bi-pencil"></i>
                        </button>

                        {{-- Eliminar --}}
                        <form class="d-inline" method="POST" action="{{ route('pagos.ut.destroy',$row['id']) }}"
                              onsubmit="return confirm('¿Eliminar este valor de UT?')">
                          @csrf @method('DELETE')
                          <button class="btn btn-sm btn-outline-secondary" title="Eliminar">
                            <i class="bi bi-trash"></i>
                          </button>
                        </form>
                      </td>
                    </tr>

                    {{-- Modal editar (demo) --}}
                    <div class="modal fade" id="editarUT{{ $row['id'] }}" tabindex="-1" aria-hidden="true">
                      <div class="modal-dialog">
                        <form class="modal-content" method="POST" action="{{ route('pagos.ut.update',$row['id']) }}">
                          @csrf @method('PUT')
                          <div class="modal-header">
                            <h5 class="modal-title">Editar UT {{ $row['anio'] }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                          </div>
                          <div class="modal-body">
                            <div class="input-group">
                              <span class="input-group-text">$</span>
                              <input type="number" step="0.01" name="valor" class="form-control"
                                     value="{{ $row['valor'] }}">
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button class="btn btn-dark">Guardar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  @empty
                    <tr>
                      <td colspan="3" class="text-center text-muted">Sin registros</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endsection
