@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-4 mb-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div>
      <div class="text-muted small">Folio/Prefolio del Expediente</div>
      <h5 class="mb-0 fw-semibold">{{ $solicitud->expediente }}</h5>
      <div class="mt-2">
        <div class="small text-muted">Trámite</div>
        <div class="fw-semibold">{{ $solicitud->tramite->nombre ?? '—' }}</div>
      </div>
      <div class="mt-2">
        <span class="badge bg-secondary">{{ $solicitud->estado }}</span>
      </div>
    </div>

    <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
  </div>

  {{-- IMPORTANTE: enctype para campos tipo "file" --}}
  <form method="POST"
        action="{{ route('profile.solicitudes.update', $solicitud->id) }}"
        enctype="multipart/form-data">
    @csrf
    @method('PUT')

    @php
      $schema = $schema ?? ($solicitud->datos ?? ['sections' => []]);
    @endphp

    @forelse(($schema['sections'] ?? []) as $si => $sec)
      <div class="card border-0 mb-3 shadow-sm rounded-4">
        <div class="card-header bg-white fw-semibold">
          {{ $sec['name'] ?? ('Sección '.($si+1)) }}
        </div>
        <div class="card-body">

          @foreach(($sec['fields'] ?? []) as $fi => $f)
            @php
              $type     = $f['type'] ?? 'text';
              $label    = $f['label'] ?? ($f['name'] ?? 'Campo');
              $help     = $f['help']  ?? '';
              $required = !empty($f['required']);
              $oldKey   = "sections.$si.fields.$fi.value";
              $value    = old($oldKey, $f['value'] ?? null);
            @endphp

            <div class="mb-3">
              {{-- Label (salvo checkboxes que llevan label al lado) --}}
              @if($type !== 'checkbox')
                <label class="form-label">
                  {{ $label }} @if($required) <span class="text-danger">*</span> @endif
                </label>
              @endif

              @switch($type)
                @case('textarea')
                  <textarea
                    class="form-control"
                    name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                    rows="4"
                    {{ $required ? 'required' : '' }}
                  >{{ $value }}</textarea>
                  @break

                @case('select')
                  <select
                    class="form-select"
                    name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                    {{ $required ? 'required' : '' }}
                  >
                    <option value="">-- Seleccionar --</option>
                    @foreach(($f['options'] ?? []) as $opt)
                      @php
                        $optVal = is_array($opt) ? ($opt['value'] ?? $opt['label'] ?? reset($opt)) : $opt;
                        $optLbl = is_array($opt) ? ($opt['label'] ?? $optVal) : $opt;
                      @endphp
                      <option value="{{ $optVal }}" {{ (string)$value === (string)$optVal ? 'selected' : '' }}>
                        {{ $optLbl }}
                      </option>
                    @endforeach
                  </select>
                  @break

                @case('checkbox')
                  <div class="form-check">
                    {{-- hidden para forzar "0" cuando no se tilde --}}
                    <input type="hidden" name="sections[{{ $si }}][fields][{{ $fi }}][value]" value="0">
                    <input
                      type="checkbox"
                      class="form-check-input"
                      id="f{{$si}}_{{$fi}}"
                      name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                      value="1"
                      {{ (int)$value === 1 ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="f{{$si}}_{{$fi}}">
                      {{ $label }}
                      @if($required) <span class="text-danger">*</span> @endif
                    </label>
                  </div>
                  @break

                @case('date')
                  <input
                    type="date"
                    class="form-control"
                    name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                    value="{{ $value }}"
                    {{ $required ? 'required' : '' }}
                  >
                  @break

                @case('file')
                  <input
                    type="file"
                    class="form-control"
                    name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                    {{ $required ? 'required' : '' }}
                  >
                  @break

                @default
                  <input
                    type="text"
                    class="form-control"
                    name="sections[{{ $si }}][fields][{{ $fi }}][value]"
                    value="{{ $value }}"
                    {{ $required ? 'required' : '' }}
                  >
              @endswitch

              @if($help)
                <div class="form-text">{{ $help }}</div>
              @endif
            </div>
          @endforeach

        </div>
      </div>
    @empty
      <div class="alert alert-info">
        Este trámite aún no tiene campos configurados para completar.
      </div>
    @endforelse

    <div class="d-flex gap-2">
      <button class="btn btn-primary">Guardar</button>
      <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
