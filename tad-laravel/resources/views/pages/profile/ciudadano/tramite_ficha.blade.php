@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0 fw-semibold">{{ $tramite->nombre }}</h5>
    <a href="{{ route('profile.catalogo') }}" class="btn btn-outline-secondary btn-sm">Volver al catálogo</a>
  </div>

  @if(!empty($tramite->descripcion))
    <p class="mb-4">{{ $tramite->descripcion }}</p>
  @endif

  {{-- Requisitos / Documentación --}}
  @if(!empty($docs))
    <div class="mb-4">
      <h6 class="fw-bold mb-2">Documentación requerida</h6>
      <ul class="mb-0">
        @foreach($docs as $doc)
          <li>{{ $doc['name'] ?? $doc['titulo'] ?? $doc['label'] ?? json_encode($doc) }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Etapas del trámite --}}
  @if(!empty($etapas))
    <div class="mb-4">
      <h6 class="fw-bold mb-2">Etapas</h6>
      <ol class="mb-0">
        @foreach($etapas as $e)
          <li>{{ $e['name'] ?? $e['titulo'] ?? 'Etapa' }}</li>
        @endforeach
      </ol>
    </div>
  @endif

  {{-- Extras del config_json si existen --}}
  @if(!empty($config))
    <div class="row g-3 mb-4">
      @if(!empty($config['plazo']))       <div class="col-md-4"><div class="small text-muted">Plazo</div><div class="fw-semibold">{{ $config['plazo'] }}</div></div>@endif
      @if(!empty($config['costo']))       <div class="col-md-4"><div class="small text-muted">Costo</div><div class="fw-semibold">{{ $config['costo'] }}</div></div>@endif
      @if(!empty($config['modalidad']))   <div class="col-md-4"><div class="small text-muted">Modalidad</div><div class="fw-semibold">{{ $config['modalidad'] }}</div></div>@endif
    </div>
  @endif

    {{-- Botón Iniciar --}}
  <div class="d-flex justify-content-end">
    @if($habilitado)
      <a href="{{ route('profile.tramites.iniciar', $tramite->id) }}" class="btn btn-primary">
        Iniciar trámite
      </a>
    @else
      <button class="btn btn-outline-secondary" disabled>No disponible para iniciar</button>
    @endif
  </div>
</div>
@endsection
