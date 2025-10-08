@extends('layouts.app')

@section('content')
<div class="container py-4">

  <div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <div class="h5 mb-1">{{ $solicitud->tramite->nombre ?? 'Trámite' }}</div>
        <div class="text-muted small">
          <span class="me-2"><strong>Expediente:</strong> {{ $solicitud->expediente }}</span>
          <span class="me-2"><strong>Creado:</strong> {{ $solicitud->created_at?->format('d/m/Y H:i') }}</span>
        </div>
      </div>
      <span class="badge bg-secondary">{{ strtoupper($solicitud->estado) }}</span>
    </div>
  </div>

  <div class="mb-3">
    <a href="{{ route('profile.solicitudes.index') }}" class="btn btn-outline-secondary btn-sm">Volver a mis trámites</a>
  </div>

  @php
    $schema   = is_array($schema ?? null) ? $schema : (json_decode($schema ?? '[]', true) ?: []);
    $sections = is_array($schema['sections'] ?? null) ? $schema['sections'] : [];

    $print = function($v) {
      if (is_array($v)) return implode(', ', array_map(fn($x)=>is_scalar($x)?(string)$x:json_encode($x, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES), $v));
      if ($v === true) return 'Sí';
      if ($v === false) return 'No';
      $s = trim((string)($v ?? ''));
      return $s === '' ? '—' : e($s);
    };
  @endphp

  @forelse($sections as $sec)
    <div class="card mb-3">
      <div class="card-header bg-primary text-white fw-semibold">{{ $sec['name'] ?? 'Sección' }}</div>
      <div class="card-body">
        @forelse(($sec['fields'] ?? []) as $f)
          @php
            $label = $f['label'] ?? ($f['name'] ?? 'Campo');
            $type  = strtolower($f['type'] ?? 'text');
            $val   = $f['value'] ?? null;  // <- ya viene fusionado
          @endphp
          <div class="mb-3">
            <div class="text-muted small">{{ $label }}</div>

            @if($type === 'file')
              @php $files = is_array($val) ? $val : ($val ? [$val] : []); @endphp
              @if(count($files))
                <ul class="list-unstyled mb-0">
                  @foreach($files as $ix => $file)
                    @php
                      $url  = is_array($file) ? ($file['url'] ?? null) : null;
                      $name = is_array($file) ? ($file['name'] ?? 'Archivo '.($ix+1)) : ('Archivo '.($ix+1));
                    @endphp
                    <li>
                      @if($url) <a href="{{ $url }}" target="_blank" rel="noopener">{{ $name }}</a>
                      @else    {{ $name }}
                      @endif
                    </li>
                  @endforeach
                </ul>
              @else
                <div>—</div>
              @endif
            @elseif($type === 'richtext')
              <div>{!! $print($val) !!}</div>
            @else
              <div>{{ $print($val) }}</div>
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
</div>
@endsection
