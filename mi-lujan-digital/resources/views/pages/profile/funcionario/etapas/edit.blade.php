@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ isset($etapa) ? 'Editar Etapa' : 'Nueva Etapa' }} para: {{ $tramite->nombre }}</h2>

    <form method="POST" action="{{ isset($etapa) ? route('etapas.update', [$tramite->id, $etapa->id]) : route('etapas.store', $tramite->id) }}">
        @csrf
        @if(isset($etapa))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre de la Etapa</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $etapa->nombre ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $etapa->descripcion ?? '') }}</textarea>
        </div>

        <div class="mb-3">
            <label for="orden" class="form-label">Orden</label>
            <input type="number" name="orden" class="form-control" value="{{ old('orden', $etapa->orden ?? 0) }}">
        </div>

        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" name="requiere_firma" value="1" {{ old('requiere_firma', $etapa->requiere_firma ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Requiere Firma</label>
        </div>

        <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" name="requiere_documentacion" value="1" {{ old('requiere_documentacion', $etapa->requiere_documentacion ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Requiere Documentación</label>
        </div>

        <button type="submit" class="btn btn-success">{{ isset($etapa) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('etapas.index', $tramite->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
