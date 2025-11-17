@extends('layouts.app-funcionario')

@section('title', 'Bandeja de entrada')

@section('profile_content')
<div class="container mt-4">
  <h2 class="h5 mb-4">Bandeja de entrada</h2>

  <!-- Filtros superiores -->
  <div class="card p-3 mb-3">
    <div class="row g-2 align-items-end mb-3">
      <div class="col-md-4">
        <label class="form-label">Tareas</label>
        <select class="form-select">
          <option selected>Mis tareas</option>
          <option>Todos los trámites</option>
          <option>Trámites asignados</option>
        </select>
      </div>
      <div class="col-md-8 d-flex flex-wrap justify-content-end align-items-end gap-2">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarFolio" checked>
          <label class="form-check-label" for="buscarFolio">Buscar por folio</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarPrefolio">
          <label class="form-check-label" for="buscarPrefolio">Buscar por prefolio</label>
        </div>
        <input type="text" class="form-control w-auto" placeholder="Buscar por folio">
        <button class="btn btn-dark"><i class="bi bi-search"></i></button>
        <button class="btn btn-outline-secondary"><i class="bi bi-sliders"></i></button>
      </div>
    </div>

    <!-- Filtros adicionales -->
    <div class="row g-2">
      <div class="col-md-5">
        <input type="text" class="form-control" placeholder="Buscar un trámite, servicio, ...">
      </div>
      <div class="col-md-2">
        <input type="text" class="form-control" placeholder="Rango de fechas">
      </div>
      <div class="col-md-3">
        <input type="text" class="form-control" placeholder="Buscar por CUIL del solicitante">
      </div>
      <div class="col-md-2 d-flex align-items-end">
        <button class="btn btn-outline-dark w-100"><i class="bi bi-download me-1"></i>Buscar</button>
      </div>
    </div>
  </div>

  <!-- Tabs -->
  <ul class="nav nav-tabs mb-3">
    <li class="nav-item">
      <a class="nav-link" href="#">Todos <span class="badge bg-secondary">146</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="#">Abiertos <span class="badge bg-secondary">146</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Cerrados <span class="badge bg-secondary">146</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Por asignar</a>
    </li>
  </ul>

  <div class="card">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th></th>
            <th>Trámite</th>
            <th>Folio/Prefolio del Expediente</th>
            <th>Tramitante</th>
            <th>Fecha de recepción de solicitud</th>
            <th class="text-center">Estatus</th>
            <th>Operador(es) asignado(s)</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input type="checkbox"></td>
            <td>Asistencia presencial para Licencias</td>
            <td>TRM-2025-0001</td>
            <td>Alicia Aranda</td>
            <td>21/07/2025 08:40:05 hrs</td>
            <td class="text-center"><span class="badge bg-success">Activo</span></td>
            <td>-</td>
            <td>
              <a href="#" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
            </td>
          </tr>
          <tr>
            <td colspan="8" class="text-center text-muted">Sin más registros</td>
          </tr>
        </tbody>
      </table>
    </div>
  @else
    <div class="alert alert-info mb-0">No hay solicitudes para mostrar.</div>
  @endif
</div>
 
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
        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-chevron-left"></i></button>
        <button class="btn btn-outline-secondary btn-sm" disabled><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>
@endsection
