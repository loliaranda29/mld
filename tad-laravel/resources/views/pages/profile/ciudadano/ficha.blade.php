@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">{{ $t->nombre }}</h5>
    <a href="{{ route('profile.catalogo') }}" class="btn btn-outline-secondary btn-sm">Volver al catálogo</a>
  </div>

  {{-- Descripción corta --}}
  @if(!empty($t->descripcion))
    <p class="text-muted mb-3">{{ $t->descripcion }}</p>
  @endif

  {{-- Meta / resumen --}}
  <div class="row g-3 mb-3">
    <div class="col-md-3">
      <div class="small text-muted">Modalidad</div>
      <div class="fw-semibold">
        @php
          $map = [
            'online' => 'En línea',
            'presencial' => 'Presencial',
            'presencial/online' => 'Presencial/Online',
            'mixta' => 'Mixta',
          ];
        @endphp
        {{ $map[strtolower((string)$modalidad)] ?? ($modalidad ?? '—') }}
      </div>
    </div>
    <div class="col-md-3">
      <div class="small text-muted">Costo</div>
      <div class="fw-semibold">
        {{ $implicaCosto ? 'Con costo' : 'Sin costo' }}
      </div>
    </div>
    @if($telefono)
      <div class="col-md-3">
        <div class="small text-muted">Teléfono de oficina</div>
        <div class="fw-semibold">{{ $telefono }}</div>
      </div>
    @endif
    @if($horario)
      <div class="col-md-3">
        <div class="small text-muted">Horario de atención</div>
        <div class="fw-semibold">{{ $horario }}</div>
      </div>
    @endif
  </div>

  {{-- Requisitos / Documentación --}}
  @php
    // Intentar detectar nombre/label y si es obligatorio
    $docName = function ($d) {
        return $d['nombre'] ?? $d['label'] ?? $d['name'] ?? null;
    };
    $docReq = function ($d) {
        $v = $d['requerido'] ?? $d['obligatorio'] ?? $d['required'] ?? false;
        return filter_var($v, FILTER_VALIDATE_BOOLEAN) || (string)$v === '1';
    };
  @endphp

  @if(!empty($docs))
    <div class="mb-3">
      <div class="fw-semibold mb-2">Requisitos</div>
      <ul class="mb-0">
        @foreach($docs as $d)
          @php $nombreDoc = $docName($d); @endphp
          @if($nombreDoc)
            <li>
              {{ $nombreDoc }}
              @if($docReq($d)) <span class="text-danger small">(obligatorio)</span> @endif
            </li>
          @endif
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Paso a paso / Tutorial (HTML enriquecido desde admin) --}}
  @if(!empty($tutorial))
    <div class="mb-3">
      <div class="fw-semibold mb-2">Paso a paso</div>
      <div class="prose">{!! $tutorial !!}</div>
    </div>
  @endif

  {{-- Detalle de costo (HTML) --}}
  @if(!empty($detalleCosto))
    <div class="mb-3">
      <div class="fw-semibold mb-2">Detalle de costo</div>
      <div class="prose">{!! $detalleCosto !!}</div>
    </div>
  @endif

  {{-- Etapas (opcional, como referencia) --}}
  @if(!empty($etapas))
    <div class="mb-4">
      <div class="fw-semibold mb-2">Etapas</div>
      <ol class="mb-0">
        @foreach($etapas as $e)
          <li>{{ $e['nombre'] ?? $e['titulo'] ?? $e['name'] ?? 'Etapa' }}</li>
        @endforeach
      </ol>
    </div>
  @endif

  {{-- Botón Iniciar --}}
  <div class="text-end">
    @if($puedeIniciar)
      <form method="POST" action="{{ route('profile.tramites.iniciar', $t->id) }}" class="d-inline">
        @csrf
        <button class="btn btn-primary btn-sm">Iniciar trámite</button>
      </form>
    @else
      <button class="btn btn-outline-secondary btn-sm" disabled
              title="Este trámite aún no está habilitado para iniciar en línea.">
        No disponible para iniciar
      </button>
    @endif
  </div>
</div>
@endsection
