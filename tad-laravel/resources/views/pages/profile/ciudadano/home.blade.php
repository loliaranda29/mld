@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="row align-items-center">

    <!-- Columna izquierda: Avatar y nombre -->
    <div class="col-md-5 text-center mb-4 mb-md-0">
      <div class="d-flex flex-column align-items-center">
        <!-- Avatar -->
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 96px; height: 96px;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 452.64" fill="#FFF" width="53px" height="53px">
            <path d="M358.27,294.16c-6.08-23.69...Z"></path>
            <path d="M193.28,218.56c54.7,0,98.71-44.14...Z"></path>
          </svg>
        </div>

        <!-- Nombre -->
        <h5 class="fw-bold mb-2">{{ $user['nombre'] ?? 'Nombre Apellido' }}</h5>

        <!-- Email -->
        <div class="mb-3 w-100 px-5">
          <label class="form-label text-muted">Dirección de correo electrónico</label>
          <input type="text" class="form-control" disabled value="{{ $user['email'] ?? 'usuario@example.com' }}">
        </div>

        <!-- Acciones -->
        <div class="d-flex flex-column gap-2 w-100 px-5">
          <a href="#" class="btn btn-outline-secondary btn-sm">Cambiar contraseña</a>
          <a href="#" class="btn btn-outline-secondary btn-sm">Cambiar correo electrónico</a>
          <a href="#" class="btn btn-primary btn-sm">Cerrar sesión</a>
        </div>
      </div>
    </div>

    <!-- Separador vertical -->
    <div class="col-md-1 d-none d-md-flex justify-content-center">
      <div class="vr" style="height: 100%; width: 1px; background-color: #dee2e6;"></div>
    </div>

    <!-- Columna derecha: Identidad digital -->
    <div class="col-md-6">
      <h6 class="fw-semibold">Identidad digital</h6>
      <p><strong>Conectado a:</strong> Luján de Cuyo</p>
      <p><strong>Wallet:</strong> {{ $user['wallet'] ?? 'No conectada' }}</p>
      <p><strong>Llave pública:</strong></p>
      <textarea class="form-control mb-2" rows="2" disabled>{{ $user['llave_publica'] ?? 'No disponible' }}</textarea>
      <button class="btn btn-sm btn-link"><i class="bi bi-clipboard"></i> Copiar</button>
      <button class="btn btn-dark w-100 mt-3"><i class="bi bi-arrow-up-circle me-2"></i> Subir de nivel</button>
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