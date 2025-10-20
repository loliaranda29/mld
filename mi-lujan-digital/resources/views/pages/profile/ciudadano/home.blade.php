@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 p-4 mb-4">
  <div class="row gy-4 align-items-start">

    <!-- Perfil: avatar + info personal -->
    <div class="col-12 col-md-5 text-center text-md-start">
      <div class="d-flex flex-column align-items-center align-items-md-start gap-3">

        <!-- Avatar -->
        <div class="text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background-color:#298e8c;">
          <svg data-v-19bc8e93="" id="Capa_1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 452.64" fill="#fff" width="64px" height="64px">
            <path d="M358.27,294.16c-6.08-23.69-16.19-45.32-36.59-60.6-22.51-16.86-45.33-17.82-68.81-2.71-2.23,1.43-4.42,2.93-6.68,4.29-26.5,15.99-54.16,19.94-83.58,8.63-13.03-5-23.88-13.56-35.79-20.39-4.75-2.72-9.59-4.01-15.12-3.76-30.71,1.39-52.59,16.6-66.29,43.37-17.26,33.74-21.22,70.16-18.65,107.39,1.39,20.17,11.34,35.99,27.91,47.64,9.99,7.02,21.34,11.94,33.18,12.02,75.01.48,144.45,0,216.67,0,30.31,0,58.48-30.34,60.71-62.64,1.72-24.78-.81-49.28-6.96-73.24ZM341.49,364.19c-1.57,26.07-17.44,41.14-43.79,41.66-33.95.67-101.9.27-101.9.32-31.31,0-62.63-.1-93.94,0-30.2.08-50.08-18.18-51.46-38.08-2.24-32.32,1.57-64.04,15.98-93.65,7.57-15.55,19.46-26.22,36.83-30,6.16-1.34,11.73-1.06,17.27,2.87,13.86,9.83,28.6,17.96,45.2,22.56,33.24,9.22,63.93,3,92.99-14.34,1.82-1.08,3.55-2.31,5.33-3.46,17.97-11.58,35.64-9.13,50.52,6.83,6.61,7.09,11.6,15.26,14.97,24.18,9.88,26.13,13.67,53.4,12,81.13Z" class="cls-1"></path>
            <path d="M193.28,218.56c54.7,0,98.71-44.14,98.32-98.6-.39-54.24-44.39-97.75-98.69-97.58-54.37.17-97.91,43.98-97.72,98.35.18,54.73,43.4,97.83,98.09,97.83ZM192.96,46.29c40.63-.31,74.26,32.96,74.72,73.91.45,40.5-33.08,74.2-74.06,74.44-41.18.24-74.07-32.34-74.47-73.77-.39-40.8,32.73-74.26,73.82-74.58Z" class="cls-1"></path>
          </svg>
        </div>

        <!-- Nombre y correo -->
        <div class="w-100">
          <h5 class="fw-bold mb-1">{{ $user['nombre']}} {{ $user['apellido_paterno']}}</h5>
          <p class="text-muted small mb-3">{{ $user['email'] ?? 'usuario@example.com' }}</p>
        </div>

        <!-- Botones de acción -->
        <div class="d-flex flex-column w-100 gap-2">
          <a href="#" class="btn btn-outline-custom btn-sm w-100">Cambiar contraseña</a>
          <a href="#" class="btn btn-outline-custom btn-sm w-100">Cambiar correo electrónico</a>
          <a href="#" class="btn btn-outline-custom btn-sm w-100">Cerrar sesión</a>
        </div>

      </div>
    </div>

    <!-- Panel de identidad digital -->
    <div class="col-12 col-md-7">
      <div class="border rounded-4 p-4 h-100 bg-light">
        <h6 class="fw-semibold mb-3">Identidad digital</h6>
        <p class="mb-1"><strong>Conectado a:</strong> Luján de Cuyo</p>
        <p class="mb-1"><strong>Wallet:</strong> {{ $user['wallet'] ?? 'No conectada' }}</p>
        <div class="mb-3">
          <label class="form-label fw-semibold mb-1">Llave pública</label>
          <textarea class="form-control" rows="2" disabled>{{ $user['llave_publica'] ?? 'No disponible' }}</textarea>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-clipboard me-1"></i> Copiar
          </button>
          <button class="btn btn-dark btn-sm ms-auto">
            <i class="bi bi-arrow-up-circle me-1"></i> Subir de nivel
          </button>
        </div>
      </div>
    </div>

  </div>
</div>


<!-- Formulario editar perfil -->
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
    <h5 class="mb-0 fw-semibold">Mi perfil</h5>
    <a href="#" class="btn btn-sm btn-outline-success">Editar</a>
  </div>
  <div class="card-body">
    <form method="POST" action="">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-12 mb-3">
          <label class="form-label">CUIL</label>
          <input type="text" name="cuil" class="form-control" value="{{ $user['cuil'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Nombre</label>
          <input type="text" name="nombre" class="form-control" value="{{ $user['nombre'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Apellido paterno</label>
          <input type="text" name="apellido_paterno" class="form-control" value="{{ $user['apellido_paterno'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Fecha de nacimiento</label>
          <input type="date" name="fecha_nacimiento" class="form-control" value="{{ $user['fecha_nacimiento'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Teléfono celular</label>
          <input type="text" name="telefono_celular" class="form-control" value="{{ $user['telefono_celular'] }}">
        </div>
      </div>
      <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0 fw-semibold">Mi perfil</h5>
      </div>
      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label">Teléfono fijo</label>
          <input type="text" name="telefono_fijo" class="form-control" value="{{ $user['telefono_fijo'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Calle</label>
          <input type="text" name="calle" class="form-control" value="{{ $user['calle'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Numero exterior</label>
          <input type="text" name="numero_exterior" class="form-control" value="{{ $user['numero_exterior'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Departamento</label>
          <input type="text" name="departamento" class="form-control" value="{{ $user['departamento'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Barrio</label>
          <input type="text" name="barrio" class="form-control" value="{{ $user['barrio'] }}">
        </div>
        <div class="col-md-6 mb-3">
          <label class="form-label">Codigo postal</label>
          <input type="text" name="codigo_postal" class="form-control" value="{{ $user['codigo_postal'] }}">
        </div>
        <div class="col-md-12 mb-3">
          <label class="form-label">Referencias</label>
          <input type="text" name="referencias" class="form-control" value="{{ $user['referencias'] }}">
        </div>

      </div>

      <button type="submit" class="btn btn-outline-success w-100">Guardar cambios</button>
    </form>
  </div>
  @endsection