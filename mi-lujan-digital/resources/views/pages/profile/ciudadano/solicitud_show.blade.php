@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-1">{{ $solicitud->tramite->nombre }}</h3>
  <div class="text-muted mb-3">
    Expediente: {{ $solicitud->folio ?? ('TRAM-'.$solicitud->id) }} — Estado: {{ $solicitud->estado }}
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header">Resumen de tu solicitud</div>
    <div class="card-body">
      @php $ans = $solicitud->respuestas_json ?? []; @endphp

      @if(empty($ans))
        <p class="text-muted">No hay respuestas registradas.</p>
      @else
        <dl class="row">
          @foreach($ans as $campo => $valor)
            <dt class="col-sm-4">{{ str_replace('_',' ',ucfirst($campo)) }}</dt>
            <dd class="col-sm-8">
              @if(is_array($valor))
                {{ implode(', ', $valor) }}
              @else
                {{ $valor }}
              @endif
            </dd>
          @endforeach
        </dl>
      @endif
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <a href="{{ route('profile.tramites') }}" class="btn btn-light">Mis trámites</a>
    {{-- Si querés permitir edición posterior, apunta a una vista de edición específica --}}
    {{-- <a href="{{ route('profile.solicitudes.edit', $solicitud->id) }}" class="btn btn-primary">Editar</a> --}}
  </div>
</div>
@endsection
