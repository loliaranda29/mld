@extends('layouts.app-funcionario')

@section('title', 'Bandeja de entrada')

@section('profile_content')
<div class="container px-4 mt-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4">Bandeja de entrada</h2>
  </div>

  <div class="card mb-3 p-3">
    <div class="row g-3">
      <div class="col-md-4">
        <select class="form-select">
          <option selected>Mis tareas</option>
          <option>Todos los trámites</option>
          <option>Trámites asignados</option>
        </select>
      </div>
      <div class="col-md-8 d-flex justify-content-end align-items-center gap-2">
        <div class="form-check me-3">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarFolio" checked>
          <label class="form-check-label" for="buscarFolio">Buscar por folio</label>
        </div>
        <div class="form-check me-3">
          <input class="form-check-input" type="radio" name="tipoBusqueda" id="buscarPrefolio">
          <label class="form-check-label" for="buscarPrefolio">Buscar por prefolio</label>
        </div>
        <input type="text" class="form-control w-50" placeholder="Buscar por folio">
        <button class="btn btn-primary"><i class="bi bi-search"></i></button>
        <button class="btn btn-outline-secondary"><i class="bi bi-sliders"></i></button>
      </div>
    </div>
  </div>

  <ul class="nav nav-tabs mb-3">
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
