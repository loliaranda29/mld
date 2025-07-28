@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Gestión de Trámites</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('funcionario.tramite.create') }}" class="btn btn-primary">Nuevo Trámite</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Publicado</th>
                <th>Disponible</th>
                <th>Mostrar en Inicio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($tramites as $tramite)
            <tr>
                <td>{{ $tramite->nombre }}</td>
                <td>{{ $tramite->publicado ? 'Sí' : 'No' }}</td>
                <td>{{ $tramite->disponible ? 'Sí' : 'No' }}</td>
                <td>{{ $tramite->mostrar_inicio ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('funcionario.tramite.edit', $tramite->id) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('funcionario.tramite.destroy', $tramite->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este trámite?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No hay trámites registrados.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
