@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="row align-items-center">

    <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center mb-3">
      <h5 class="mb-0 fw-semibold">Mis pagos</h5>
    </div>
    <form method="GET" action="" class="mb-4">
      <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Buscar por folio" value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Buscar</button>
      </div>
    </form>

    @forelse ($pagos as $pago)
    <div class="card mb-4 shadow rounded-4 border-0">
      <div class="card-body">

        <div class="d-flex align-items-center mb-3">
          <svg xmlns="http://www.w3.org/2000/svg" height="60" viewBox="0 -960 960 960" width="60" class="svg-color">
            <path d="M240-100.001q-41.922 0-70.961-29.038-29.038-29.039-29.038-70.78v-100.18h120v-552.307l55.385 47.692 56.153-47.692 56.154 47.692 56.153-47.692L540-804.614l56.154-47.692 56.153 47.692 56.154-47.692 56.153 47.692 55.385-47.692V-200q0 41.922-29.038 70.961-29.039 29.038-70.961 29.038H240ZM720-160q17 0 28.5-11.5T760-200v-560H320v460.001h360V-200q0 17 11.5 28.5T720-160ZM367.693-610.001v-59.998h226.922v59.998H367.693Zm0 120v-59.998h226.922v59.998H367.693Zm309.999-114.615q-14.692 0-25.038-10.346T642.308-640q0-14.692 10.346-25.038t25.038-10.346q14.692 0 25.038 10.346T713.076-640q0 14.692-10.346 25.038t-25.038 10.346Zm0 120q-14.692 0-25.038-10.346T642.308-520q0-14.692 10.346-25.038t25.038-10.346q14.692 0 25.038 10.346T713.076-520q0 14.692-10.346 25.038t-25.038 10.346ZM240-160h380.001v-80H200v40q0 17 11.5 28.5T240-160Zm-40 0v-80 80Z" style="fill: var(--v-icon-base) !important;"></path>
          </svg>
          <h5 class="card-title mb-0">{{ $pago['folio_tramite'] }}</h5>
        </div>

        <div class="row">
          <div class="col-md-4 mb-2">
            <p class="mb-1"><strong>Número de folio:</strong> {{ $pago['numero_folio'] }}</p>
            <p class="mb-1"><strong>Folio de trámite:</strong> {{ $pago['folio_tramite'] }}</p>
            <p class="mb-1"><strong>Fecha:</strong> {{ \Carbon\Carbon::parse($pago['fecha'])->format('d/m/Y H:i:s') }}</p>
          </div>

          <div class="col-md-4 mb-2">
            <p class="mb-1"><strong>Número de transacción:</strong> {{ $pago['numero_transaccion'] }}</p>
            <p class="mb-1"><strong>Costo:</strong> {{ $pago['costo'] }}</p>
            <p class="mb-1"><strong>CUIT:</strong> {{ $pago['cuit'] }}</p>
            <p class="mb-1"><strong>Estado:</strong> <span class="badge bg-{{ $pago['estado'] === 'Pagado' ? 'success' : 'warning' }}">{{ $pago['estado'] }}</span></p>
          </div>

          <div class="col-md-4 mb-2 d-flex align-items-end">
            <a href="{{ route('profile.pagos.detail', $pago['id']) }}" class="btn btn-outline-custom ms-auto">Ver detalle</a>
          </div>
        </div>

      </div>
    </div>

    @empty
    <p>No se encontraron expedientes.</p>
    @endforelse

    <x-pagination :items="$pagos" />
  </div>
</div>
@endsection