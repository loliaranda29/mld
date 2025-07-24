@extends('layouts.app-funcionario')

@section('title', 'Listado de trámites')

@section('profile_content')
<div class="flex justify-between items-center mb-4">
  <h2 class="text-xl font-semibold">Listado de trámites</h2>
  <div class="space-x-2">
    <a href="{{ route('funcionario.tramite.create') }}" class="btn btn-primary">Nuevo</a>
  </div>
</div>

<div class="bg-white rounded shadow p-4">
  <div class="flex flex-wrap items-center gap-2 mb-4">
    <select class="form-select w-auto">
      <option>Todos</option>
      <option>Activos</option>
      <option>Inactivos</option>
    </select>
    <label class="flex items-center space-x-2">
      <input type="checkbox" class="form-checkbox">
      <span class="text-sm">¿Ver solo los publicados en el inicio?</span>
    </label>
    <div class="flex-grow relative">
      <input type="text" class="form-control w-full" placeholder="Buscar">
      <button class="absolute right-2 top-1/2 -translate-y-1/2">
        <i class="bi bi-search"></i>
      </button>
    </div>
    <div class="flex items-center space-x-1">
      <button class="btn btn-outline-secondary p-2"><i class="bi bi-upload"></i></button>
      <button class="btn btn-outline-secondary p-2"><i class="bi bi-download"></i></button>
    </div>
  </div>

  <div class="overflow-x-auto">
    <table class="table w-full text-sm text-left">
      <thead class="bg-gray-100">
        <tr>
          <th><input type="checkbox" /></th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Fecha de creación</th>
          <th>Disponible en línea</th>
          <th>Trámite publicado</th>
          <th>Aceptar solicitudes</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr class="border-t">
          <td><input type="checkbox" /></td>
          <td>Asistencia presencial para Licencias</td>
          <td>Te ayudamos a cargar tu trámite online...</td>
          <td>21/07/2025 08:40:05 hrs</td>
          <td><span class="badge bg-success">Activo</span></td>
          <td><span class="badge bg-success">Activo</span></td>
          <td><span class="badge bg-secondary">Inactivo</span></td>
          <td>
            <div class="flex space-x-2">
              <button class="text-blue-500"><i class="bi bi-pencil"></i></button>
              <button class="text-red-500"><i class="bi bi-trash"></i></button>
            </div>
          </td>
        </tr>
        <!-- Más filas dummy -->
      </tbody>
    </table>
  </div>

  <div class="mt-4 text-center">
    <button class="btn btn-outline-primary">Mostrar más</button>
  </div>
</div>
@endsection