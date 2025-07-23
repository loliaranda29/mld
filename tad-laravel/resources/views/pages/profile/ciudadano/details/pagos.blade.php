@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">

  {{-- Acciones --}}
  <div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ url()->previous() }}" class="btn btn-sm btn-outline-secondary">
      <i class="mdi mdi-keyboard-return"></i> Volver
    </a>
    <button onclick="window.print()" class="btn btn-outline-custom btn-sm">
      <i class="mdi mdi-printer"></i> Imprimir
    </button>
  </div>

  {{-- Encabezado --}}
  <div class="row mb-4 text-center">
    {{-- Columna: Logo --}}
    <div class="col-md-6 mb-3">
      <img src="{{ asset('assets/img/logo-lujan.png') }}" alt="Logo" width="100">
    </div>

    {{-- Columna: Folio --}}
    <div class="col-md-6">
      <h5 class="fw-bold">Folio del comprobante</h5>
      <div class="bg-primary text-white py-2 px-3 rounded d-inline-block">
        {{ $pago['numero_folio'] }}
      </div>
    </div>
  </div>


  {{-- Información principal --}}
  <div class="mb-4">
    <h5 class="fw-bold mb-3">Información del pago</h5>
    <div class="row row-cols-1 row-cols-md-2 g-3">

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Trámite</p>
          <h6 class="mb-0">{{ $pago['tramite'] }}</h6>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Folio de trámite</p>
          <h6 class="mb-0">{{ $pago['folio_tramite'] }}</h6>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Estado</p>
          <span class="badge bg-success text-uppercase">{{ $pago['estado'] }}</span>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Número de transacción</p>
          <h6 class="mb-0">{{ $pago['numero_transaccion'] }}</h6>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Fecha de pago</p>
          <h6 class="mb-0">{{ \Carbon\Carbon::parse($pago['fecha'])->format('d/m/Y H:i:s') }}</h6>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">CUIT</p>
          <h6 class="mb-0">{{ $pago['cuit'] }}</h6>
        </div>
      </div>

      <div class="col">
        <div class="border rounded p-3 h-100 bg-light">
          <p class="mb-1 text-muted fw-semibold">Padrón / Nomenclatura</p>
          <h6 class="mb-0">{{ $pago['padron_nomenclatura'] }}</h6>
        </div>
      </div>

    </div>
  </div>


  <h5 class="fw-bold mb-3">Detalle del pago</h5>

  <div class="table-responsive">
    <table class="table table-hover align-middle shadow-sm rounded overflow-hidden border">
      <thead class="table-light">
        <tr>
          <th scope="col">Concepto</th>
          <th scope="col">Monto</th>
          <th scope="col">Total</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td class="fw-medium">{{ $pago['folio_tramite'] }}</td>
          <td>1.00 UTS</td>
          <td>${{ number_format($pago['costo'], 2) }}</td>
        </tr>
        <tr class="table-primary">
          <td><strong>Total a pagar</strong></td>
          <td></td>
          <td>
            <strong>${{ number_format($pago['costo'], 2) }}</strong><br>
            <small class="text-muted fst-italic">
              ({{ strtoupper(numfmt_format(numfmt_create('es_AR', NumberFormatter::SPELLOUT), $pago['costo'])) }} PESOS {{ intval($pago['costo'] * 100) % 100 }}/100 M.N.)
            </small>
          </td>
        </tr>
      </tbody>
    </table>
  </div>


</div>
@endsection