{{-- resources/views/pages/profile/ciudadano/catalogo.blade.php --}}
@extends('layouts.profile')

@section('profile_content')

<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-bold">Catálogo de trámites</h5>
    {{-- Link general a "Mis trámites" --}}
    <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary btn-sm">Mis solicitudes</a>
  </div>

  {{-- Importante: no usar $t fuera del loop --}}
  @forelse ($plantillas as $t)
    <div class="card mb-3 border-0 shadow-sm rounded-4">
      <div class="card-body d-flex justify-content-between align-items-center">

        <div class="pe-3">
          <div class="fw-bold">{{ $t->nombre ?? 'Trámite sin nombre' }}</div>

          @if (!empty($t->descripcion))
            <div class="small text-muted mt-1">
              {{ $t->descripcion }}
            </div>
          @endif

          {{-- Metadatos opcionales --}}
          <div class="small text-muted mt-2">
            @if (!empty($t->area))
              <span class="me-2"><i class="bi bi-building"></i> {{ $t->area }}</span>
            @endif
            @if (isset($t->publicado))
              <span class="me-2">
                <i class="bi bi-circle-fill {{ ($t->publicado ?? 0) == 1 ? 'text-success' : 'text-secondary' }}"></i>
                {{ ($t->publicado ?? 0) == 1 ? 'Publicado' : 'No publicado' }}
              </span>
            @endif
          </div>
        </div>

        <div class="d-flex gap-2 text-nowrap">
          {{-- Ficha / detalles del trámite --}}
          <a href="{{ route('profile.tramites.ficha', $t->id) }}" class="btn btn-outline-primary btn-sm">
            Ver detalles
          </a>

          @php
            // Condición para habilitar el inicio online (ajustá según tu modelo)
            $puedeIniciar =
              ( (int)($t->acepta_solicitudes ?? 0) === 1 ) &&
              ( (int)($t->disponible ?? 0) === 1 ) &&
              ( (int)($t->publicado ?? 0) === 1 );
          @endphp

          @if ($puedeIniciar)
            {{-- GET: abre el formulario de inicio, NO guarda nada aún --}}
            <a href="{{ route('profile.tramites.iniciar', $t->id) }}" class="btn btn-primary btn-sm">
              Iniciar trámite
            </a>
          @else
            <button class="btn btn-outline-secondary btn-sm" disabled
              title="Este trámite aún no está habilitado para iniciar en línea.">
              No disponible
            </button>
          @endif
        </div>
      </div>
    </div>
  @empty
    <div class="alert alert-info mb-0 rounded-4">
      No hay trámites disponibles por el momento.
    </div>
  @endforelse

  {{-- Paginación opcional --}}
  @isset($plantillas)
    @if(method_exists($plantillas, 'links'))
      <div class="mt-3">
        {{ $plantillas->links() }}
      </div>
    @endif
  @endisset
</div>

@endsection
