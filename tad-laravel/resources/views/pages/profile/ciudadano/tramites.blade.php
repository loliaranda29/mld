@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="row align-items-center">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-semibold">Mis servicios digitales</h5>
    </div>
    <form method="GET" action="" class="mb-4">
      <div class="input-group">
        <input type="text" name="buscar" class="form-control" placeholder="Buscar por expediente" value="{{ request('buscar') }}">
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
      </div>
    </form>

    @forelse ($tramites as $tramite)
    <div class="card mb-4 shadow rounded-4 border-0">
      <div class="card-body">

        <div class="d-flex align-items-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 39.234 47.908" fill="var(--v-icon-base)" class="me-2 svg-color">
            <g id="Grupo_12312" transform="translate(362.276 12265)">
              <path d="M23.775,13.957H6.9a1.546,1.546,0,1,0,0,3.091H23.775a1.546,1.546,0,1,0,0-3.091" transform="translate(-361.202 -12262.202)" />
              <path d="M23.775,17.748H6.9a1.546,1.546,0,1,0,0,3.092H23.775a1.546,1.546,0,1,0,0-3.092" transform="translate(-361.202 -12261.441)" />
              <path d="M23.775,21.516H6.9a1.546,1.546,0,1,0,0,3.091H23.775a1.546,1.546,0,1,0,0-3.091" transform="translate(-361.202 -12260.687)" />
              <path d="M23.775,25.284H6.9a1.546,1.546,0,1,0,0,3.092H23.775a1.546,1.546,0,1,0,0-3.092" transform="translate(-361.202 -12259.931)" />
              <path d="M23.666,6.358H7.011A1.657,1.657,0,0,0,5.356,8.013v3.753A1.656,1.656,0,0,0,7.011,13.42H23.666a1.656,1.656,0,0,0,1.655-1.654V8.013a1.657,1.657,0,0,0-1.655-1.655M22.38,9.766v.241a.359.359,0,0,1-.359.359H8.721a.359.359,0,0,1-.359-.359V9.766a.359.359,0,0,1,.359-.359h13.3a.359.359,0,0,1,.359.359" transform="translate(-361.202 -12263.725)" />
              <path d="M37.645,23.625a1.591,1.591,0,0,0,1.588-1.588V9.415a4.215,4.215,0,0,0-4.211-4.21H32.865v-1A4.214,4.214,0,0,0,28.656,0H4.21A4.214,4.214,0,0,0,0,4.21V38.493A4.214,4.214,0,0,0,4.21,42.7H6.367v1a4.214,4.214,0,0,0,4.21,4.21H35.022a4.215,4.215,0,0,0,4.211-4.21V30.8a1.589,1.589,0,0,0-3.178,0V43.7a1.033,1.033,0,0,1-1.034,1.032H10.577A1.032,1.032,0,0,1,9.545,43.7v-1H28.656a4.214,4.214,0,0,0,4.209-4.21V8.383h2.157a1.034,1.034,0,0,1,1.034,1.032V22.037a1.59,1.59,0,0,0,1.589,1.588M29.689,4.21V38.493a1.034,1.034,0,0,1-1.032,1.032H4.21a1.034,1.034,0,0,1-1.032-1.032V4.21A1.033,1.033,0,0,1,4.21,3.178H28.656A1.033,1.033,0,0,1,29.689,4.21" transform="translate(-362.276 -12265)" />
            </g>
          </svg>
          <h5 class="card-title mb-0">{{ $tramite['titulo'] }}</h5>
        </div>

        <div class="row">
          <div class="col-md-4 mb-2">
            <p class="mb-1"><strong>Expediente:</strong> {{ $tramite['numero'] }}</p>
            <p class="mb-1"><strong>Fecha de emisión:</strong> {{ \Carbon\Carbon::parse($tramite['fecha_emision'])->format('Y/m/d') }}</p>
          </div>

          <div class="col-md-4 mb-2">
            <p class="mb-1"><strong>Tipo:</strong> {{ $tramite['tipo'] }}</p>
            <p class="mb-1"><strong>Estatus:</strong> <span class="badge bg-danger">{{ $tramite['estatus'] }}</span></p>
            <p class="mb-1"><strong>Etapas:</strong> ({{ $tramite['etapa_actual'] }} / {{ $tramite['etapas_totales'] }})</p>
          </div>

          <div class="col-md-4 mb-2 d-flex align-items-end">
            <a href="{{ route('profile.tramites.detail', ['id' => $tramite['id']]) }}"
              class="btn btn-sm btn btn-outline-custom ms-auto">Ver detalle</a>
          </div>
        </div>

      </div>
    </div>



    @empty
    <p>No se encontraron expedientes.</p>
    @endforelse
    <x-pagination :items="$tramites" />
  </div>
</div>
@endsection