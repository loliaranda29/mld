{{-- resources/views/pages/profile/ciudadano/solicitud_show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h4 class="mb-3">{{ $solicitud->tramite->nombre }}</h4>
  <div class="text-muted mb-3">
    Expediente: <strong>{{ $solicitud->expediente }}</strong>
    — Estado: <span class="badge bg-secondary text-uppercase">{{ $solicitud->estado }}</span>
  </div>

  @php
    // Fallback al formato viejo (si existía)
    $answersLegacy = is_array($solicitud->respuestas_json ?? null) ? $solicitud->respuestas_json : [];
    $sections = is_array($schema['sections'] ?? null) ? $schema['sections'] : [];
    $printDash = fn($v) => isset($v) && $v !== '' ? $v : '—';

    $fieldValue = function ($field) use ($answersLegacy) {
        // 1) nuevo: value dentro de cada field
        if (array_key_exists('value', $field)) {
            return $field['value'];
        }
        // 2) legacy: respuestas_json[name]
        $name = $field['name'] ?? null;
        if ($name && array_key_exists($name, $answersLegacy)) {
            return $answersLegacy[$name];
        }
        return null;
    };
  @endphp

  @forelse($sections as $sec)
    <div class="card mb-3">
      <div class="card-header bg-primary text-white fw-semibold">
        {{ $sec['name'] ?? 'Sección' }}
      </div>
      <div class="card-body">
        @forelse(($sec['fields'] ?? []) as $f)
          @php
            $label = $f['label'] ?? $f['name'] ?? 'Campo';
            $type  = strtolower($f['type'] ?? 'text');
            $val   = $fieldValue($f);
          @endphp

          <div class="mb-3">
            <div class="text-muted small">{{ $label }}</div>

            @if($type === 'file')
              @php
                $files = is_array($val) ? $val : ($val ? [$val] : []);
              @endphp

              @if(count($files))
                <ul class="list-unstyled mb-0">
                  @foreach($files as $ix => $file)
                    @php
                      $url  = $file['url']  ?? null;
                      $name = $file['name'] ?? ('Archivo '.($ix+1));
                    @endphp
                    <li>
                      @if($url)
                        <a href="{{ $url }}" target="_blank" rel="noopener">{{ $name }}</a>
                      @else
                        {{ $name }}
                      @endif
                    </li>
                  @endforeach
                </ul>
              @else
                <div>—</div>
              @endif

            @else
              <div>{{ $printDash(is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val) }}</div>
            @endif
          </div>
        @empty
          <div class="text-muted">—</div>
        @endforelse
      </div>
    </div>
  @empty
    <div class="alert alert-warning">Este trámite no tiene secciones configuradas.</div>
  @endforelse

  <div class="mt-3">
    <a href="{{ route('profile.solicitudes.index') }}" class="btn btn-outline-secondary">Volver a mis trámites</a>
  </div>
</div>
@endsection
