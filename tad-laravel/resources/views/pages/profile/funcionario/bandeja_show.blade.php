@extends('layouts.app-funcionario')

@section('content')
<div class="container-fluid py-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
                    {{-- Rechazar --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.rechazar', $solicitud->id) }}" onsubmit="return confirm('¿Rechazar el trámite?');">
                        @csrf
                        <input type="hidden" name="motivo" value="Rechazado por operador">
                        <button class="btn btn-danger btn-sm fw-bold" type="submit">Rechazar trámite</button>
                    </form>
                    {{-- Aceptar --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.aceptar', $solicitud->id) }}">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Aceptar etapa</button>
                    </form>
                    {{-- Guardar --}}
                    <form method="POST" action="{{ route('funcionario.bandeja.guardar', $solicitud->id) }}" onsubmit="return flushValidationsAndSubmit(this);">
                        @csrf
                        <button class="btn btn-outline-secondary btn-sm" type="submit">Guardar</button>
                    </form>
                </div>
                <div class="d-grid gap-2">
                    <a class="btn btn-outline-dark btn-sm" href="{{ route('funcionario.bandeja.descargas', $solicitud->id) }}">
                        <i class="bi bi-download me-1"></i> Área de descargas
                    </a>
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

    {{-- ========== Cuerpo: Documentos + Secciones ========== --}}
    <div class="row g-4">
        {{-- ===== Documentos (izquierda) ===== --}}
        <div class="col-md-5">
            <div class="card shadow-sm overflow-hidden">
                @php
                    use Illuminate\Support\Facades\Storage;
                    use Illuminate\Support\Str;

                    // 1) Schema base
                    $schema = is_array($schema ?? null)
                        ? $schema
                        : (is_array($solicitud->datos) ? $solicitud->datos : (json_decode($solicitud->datos ?? '[]', true) ?: ['sections'=>[]]));
                    $sectionsAll = $schema['sections'] ?? [];

                    // 2) Dirs fallback cuando el value quedó como texto
                    $baseDirs = array_filter([
                        'solicitudes/'.($solicitud->id ?? 0),
                        $solicitud->usuario_id ? 'solicitudes/tmp/'.$solicitud->usuario_id : null,
                    ]);

                    $findByName = function(string $name) use ($baseDirs) {
                        $needle = strtolower(basename($name));
                        foreach ($baseDirs as $dir) {
                            try {
                                foreach (Storage::disk('public')->files($dir) as $p) {
                                    if (Str::endsWith(strtolower($p), $needle)) {
                                        return [
                                            'path' => $p,
                                            'url'  => Storage::disk('public')->url($p),
                                            'name' => basename($p),
                                        ];
                                    }
                                }
                            } catch (\Throwable $e) {}
                        }
                        return null;
                    };

                    // 3) Construir listado normalizado de documentos
                    $docs = []; // [{label, required, items:[{name,url,path,fieldIndex,fileIndex,isImage,isPdf,ok}]}]
                    $fileFieldPos = 0;
                    // mapa de validaciones guardadas
                    $validMap = [];
                    try { $metaAll = is_array($solicitud->respuestas_json ?? null) ? $solicitud->respuestas_json : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []); $validMap = (array)($metaAll['_funcionario']['validaciones'] ?? []); } catch (\Throwable $e) { $validMap = []; }
                    foreach ($sectionsAll as $sec) {
                        foreach (($sec['fields'] ?? []) as $f) {
                            if (strtolower($f['type'] ?? '') !== 'file') { $fileFieldPos++; continue; }

                            $label = $f['label'] ?? ($f['name'] ?? 'Documento');
                            $val   = $f['value'] ?? null;
                            $items = [];

                            if (is_array($val)) {
                                $arr = array_keys($val)!==range(0,count($val)-1) ? [$val] : $val;
                                foreach ($arr as $ix => $one) {
                                    $path = $one['path'] ?? null;
                                    $url  = $one['url'] ?? null;
                                    $name = $one['name'] ?? ($path ? basename($path) : ('Archivo '.($ix+1)));
                                    if (!$url && $path) { try { $url = Storage::disk('public')->url($path); } catch (\Throwable $e) { $url = null; } }
                                    // Si no hay path/url, intentar localizar por nombre en storage
                                    if (!$path && !$url && $name) {
                                        $guess = $findByName($name) ?: $findByName(pathinfo($name, PATHINFO_BASENAME));
                                        if ($guess) {
                                            $path = $guess['path'] ?? $path;
                                            $url  = $guess['url']  ?? $url;
                                            $name = $guess['name'] ?? $name;
                                        }
                                    }
                                    // Prefiero descarga segura si existe
                                    $secure = (Route::has('funcionario.bandeja.file') && $path)
                                        ? route('funcionario.bandeja.file', [$solicitud->id, $fileFieldPos, $ix])
                                        : null;

                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']);
                                    $isPdf = $ext === 'pdf';

                                    $key = 'f'.$fileFieldPos.'.'.$ix;
                                    $valState = $validMap[$key] ?? null;
                                    $state = $valState ? (($valState['ok'] ?? false) ? 'valid' : 'invalid') : 'pending';
                                    $items[] = [
                                        'name'=>$name,
                                        'url'=> $secure ?: $url,
                                        'path'=>$path,
                                        'fieldIndex'=>$fileFieldPos,
                                        'fileIndex'=>$ix,
                                        'key'=>$key,
                                        'isImage'=>$isImg,
                                        'isPdf'=>$isPdf,
                                        'ok'=> (bool)($secure ?: $url),
                                        'state'=>$state,
                                    ];
                                }
                            } elseif (is_string($val) && trim($val) !== '') {
                                // Valor texto -> buscar en storage
                                $guess = $findByName($val) ?: $findByName(pathinfo($val, PATHINFO_BASENAME));
                                $name = trim($val);
                                $path = $guess['path'] ?? null;
                                $url  = $guess['url']  ?? null;

                                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                $isImg = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']);
                                $isPdf = $ext === 'pdf';

                                $secure = (Route::has('funcionario.bandeja.file') && $path)
                                    ? route('funcionario.bandeja.file', [$solicitud->id, $fileFieldPos, 0])
                                    : null;

                                $key = 'f'.$fileFieldPos.'.0';
                                $valState = $validMap[$key] ?? null;
                                $state = $valState ? (($valState['ok'] ?? false) ? 'valid' : 'invalid') : 'pending';
                                $items[] = [
                                    'name'=>$guess['name'] ?? $name,
                                    'url'=> $secure ?: $url,
                                    'path'=>$path,
                                    'fieldIndex'=>$fileFieldPos,
                                    'fileIndex'=>0,
                                    'key'=>$key,
                                    'isImage'=>$isImg,
                                    'isPdf'=>$isPdf,
                                    'ok'=> (bool)($secure ?: $url),
                                    'state'=>$state,
                                ];
                            }

                            $docs[] = [
                                'label'=>$label,
                                'required'=>!empty($f['required']),
                                'items'=>$items,
                            ];

                            $fileFieldPos++;
                        }
                    }

                    // Heurística adicional: valores texto con pinta de archivo en campos no-file
                    foreach ($sectionsAll as $sec2) {
                        foreach (($sec2['fields'] ?? []) as $f2) {
                            $t2 = strtolower($f2['type'] ?? 'text');
                            if ($t2 === 'file') continue;
                            $v2 = $f2['value'] ?? null;
                            if (is_string($v2) && preg_match('/\.(pdf|png|jpe?g|gif|webp|bmp|svg)$/i', $v2)) {
                                $guess = $findByName($v2) ?: $findByName(pathinfo($v2, PATHINFO_BASENAME));
                                $name  = $guess['name'] ?? trim($v2);
                                $path  = $guess['path'] ?? null;
                                $url   = $guess['url']  ?? null;
                                $ext   = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                $labelH = $f2['label'] ?? ($f2['name'] ?? 'Documento');
                                $docs[] = [
                                    'label'=>$labelH,
                                    'required'=>!empty($f2['required']),
                                    'items'=>[[
                                        'name'=>$name,
                                        'url'=>$url,
                                        'path'=>$path,
                                        'fieldIndex'=>null,'fileIndex'=>null,
                                        'isImage'=>in_array($ext,['jpg','jpeg','png','gif','webp','bmp','svg']),
                                        'isPdf'=>$ext==='pdf',
                                        'ok'=> (bool)$url,
                                        // clave sintética para permitir guardar validaciones
                                        'key'=> 'h:'.md5(($labelH??'').'|'.($name??'')),
                                        'state'=>'pending',
                                    ]],
                                ];
                            }
                        }
                    }

                    // aplanado para visor
                    $flat = [];
                    foreach ($docs as $di => $d) {
                        foreach (($d['items'] ?? []) as $ii => $it) {
                            // Si tiene nombre pero no URL, intentar resolver por nombre
                            if ((!($it['url'] ?? null)) && ($it['name'] ?? null)) {
                                $guess = $findByName($it['name']) ?: $findByName(pathinfo($it['name'], PATHINFO_BASENAME));
                                if ($guess) {
                                    $it['path'] = $it['path'] ?? ($guess['path'] ?? null);
                                    $it['url']  = $it['url']  ?? ($guess['url']  ?? null);
                                }
                            }
                            $flat[] = array_merge($it, ['label'=>$d['label']]);
                        }
                    }

                    // Fallback intermedio: usar $documentos calculado por el controlador, si existe
                    if (!count($flat) && !empty($documentos)) {
                        foreach ((array)$documentos as $doc) {
                            $label = $doc['label'] ?? 'Adjuntos';
                            foreach ((array)($doc['urls'] ?? []) as $u) {
                                $name = $u['name'] ?? 'Archivo';
                                $url  = $u['url']  ?? null;
                                if (!$url && $name) {
                                    $guess = $findByName($name) ?: $findByName(pathinfo($name, PATHINFO_BASENAME));
                                    $url   = $guess['url'] ?? null;
                                }
                                if ($url) {
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    if (!$ext) {
                                        // intentar deducir por URL si el nombre no tiene extensión
                                        try {
                                            $p = parse_url($url, PHP_URL_PATH);
                                            $ext = strtolower(pathinfo($p ?? '', PATHINFO_EXTENSION));
                                        } catch (\Throwable $e) { $ext = ''; }
                                    }
                                    $flat[] = [
                                        'label'   => $label,
                                        'name'    => $name,
                                        'url'     => $url,
                                        'path'    => null,
                                        'isImage' => in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']),
                                        'isPdf'   => $ext === 'pdf',
                                        'state'   => 'pending',
                                        'key'     => 'd:'.md5(($label??'').'|'.($name??'')),
                                    ];
                                }
                            }
                        }
                        if (!count($docs) && count($flat)) {
                            // construir una agrupación simple para la barra lateral
                            $grouped = [];
                            foreach ($flat as $it) { $grouped[$it['label']][] = $it; }
                            $docs = [];
                            foreach ($grouped as $label => $items) { $docs[] = ['label'=>$label,'required'=>false,'items'=>$items]; }
                        }
                    }

                    // Asegurar flags de tipo por URL si aún no quedaron seteados
                    foreach ($flat as &$it) {
                        $name = (string)($it['name'] ?? '');
                        $url  = (string)($it['url'] ?? '');
                        $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                        if (!$ext && $url) {
                            try { $p = parse_url($url, PHP_URL_PATH); $ext = strtolower(pathinfo($p ?? '', PATHINFO_EXTENSION)); } catch (\Throwable $e) { $ext = ''; }
                        }
                        if (!isset($it['isPdf']))   { $it['isPdf']   = ($ext === 'pdf'); }
                        if (!isset($it['isImage'])) { $it['isImage'] = in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']); }
                    }

                    // Fallback: si no hay nada en el schema, listamos lo que exista en storage
                    if (!count($flat)) {
                        $others = [];
                        // Recolectar primero solo definitivos (solicitudes/{id})
                        $orderedDirs = [ 'solicitudes/'.($solicitud->id ?? 0) ];
                        // Solo si no encontramos nada en definitivo, miramos el tmp del usuario
                        $tryTmp = $solicitud->usuario_id ? ('solicitudes/tmp/'.$solicitud->usuario_id) : null;
                        foreach ($orderedDirs as $dir) {
                            try {
                                foreach (Storage::disk('public')->files($dir) as $p) {
                                    $name = basename($p);
                                    $url  = null;
                                    try { $url = Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $others[] = [
                                        'label'   => 'Adjuntos',
                                        'name'    => $name,
                                        'url'     => $url,
                                        'path'    => $p,
                                        'isImage' => in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']),
                                        'isPdf'   => $ext === 'pdf',
                                    ];
                                }
                            } catch (\Throwable $e) {}
                        }
                        if ($tryTmp) {
                            // Listar TODOS los archivos temporales del usuario (sin heurística)
                            try {
                                foreach (Storage::disk('public')->files($tryTmp) as $p) {
                                    $name = basename($p);
                                    $url  = null; try { $url = Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $others[] = [
                                        'label'   => 'Adjuntos (temporal)',
                                        'name'    => $name,
                                        'url'     => $url,
                                        'path'    => $p,
                                        'isImage' => in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']),
                                        'isPdf'   => $ext === 'pdf',
                                    ];
                                }
                            } catch (\Throwable $e) {}
                        }

                        // Si aún no hay nada (posible mismatch de usuario_id), tomar el más reciente de cualquier tmp/*
                        /* Deshabilitado: no mezclar adjuntos de otros usuarios
                        if (!count($others)) {
                            try {
                                $pairs = [];
                                foreach ((Storage::disk('public')->directories('solicitudes/tmp') ?: []) as $sub) {
                                    foreach (Storage::disk('public')->files($sub) as $p) {
                                        try { $ts = Storage::disk('public')->lastModified($p); } catch (\Throwable $e) { $ts = 0; }
                                        $pairs[] = [$p, $ts];
                                    }
                                }
                                usort($pairs, function($a,$b){ return $b[1] <=> $a[1]; });
                                if (isset($pairs[0][0])) {
                                    $p = $pairs[0][0];
                                    $name = basename($p);
                                    $url  = null; try { $url = Storage::disk('public')->url($p); } catch (\Throwable $e) { $url = null; }
                                    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                                    $others[] = [
                                        'label'   => 'Adjuntos (temporal)',
                                        'name'    => $name,
                                        'url'     => $url,
                                        'path'    => $p,
                                        'isImage' => in_array($ext, ['jpg','jpeg','png','gif','webp','bmp','svg']),
                                        'isPdf'   => $ext === 'pdf',
                                    ];
                                }
                            } catch (\Throwable $e) {}
                        }
                        */
                        $flat = $others;
                        if (!count($docs) && count($others)) {
                            $docs = [[ 'label' => 'Adjuntos', 'required' => false, 'items' => $others ]];
                        }
                    }
                @endphp

                {{-- Header azul + contador --}}
                <div class="px-3 py-2" style="background:#0f265c; color:#fff;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-semibold"><i class="bi bi-journal-text me-2"></i>Documentos</div>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge rounded-pill bg-light text-dark">{{ count($flat) }}</span>
                            <form method="POST" action="{{ route('funcionario.bandeja.normalize', $solicitud->id) }}">
                                @csrf
                                <button class="btn btn-sm btn-outline-light" type="submit">Normalizar adjuntos</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Layout: sidebar lista + visor --}}
                <div class="row g-0" 
                     x-data="docUI({{ json_encode($flat, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES) }})"
                     x-init="syncChoice()">

                    {{-- Sidebar --}}
                    <div class="col-5 border-end" style="max-height: 70vh; overflow:auto;">
                        @if(count($docs))
                            @foreach($docs as $i => $d)
                                <div class="px-3 py-2 border-bottom d-flex align-items-center justify-content-between"
                                     :class="{'bg-light': groupActive === {{ $i }} }"
                                     @click="gotoGroup({{ $i }})" style="cursor:pointer">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi" :class="groupIcon({{ $i }})"></i>
                                        <div class="small fw-semibold">{{ $d['label'] }}</div>
                                    </div>
                                    <div class="small text-muted">{{ count($d['items'] ?? []) ?: 0 }}</div>
                                </div>
                            @endforeach
                        @else
                            <div class="px-3 py-3 text-muted small">No hay documentos configurados.</div>
                        @endif
                    </div>

                    {{-- Visor --}}
                    <div class="col-7">
                        <template x-if="total() === 0">
                            <div class="p-3 text-muted small">No hay documentos cargados.</div>
                        </template>

                        <template x-if="total() > 0">
                            <div class="d-flex flex-column" style="min-height:70vh;">
                                {{-- Barra del visor --}}
                                <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom bg-white">
                                    <div class="d-flex align-items-center gap-2">
                                        <button class="btn btn-link p-0" @click="prev()" :disabled="idx===0"><i class="bi bi-chevron-left"></i></button>
                                        <strong class="text-truncate" style="max-width:240px;" x-text="cur().label"></strong>
                                        <i class="bi" :class="stateIcon(cur().state)"></i>
                                    </div>
                                    <div class="text-muted small">
                                        <span x-text="idx+1"></span>/<span x-text="total()"></span>
                                    </div>
                                </div>

                                {{-- Lienzo --}}
                                <div class="flex-grow-1 p-3" style="background:#f5f7fb;">
                                    <template x-if="cur().isImage && cur().url">
                                        <div class="d-flex justify-content-center">
                                            <img :src="cur().url" :alt="cur().name" class="img-fluid" style="max-height:60vh;border-radius:8px;">
                                        </div>
                                    </template>
                                    <template x-if="cur().isPdf && cur().url">
                                        <iframe :src="cur().url" style="width:100%;height:60vh;border:none;border-radius:8px;background:#fff;"></iframe>
                                    </template>
                                    <template x-if="!cur().isImage && !cur().isPdf">
                                        <div class="text-center">
                                            <div class="mb-2"><i class="bi bi-file-earmark" style="font-size:2rem;"></i></div>
                                            <div class="mb-2" x-text="cur().name"></div>
                                            <template x-if="cur().url">
                                                <a :href="cur().url" target="_blank" class="btn btn-outline-primary btn-sm">
                                                    Abrir/Descargar
                                                </a>
                                            </template>
                                        </div>
                                    </template>
                                </div>

                                {{-- Pie del visor: nombre + validar + acciones --}}
                                <div class="px-3 py-2 border-top bg-white">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="small text-truncate" style="max-width: 60%;" x-text="cur().name"></div>
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="small">Validar:</div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="validar" id="v-si" value="si" x-model="choice" @change="setValidation(true)">
                                                <label class="form-check-label" for="v-si">Sí</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="validar" id="v-no" value="no" x-model="choice" @change="choice='no'">
                                                <label class="form-check-label" for="v-no">No</label>
                                            </div>
                                            <input type="text" class="form-control form-control-sm" placeholder="Detalle obligatorio" x-show="choice==='no'" x-model="comment" style="width:220px;">
                                            <button class="btn btn-outline-danger btn-sm" @click="setValidation(false)" :disabled="choice!=='no' || !comment.trim()">Guardar</button>
                                            <a class="btn btn-outline-secondary btn-sm" :href="cur().url" target="_blank" rel="noopener" :class="{'disabled': !cur().url}">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Abrir
                                            </a>
                                            <button class="btn btn-outline-secondary btn-sm" @click="next()" :disabled="idx===total()-1">
                                                Siguiente <i class="bi bi-chevron-right ms-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <script>
                    const validarUrl = "{{ route('funcionario.bandeja.validar', $solicitud->id) }}";
                    function postValid(key, ok, motivo){
                        try {
                            fetch(validarUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
                                },
                                body: new URLSearchParams({ key, ok: ok?1:0, motivo: motivo||'' })
                            }).catch(()=>{});
                        } catch(e) {}
                    }
                    document.addEventListener('alpine:init', () => {
                        Alpine.data('docUI', (docs) => ({
                            list: Array.isArray(docs) ? docs : [],
                            idx: 0,
                            groupActive: 0,
                            choice: 'pending',
                            total(){ return this.list.length; },
                            cur(){ return this.list[this.idx] || {}; },
                            init(){ try { window.docUI = this; } catch(e){} },
                            syncChoice(){
                                const s = this.cur().state || 'pending';
                                this.choice = s === 'valid' ? 'si' : (s === 'invalid' ? 'no' : 'pending');
                                if (s !== 'invalid') this.comment = '';
                            },
                            next(){ if (this.idx < this.total()-1) { this.idx++; this.syncChoice && this.syncChoice(); } },
                            prev(){ if (this.idx > 0) { this.idx--; this.syncChoice && this.syncChoice(); } },
                            gotoGroup(g){
                                this.groupActive = g;
                                // buscar el primer índice del grupo g dentro del aplanado
                                let label = document.querySelectorAll('.border-bottom .small.fw-semibold')[g]?.innerText;
                                let i = this.list.findIndex(it => it.label === label);
                                if (i >= 0) { this.idx = i; this.syncChoice && this.syncChoice(); }
                            },
                            groupAny(g){
                                let label = document.querySelectorAll('.border-bottom .small.fw-semibold')[g]?.innerText;
                                return this.list.some(it => it.label === label);
                            },
                            groupState(g){
                                let label = document.querySelectorAll('.border-bottom .small.fw-semibold')[g]?.innerText;
                                const items = this.list.filter(it => it.label === label);
                                if (!items.length) return 'pending';
                                if (items.some(it => it.state === 'invalid')) return 'invalid';
                                if (items.every(it => it.state === 'valid')) return 'valid';
                                return 'pending';
                            },
                            stateIcon(s){
                                return s === 'valid' ? 'bi-check-circle text-success'
                                     : s === 'invalid' ? 'bi-x-circle text-danger'
                                     : 'bi-hourglass-split text-warning';
                            },
                            groupIcon(g){ return this.stateIcon(this.groupState(g)); },
                            comment: '',
                            setValidation(ok){
                                const c = this.cur();
                                if (!c || !c.key) return;
                                if (!ok) {
                                    if (!this.comment || !this.comment.trim()) { return; }
                                }
                                c.state = ok ? 'valid' : 'invalid';
                                this.choice = ok ? 'si' : 'no';
                                postValid(c.key, !!ok, !ok ? (this.comment||'') : '');
                            }
                        }));
                    });
                </script>
                <script>
                    async function flushValidationsAndSubmit(form){
                        try {
                            const tasks = [];
                            const validarUrl = "{{ route('funcionario.bandeja.validar', $solicitud->id) }}";
                            const csrf = '{{ csrf_token() }}';
                            const ui = window.docUI;
                            if (ui && Array.isArray(ui.list)){
                                ui.list.forEach(it => {
                                    if (!it || !it.key) return;
                                    const st = it.state || 'pending';
                                    if (st === 'valid' || st === 'invalid'){
                                        const ok = st === 'valid' ? 1 : 0;
                                        const motivo = st === 'invalid' ? (ui.comment || '') : '';
                                        const body = new URLSearchParams({ key: it.key, ok: ok, motivo: motivo });
                                        tasks.push(fetch(validarUrl, { method:'POST', headers:{'X-CSRF-TOKEN': csrf}, body }).catch(()=>{}));
                                    }
                                });
                            }
                            // Secciones
                            document.querySelectorAll("input[id^='sec'][id$='_si']").forEach(si => {
                                const i = si.id.replace('sec','').replace('_si','');
                                const no = document.getElementById(`sec${i}_no`);
                                let okVal = null, motivo='';
                                if (si.checked) okVal = 1; else if (no && no.checked) okVal = 0;
                                if (okVal !== null){
                                    if (okVal === 0){
                                        const input = document.getElementById(`sec${i}_motivo`);
                                        motivo = (input && input.value) ? input.value.trim() : '';
                                        if (!motivo){ try { input && input.focus(); } catch(e){} alert('Ingrese el detalle del motivo para la sección'); throw new Error('missing motivo'); }
                                    }
                                    const body = new URLSearchParams({ key: `sec${i}`, ok: okVal, motivo: motivo });
                                    tasks.push(fetch(validarUrl, { method:'POST', headers:{'X-CSRF-TOKEN': csrf}, body }).catch(()=>{}));
                                }
                            });
                            await Promise.allSettled(tasks);
                        } catch(e) { return false; }
                        try { form.submit(); } catch(e){}
                        return false;
                    }
                </script>
            </div>
        </div>

        {{-- ===== Secciones (derecha) ===== --}}
        <div class="col-md-7">
            {{-- Tabs --}}
            <ul class="nav nav-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-datos" type="button" role="tab">Datos del solicitante</button>
                </li>
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
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-notas" type="button" role="tab">Notas/actuaciones</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-mensajes" type="button" role="tab">Mensajes</button>
                </li>
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
                        if (is_array($v)) return count($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : '–';
                        if ($v === null || $v === '') return '–';
                        return (string)$v;
                    };
                    // Estados guardados de secciones para prefijar radios desde JS
                    $secStates = [];
                    $secReasons = [];
                    try {
                        $__metaAll = is_array($solicitud->respuestas_json ?? null)
                            ? $solicitud->respuestas_json
                            : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
                        foreach ((array)($__metaAll['_funcionario']['validaciones'] ?? []) as $k => $v) {
                            if (preg_match('/^sec(\d+)$/', (string)$k, $m)) {
                                $secStates[$m[1]] = !empty($v['ok']) ? 'si' : 'no';
                                $secReasons[$m[1]] = (string)($v['motivo'] ?? '');
                            }
                        }
                    } catch (\Throwable $e) { $secStates = []; }
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
                                                                $secureUrl = $p ? route('funcionario.bandeja.file', [$solicitud->id, $fieldKey, $ix]) : null;
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
                                <div class="mt-3 p-2 border-top">
                                    <div class="small mb-2">Validar esta sección:</div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sec{{ $i }}_ok" id="sec{{ $i }}_si" onclick="postValid('sec{{ $i }}', 1)">
                                        <label class="form-check-label" for="sec{{ $i }}_si">Sí</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="sec{{ $i }}_ok" id="sec{{ $i }}_no" onclick="postValid('sec{{ $i }}', 0)">
                                        <label class="form-check-label" for="sec{{ $i }}_no">No</label>
                                    </div>
                                    <div class="mt-2 d-none" id="sec{{ $i }}_motivo_wrap">
                                        <input type="text" class="form-control form-control-sm mb-2" id="sec{{ $i }}_motivo" placeholder="Detalle obligatorio">
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                onclick="(function(){var v=document.getElementById('sec{{ $i }}_motivo').value.trim(); if(!v){ alert('Ingrese el detalle'); return;} postValid('sec{{ $i }}',0,v); var prev=document.getElementById('sec{{ $i }}_motivo_prev'); if(prev){ prev.classList.remove('d-none'); prev.innerText='Último motivo guardado: '+v; } })()">Guardar</button>
                                        @php $prev = (string)($secReasons[$i] ?? ''); @endphp
                                        <div id="sec{{ $i }}_motivo_prev" class="small text-muted @if($prev==='') d-none @endif">@if($prev!=='') Último motivo guardado: {{ $prev }} @endif</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Datos del solicitante --}}
                <div class="tab-pane fade" id="pane-datos" role="tabpanel">
                    @php
                        // Preferir la relación; si no coincide con la FK, forzar por FK
                        $u = $solicitud->usuario;
                        try {
                            if (!$u || ($solicitud->usuario_id && (string)($u->id ?? '') !== (string)$solicitud->usuario_id)) {
                                $u = \App\Models\Usuario::query()->where('id', $solicitud->usuario_id)->first();
                            }
                        } catch (\Throwable $e) {}
                    @endphp
                    <div class="card shadow-sm mb-4">
                        <div class="card-header text-white" style="background:#0f265c;">Datos del solicitante</div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Nombre</label>
                                    <input class="form-control" value="{{ $u->name ?? '-' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Email</label>
                                    <input class="form-control" value="{{ $u->email ?? '-' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">ID Usuario</label>
                                    <input class="form-control" value="{{ $u->id ?? '-' }}" disabled>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small text-muted">Creado</label>
                                    <input class="form-control" value="{{ optional($u->created_at)->format('d/m/Y H:i') ?? '-' }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Notas/actuaciones --}}
                <div class="tab-pane fade" id="pane-notas" role="tabpanel">
                    @php
                        $meta = is_array($solicitud->respuestas_json ?? null) ? $solicitud->respuestas_json : (json_decode($solicitud->respuestas_json ?? '[]', true) ?: []);
                        $notas = (array)($meta['_funcionario']['notas'] ?? []);
                    @endphp
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Lista de notas/actuaciones</h6>
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalNota">Nueva nota/actuación</button>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body">
                            @if(!count($notas))
                                <div class="text-muted">No hay notas/actuaciones</div>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($notas as $n)
                                        <li class="mb-2">
                                            <div class="fw-semibold">{{ $n['titulo'] ?? 'Nota' }} <span class="text-muted small">{{ $n['created_at'] ?? '' }}</span></div>
                                            <div class="text-muted">{{ $n['descripcion'] ?? '' }}</div>
                                            @php $a = $n['archivo'] ?? null; @endphp
                                            @if(is_array($a) && !empty($a['url']))
                                                <a href="{{ $a['url'] }}" target="_blank" rel="noopener">{{ $a['name'] ?? basename($a['path'] ?? '') }}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Mensajes --}}
                <div class="tab-pane fade" id="pane-mensajes" role="tabpanel">
                    @php $msgs = (array)($meta['_mensajes'] ?? []); @endphp
                    <div class="card shadow-sm mb-3">
                        <div class="card-body" style="max-height:260px; overflow:auto;">
                            @if(!count($msgs))
                                <div class="text-muted">Actualmente no hay ningún mensaje</div>
                            @else
                                <ul class="list-unstyled mb-0">
                                    @foreach($msgs as $m)
                                        <li class="mb-2">
                                            <div class="small text-muted">{{ ucfirst($m['from'] ?? 'funcionario') }} • {{ $m['created_at'] ?? '' }}</div>
                                            <div>{{ $m['body'] ?? '' }}</div>
                                            @if(!empty($m['require_reply']))
                                                <div class="small text-primary">Solicita respuesta</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('funcionario.bandeja.mensaje.store', $solicitud->id) }}">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Mensaje</label>
                            <textarea name="mensaje" class="form-control" rows="4" placeholder="Escribir mensaje..."></textarea>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" class="form-check-input" id="reqReply" name="solicitar_respuesta" value="1">
                            <label class="form-check-label" for="reqReply">Solicitar al ciudadano que responda este mensaje</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>

                @if(empty($sections))
                    <div class="alert alert-warning">
                        Este trámite no tiene un formulario configurado (sin secciones).
                    </div>
                @endif
            </div>
        </div>
    </div>
    <script>
        // Prefija estado de radios por sección y muestra el campo de motivo cuando corresponde
        (function(){
            try {
                const states = @json($secStates);
                function showMotivo(i, show){
                    const wrap = document.getElementById(`sec${i}_motivo_wrap`);
                    if (wrap) wrap.classList.toggle('d-none', !show);
                }
                Object.entries(states||{}).forEach(([i, st]) => {
                    const si = document.getElementById(`sec${i}_si`);
                    const no = document.getElementById(`sec${i}_no`);
                    if (st==='si' && si) si.checked = true;
                    if (st==='no' && no) { no.checked = true; showMotivo(i, true); }
                });
                document.querySelectorAll("input[id^='sec'][id$='_no']").forEach(el => {
                    el.addEventListener('change', e => {
                        const i = e.target.id.replace('sec','').replace('_no','');
                        showMotivo(i, e.target.checked);
                    });
                });
                document.querySelectorAll("input[id^='sec'][id$='_si']").forEach(el => {
                    el.addEventListener('change', e => {
                        const i = e.target.id.replace('sec','').replace('_si','');
                        showMotivo(i, false);
                        const prev = document.getElementById(`sec${i}_motivo_prev`);
                        if (prev) prev.classList.add('d-none');
                    });
                });
            } catch(e){}
        })();
    </script>
</div>
@endsection

{{-- Modal Nota/Actuación --}}
<div class="modal fade" id="modalNota" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background:#0f265c; color:#fff;">
        <h5 class="modal-title">Nueva nota/actuación</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form method="POST" action="{{ route('funcionario.bandeja.nota.store', $solicitud->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nombre de la nota/actuación *</label>
            <input type="text" name="titulo" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Descripción *</label>
            <textarea name="descripcion" class="form-control" rows="4" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Adjuntar archivo</label>
            <input type="file" name="archivo" class="form-control">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
      </form>
    </div>
  </div>
  </div>
