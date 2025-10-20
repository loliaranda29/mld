@extends('layouts.app-funcionario')

@section('title', 'Ubicación del mapa')

@section('profile_content')
<div class="container-fluid mt-3">

  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item"><a href="{{ route('configuracion.index') }}">Configuración</a></li>
      <li class="breadcrumb-item active">Ubicación del mapa</li>
    </ol>
  </nav>

  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h5 class="mb-0">Ubicación del mapa</h5>
      <button form="form-mapa" class="btn btn-primary">Guardar cambios</button>
    </div>

    <div class="card-body">
      <form id="form-mapa" action="{{ route('configuracion.mapa.guardar') }}" method="POST">
        @csrf

        <div class="mb-3">
          <label class="form-label">Título <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control" required
                 value="{{ old('title', $mapa['title'] ?? '') }}"
                 placeholder="Elige el centro del mapa para la plataforma">
        </div>

        <div class="mb-3">
          <label class="form-label">Busca tu dirección para ubicarla en el mapa:</label>
          <input id="map-search" type="text" class="form-control" placeholder="Buscar dirección">
        </div>

        {{-- Hidden values a guardar --}}
        <input type="hidden" id="lat"  name="lat"  value="{{ old('lat',  $mapa['lat']) }}">
        <input type="hidden" id="lng"  name="lng"  value="{{ old('lng',  $mapa['lng']) }}">
        <input type="hidden" id="zoom" name="zoom" value="{{ old('zoom', $mapa['zoom']) }}">

        <div id="map" style="width:100%;height:520px;border-radius:8px;overflow:hidden;"></div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
  @if($gmaps_key)
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $gmaps_key }}&libraries=places&callback=initAdminMap"></script>
  @else
    <script>console.warn('Falta GOOGLE MAPS KEY (services.google.maps_key).');</script>
  @endif

  <script>
    function initAdminMap() {
      const lat = parseFloat(document.getElementById('lat').value) || -33.0160;
      const lng = parseFloat(document.getElementById('lng').value) || -68.8750;
      const zoom = parseInt(document.getElementById('zoom').value) || 11;

      const center = { lat: lat, lng: lng };
      const map = new google.maps.Map(document.getElementById('map'), {
        center, zoom, mapTypeControl: true, streetViewControl: true, fullscreenControl: true
      });

      const marker = new google.maps.Marker({
        position: center, map, draggable: true
      });

      // Sincroniza hidden fields
      const syncFields = (pos) => {
        document.getElementById('lat').value = pos.lat();
        document.getElementById('lng').value = pos.lng();
        document.getElementById('zoom').value = map.getZoom();
      };

      marker.addListener('dragend', (e) => {
        syncFields(marker.getPosition());
      });

      map.addListener('zoom_changed', () => {
        document.getElementById('zoom').value = map.getZoom();
      });

      // Autocomplete de Places
      const input = document.getElementById('map-search');
      const autocomplete = new google.maps.places.Autocomplete(input, { fields: ['geometry'] });
      autocomplete.addListener('place_changed', () => {
        const place = autocomplete.getPlace();
        if (!place.geometry || !place.geometry.location) return;
        map.panTo(place.geometry.location);
        map.setZoom(14);
        marker.setPosition(place.geometry.location);
        syncFields(place.geometry.location);
      });
    }
    // Si el script de Maps no carga (sin key), evita error
    window.initAdminMap = window.initAdminMap || function(){};
  </script>
@endpush
@endsection
