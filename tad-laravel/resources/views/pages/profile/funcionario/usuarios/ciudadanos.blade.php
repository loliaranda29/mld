@extends('layouts.app-funcionario')

@section('title', 'Ciudadanos')

@section('profile_content')
<div class="container mt-4">
  <!-- Encabezado -->
  <h2 class="h5 mb-4">Ciudadanos</h2>

  <!-- Tarjeta resumen -->
  <div class="card bg-light border-0 shadow-sm mb-4">
    <div class="card-body d-flex align-items-center">
      <div class="me-3">
        <i class="bi bi-person-vcard-fill fs-1 text-primary"></i>
      </div>
      <div>
        <div class="text-muted small">Total de ciudadanos</div>
        <div class="h4 mb-0">23.688</div> <!-- Podés reemplazar con variable {{ $totalCiudadanos }} -->
      </div>
    </div>
  </div>

  <!-- Filtros -->
  <div class="mb-3 d-flex align-items-center gap-3">
    <label class="form-check">
      <input type="radio" class="form-check-input" name="buscarPor" checked>
      Buscar por correo
    </label>
    <label class="form-check">
      <input type="radio" class="form-check-input" name="buscarPor">
      Buscar por CUIL
    </label>
  </div>

  <!-- Buscador -->
  <div class="input-group mb-4">
    <input type="text" class="form-control" placeholder="Buscar por correo">
    <button class="btn btn-dark"><i class="bi bi-search"></i></button>
    <button class="btn btn-outline-secondary"><i class="bi bi-download"></i></button>
    <button class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise"></i></button>
  </div>

  <!-- Tabla -->
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Fecha de inscripción</th>
            <th class="text-center">Mostrar detalles</th>
            <th class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <!-- Ejemplo de fila -->
          <tr>
            <td>Pam Martínez</td>
            <td>fernando~1@os.city</td>
            <td>16/11/2023 21:04:35</td>
            <td class="text-center"><i class="bi bi-eye"></i></td>
            <td class="text-center">
              <i class="bi bi-person-lines-fill text-danger me-2"></i>
              <i class="bi bi-share-fill text-danger me-2"></i>
              <i class="bi bi-envelope-check-fill text-primary"></i>
            </td>
          </tr>
          <!-- Agregá aquí más filas dinámicamente -->
        </tbody>
      </table>
    </div>

    <!-- Footer tabla -->
    <div class="card-footer d-flex justify-content-between align-items-center">
      <div>
        Filas por página:
        <select class="form-select d-inline w-auto ms-2">
          <option>5</option>
          <option>10</option>
          <option>20</option>
        </select>
      </div>
      <div>
        <span class="text-muted small">1–5 de 23688</span>
        <button class="btn btn-outline-secondary btn-sm ms-2"><i class="bi bi-chevron-left"></i></button>
        <button class="btn btn-outline-secondary btn-sm"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection
