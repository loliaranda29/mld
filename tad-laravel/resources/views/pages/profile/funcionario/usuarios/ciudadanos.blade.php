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
            <td class="text-center">
              <a href="{{ route('funcionario.usuarios.ciudadanos.show', 1) }}" class="text-decoration-none">
                <i class="bi bi-eye"></i>
              </a>
            </td>

            <td class="text-center">
                {{-- Desactivar / Activar --}}
                <button type="button"
                        class="btn btn-link p-0 me-2"
                        title="Activar/Desactivar usuario"
                        data-bs-toggle="modal"
                        data-bs-target="#deactivateUserModal"
                        data-id="{{ $ciudadano['id'] ?? 1 }}"
                        data-name="{{ $ciudadano['nombre'] ?? 'Usuario' }}"
                        data-active="{{ $ciudadano['activo'] ?? 1 }}">
                  <i class="bi bi-person-dash-fill text-danger"></i>
                </button>

                {{-- Cambiar contraseña --}}
                <button type="button"
                        class="btn btn-link p-0 me-2"
                        title="Cambiar contraseña"
                        data-bs-toggle="modal"
                        data-bs-target="#changePasswordModal"
                        data-id="{{ $ciudadano['id'] ?? 1 }}"
                        data-name="{{ $ciudadano['nombre'] ?? 'Usuario' }}">
                  <i class="bi bi-key-fill text-danger"></i>
                </button>

                {{-- Cambiar correo --}}
                <button type="button"
                        class="btn btn-link p-0"
                        title="Cambiar correo electrónico"
                        data-bs-toggle="modal"
                        data-bs-target="#changeEmailModal"
                        data-id="{{ $ciudadano['id'] ?? 1 }}"
                        data-name="{{ $ciudadano['nombre'] ?? 'Usuario' }}"
                        data-email="{{ $ciudadano['correo'] ?? '' }}">
                  <i class="bi bi-envelope-check-fill text-primary"></i>
                </button>
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
{{-- Desactivar / Activar --}}
<div class="modal fade" id="deactivateUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title"><span class="deact-text">Desactivar</span> usuario</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        ¿Seguro que querés <strong class="deact-text">desactivar</strong> al usuario
        <strong class="user-name"></strong>?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-danger">
          <i class="bi bi-person-dash-fill me-1"></i><span class="deact-text">Desactivar</span>
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Cambiar contraseña --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Cambiar contraseña — <span class="user-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Nueva contraseña</label>
          <input type="password" name="password" class="form-control" required minlength="8">
        </div>
        <div>
          <label class="form-label">Confirmar contraseña</label>
          <input type="password" name="password_confirmation" class="form-control" required minlength="8">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-dark">
          <i class="bi bi-key-fill me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>

{{-- Cambiar correo --}}
<div class="modal fade" id="changeEmailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Cambiar correo — <span class="user-name"></span></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <label class="form-label">Correo electrónico</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-envelope-check-fill me-1"></i> Guardar
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
