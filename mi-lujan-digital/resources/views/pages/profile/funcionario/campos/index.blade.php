@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Campos del Trámite: {{ $tramite->nombre }}</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3 text-end">
        <a href="{{ route('campos.create', $tramite->id) }}" class="btn btn-primary">Nuevo Campo</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Etiqueta</th>
                <th>Tipo</th>
                <th>Requerido</th>
                <th>Orden</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($campos as $campo)
            <tr>
                <td>{{ $campo->etiqueta }}</td>
                <td>{{ $campo->tipo }}</td>
                <td>{{ $campo->requerido ? 'Sí' : 'No' }}</td>
                <td>{{ $campo->orden }}</td>
                <td>
                    <a href="{{ route('campos.edit', [$tramite->id, $campo->id]) }}" class="btn btn-sm btn-warning">Editar</a>
                    <form action="{{ route('campos.destroy', [$tramite->id, $campo->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este campo?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No hay campos configurados aún.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
