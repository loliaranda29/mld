@extends('layouts.app-funcionario')

@section('title', 'Nuevo trámite')

@section('profile_content')
<div class="max-w-4xl mx-auto bg-white shadow rounded p-6">
    <h2 class="text-xl font-semibold mb-4">Crear nuevo trámite</h2>

    <form action="#" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block font-semibold">Nombre del trámite</label>
            <input type="text" name="nombre" class="form-input w-full" required>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Descripción</label>
            <textarea name="descripcion" rows="4" class="form-textarea w-full" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">¿Disponible en línea?</label>
            <select name="disponible" class="form-select w-full">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">¿Publicado?</label>
            <select name="publicado" class="form-select w-full">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">¿Aceptar solicitudes?</label>
            <select name="acepta_solicitudes" class="form-select w-full">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-semibold">Archivo adjunto (opcional)</label>
            <input type="file" name="archivo" class="form-input w-full">
        </div>

        <div class="text-right">
            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="{{ route('funcionario.tramite_config') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
