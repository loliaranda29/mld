@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="row align-items-center">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-semibold">Inspecciones</h5>
    </div>

    <form method="GET" action="" class="mb-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Buscar por nombre" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
      </div>
    </form>

    @forelse ($inspecciones as $inspeccion)
    <div class="card mb-4 shadow rounded-4 border-0">
      <div class="card-body">

        <div class="d-flex align-items-center mb-3">
          <svg data-v-36f4229b="" id="Grupo_7954" data-name="Grupo 7954" xmlns="http://www.w3.org/2000/svg" width="40px" height="40px" viewBox="0 0 24.06 26.975" fill="var(--v-icon-base)" class="svg-color">
            <g id="Grupo_7955" data-name="Grupo 7955" transform="translate(0 0)">
              <path id="Trazado_6762" data-name="Trazado 6762" d="M0,6.225c.051-.212.1-.426.155-.636A3.848,3.848,0,0,1,3.908,2.7c.415-.006.83,0,1.29,0,0-.451-.006-.878,0-1.305a1.27,1.27,0,0,1,.821-1.3A1.29,1.29,0,0,1,7.8,1.391c0,.42,0,.84,0,1.286h7.8c0-.425,0-.853,0-1.281A1.3,1.3,0,0,1,17.415.1a1.282,1.282,0,0,1,.8,1.292c.01.41.008.821.012,1.231a.532.532,0,0,0,.028.079c.341,0,.7-.026,1.063.006a6.871,6.871,0,0,1,1.439.21A3.831,3.831,0,0,1,23.4,6.547c.028,2.242.015,4.485,0,6.727a1.291,1.291,0,0,1-2.575.14,12.4,12.4,0,0,1-.007-1.244H2.62c0,.1-.014.213-.014.324q0,5.148,0,10.3c0,1.01.463,1.489,1.439,1.49,2.113,0,4.226-.005,6.338,0a1.3,1.3,0,0,1,1.264,1.758,1.177,1.177,0,0,1-1.018.908,3.821,3.821,0,0,1-.426.015c-2.042,0-4.084-.032-6.125.009A3.891,3.891,0,0,1,.045,23.554c0-.028-.029-.053-.045-.079V6.225m15.607-.8H7.8c0,.442,0,.862,0,1.282A1.267,1.267,0,0,1,6.512,8.1,1.27,1.27,0,0,1,5.2,6.727c-.009-.337,0-.674,0-1.01,0-.1-.011-.2-.018-.313-.438,0-.843,0-1.248,0a1.238,1.238,0,0,0-1.322,1.35c-.01.726,0,1.452,0,2.179q0,.246,0,.492H20.794c0-.968.018-1.915-.007-2.861A1.12,1.12,0,0,0,19.78,5.439a14.243,14.243,0,0,0-1.57-.012c0,.464.017.916,0,1.365a1.3,1.3,0,1,1-2.6-.054c-.005-.43,0-.861,0-1.312" transform="translate(0 0)"></path>
              <path id="Trazado_6763" data-name="Trazado 6763" d="M216.794,283.082l5.354-5.553c.093-.1.185-.2.283-.288a1.215,1.215,0,0,1,1.758.019,1.329,1.329,0,0,1,.07,1.845c-.236.275-.5.526-.749.787-1.889,1.958-3.794,3.9-5.659,5.883a1.341,1.341,0,0,1-2.1.008c-.793-.888-1.652-1.712-2.467-2.579a1.343,1.343,0,0,1,.64-2.25,1.215,1.215,0,0,1,1.216.407c.546.565,1.088,1.133,1.651,1.721" transform="translate(-200.539 -259.34)"></path>
            </g>
          </svg>
          <h5 class="card-title mb-0">{{ $inspeccion['folio_inspeccion'] }}</h5>
        </div>

        <div class="row">
          <div class="col-md-6 mb-2">
            <p class="mb-1"><strong>Folio:</strong> {{ $inspeccion['folio_inspeccion'] }}</p>
            <p class="mb-1"><strong>Inspector:</strong> {{$inspeccion['inspector']['nombre']}} {{$inspeccion['inspector']['apellido']}}</p>
            <p class="mb-1"><strong>Fecha de la inspección:</strong> {{$inspeccion['fecha_inspeccion']}}</p>
          </div>

          <div class="col-md-6 mb-2 d-flex align-items-center">
            <p class="mb-1 mb-md-0"><strong>Estado:</strong>
              @if(strtolower($inspeccion['estado']) === 'aprobado')
              <span class="badge bg-success">{{ $inspeccion['estado'] }}</span>
              @elseif(strtolower($inspeccion['estado']) === 'pendiente')
              <span class="badge bg-warning text-dark">{{ $inspeccion['estado'] }}</span>
              @elseif(strtolower($inspeccion['estado']) === 'rechazado')
              <span class="badge bg-danger">{{ $inspeccion['estado'] }}</span>
              @else
              <span class="badge bg-secondary">{{ $inspeccion['estado'] }}</span>
              @endif
            </p>
            <a href="{{ route('profile.inspecciones.detail', $inspeccion['id']) }}" class="btn btn-outline-custom ms-auto">Ver detalle</a>
          </div>
        </div>

      </div>
    </div>

    @empty
    <p>No se encontraron expedientes.</p>
    @endforelse

    <x-pagination :items="$inspecciones" />

  </div>
</div>
@endsection