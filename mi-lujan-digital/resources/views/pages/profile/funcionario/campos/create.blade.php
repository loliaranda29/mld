@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ isset($campo) ? 'Editar Campo' : 'Nuevo Campo' }} para: {{ $tramite->nombre }}</h2>

    <form method="POST" action="{{ isset($campo) ? route('campos.update', [$tramite->id, $campo->id]) : route('campos.store', $tramite->id) }}">
        @csrf
        @if(isset($campo))
            @method('PUT')
        @endif

        <div class="mb-3">
            <label for="etiqueta" class="form-label">Etiqueta</label>
            <input type="text" name="etiqueta" class="form-control" value="{{ old('etiqueta', $campo->etiqueta ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre interno (slug)</label>
            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $campo->nombre ?? '') }}" required>
        </div>

        <div class="mb-3">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" class="form-select" required>
                <option value="">Seleccionar tipo...</option>
                @php
                    $tipos = ['texto_corto', 'parrafo', 'opcion_multiple', 'checkbox', 'select', 'fecha', 'hora', 'archivo', 'ide', 'direccion', 'direccion_prevencion', 'busqueda', 'codigo_postal', 'grupo_preguntas', 'texto_enriquecido', 'informe_urbanistico', 'codigo', 'api'];
                @endphp
                @foreach($tipos as $tipo)
                    <option value="{{ $tipo }}" {{ old('tipo', $campo->tipo ?? '') == $tipo ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $tipo)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="requerido" value="1" {{ old('requerido', $campo->requerido ?? false) ? 'checked' : '' }}>
            <label class="form-check-label">Requerido</label>
        </div>

        <div class="mb-3">
            <label for="orden" class="form-label">Orden</label>
            <input type="number" name="orden" class="form-control" value="{{ old('orden', $campo->orden ?? 0) }}">
        </div>

        <button type="submit" class="btn btn-success">{{ isset($campo) ? 'Actualizar' : 'Guardar' }}</button>
        <a href="{{ route('campos.index', $tramite->id) }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
