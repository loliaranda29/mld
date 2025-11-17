{{-- resources/views/pages/profile/ciudadano/details/solicitud.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
  {{-- Header Section --}}
  <div class="mb-5">
    <div class="d-flex align-items-center gap-3 mb-3">
      <a href="{{ route('profile.solicitudes.index') }}" 
         class="btn btn-light border-0 shadow-sm d-flex align-items-center justify-content-center"
         style="width: 40px; height: 40px; border-radius: 10px; transition: all 0.2s;"
         onmouseover="this.style.transform='translateX(-4px)'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.1)';"
         onmouseout="this.style.transform='translateX(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path d="M19 12H5M12 19l-7-7 7-7"/>
        </svg>
      </a>
      <div>
        <h1 class="mb-1 fw-bold" style="font-size: 1.75rem; color: #1e293b;">
          {{ $solicitud->tramite->nombre }}
        </h1>
        <div class="d-flex flex-wrap align-items-center gap-3 mt-2">
          <div class="d-flex align-items-center gap-2 text-muted">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
              <polyline points="14 2 14 8 20 8"/>
            </svg>
            <span style="font-size: 0.9rem;">
              Expediente: <strong class="text-dark">{{ $solicitud->expediente }}</strong>
            </span>
          </div>
          <div class="d-flex align-items-center gap-3">
            <span class="badge px-3 py-2" 
                  style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); 
                         color: white; 
                         font-size: 0.75rem; 
                         font-weight: 600; 
                         letter-spacing: 0.5px;
                         border-radius: 8px;
                         text-transform: uppercase;">
              {{ $solicitud->estado }}
            </span>
            @php
              $etapas = [];
              try { $etapas = json_decode($solicitud->tramite->etapas_json ?? '[]', true) ?: []; } catch (\Throwable $e) { $etapas = []; }
              $totalEtapas = is_array($etapas) ? count($etapas) : 0;
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
              <span class="small text-muted">Etapa ({{ $etapaActual }} / {{ $totalEtapas }})</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

@php
  $tramite  = $solicitud->tramite ?? null;
  $sections = is_array($schema) ? ($schema['sections'] ?? []) : [];

  $fieldValue = function (array $f) {
    if (array_key_exists('value', $f)) {
        return $f['value'];
    }
    return $f['val'] ?? null;
  };

  $printDash = function ($v) {
    if ($v === null) return '—';
    $s = is_string($v) ? trim($v) : $v;
    if ($s === '' || $s === false) return '—';
    return $s;
  };

  $getFileExtension = function($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
  };

  $isImage = function($filename) use ($getFileExtension) {
    $ext = $getFileExtension($filename);
    return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp']);
  };

  $isPdf = function($filename) use ($getFileExtension) {
    return $getFileExtension($filename) === 'pdf';
  };
@endphp

  {{-- Sections --}}
  @forelse($sections as $secIndex => $sec)
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px; overflow: hidden;">
      <div class="card-header border-0 py-4 px-4" 
           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-3" 
               style="width: 48px; height: 48px; flex-shrink: 0;">
            <span class="text-white fw-bold" style="font-size: 1.25rem;">
              {{ $secIndex + 1 }}
            </span>
          </div>
          <h2 class="mb-0 text-white fw-semibold" style="font-size: 1.25rem;">
            {{ $sec['name'] ?? 'Sección' }}
          </h2>
        </div>
      </div>
      
      <div class="card-body p-4" style="background-color: #fafbfc;">
        @forelse(($sec['fields'] ?? []) as $fieldIndex => $f)
          @php
            $label = $f['label'] ?? $f['name'] ?? 'Campo';
            $type  = strtolower($f['type'] ?? 'text');
            $val   = $fieldValue($f);
          @endphp

          <div class="mb-4 @if(!$loop->last) pb-4 border-bottom @endif">
            <label class="d-block mb-3 text-uppercase fw-semibold" 
                   style="color: #64748b; font-size: 0.75rem; letter-spacing: 1px;">
              {{ $label }}
            </label>

            @if($type === 'file')
              @php
                $files = is_array($val) ? $val : ($val ? [$val] : []);
              @endphp

              @if(count($files))
                <div class="row g-3">
                  @foreach($files as $ix => $file)
                    @php
                      $url  = is_array($file) ? ($file['url'] ?? null) : null;
                      $path = is_array($file) ? ($file['path'] ?? null) : null;
                      $name = is_array($file) ? ($file['name'] ?? '') : '';
                      if (!$name && $path) $name = basename($path);
                      if (!$name) $name = 'Archivo '.($ix+1);
                      if (!$url && $path) { try { $url = \Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; } }
                      $fname = $f['_name'] ?? ($f['name'] ?? 'archivo');
                      $secure = $path ? route('profile.solicitudes.file', [$solicitud->id, $fname, $ix]) : null;
                      $finalUrl = $secure ?? $url;
                      $isImageFile = $isImage($name);
                      $isPdfFile = $isPdf($name);
                      $canPreview = $isImageFile || $isPdfFile;
                    @endphp

                    <div class="col-12 col-sm-6 col-lg-4">
                      <div class="card border-0 shadow-sm h-100 file-card" 
                           style="border-radius: 12px; overflow: hidden; transition: all 0.3s ease;">
                        
                        {{-- Preview Thumbnail --}}
                        @if($isImageFile && $finalUrl)
                          <div class="position-relative" style="height: 180px; background: #f1f5f9; overflow: hidden;">
                            <img src="{{ $finalUrl }}" 
                                 alt="{{ $name }}" 
                                 class="w-100 h-100" 
                                 style="object-fit: cover;"
                                 loading="lazy"
                                 onerror="this.parentElement.innerHTML='<div class=\'d-flex align-items-center justify-content-center h-100 bg-gradient\'><svg width=\'56\' height=\'56\' viewBox=\'0 0 24 24\' fill=\'none\' stroke=\'#cbd5e1\' stroke-width=\'1.5\'><rect x=\'3\' y=\'3\' width=\'18\' height=\'18\' rx=\'2\' ry=\'2\'/><circle cx=\'8.5\' cy=\'8.5\' r=\'1.5\'/><polyline points=\'21 15 16 10 5 21\'/></svg></div>';">
                            <div class="position-absolute top-0 start-0 w-100 h-100" 
                                 style="background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, transparent 50%);"></div>
                            <div class="position-absolute top-0 end-0 m-3">
                              <span class="badge bg-dark bg-opacity-75 px-2 py-1" 
                                    style="font-size: 0.7rem; border-radius: 6px; backdrop-filter: blur(8px);">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-1" style="vertical-align: text-top;">
                                  <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                                  <circle cx="8.5" cy="8.5" r="1.5"/>
                                  <polyline points="21 15 16 10 5 21"/>
                                </svg>
                                Imagen
                              </span>
                            </div>
                          </div>
                        @elseif($isPdfFile)
                          <div class="d-flex align-items-center justify-content-center position-relative" 
                               style="height: 180px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                            <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" opacity="0.9">
                              <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                              <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            <div class="position-absolute" style="bottom: 30%; font-size: 1.5rem; font-weight: 700; color: white; letter-spacing: 1px;">
                              PDF
                            </div>
                          </div>
                        @else
                          <div class="d-flex align-items-center justify-content-center" 
                               style="height: 180px; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);">
                            <svg width="72" height="72" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="1.5" opacity="0.9">
                              <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                              <polyline points="13 2 13 9 20 9"/>
                            </svg>
                          </div>
                        @endif

                        <div class="card-body p-3 bg-white">
                          <h6 class="mb-3 text-truncate fw-semibold" 
                              style="font-size: 0.9rem; color: #1e293b;" 
                              title="{{ $name }}">
                            {{ $name }}
                          </h6>
                          
                          <div class="d-flex gap-2">
                            @if($canPreview && $finalUrl)
                              <button type="button" 
                                      class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2 border-0"
                                      style="border-radius: 8px; font-size: 0.85rem; padding: 0.5rem 1rem; font-weight: 500; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); transition: all 0.2s;"
                                      onclick="previewFile('{{ $finalUrl }}', '{{ $name }}', '{{ $isImageFile ? 'image' : 'pdf' }}')"
                                      onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(102, 126, 234, 0.4)';"
                                      onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none';">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                  <circle cx="12" cy="12" r="3"/>
                                </svg>
                                Ver
                              </button>
                            @endif
                            
                            @if($finalUrl)
                              <a href="{{ $finalUrl }}" 
                                 download="{{ $name }}"
                                 class="btn btn-outline-secondary d-flex align-items-center justify-content-center @if($canPreview) @else flex-grow-1 gap-2 @endif"
                                 style="border-radius: 8px; font-size: 0.85rem; padding: 0.5rem; font-weight: 500; transition: all 0.2s; border-width: 2px;"
                                 onmouseover="this.style.backgroundColor='#f1f5f9'; this.style.borderColor='#64748b';"
                                 onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='#cbd5e1';"
                                 title="Descargar {{ $name }}">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                  <polyline points="7 10 12 15 17 10"/>
                                  <line x1="12" y1="15" x2="12" y2="3"/>
                                </svg>
                                @if(!$canPreview) <span>Descargar</span> @endif
                              </a>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-center py-5 px-4 rounded-3 border-2 border-dashed" 
                     style="background-color: #f8fafc; border-color: #e2e8f0;">
                  <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="#cbd5e1" stroke-width="1.5" class="mb-3">
                    <path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"/>
                    <polyline points="13 2 13 9 20 9"/>
                  </svg>
                  <div class="text-muted" style="font-size: 0.9rem;">Sin archivos adjuntos</div>
                </div>
              @endif

            @else
              <div class="px-4 py-3 rounded-3 border" 
                   style="background-color: white; border-color: #e2e8f0 !important; font-size: 0.95rem; color: #1e293b; line-height: 1.6;">
                {{ $printDash(is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val) }}
              </div>
            @endif
          </div>
        @empty
          <div class="text-center py-4 text-muted fst-italic" style="font-size: 0.9rem;">
            No hay campos en esta sección
          </div>
        @endforelse
      </div>
    </div>
  @empty
    <div class="alert border-0 shadow-sm d-flex align-items-start gap-3 p-4" 
         style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px;">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#92400e" stroke-width="2" class="flex-shrink-0 mt-1">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
      <span style="color: #92400e; font-size: 0.95rem;">
        Este trámite no tiene secciones configuradas.
      </span>
    </div>
  @endforelse

  {{-- Footer Actions --}}
  <div class="mt-5 d-flex gap-3">
    <a href="{{ route('profile.solicitudes.index') }}" 
       class="btn btn-lg px-5 py-3 border-0 shadow-sm d-inline-flex align-items-center gap-2"
       style="background-color: #f1f5f9; color: #475569; border-radius: 12px; font-weight: 600; transition: all 0.2s; font-size: 0.95rem;"
       onmouseover="this.style.backgroundColor='#e2e8f0'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.1)';"
       onmouseout="this.style.backgroundColor='#f1f5f9'; this.style.transform='translateY(0)'; this.style.boxShadow='0 1px 3px rgba(0,0,0,0.1)';">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
        <path d="M19 12H5M12 19l-7-7 7-7"/>
      </svg>
      Volver a mis trámites
    </a>
  </div>
</div>

{{-- File Preview Modal --}}
<div class="modal fade" id="filePreviewModal" tabindex="-1" aria-labelledby="filePreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-header border-0 py-4 px-4" 
           style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <h5 class="modal-title text-white fw-semibold d-flex align-items-center gap-3" 
            id="filePreviewModalLabel"
            style="font-size: 1.1rem;">
          <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-2" 
               style="width: 36px; height: 36px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
              <circle cx="12" cy="12" r="3"/>
            </svg>
          </div>
          <span id="previewFileName">Vista Previa</span>
        </h5>
        <button type="button" 
                class="btn-close btn-close-white" 
                data-bs-dismiss="modal" 
                aria-label="Cerrar vista previa"
                style="opacity: 0.9;"
                onmouseover="this.style.opacity='1';"
                onmouseout="this.style.opacity='0.9';"></button>
      </div>
      <div class="modal-body p-0" style="background-color: #f8fafc; min-height: 500px;">
        <div id="previewContent" class="d-flex align-items-center justify-content-center" style="min-height: 500px;">
          <div class="text-center">
            <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
              <span class="visually-hidden">Cargando...</span>
            </div>
            <p class="text-muted">Cargando vista previa...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .file-card {
    cursor: pointer;
  }
  
  .file-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.12) !important;
  }
  
  #previewContent img {
    max-width: 100%;
    max-height: 80vh;
    object-fit: contain;
    border-radius: 8px;
  }
  
  #previewContent iframe {
    width: 100%;
    height: 80vh;
    border: none;
  }
  
  .border-dashed {
    border-style: dashed !important;
  }
</style>

<script>
function previewFile(url, name, type) {
  const modal = new bootstrap.Modal(document.getElementById('filePreviewModal'));
  const previewContent = document.getElementById('previewContent');
  const previewFileName = document.getElementById('previewFileName');
  
  previewFileName.textContent = name;
  
  // Show loading spinner
  previewContent.innerHTML = `
    <div class="text-center">
      <div class="spinner-border text-primary mb-3" role="status" style="width: 3rem; height: 3rem;">
        <span class="visually-hidden">Cargando...</span>
      </div>
      <p class="text-muted">Cargando vista previa...</p>
    </div>
  `;
  
  modal.show();
  
  // Load content based on type
  setTimeout(() => {
    if (type === 'image') {
      previewContent.innerHTML = `
        <div class="p-4 w-100 d-flex align-items-center justify-content-center">
          <img src="${url}" 
               alt="${name}" 
               class="img-fluid" 
               style="max-height: 80vh; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,0.15);">
        </div>
      `;
    } else if (type === 'pdf') {
      previewContent.innerHTML = `
        <iframe src="${url}" 
                style="width: 100%; height: 80vh; border: none;"
                title="Vista previa de ${name}"></iframe>
      `;
    }
  }, 300);
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
  if (event.key === 'Escape') {
    const modalElement = document.getElementById('filePreviewModal');
    const modal = bootstrap.Modal.getInstance(modalElement);
    if (modal) {
      modal.hide();
    }
  }
});
</script>
@endsection
