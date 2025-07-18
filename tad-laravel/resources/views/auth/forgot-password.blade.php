@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm rounded-3">
        <div class="card-header text-center text-primary fw-bold">
          Recuperar contraseña
        </div>

        <div class="card-body">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Correo electrónico</label>
              <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

              @error('email')
              <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                Enviar enlace de recuperación
              </button>
            </div>
          </form>

          <div class="mt-3 text-center">
            <a href="{{ route('login') }}">Volver al inicio de sesión</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection