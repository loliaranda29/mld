@extends('layouts.app-funcionario')

@section('title', 'Detalle de solicitud')

@section('content')
<div class="container-fluid py-3">
    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <strong>Hay errores:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Header --}}
    <div class="card shadow-sm mb-3">
        <div class="card-body d-flex flex-wrap justify-content-between align-items-start gap-3">
            <div>
                <div class="text-muted small">Folio/Prefolio del Expediente</div>
                <h4 class="mb-2">
                    {{ $solicitud->expediente ?? '—' }}
                </h4>
                <div class="text-muted small">Trámite</div>
                <div class="fw-semibold mb-2">{{ $tramite->nombre ?? '—' }}</div>

                <div class="text-muted small">Fecha de recepción de solicitud</div>
                <div class="fw-semibold">
                    {{ optional($solicitud->created_at)->format('d/m/Y H:ih') }}
                </div>
            </div>

            <div class="ms-auto" style="min-width:280px;max-width:360px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small">Estatus</span>
                    <span class="badge bg-secondary">{{ Str::upper($solicitud->estado ?? '—') }}</span>
                </div>

                {{-- Botonera acciones --}}
                <div class="d-grid gap-2">
                    {{-- Ver historial (si luego creamos vista) --}}
                    <a href="#"
                       class="btn btn-outline-secondary btn-sm disabled"
                       aria-disabled="true">Ver historial</a>

                    {{-- Historial de asignación (placeholder) --}}
                    <a href="#"
                       class="btn btn-outline-secondary btn-sm disabled"
                       aria-disabled="true">Historial de asignación</a>

                    {{-- Asignación de etapas (abre modal) --}}
                    @if(isset($funcionarios) && count($funcionarios))
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAsignar">
                        Asignación de etapas
                    </button>
                    @endif

                    {{-- Aceptar etapa --}}
                    <form method="POST" action="{{ route('bandeja.etapas.aceptar', $solicitud->id) }}">
                        @csrf
                        <button class="btn btn-success btn-sm w-100">Aceptar etapa</button>
                    </form>

                    {{-- Guardar (placeholder UI) --}}
                    <button class="btn btn-outline-secondary btn-sm w-100" disabled>Guardar</button>

                    {{-- Rechazar trámite (abre modal) --}}
                    <button class="btn btn-outline-danger btn-sm w-100" data-bs-toggle="modal" data-bs-target="#modalRechazar">
                        Rechazar trámite
                    </button>

                    {{-- Área de descargas (placeholder) --}}
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Área de descargas</span>
                        <input class="form-control" value="" placeholder="Ningún archivo seleccionado" disabled>
                    </div>

                    {{-- Adjuntar documentos de salida --}}
                    <form method="POST" action="{{ route('bandeja.adjuntos.agregar', $solicitud->id) }}" enctype="multipart/form-data" class="mt-1">
                        @csrf
                        <div class="input-group input-group-sm">
                            <input type="file" name="archivo" class="form-control">
                            <button class="btn btn-outline-secondary" type="submit">Adjuntar documentos de salida</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Body: 2 columnas --}}
    <div class="row">
        {{-- Col izquierda: Documentos --}}
        <div class="col-12 col-lg-4 col-xl-3 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-folder2-open me-2"></i>
                        <strong>Documentos</strong>
                    </div>
                    <span class="badge bg-secondary">{{ count($documentos ?? []) }}</span>
                </div>
                <div class="card-body">
                    @php
                        use Illuminate\Support\Str;
                    @endphp

                    @if(empty($documentos))
                        <div class="text-muted small">No hay documentos adjuntos para esta solicitud.</div>
                    @else
                        @foreach($documentos as $doc)
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <div class="fw-semibold">{{ $doc['name'] ?? 'Archivo' }}</div>
                                        <div class="text-muted small">
                                            Campo: <code>{{ $doc['campo'] ?? '—' }}</code>
                                            @if(!empty($doc['mime'])) — {{ $doc['mime'] }} @endif
                                        </div>
                                    </div>
                                    @php
                                        $fallbackUrl = null;
                                        if (empty($doc['url'])) {
                                            try { $fallbackUrl = route('funcionario.bandeja.file', [$solicitud->id, ($doc['campo'] ?? 0), 0]); } catch (\Throwable $e) { $fallbackUrl = null; }
                                        }
                                        $finalUrl = $doc['url'] ?? $fallbackUrl;
                                    @endphp
                                    @if(!empty($finalUrl))
                                        <div class="btn-group">
                                            <a class="btn btn-sm btn-outline-secondary" href="{{ $finalUrl }}" target="_blank" rel="noopener">
                                                <i class="bi bi-box-arrow-up-right me-1"></i> Abrir
                                            </a>
                                            <button type="button"
                                                    class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#visorDocumentoModal"
                                                    data-doc-url="{{ $finalUrl }}"
                                                    data-doc-name="{{ $doc['name'] ?? 'Documento' }}"
                                                    data-doc-mime="{{ $doc['mime'] ?? '' }}">
                                                <i class="bi bi-zoom-in"></i>
                                            </button>
                                        </div>
                                    @endif
                                </div>

                                @php $mime = $doc['mime'] ?? ''; @endphp

                                {{-- Imágenes inline --}}
                                @if($mime && Str::startsWith($mime, 'image/') && !empty($finalUrl))
                                    <img src="{{ $finalUrl }}" class="img-fluid rounded border" alt="{{ $doc['name'] ?? 'imagen' }}">

                                {{-- PDF embebido --}}
                                @elseif($mime === 'application/pdf' && !empty($finalUrl))
                                    <div class="ratio ratio-4x3 border rounded">
                                        <iframe src="{{ $finalUrl }}" title="PDF" style="border:0;"></iframe>
                                    </div>

                                {{-- Otros tipos: link o fallback seguro --}}
                                @else
                                    @if(!empty($finalUrl))
                                        <a href="{{ $finalUrl }}" target="_blank" rel="noopener">
                                            <i class="bi bi-file-earmark-text me-1"></i> Ver/Descargar
                                        </a>
                                    @else
                                        <span class="text-muted">Archivo no disponible</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Col derecha: Tabs de datos --}}
        <div class="col-12 col-lg-8 col-xl-9">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <ul class="nav nav-tabs card-header-tabs" id="tabsSolicitud" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="tab-datos" data-bs-toggle="tab" data-bs-target="#pane-datos" type="button" role="tab">Datos del solicitante</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-sol" data-bs-toggle="tab" data-bs-target="#pane-sol" type="button" role="tab">Solicitante</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-resp" data-bs-toggle="tab" data-bs-target="#pane-resp" type="button" role="tab">Responsable Autorizado</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-inicio" data-bs-toggle="tab" data-bs-target="#pane-inicio" type="button" role="tab">Inicio de Trámite</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-docs" data-bs-toggle="tab" data-bs-target="#pane-docs" type="button" role="tab">Documentos</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-notas" data-bs-toggle="tab" data-bs-target="#pane-notas" type="button" role="tab">Notas/actuaciones</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="tab-msg" data-bs-toggle="tab" data-bs-target="#pane-msg" type="button" role="tab">Mensajes</button>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content">
                        {{-- Pestaña: Datos del solicitante (básico del usuario) --}}
                        <div class="tab-pane fade show active" id="pane-datos" role="tabpanel" aria-labelledby="tab-datos">
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

                        {{-- Pestaña: Solicitante (render genérico por secciones) --}}
                        <div class="tab-pane fade" id="pane-sol" role="tabpanel" aria-labelledby="tab-sol">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections,
                                'only'     => ['Solicitante'],
                                'answers'  => $answers ?? []
                            ])
                        </div>

                        {{-- Pestaña: Responsable Autorizado --}}
                        <div class="tab-pane fade" id="pane-resp" role="tabpanel" aria-labelledby="tab-resp">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections,
                                'only'     => ['Responsable Autorizado'],
                                'answers'  => $answers ?? []
                            ])
                        </div>

                        {{-- Pestaña: Inicio de Trámite --}}
                        <div class="tab-pane fade" id="pane-inicio" role="tabpanel" aria-labelledby="tab-inicio">
                            @includeWhen(isset($sections), 'pages.profile.funcionario.partials.sections', [
                                'sections' => $sections,
                                'only'     => ['Inicio de Trámite','Inicio del trámite']
                            ])
                        </div>

                        {{-- Pestaña: Documentos (lista también en tabla) --}}
                        <div class="tab-pane fade" id="pane-docs" role="tabpanel" aria-labelledby="tab-docs">
                            @if(empty($documentos))
                                <div class="text-muted">No hay documentos.</div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-sm align-middle">
                                        <thead>
                                            <tr>
                                                <th>Campo</th>
                                                <th>Archivo</th>
                                                <th>Tipo</th>
                                                <th class="text-end">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($documentos as $d)
                                                <tr>
                                                    <td><code>{{ $d['campo'] ?? '—' }}</code></td>
                                                    <td>{{ $d['name'] ?? 'Archivo' }}</td>
                                                    <td>{{ $d['mime'] ?? '—' }}</td>
                                                    <td class="text-end">
                                                        @php
                                                            $fallbackUrl = null;
                                                            if (empty($d['url'])) {
                                                                try { $fallbackUrl = route('funcionario.bandeja.file', [$solicitud->id, ($d['campo'] ?? 0), 0]); } catch (\Throwable $e) { $fallbackUrl = null; }
                                                            }
                                                            $finalUrl = $d['url'] ?? $fallbackUrl;
                                                        @endphp
                                                        @if(!empty($finalUrl))
                                                            <div class="btn-group">
                                                                <a class="btn btn-outline-secondary btn-sm" href="{{ $finalUrl }}" target="_blank" rel="noopener">Abrir</a>
                                                                <button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#visorDocumentoModal"
                                                                        data-doc-url="{{ $finalUrl }}"
                                                                        data-doc-name="{{ $d['name'] ?? 'Documento' }}"
                                                                        data-doc-mime="{{ $d['mime'] ?? '' }}">
                                                                    <i class="bi bi-zoom-in"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>

                        {{-- Pestaña: Notas/actuaciones (placeholder) --}}
                        <div class="tab-pane fade" id="pane-notas" role="tabpanel" aria-labelledby="tab-notas">
                            <div class="alert alert-secondary">Próximamente: registro de actuaciones y bitácora.</div>
                        </div>

                        {{-- Pestaña: Mensajes (placeholder) --}}
                        <div class="tab-pane fade" id="pane-msg" role="tabpanel" aria-labelledby="tab-msg">
                            <div class="alert alert-secondary">Próximamente: mensajería interna con el ciudadano.</div>
                        </div>
                    </div>
                </div>

                {{-- Footer: progreso de etapas (simple) --}}
                <div class="card-footer bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>Etapa actual: <strong>{{ $etapaActual ?? 1 }}</strong> / {{ $totalEtapas ?? 1 }}</div>
                        <div class="progress" style="width: 220px; height: 6px;">
                            @php
                                $p = ($totalEtapas ?? 0) > 0 ? round( ( ($etapaActual ?? 1) / max(1,$totalEtapas) ) * 100 ) : 0;
                            @endphp
                            <div class="progress-bar" role="progressbar" style="width: {{ $p }}%" aria-valuenow="{{ $p }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: Rechazar --}}
    <div class="modal fade" id="modalRechazar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('bandeja.etapas.rechazar', $solicitud->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Rechazar trámite</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Motivo</label>
                        <textarea name="motivo" class="form-control" rows="4" placeholder="Explique el motivo del rechazo"></textarea>
                    </div>
                    <div class="alert alert-warning mb-0">
                        Esta acción devolverá la solicitud a la etapa anterior (si existe) y marcará el estado como <strong>observado</strong>.
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-danger" type="submit">Rechazar</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL: Asignar operadores --}}
    @if(isset($funcionarios) && count($funcionarios))
    <div class="modal fade" id="modalAsignar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" method="POST" action="{{ route('bandeja.etapas.asignar', $solicitud->id) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Asignar operadores</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Seleccioná uno o más operadores</label>
                    <select class="form-select" name="operadores[]" multiple size="8">
                        @foreach($funcionarios as $op)
                            <option value="{{ $op->id }}">
                                {{ $op->nombre ?? $op->name ?? ('ID '.$op->id) }}
                                @if(!empty($op->email)) — {{ $op->email }} @endif
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-2">Tip: mantené <kbd>Ctrl</kbd> (o <kbd>Cmd</kbd>) para selección múltiple.</small>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Asignar</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('visorDocumentoModal');
  if (!modal) return;
  modal.addEventListener('show.bs.modal', function (event) {
    const btn = event.relatedTarget;
    if (!btn) return;
    const url  = btn.getAttribute('data-doc-url') || '';
    const name = btn.getAttribute('data-doc-name') || 'Documento';
    const mime = (btn.getAttribute('data-doc-mime') || '').toLowerCase();

    const title = document.getElementById('visorTitulo');
    const wrapper = document.getElementById('visorWrapper');
    const abrirBtn = document.getElementById('visorAbrirBtn');

    if (title) title.textContent = name;
    if (wrapper) {
      wrapper.innerHTML = '';
      let el;
      if (mime.startsWith('image/')) {
        el = document.createElement('img');
        el.src = url; el.className = 'img-fluid rounded border'; el.alt = name;
      } else if (mime === 'application/pdf') {
        el = document.createElement('div');
        el.className = 'ratio ratio-16x9 border rounded';
        el.innerHTML = `<iframe src="${url}" title="PDF" style="border:0;"></iframe>`;
      } else {
        el = document.createElement('p');
        el.innerHTML = `Archivo: <a href="${url}" target="_blank" rel="noopener">${name}</a>`;
      }
      wrapper.appendChild(el);
    }
    if (abrirBtn) abrirBtn.href = url;
  });
});
</script>
@endpush

{{-- MODAL: Visor de documento (se apoya en @push('scripts') arriba) --}}
<div class="modal fade" id="visorDocumentoModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="visorTitulo">Documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <div id="visorWrapper"></div>
        <hr>
        <div class="d-flex align-items-center gap-3">
          <span class="fw-semibold">Validar</span>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="validarOpc" id="validarSi">
            <label class="form-check-label" for="validarSi">Sí</label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="validarOpc" id="validarNo">
            <label class="form-check-label" for="validarNo">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <a id="visorAbrirBtn" href="#" target="_blank" class="btn btn-outline-secondary">
          <i class="bi bi-box-arrow-up-right me-1"></i> Abrir en pestaña
        </a>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
