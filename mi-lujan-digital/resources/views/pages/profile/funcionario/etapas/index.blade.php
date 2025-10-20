@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Etapas del Trámite: {{ $tramite->nombre }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('etapas.create', $tramite->id) }}" class="btn btn-primary">Nueva Etapa</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Nombre</th>
                <th>Orden</th>
                <th>Requiere Firma</th>
                <th>Requiere Documentación</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($etapas as $etapa)
            <tr>
                <td>{{ $etapa->nombre }}</td>
                <td>{{ $etapa->orden }}</td>
                <td>{{ $etapa->requiere_firma ? 'Sí' : 'No' }}</td>
                <td>{{ $etapa->requiere_documentacion ? 'Sí' : 'No' }}</td>
                <td>
                    <a href="{{ route('etapas.edit', [$tramite->id, $etapa->id]) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('etapas.destroy', [$tramite->id, $etapa->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta etapa?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hay etapas configuradas aún.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
