@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h3 class="mb-1">{{ $solicitud->tramite->nombre }}</h3>
  <div class="text-muted mb-3">
    Expediente: {{ $solicitud->folio ?? ('TRAM-'.$solicitud->id) }} — Estado: {{ $solicitud->estado }}
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="card">
    <div class="card-header">Resumen de tu solicitud</div>
    <div class="card-body">
      @php
        $ans = $solicitud->respuestas_json ?? [];
        // Mapear nombre de campo de archivo -> índice de campo file en el schema
        $fileFieldIndex = [];
        $pos = 0;
        $schemaArr = isset($schema) && is_array($schema) ? $schema : [];
        foreach (($schemaArr['sections'] ?? []) as $sec) {
          foreach (($sec['fields'] ?? []) as $f) {
            if ((strtolower($f['type'] ?? '') === 'file')) {
              $fname = $f['_name'] ?? ($f['name'] ?? 'archivo');
              if (!array_key_exists($fname, $fileFieldIndex)) { $fileFieldIndex[$fname] = $pos; }
              $pos++;
            }
          }
        }
      @endphp

      @if(empty($ans))
        <p class="text-muted">No hay respuestas registradas.</p>
      @else
        <dl class="row">
          @foreach($ans as $campo => $valor)
            <dt class="col-sm-4">{{ str_replace('_',' ',ucfirst($campo)) }}</dt>
            <dd class="col-sm-8">
              @php
                $rendered = false;
                // Detectar estructura de archivo(s)
                if (is_array($valor)) {
                  $isAssoc = array_keys($valor) !== range(0, count($valor) - 1);
                  $archivos = $isAssoc ? [$valor] : $valor;
                  // Si el primero parece tener path/url, asumimos que son archivos
                  $looksFile = isset(($archivos[0] ?? [])['path']) || isset(($archivos[0] ?? [])['url']);
                  if ($looksFile) {
                    echo '<ul class="list-unstyled mb-0">';
                    foreach ($archivos as $ix => $file) {
                      $name = $file['name'] ?? '';
                      $path = $file['path'] ?? null;
                      $url  = $file['url']  ?? null;
                      if (!$name && $path) { $name = basename($path); }
                      if (!$name) { $name = 'Archivo '.($ix+1); }
                      if (!$url && $path) {
                        try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; }
                      }
                      $fieldParam = array_key_exists($campo, $fileFieldIndex) ? $fileFieldIndex[$campo] : $campo;
                      $secure = $path ? route('profile.solicitudes.file', [$solicitud->id, $fieldParam, $ix]) : null;
                      if ($secure) {
                        echo '<li><a href="'.e($secure).'" target="_blank" rel="noopener">'.e($name).'</a></li>';
                      } elseif ($url) {
                        echo '<li><a href="'.e($url).'" target="_blank" rel="noopener">'.e($name).'</a></li>';
                      } else {
                        echo '<li>'.e($name).'</li>';
                      }
                    }
                    echo '</ul>'; 
                    $rendered = true;
                  }
                }
              @endphp

              @if(!$rendered)
                @if(is_array($valor))
                  {{ implode(', ', $valor) }}
                @else
                  {{ $valor }}
                @endif
              @endif
            </dd>
          @endforeach
        </dl>
      @endif
    </div>
  </div>

  <div class="d-flex gap-2 mt-3">
    <a href="{{ route('profile.tramites') }}" class="btn btn-light">Mis trámites</a>
    {{-- Si querés permitir edición posterior, apunta a una vista de edición específica --}}
    {{-- <a href="{{ route('profile.solicitudes.edit', $solicitud->id) }}" class="btn btn-primary">Editar</a> --}}
  </div>
</div>
@endsection
