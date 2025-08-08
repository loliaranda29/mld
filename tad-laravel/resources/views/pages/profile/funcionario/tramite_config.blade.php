@extends('layouts.app-funcionario')

@section('profile_content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Listado de trámites</h4>
        <div class="d-flex gap-2">
            <a href="{{ route('funcionario.tramite.create') }}" class="btn btn-primary">Nuevo</a>
            <button class="btn btn-outline-secondary">Subir trámites</button>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="form-group mb-0">
                    <select class="form-select">
                        <option>Todos</option>
                        <option>Activos</option>
                        <option>Inactivos</option>
                    </select>
                </div>
                <div class="form-check mb-0">
                    <input type="checkbox" class="form-check-input" id="soloInicio">
                    <label class="form-check-label" for="soloInicio">¿Ver solo los publicados en el inicio?</label>
                </div>
                <div class="form-group mb-0 flex-grow-1 mx-3">
                    <input type="text" class="form-control" placeholder="Buscar...">
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary"><i class="bi bi-download"></i></button>
                    <button class="btn btn-outline-secondary"><i class="bi bi-upload"></i></button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center">
                                <input type="checkbox">
                                <br>
                                <small>Mostrar<br>en inicio</small>
                            </th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Fecha de creación</th>
                            <th>Disponible en línea</th>
                            <th>Trámite publicado</th>
                            <th>Aceptar solicitudes</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
    @forelse ($tramites as $tramite)
        <tr>
            <td class="text-center">
                <input type="checkbox" {{ $tramite->mostrar_inicio ? 'checked' : '' }}>
            </td>
            <td>
                {{ $tramite->nombre }}
                @if($tramite->tiene_adjuntos)
                    <i class="bi bi-paperclip text-secondary ms-1" title="Tiene adjuntos"></i>
                @endif
            </td>
            <td style="max-width: 300px;" class="text-truncate" title="{{ $tramite->descripcion }}">
                {{ $tramite->descripcion }}
            </td>
            <td>{{ $tramite->created_at->format('d/m/Y H:i:s') }} hrs</td>
            <td><span class="badge bg-success">Activo</span></td>
            <td><span class="badge bg-success">Activo</span></td>
            <td><span class="badge bg-secondary">Inactivo</span></td>
            <td class="text-center">
                <a href="{{ route('funcionario.tramite.edit', $tramite->id) }}" class="text-primary me-2" title="Editar">
                    <i class="bi bi-pencil"></i>
                </a>
                <a href="#" class="text-danger" title="Eliminar" onclick="confirm('¿Estás seguro que querés eliminar este trámite?')">
                    <i class="bi bi-trash"></i>
                </a>
            </td>

        </tr>
    @empty
        <tr>
            <td class="text-center"><input type="checkbox" checked></td>
            <td>
                Asistencia presencial para el trámite de Licencias de Conducir
                <i class="bi bi-paperclip text-secondary ms-1" title="Tiene adjuntos"></i>
            </td>
            <td>
                🚗 ¿Tuviste problemas para cargar tu trámite de Licencia de Conducir online? ¡Te ayudamos!
            </td>
            <td>21/07/2025 08:40:05 hrs</td>
            <td><span class="badge bg-success">Activo</span></td>
            <td><span class="badge bg-success">Activo</span></td>
            <td><span class="badge bg-secondary">Inactivo</span></td>
            <td class="text-center">
                <i class="bi bi-pencil-square text-secondary mx-1" title="Editar (deshabilitado)"></i>
                <i class="bi bi-trash-fill text-secondary mx-1" title="Eliminar (deshabilitado)"></i>
            </td>
        </tr>
    @endforelse
</tbody>

                </table>
                <div class="text-center mt-3">
                    <button class="btn btn-outline-primary">Mostrar más</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
