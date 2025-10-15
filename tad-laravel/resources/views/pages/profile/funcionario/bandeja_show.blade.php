@extends('layouts.app-funcionario')

@section('content')
<div class="container-fluid py-3">

    {{-- Header / resumen --}}
    <div class="card shadow-sm p-4 mb-4">
        <div class="row align-items-start">
            {{-- Izquierda --}}
            <div class="col-md-4 border-end">
                <p class="text-muted mb-1 small">Folio/Prefolio del Expediente</p>
                <h5 class="fw-bold text-primary mb-2">{{ $solicitud->expediente ?? '—' }}</h5>

                <p class="mb-1 small text-muted">Trámite:</p>
                <p class="fw-bold">{{ $tramite->nombre ?? '—' }}</p>

                <p class="mb-1 small text-muted">Fecha de recepción de solicitud:</p>
                <p class="fw-bold">{{ optional($solicitud->created_at)->format('d/m/Y H:i\h') }}</p>

                <p class="mb-1 small text-muted">Operador(es) asignado(s):</p>
                <p class="fw-bold text-danger">Sin asignar</p>
            </div>

            {{-- Centro --}}
            <div class="col-md-4 border-end px-4">
                <p class="text-muted mb-1 small">Estatus</p>
                <span class="badge bg-secondary rounded-pill mb-2 text-uppercase">
                    {{ $solicitud->estado ?? '—' }}
                </span>

                @if(($totalEtapas ?? 0) > 0)
                    <p class="text-muted mb-1 small">
                        Etapa <strong>({{ $etapaActual }} / {{ $totalEtapas }})</strong>
                    </p>
                @endif

                <div class="d-grid gap-2 mt-2">
                    <a href="{{ route('funcionario.bandeja.historial', $solicitud->id) }}" class="btn btn-outline-secondary btn-sm">Ver historial</a>
                    <a href="{{ route('funcionario.bandeja.asignacion', $solicitud->id) }}" class="btn btn-outline-secondary btn-sm">Historial de asignación</a>
                    <a href="{{ route('funcionario.bandeja.asignacion', $solicitud->id) }}" class="btn btn-primary btn-sm fw-bold">Asignación de etapas</a>
                </div>
            </div>

            {{-- Derecha --}}
            <div class="col-md-4 px-4">
                <div class="d-grid gap-2 mb-3">
                    {{-- Rechazar trámite --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.rechazar', $solicitud->id) }}" onsubmit="return confirm('¿Rechazar el trámite?');">
                        @csrf
                        <input type="hidden" name="motivo" value="Rechazado por operador">
                        <button class="btn btn-danger btn-sm fw-bold" type="submit">Rechazar trámite</button>
                    </form>

                    {{-- Aceptar etapa --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.aceptar', $solicitud->id) }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Aceptar etapa</button>
                    </form>

                    {{-- Guardar --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.guardar', $solicitud->id) }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Guardar</button>
                    </form>
                </div>
                <div class="d-grid gap-2">
                    {{-- Área de descargas (ZIP) --}}
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('funcionario.bandeja.descargas', $solicitud->id) }}">
                        <i class="bi bi-download me-1"></i> Área de descargas
                    </a>

                    {{-- Adjuntar documentos de salida --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.salida.upload', $solicitud->id) }}" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="salida[]" multiple class="form-control form-control-sm mb-2">
                        <button class="btn btn-outline-dark btn-sm" type="submit">
                            <i class="bi bi-upload me-1"></i> Adjuntar documentos de salida
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Cuerpo: Documentos + Secciones --}}
    <div class="row g-4">
        {{-- Documentos (izquierda) --}}
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Documentos</strong>
                </div>

                {{-- Documentos: visor con navegación --}}
                @php
                    // 1) Schema con values
                    $schema = is_array($schema ?? null)
                        ? $schema
                        : (is_array($solicitud->datos) ? $solicitud->datos : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections'=>[]]));
                    $__sectionsDocs = $schema['sections'] ?? [];

                    // 2) Aplanar todos los archivos en una lista "paseable"
                    // Cada item => {label, name, url, path, fieldIndex, fileIndex, isImage, isPdf}
                    $__flatDocs = [];
                    $__fileFieldPos = 0;
                    foreach ($__sectionsDocs as $__sec) {
                        foreach (($__sec['fields'] ?? []) as $__f) {
                            if (strtolower($__f['type'] ?? '') !== 'file') continue;
                            $__label = $__f['label'] ?? ($__f['name'] ?? 'Documento');
                            $__val   = $__f['value'] ?? null;
                            $__arr   = [];
                            if (is_array($__val)) {
                                $__arr = array_keys($__val)!==range(0,count($__val)-1) ? [$__val] : $__val;
                            }

                            foreach ($__arr as $__ix => $__one) {
                                $__path = $__one['path'] ?? null;
                                $__url  = $__one['url']  ?? null;
                                if (!$__url && $__path) { try { $__url = \Storage::disk('public')->url($__path); } catch (\Throwable $e) { $__url = null; } }
                                $__name = $__one['name'] ?? ($__path ? basename($__path) : ('Archivo '.($__ix+1)));

                                $__ext = strtolower(pathinfo($__name, PATHINFO_EXTENSION));
                                $__isImage = in_array($__ext, ['jpg','jpeg','png','gif','webp','bmp','svg']);
                                $__isPdf   = $__ext === 'pdf';

                                // Link seguro (si existe la ruta y hay path)
                                $__secure = (Route::has('funcionario.bandeja.file') && $__path)
                                    ? route('funcionario.bandeja.file', [$solicitud->id, $__fileFieldPos, $__ix])
                                    : null;

                                $__flatDocs[] = [
                                    'label'      => $__label,
                                    'name'       => $__name,
                                    'url'        => $__secure ?: $__url, // prefiero seguro si está
                                    'path'       => $__path,
                                    'isImage'    => $__isImage,
                                    'isPdf'      => $__isPdf,
                                    'fieldIndex' => $__fileFieldPos,
                                    'fileIndex'  => $__ix,
                                ];
                            }
                            $__fileFieldPos++;
                        }
                    }
                    $__totalDocs = count($__flatDocs);
                @endphp

                <div class="card-body p-0"
                     x-data="docViewer({{ json_encode($__flatDocs, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }})">

                    <template x-if="total() === 0">
                        <div class="px-3 py-2 text-muted small">No hay documentos cargados.</div>
                    </template>

                    <template x-if="total() > 0">
                        <div>
                            {{-- Barra superior del visor --}}
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom bg-light">
                                <div class="d-flex align-items-center gap-2">
                                    <button class="btn btn-link p-0" @click="prev()" :disabled="index===0" title="Anterior">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>
                                    <strong x-text="current().label"></strong>
                                </div>
                                <div class="text-muted small">
                                    <span x-text="index+1"></span>/<span x-text="total()"></span>
                                </div>
                            </div>

                            {{-- Lienzo de preview --}}
                            <div class="p-3" style="background:#f8f9fa;">
                                <template x-if="current().isImage && current().url">
                                    <div class="d-flex justify-content-center">
                                        <img :src="current().url" :alt="current().name" class="img-fluid" style="max-height:65vh; border-radius:8px;">
                                    </div>
                                </template>

                                <template x-if="current().isPdf && current().url">
                                    <iframe :src="current().url" style="width:100%;height:65vh;border:none;"></iframe>
                                </template>

                                <template x-if="!current().isImage && !current().isPdf">
                                    <div class="text-center">
                                        <div class="mb-2"><i class="bi bi-file-earmark" style="font-size:2rem;"></i></div>
                                        <div class="mb-3" x-text="current().name"></div>
                                        <a :href="current().url" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">Abrir/Descargar</a>
                                    </div>
                                </template>
                            </div>

                            {{-- Acciones del documento --}}
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top bg-white">
                                <div class="text-muted small" x-text="current().name"></div>
                                <div class="d-flex gap-2">
                                    <a class="btn btn-outline-secondary btn-sm" :href="current().url" target="_blank" rel="noopener">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Abrir
                                    </a>
                                    <button class="btn btn-outline-secondary btn-sm" @click="next()" :disabled="index===total()-1">
                                        Siguiente <i class="bi bi-chevron-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <script>
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('docViewer', (docs) => ({
                            docs: Array.isArray(docs) ? docs : [],
                            index: 0,
                            total(){ return this.docs.length; },
                            current(){ return this.docs[this.index] || {label:'',name:'',url:null,isImage:false,isPdf:false}; },
                            next(){ if (this.index < this.total()-1) this.index++; },
                            prev(){ if (this.index > 0) this.index--; },
                        }));
                    });
                </script>
            </div>
        </div>

        {{-- Secciones (derecha) --}}
        <div class="col-md-7">
            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-3" role="tablist">
                @foreach(($sections ?? []) as $i => $sec)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link @if($i===0) active @endif"
                                data-bs-toggle="tab"
                                data-bs-target="#pane{{ $i }}"
                                type="button" role="tab">
                            {{ $sec['name'] ?? ('Sección '.($i+1)) }}
                        </button>
                    </li>
                @endforeach
            </ul>

            <div class="tab-content">
                @php
                    // Helpers locales
                    $fieldValue = function(array $f){
                        $v = $f['value'] ?? null;
                        $t = strtolower($f['type'] ?? 'text');
                        if ($t === 'checkbox' && !empty($f['multiple'])) return is_array($v) ? $v : ($v ? [$v] : []);
                        return $v;
                    };
                    $dash = function($v){
                        if (is_array($v)) return count($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : '—';
                        if ($v === null || $v === '') return '—';
                        return (string)$v;
                    };
                @endphp

                @foreach(($sections ?? []) as $i => $sec)
                    <div class="tab-pane fade @if($i===0) show active @endif" id="pane{{ $i }}" role="tabpanel">
                        <div class="card shadow-sm mb-4">
                            <div class="card-header bg-light">
                                {{ $sec['name'] ?? ('Sección '.($i+1)) }}
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @forelse(($sec['fields'] ?? []) as $f)
                                        @php
                                            $label = $f['label'] ?? $f['name'] ?? 'Campo';
                                            $type  = strtolower($f['type'] ?? 'text');
                                            $val   = $fieldValue($f);
                                        @endphp
                                        <div class="col-md-6">
                                            <label class="form-label small text-muted">{{ $label }}</label>

                                            @if($type === 'file')
                                                @php
                                                    $arr = [];
                                                    if (is_array($val)) {
                                                        $arr = array_keys($val) !== range(0, count($val)-1) ? [$val] : $val;
                                                    }
                                                    $fieldKey = $f['_name'] ?? ($f['name'] ?? 'archivo');
                                                @endphp
                                                @if(count($arr))
                                                    <ul class="list-unstyled mb-0">
                                                        @foreach($arr as $ix => $file)
                                                            @php
                                                                $p = $file['path'] ?? null;
                                                                $u = $file['url']  ?? null;
                                                                $name = $file['name'] ?? ($p ? basename($p) : 'Archivo '.($ix+1));
                                                                // Si hay path, usamos descarga segura por route (campo + índice)
                                                                $secureUrl = $p ? route('funcionario.bandeja.file', [$solicitud->id, $fieldKey, $ix]) : null;
                                                                // Fallback: si no hay path pero sí url pública
                                                                if (!$secureUrl && !$u && $p) {
                                                                    try { $u = \Storage::disk('public')->url($p); } catch (\Throwable $e) { $u = null; }
                                                                }
                                                            @endphp
                                                            <li>
                                                                @if($secureUrl)
                                                                    <a href="{{ $secureUrl }}" class="link-dark">{{ $name }}</a>
                                                                @elseif($u)
                                                                    <a href="{{ $u }}" target="_blank" rel="noopener" class="link-dark">{{ $name }}</a>
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

                @if(empty($sections))
                    <div class="alert alert-warning">
                        Este trámite no tiene un formulario configurado (sin secciones).
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
