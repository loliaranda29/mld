@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5">
  <h5 class="fw-semibold mb-1">{{ $solicitud->tramite->nombre }}</h5>
  <div class="text-muted small mb-4">Expediente: {{ $solicitud->expediente }} — Estado: {{ ucfirst($solicitud->estado) }}</div>

  <form method="POST" action="{{ route('solicitudes.update', $solicitud->id) }}">
    @csrf @method('PUT')

    @forelse(($schema['sections'] ?? []) as $si => $sec)
      <h6 class="mt-4">{{ $sec['name'] ?? 'Sección '.($si+1) }}</h6>

      @foreach(($sec['fields'] ?? []) as $fi => $f)
        @php
          $name    = $f['name'] ?? "s{$si}_f{$fi}";
          $label   = $f['label'] ?? $name;
          $type    = $f['type']  ?? 'text';
          $value   = $f['value'] ?? null;
          $opts    = $f['options'] ?? [];
        @endphp

        <div class="mb-3">
          <label class="form-label">{{ $label }}</label>

          @if($type === 'textarea')
            <textarea class="form-control" name="form[{{ $name }}]">{{ old("form.$name", $value) }}</textarea>

          @elseif($type === 'select')
            <select class="form-select" name="form[{{ $name }}]">
              <option value="">— Seleccionar —</option>
              @foreach($opts as $opt)
                @php
                  $ov = is_array($opt) ? ($opt['value'] ?? $opt['label'] ?? $opt) : $opt;
                  $ol = is_array($opt) ? ($opt['label'] ?? $ov) : $opt;
                @endphp
                <option value="{{ $ov }}" @selected(old("form.$name",$value)==$ov)>{{ $ol }}</option>
              @endforeach
            </select>

          @else
            <input type="text" class="form-control" name="form[{{ $name }}]" value="{{ old("form.$name", $value) }}">
          @endif

          @if(!empty($f['help'])) <div class="form-text">{{ $f['help'] }}</div> @endif
        </div>
      @endforeach
    @empty
      <div class="alert alert-info">Este formulario no tiene campos configurados.</div>
    @endforelse

    <div class="d-flex gap-2 mt-3">
      <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary">Volver</a>
      <button class="btn btn-primary">Guardar</button>
    </div>
  </form>
</div>
@endsection
