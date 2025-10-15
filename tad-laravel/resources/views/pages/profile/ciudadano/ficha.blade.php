@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">{{ $tramite->nombre }}</h5>
    <a href="{{ route('profile.catalogo') }}" class="btn btn-outline-secondary btn-sm">Volver al catálogo</a>
  </div>

  @if(!empty($tramite->descripcion))
    <p class="mb-4">{{ $tramite->descripcion }}</p>
  @endif

  <div class="row g-4">
    <div class="col-md-6">
      <h6 class="fw-semibold mb-2">Información general</h6>
      <ul class="list-unstyled small">
        @if(!empty($config['modalidad'])) <li><b>Modalidad:</b> {{ $config['modalidad'] }}</li>@endif
        <li><b>Costo:</b> {{ !empty($config['implica_costo']) ? 'Con costo' : 'Sin costo' }}</li>
        @if(!empty($config['telefono'])) <li><b>Teléfono:</b> {{ $config['telefono'] }}</li>@endif
        @if(!empty($config['horario']))  <li><b>Horario:</b> {{ $config['horario'] }}</li>@endif
      </ul>
      @if(!empty($config['tutorial_html']))
        <div class="mt-3">{!! $config['tutorial_html'] !!}</div>
      @endif>
      @if(!empty($config['detalle_costo_html']))
        <div class="mt-3">{!! $config['detalle_costo_html'] !!}</div>
      @endif
    </div>

    <div class="col-md-6">
      @if(!empty($requisitos) && $requisitos->count())
        <h6 class="fw-semibold mb-2">Requisitos</h6>
        <ul class="small">
          @foreach($requisitos as $r) <li>{{ $r }}</li> @endforeach
        </ul>
      @endif

      @if(!empty($etapas))
        <h6 class="fw-semibold mb-2">Paso a paso</h6>
        <ol class="small">
          @foreach($etapas as $e) <li>{{ $e['name'] ?? $e['nombre'] ?? 'Etapa' }}</li> @endforeach
        </ol>
      @endif
    </div>
  </div>

  <div class="mt-4">
    @if($puedeIniciar)
      <form method="GET" action="{{ route('profile.tramites.iniciar', $tramite->id) }}">
    <button type="submit" class="btn btn-primary">Iniciar trámite</button>
</form>
    @else
      <button class="btn btn-outline-secondary btn-sm" disabled>Este trámite todavía no está habilitado</button>
    @endif
  </div>
</div>
@endsection
