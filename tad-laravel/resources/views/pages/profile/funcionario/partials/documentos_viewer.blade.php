@php
    use Illuminate\Support\Str;
    $docs = $documentos ?? [];
@endphp

<div class="card shadow-sm h-100">
    <div class="card-header doc-head d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <i class="bi bi-folder2-open me-2"></i>
            <strong>Documentos</strong>
        </div>
        <span class="badge bg-light text-dark" id="docCounter">1/{{ max(1, count($docs)) }}</span>
    </div>

    <div class="card-body">
        @if(empty($docs))
            <div class="text-muted small">No hay documentos adjuntos para esta solicitud.</div>
        @else
            {{-- Barra título + flechas como en el diseño objetivo --}}
            <div class="d-flex align-items-center justify-content-between mb-2">
                <button id="docPrev" type="button" class="btn btn-link p-0 text-decoration-none">
                    <i class="bi bi-chevron-left" style="font-size:1.2rem;"></i>
                </button>
                <div class="flex-grow-1 text-truncate text-center px-2">
                    <span id="docTitle" class="fw-semibold">{{ $docs[0]['name'] ?? 'Documento' }}</span>
                </div>
                <button id="docNext" type="button" class="btn btn-link p-0 text-decoration-none">
                    <i class="bi bi-chevron-right" style="font-size:1.2rem;"></i>
                </button>
            </div>

            <div class="d-flex justify-content-end mb-2">
                <span id="docRequired" class="small text-muted">Requerido</span>
            </div>

            {{-- Visor principal --}}
            @php
                $first = $docs[0] ?? null;
                $fallback = $first ? route('funcionario.bandeja.file', [$solicitudId, ($first['campo'] ?? 0), 0]) : null;
                $firstUrl = $first['url'] ?? $fallback;
            @endphp

            <div id="docViewer" class="mb-3">
                @if($first && Str::startsWith(($first['mime'] ?? ''), 'image/') && $firstUrl)
                    <img id="docImg" src="{{ $firstUrl }}" class="img-fluid rounded border w-100" alt="{{ $first['name'] ?? 'imagen' }}">
                @elseif($first && ($first['mime'] ?? '') === 'application/pdf' && $firstUrl)
                    <div id="docPdfWrapper" class="ratio ratio-4x3 border rounded">
                        <iframe id="docPdf" src="{{ $firstUrl }}" title="PDF" style="border:0;"></iframe>
                    </div>
                @else
                    <div id="docOther" class="border rounded p-3">
                        @if($firstUrl)
                            <a id="docLink" href="{{ $firstUrl }}" target="_blank" rel="noopener">
                                <i class="bi bi-file-earmark-text me-1"></i> Ver/Descargar
                            </a>
                        @else
                            <span class="text-muted">Archivo no disponible</span>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Validación --}}
            <div class="mb-3">
                <div class="text-muted mb-1">Validar</div>
                <div class="d-flex align-items-center gap-3">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="docValid" id="validSi">
                        <label class="form-check-label" for="validSi">Sí</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="docValid" id="validNo">
                        <label class="form-check-label" for="validNo">No</label>
                    </div>
                </div>
            </div>

            {{-- Thumbnails (scroll horizontal) --}}
            <div id="docThumbs" class="d-flex gap-2">
                @foreach($docs as $i => $d)
                    @php
                        $f = $d['url'] ?? route('funcionario.bandeja.file', [$solicitudId, ($d['campo'] ?? $i), 0]);
                        $isImg = Str::startsWith(($d['mime'] ?? ''), 'image/');
                    @endphp
                    <img class="doc-thumb {{ $i===0?'active':'' }}" data-idx="{{ $i }}" data-url="{{ $f }}" data-name="{{ $d['name'] ?? 'Documento' }}" data-mime="{{ $d['mime'] ?? '' }}"
                         src="{{ $isImg ? $f : asset('images/file-icon.png') }}" alt="thumb {{ $i }}">
                @endforeach
            </div>

            {{-- Botón de zoom flotante --}}
            <div class="d-flex justify-content-end mt-2">
                <button type="button" class="btn btn-outline-primary"
                        id="zoomBtn"
                        data-bs-toggle="modal"
                        data-bs-target="#visorDocumentoModal"
                        data-doc-url="{{ $firstUrl }}"
                        data-doc-name="{{ $first['name'] ?? 'Documento' }}"
                        data-doc-mime="{{ $first['mime'] ?? '' }}">
                    <i class="bi bi-zoom-in"></i> Ampliar
                </button>
            </div>

            {{-- Datos para JS --}}
            <script>
                (function(){
                  const DOCS = @json($docs);
                  const solicitudId = @json($solicitudId);
                  const isImg = (m)=> (m||'').toLowerCase().startsWith('image/');
                  const isPdf = (m)=> (m||'').toLowerCase()==='application/pdf';

                  let idx = 0;

                  const elCounter = document.getElementById('docCounter');
                  const elTitle   = document.getElementById('docTitle');
                  const elViewer  = document.getElementById('docViewer');
                  const elPrev    = document.getElementById('docPrev');
                  const elNext    = document.getElementById('docNext');
                  const elThumbs  = document.getElementById('docThumbs');
                  const elZoom    = document.getElementById('zoomBtn');

                  function buildUrl(d, i){
                    if (d.url && d.url.length) return d.url;
                    return "{{ route('funcionario.bandeja.file', ['__ID__','__FIELD__',0]) }}"
                             .replace('__ID__', solicitudId)
                             .replace('__FIELD__', (d.campo ?? i));
                  }

                  function setActiveThumb(){
                    document.querySelectorAll('.doc-thumb').forEach(t=>t.classList.remove('active'));
                    const t = document.querySelector(`.doc-thumb[data-idx="${idx}"]`);
                    if (t) t.classList.add('active');
                  }

                  function render(){
                    const d = DOCS[idx] || {};
                    const url = buildUrl(d, idx);
                    elCounter.textContent = (idx+1)+'/'+DOCS.length;
                    elTitle.textContent   = d.name || 'Documento';

                    elViewer.innerHTML = '';
                    if (isImg(d.mime) && url){
                      const img = document.createElement('img');
                      img.src=url; img.className='img-fluid rounded border w-100'; img.alt=d.name||'imagen';
                      elViewer.appendChild(img);
                    } else if (isPdf(d.mime) && url){
                      const wrap = document.createElement('div');
                      wrap.className='ratio ratio-4x3 border rounded';
                      wrap.innerHTML = `<iframe src="${url}" style="border:0;"></iframe>`;
                      elViewer.appendChild(wrap);
                    } else {
                      const box = document.createElement('div');
                      box.className='border rounded p-3';
                      box.innerHTML = url
                        ? `<a href="${url}" target="_blank" rel="noopener"><i class="bi bi-file-earmark-text me-1"></i> Ver/Descargar</a>`
                        : `<span class="text-muted">Archivo no disponible</span>`;
                      elViewer.appendChild(box);
                    }

                    if (elZoom){
                      elZoom.dataset.docUrl  = url || '';
                      elZoom.dataset.docName = d.name || 'Documento';
                      elZoom.dataset.docMime = d.mime || '';
                    }

                    setActiveThumb();
                  }

                  // Navegación
                  elPrev?.addEventListener('click', ()=>{ idx=(idx-1+DOCS.length)%DOCS.length; render(); });
                  elNext?.addEventListener('click', ()=>{ idx=(idx+1)%DOCS.length; render(); });

                  // Atajos teclado
                  document.addEventListener('keydown', (e)=>{
                    if (e.key==='ArrowLeft'){ idx=(idx-1+DOCS.length)%DOCS.length; render(); }
                    if (e.key==='ArrowRight'){ idx=(idx+1)%DOCS.length; render(); }
                  });

                  // Thumbnails
                  elThumbs?.addEventListener('click', (ev)=>{
                    const t = ev.target.closest('.doc-thumb');
                    if (!t) return;
                    idx = parseInt(t.dataset.idx||'0',10);
                    render();
                  });

                  // Swipe (básico)
                  let startX=null;
                  elViewer.addEventListener('touchstart', (e)=> startX = e.touches[0].clientX, {passive:true});
                  elViewer.addEventListener('touchend', (e)=>{
                    if (startX===null) return;
                    const dx = e.changedTouches[0].clientX - startX;
                    if (Math.abs(dx)>40){
                      if (dx<0) idx=(idx+1)%DOCS.length; else idx=(idx-1+DOCS.length)%DOCS.length;
                      render();
                    }
                    startX=null;
                  }, {passive:true});

                  render();
                })();
            </script>
        @endif
    </div>
</div>
