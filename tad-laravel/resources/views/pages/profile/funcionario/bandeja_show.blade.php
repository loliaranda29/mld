@extends('layouts.app-funcionario')

@section('title', 'Detalle de solicitud')

@section('content')
<div class="container-fluid px-3 py-2">

    {{-- ====== TOP ACTIONBAR (como la plataforma objetivo) ====== --}}
    <div class="d-flex align-items-center gap-2 sticky-top bg-white py-2" style="z-index: 5; border-bottom:1px solid #e9ecef;">
        <div class="ms-auto d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" disabled>Prevenir al solicitante</button>
            <form method="POST" action="{{ route('bandeja.etapas.rechazar', $solicitud->id) }}">
                @csrf
                <button class="btn btn-sm btn-danger">Rechazar trámite</button>
            </form>
            <form method="POST" action="{{ route('bandeja.etapas.aceptar', $solicitud->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" disabled>Aceptar etapa</button>
            </form>
            <button class="btn btn-sm btn-outline-secondary" disabled>Guardar</button>
            <form method="POST" action="{{ route('funcionario.bandeja.normalize', $solicitud->id) }}">
                @csrf
                <button class="btn btn-sm btn-outline-primary" title="Mover adjuntos temporales a definitivos y rehidratar el schema">Normalizar adjuntos</button>
            </form>
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success my-2">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info my-2">{{ session('info') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger my-2">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
            </ul>
        </div>
    @endif

    {{-- ENCABEZADO (folio, trámite, fecha) --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="text-muted small">Folio/Prefolio del Expediente</div>
                <h5 class="mb-2">{{ $solicitud->expediente ?? '—' }}</h5>

                <div class="text-muted small">Trámite</div>
                <div class="fw-semibold mb-2">{{ $tramite->nombre ?? '—' }}</div>

                <div class="text-muted small">Fecha de recepción de solicitud</div>
                <div class="fw-semibold">{{ optional($solicitud->created_at)->format('d/m/Y H:i') }}</div>
            </div>

            <div class="ms-auto" style="min-width:280px;max-width:360px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Estatus</span>
                    <span class="badge bg-secondary">{{ Str::upper($solicitud->estado ?? '—') }}</span>
                </div>

                {{-- Área de descargas + adjuntar salida (placeholder) --}}
                <div class="input-group input-group-sm mb-2">
                    <span class="input-group-text">Área de descargas</span>
                    <input class="form-control" placeholder="Ningún archivo seleccionado" disabled>
                </div>
                <form method="POST" action="{{ route('bandeja.adjuntos.agregar', $solicitud->id) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="input-group input-group-sm">
                        <input type="file" name="archivo" class="form-control">
                        <button class="btn btn-outline-secondary">Adjuntar documentos de salida</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CUERPO EN 2 COLUMNAS --}}
    <div class="row g-3">
        {{-- IZQUIERDA: VISOR DOCUMENTOS “tal cual” --}}
        <div class="col-12 col-xl-4 col-lg-5">
            @include('pages.profile.funcionario.partials.documentos_viewer', [
                'solicitudId' => $solicitud->id,
                'documentos'  => $documentos ?? [],
            ])
        </div>

        {{-- DERECHA: TABS DE DATOS --}}
        <div class="col-12 col-xl-8 col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-datos" type="button">Datos del solicitante</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-sol" type="button">Solicitante</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-resp" type="button">Responsable Autorizado</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-inicio" type="button">Inicio de Trámite</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-docs" type="button">Documentos</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-notas" type="button">Notas/actuaciones</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-msg" type="button">Mensajes</button></li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="pane-datos">
                            <dl class="row mb-0">
                                <dt class="col-sm-3">Ciudadano</dt>
                                <dd class="col-sm-9">{{ $solicitud->usuario->nombre ?? $solicitud->usuario->name ?? '—' }}</dd>
                                <dt class="col-sm-3">Email</dt>
                                <dd class="col-sm-9">{{ $solicitud->usuario->email ?? '—' }}</dd>
                                <dt class="col-sm-3">CUIL/CUIT</dt>
                                <dd class="col-sm-9">
                                    @php
                                        $cuil = $solicitud->usuario->cuil
                                            ?? $solicitud->usuario->CUIL
                                            ?? $solicitud->usuario->cuit
                                            ?? $solicitud->usuario->CUIT
                                            ?? null;
                                    @endphp
                                    {{ $cuil ?? '—' }}
                                </dd>
                            </dl>
                        </div>

                        <div class="tab-pane fade" id="pane-sol">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections, 'only' => ['Solicitante'], 'answers' => $answers ?? []
                            ])
                        </div>

                        <div class="tab-pane fade" id="pane-resp">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections, 'only' => ['Responsable Autorizado'], 'answers' => $answers ?? []
                            ])
                        </div>

                        <div class="tab-pane fade" id="pane-inicio">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections, 'only' => ['Inicio de Trámite','Inicio del trámite']
                            ])
                        </div>

                        <div class="tab-pane fade" id="pane-docs">
                            @if(empty($documentos))
                                <div class="text-muted">No hay documentos.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead><tr><th>Campo</th><th>Archivo</th><th>Tipo</th><th class="text-end">Acción</th></tr></thead>
                                        <tbody>
                                        @foreach($documentos as $i => $d)
                                            @php
                                                $fallback = route('funcionario.bandeja.file', [$solicitud->id, ($d['campo'] ?? $i), 0]);
                                                $finalUrl = $d['url'] ?? $fallback;
                                            @endphp
                                            <tr>
                                                <td><code>{{ $d['campo'] ?? '—' }}</code></td>
                                                <td>{{ $d['name'] ?? 'Archivo' }}</td>
                                                <td>{{ $d['mime'] ?? '—' }}</td>
                                                <td class="text-end">
                                                    <a class="btn btn-outline-secondary btn-sm"
                                                       href="{{ $finalUrl }}" target="_blank" rel="noopener">Abrir</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        <div class="tab-pane fade" id="pane-notas">
                            <div class="alert alert-secondary">Próximamente: registro de actuaciones y bitácora.</div>
                        </div>

                        <div class="tab-pane fade" id="pane-msg">
                            <div class="alert alert-secondary">Próximamente: mensajería interna con el ciudadano.</div>
                        </div>
                    </div>
                </div>

                <div class="card-footer bg-white">
                    @php
                        $p = ($totalEtapas ?? 0) > 0 ? round((($etapaActual ?? 1)/max(1,$totalEtapas))*100) : 0;
                    @endphp
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Etapa actual: <strong>{{ $etapaActual ?? 1 }}</strong> / {{ $totalEtapas ?? 1 }}</div>
                        <div class="progress" style="width:220px;height:6px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $p }}%"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- MODAL ZOOM/VIEW --}}
<div class="modal fade" id="visorDocumentoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="visorTitulo">Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="visorWrapper"></div>
      </div>
      <div class="modal-footer">
        <a id="visorAbrirBtn" href="#" target="_blank" class="btn btn-outline-secondary">
          <i class="bi bi-box-arrow-up-right me-1"></i> Abrir en pestaña
        </a>
        <button class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
@endsection

@push('styles')
<style>
/* Paleta y detalles para “clonado” */
.doc-head { background:#0a2653; color:#fff; }
.doc-pill { background:#0a2653; color:#fff; border-radius:12px; padding:2px 8px; font-size:.8rem; }
.doc-thumb { width:64px; height:64px; object-fit:cover; border-radius:6px; border:1px solid #e9ecef; cursor:pointer; }
.doc-thumb.active { outline:2px solid #0d6efd; }
#docThumbs { overflow-x:auto; white-space:nowrap; }
</style>
@endpush

@push('scripts')
{{-- El parcial añade su JS; aquí solo el modal genérico --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('visorDocumentoModal');
  if (!modal) return;
  modal.addEventListener('show.bs.modal', ev => {
    const btn = ev.relatedTarget;
    const url  = btn?.dataset.docUrl || '';
    const name = btn?.dataset.docName || 'Documento';
    const mime = (btn?.dataset.docMime || '').toLowerCase();
    document.getElementById('visorTitulo').textContent = name;
    const wrap = document.getElementById('visorWrapper');
    wrap.innerHTML = '';
    let el;
    if (mime.startsWith('image/')) {
      el = document.createElement('img'); el.src=url; el.className='img-fluid rounded border'; el.alt=name;
    } else if (mime==='application/pdf') {
      el = document.createElement('div'); el.className='ratio ratio-16x9 border rounded';
      el.innerHTML = `<iframe src="${url}" style="border:0;"></iframe>`;
    } else {
      el = document.createElement('p'); el.innerHTML = `Archivo: <a href="${url}" target="_blank" rel="noopener">${name}</a>`;
    }
    wrap.appendChild(el);
    document.getElementById('visorAbrirBtn').href = url;
  });
});
</script>
@endpush
