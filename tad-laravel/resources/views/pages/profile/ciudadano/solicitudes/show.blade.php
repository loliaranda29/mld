{{-- resources/views/pages/profile/ciudadano/solicitud_show.blade.php --}}
@extends('layouts.app')

@section('content')
@php
  $tramite  = $solicitud->tramite ?? null;
  $sections = is_array($schema) ? ($schema['sections'] ?? []) : [];
  $fieldValue = function (array $f) {
      $v    = $f['value'] ?? null;
      $type = strtolower($f['type'] ?? 'text');
      if ($type === 'checkbox' && !empty($f['multiple'])) {
          return is_array($v) ? $v : ($v ? [$v] : []);
      }
      return $v;
  };
  $dash = function ($v) {
      if (is_array($v)) return count($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : '—';
      if ($v === null || $v === '') return '—';
      return (string)$v;
  };
  $tabId = fn($i) => 'tab-'.($i+1);
  $paneId = fn($i) => 'pane-'.($i+1);
@endphp

<div class="container-xxl py-4">
  <div class="row g-4">
    <div class="col-12">
      <div class="card shadow-sm mb-3">
        <div class="card-body d-flex justify-content-between align-items-start">
          <div>
            <div class="text-muted small">Folio/Prefolio del Expediente</div>
            <div class="h5 mb-2">{{ $solicitud->expediente }}</div>
            <div class="text-muted small">Trámite</div>
            <div class="fw-semibold">{{ $tramite->nombre ?? 'Trámite' }}</div>
            <div class="text-muted small mt-2">Fecha de recepción de solicitud</div>
            <div class="fw-medium">{{ optional($solicitud->created_at)->format('d/m/Y H:i\h') }}</div>
          </div>
          <div class="text-end">
            <div class="text-muted small mb-1">Estatus</div>
            <span class="badge bg-secondary text-uppercase">{{ $solicitud->estado }}</span>
            @php
              $etapas = [];
              try { $etapas = json_decode($tramite->etapas_json ?? '[]', true) ?: []; } catch (\Throwable $e) { $etapas = []; }
              $totalEtapas = is_array($etapas) ? count($etapas) : 0;
              // Mapa simple por estado -> etapa actual (si no hay campo persistente)
              $estado = strtolower((string)($solicitud->estado ?? ''));
              $map = [
                'iniciado'    => 1,
                'en_proceso'  => min(2, max(1, $totalEtapas - 1)),
                'en_revision' => min(2, max(1, $totalEtapas - 1)),
                'observado'   => min(2, max(1, $totalEtapas - 1)),
                'aprobado'    => max(1, $totalEtapas),
                'finalizado'  => max(1, $totalEtapas),
                'rechazado'   => max(1, $totalEtapas),
              ];
              $etapaActual = $map[$estado] ?? 1;
            @endphp
            @if($totalEtapas > 0)
              <div class="text-muted small mt-2">Etapa ({{ $etapaActual }} / {{ $totalEtapas }})</div>
            @endif
            <div class="text-muted small mt-3">Fecha de actualización</div>
            <div class="fw-medium">{{ optional($solicitud->updated_at)->format('d/m/Y H:i\h') }}</div>
            @if(\Illuminate\Support\Facades\Route::has('profile.solicitudes.historial'))
              <a href="{{ route('profile.solicitudes.historial', $solicitud->id) }}" class="btn btn-outline-secondary btn-sm mt-3">Ver historial</a>
            @endif
          </div>
        </div>
      </div>

      <ul class="nav nav-tabs mb-3" role="tablist">
        @foreach($sections as $i => $sec)
          <li class="nav-item" role="presentation">
            <button class="nav-link @if($i===0) active @endif" data-bs-toggle="tab" data-bs-target="#{{ $paneId($i) }}" type="button" role="tab">
              {{ $sec['name'] ?? ('Sección '.($i+1)) }}
            </button>
          </li>
        @endforeach
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#docs-pane" type="button">Documentos</button></li>
        <li class="nav-item" role="presentation"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#msgs-pane" type="button">Mensajes</button></li>
      </ul>

      <div class="tab-content">
        @foreach($sections as $i => $sec)
          <div class="tab-pane fade @if($i===0) show active @endif" id="{{ $paneId($i) }}" role="tabpanel">
            <div class="card shadow-sm mb-4">
              <div class="card-header text-white" style="background-color:#1f7a74;">
                {{ $sec['name'] ?? ('Sección '.($i+1)) }}
              </div>
              <div class="card-body">
                <div class="row g-3">
                  @forelse(($sec['fields'] ?? []) as $f)
                    @php
                      $label = $f['label'] ?? $f['name'] ?? 'Campo';
                      $type  = strtolower($f['type'] ?? 'text');
                      $val   = $fieldValue($f);
                      $fname = $f['_name'] ?? ($f['name'] ?? 'archivo');
                    @endphp
                    <div class="col-md-6">
                      <label class="form-label small text-muted">{{ $label }} @if(!empty($f['required'])) * @endif</label>

                      @if($type === 'textarea')
                        <textarea class="form-control" rows="2" disabled>{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : ($val ?? '') }}</textarea>
                      @elseif($type === 'file')
                        @php
                          $files = [];
                          if (is_array($val)) { $files = array_keys($val)!==range(0,count($val)-1) ? [$val] : $val; }
                        @endphp
                        @if(count($files))
                          <ul class="list-unstyled mb-0">
                            @foreach($files as $ix => $file)
                              @php
                                $path = is_array($file) ? ($file['path'] ?? null) : null;
                                $url  = is_array($file) ? ($file['url'] ?? null)   : null;
                                $name = is_array($file) ? ($file['name'] ?? '')     : '';
                                if(!$name && $path) $name = basename($path);
                                if(!$name) $name = 'Archivo '.($ix+1);
                                if(!$url && $path) { try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; } }
                                $secure = $path ? route('profile.solicitudes.file', [$solicitud->id, $fname, $ix]) : null;
                              @endphp
                              <li>
                                @if($secure)
                                  <a href="{{ $secure }}" target="_blank" rel="noopener">{{ $name }}</a>
                                @elseif($url)
                                  <a href="{{ $url }}" target="_blank" rel="noopener">{{ $name }}</a>
                                @else
                                  {{ $name }}
                                @endif
                              </li>
                            @endforeach
                          </ul>
                        @else
                          <input class="form-control" value="—" disabled>
                        @endif
                      @else
                        @php $text = $dash(is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val); @endphp
                        <input class="form-control" value="{{ $text }}" disabled>
                      @endif
                    </div>
                  @empty
                    <div class="col-12 text-muted">No hay campos en esta sección</div>
                  @endforelse
                </div>
              </div>
            </div>
          </div>
        @endforeach

        <div class="tab-pane fade" id="docs-pane" role="tabpanel">
          <div class="card shadow-sm"><div class="card-body text-muted">Sin documentos adicionales.</div></div>
        </div>
        <div class="tab-pane fade" id="msgs-pane" role="tabpanel">
          <div class="card shadow-sm"><div class="card-body text-muted">Aún no hay mensajes.</div></div>
        </div>
      </div>

      <div class="mt-3">
        <a href="{{ route('profile.solicitudes.index') }}" class="btn btn-outline-secondary">Volver a mis trámites</a>
      </div>
    </div>
  </div>
</div>
@endsection
