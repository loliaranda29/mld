@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0 fw-semibold">
      {{ $solicitud->tramite->nombre ?? 'Trámite' }}
    </h5>
    <a href="{{ route('profile.tramites') }}" class="btn btn-outline-secondary btn-sm">Volver</a>
  </div>

 <form method="POST" action="{{ route('profile.solicitudes.update', $solicitud->id) }}">
  @csrf
  @method('PUT')

  @foreach(($solicitud->datos['sections'] ?? []) as $si => $sec)
    <div class="mb-4 card card-body">
      <div class="fw-bold mb-2">{{ $sec['name'] ?? 'Sección' }}</div>

      @foreach(($sec['fields'] ?? []) as $fi => $field)
        @php
          $type  = $field['type']  ?? 'text';
          $label = $field['label'] ?? ($field['name'] ?? 'Campo');
          $name  = "sections.$si.fields.$fi.value";
          $value = $field['value'] ?? '';
          $opts  = $field['options'] ?? [];
        @endphp

        <div class="mb-3">
          <label class="form-label">{{ $label }}</label>

          @if($type === 'textarea')
            <textarea name="{{ $name }}" class="form-control">{{ old($name, $value) }}</textarea>

          @elseif($type === 'select')
            <select name="{{ $name }}" class="form-select">
              @foreach($opts as $opt)
                @php
                  $optVal = is_array($opt) ? ($opt['value'] ?? $opt['label'] ?? '') : $opt;
                  $optLbl = is_array($opt) ? ($opt['label'] ?? $opt['value'] ?? '') : $opt;
                @endphp
                <option value="{{ $optVal }}" @selected(old($name, $value) == $optVal)>{{ $optLbl }}</option>
              @endforeach
            </select>

          @elseif(in_array($type, ['checkbox','radio']))
            <input type="{{ $type }}" name="{{ $name }}" class="form-check-input" value="1"
                   @checked(old($name, $value))>

          @else
            <input type="{{ $type }}" name="{{ $name }}" class="form-control" value="{{ old($name, $value) }}">
          @endif

          @if(!empty($field['help']))
            <div class="form-text">{{ $field['help'] }}</div>
          @endif
        </div>
      @endforeach
    </div>
  @endforeach

  <button type="submit" class="btn btn-primary">Guardar</button>
</form>

</div>
@endsection
