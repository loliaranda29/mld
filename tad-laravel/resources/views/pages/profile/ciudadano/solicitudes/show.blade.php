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
  // Observaciones del funcionario
  $metaAll = is_array($solicitud->respuestas_json ?? null) ? $solicitud->respuestas_json : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
  $validMap = (array)($metaAll['_funcionario']['validaciones'] ?? []);
  $secInvalid = [];
  $secMotivo  = [];
  foreach ($sections as $i => $_) {
      $k = 'sec'.$i;
      if (isset($validMap[$k]) && empty($validMap[$k]['ok'])) {
          $secInvalid[$i] = true;
          $secMotivo[$i]  = (string)($validMap[$k]['motivo'] ?? '');
      }
  }
  $isObserved = (strtolower((string)($solicitud->estado ?? '')) === 'observado');
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

      @php $hasEdit = $isObserved && count($secInvalid)>0; @endphp
      @if($hasEdit)
      <form method="POST" action="{{ route('solicitudes.update', $solicitud->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
      @endif
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
                                // Completar URL desde path si falta
                                if(!$url && $path) { try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; } }
                                // Completar nombre desde path o URL si falta
                                if(!$name) {
                                  if($path) { $name = basename($path); }
                                  elseif($url) { try { $bn = basename(parse_url($url, PHP_URL_PATH)); if ($bn) $name = $bn; } catch (\Throwable $e) {} }
                                }
                                if(!$name) $name = 'Archivo '.($ix+1);
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
          @php
            use Illuminate\Support\Facades\Storage;

            $docItems = [];
            foreach (($sections ?? []) as $sec) {
              foreach (($sec['fields'] ?? []) as $f) {
                if (strtolower($f['type'] ?? '') !== 'file') continue;
                $label = $f['label'] ?? ($f['name'] ?? 'Documento');
                $fname = $f['_name'] ?? ($f['name'] ?? null);
                $val   = $f['value'] ?? null;
                $arr   = [];
                if (is_array($val)) {
                  $arr = array_keys($val)!==range(0,count($val)-1) ? [$val] : $val;
                } elseif ($val) {
                  $arr = [$val];
                }
                foreach ($arr as $ix => $one) {
                  if (!is_array($one)) { $one = ['path'=>is_string($one)?$one:null]; }
                  $p = $one['path'] ?? null; $u = $one['url'] ?? null; $n = $one['name'] ?? null;
                  if (!$u && $p) { try { $u = Storage::disk('public')->url($p); } catch (\Throwable $e) { $u = null; } }
                  if (!$n) {
                    if ($p) { $n = basename($p); }
                    elseif ($u) { try { $bn = basename(parse_url($u, PHP_URL_PATH)); if ($bn) $n = $bn; } catch (\Throwable $e) {} }
                    if (!$n) { $n = 'Archivo '.($ix+1); }
                  }
                  $secure = $p && $fname ? route('profile.solicitudes.file', [$solicitud->id, $fname, $ix]) : null;
                  $docItems[] = ['label'=>$label,'name'=>$n,'url'=>$u,'path'=>$p,'secure'=>$secure];
                }
              }
            }
            if (!count($docItems)) {
              // Fallback: listar archivos detectados en storage si existen
              // Primero intento definitivos; solo si no hay, muestro tmp
              $def = 'solicitudes/'.($solicitud->id ?? 0);
              $tmp = $solicitud->usuario_id ? 'solicitudes/tmp/'.$solicitud->usuario_id : null;
              $dirs = [$def];
              foreach ($dirs as $d) {
                try {
                  foreach (Storage::disk('public')->files($d) as $p) {
                    $name = basename($p);
                    $url  = null; try { $url = Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                    $docItems[] = ['label'=>'Adjuntos','name'=>$name,'url'=>$url,'path'=>$p,'secure'=>null];
                  }
                } catch (\Throwable $e) {}
              }
              if (!count($docItems) && $tmp) {
                try {
                  foreach (Storage::disk('public')->files($tmp) as $p) {
                    $name = basename($p);
                    $url  = null; try { $url = Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                    $docItems[] = ['label'=>'Adjuntos (temporal)','name'=>$name,'url'=>$url,'path'=>$p,'secure'=>null];
                  }
                } catch (\Throwable $e) {}
              }
            }
          @endphp
          <div class="card shadow-sm">
            <div class="card-body">
              @if(count($docItems))
                <ul class="list-unstyled mb-0">
                  @foreach($docItems as $it)
                    <li class="mb-1">
                      @php $href = $it['secure'] ?: ($it['url'] ?? null); @endphp
                      @if(!empty($href))
                        <a href="{{ $href }}" target="_blank" rel="noopener">{{ $it['label'] }} — {{ $it['name'] }}</a>
                      @else
                        {{ $it['label'] }} — {{ $it['name'] }}
                      @endif
                    </li>
                  @endforeach
                </ul>
              @else
                <div class="text-muted">Sin documentos adicionales.</div>
              @endif
            </div>
          </div>
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
