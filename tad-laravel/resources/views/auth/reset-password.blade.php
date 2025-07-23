@extends('layouts.app')

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm rounded-3">
        <div class="card-header text-center text-primary fw-bold">
          Restablecer contraseña
        </div>

        <div class="card-body">
          <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

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

            <div class="mb-3">
              <label for="password" class="form-label">Nueva contraseña</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password">

              @error('password')
              <span class="invalid-feedback d-block" role="alert">
                <strong>{{ $message }}</strong>
              </span>
              @enderror
            </div>

            <div class="mb-3">
              <label for="password-confirm" class="form-label">Confirmar contraseña</label>
              <input id="password-confirm" type="password" class="form-control"
                name="password_confirmation" required autocomplete="new-password">
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                Restablecer contraseña
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection