@php
    // Intenta decodificar JSON hasta 3 veces (por si quedó doble/triple-escapado)
    // Aplica el fix recursivo sobre arrays/objetos


    // 1) Tomamos lo que venga del modelo
    $raw      = $tramite->formulario_json ?? null;

  
    // 4) Fallback seguro
    if (!isset($formInit['sections']) || !is_array($formInit['sections'])) {
        $formInit = ['sections' => [ ['name' => 'Inicio del trámite', 'fields' => []] ]];
    }

    // 5) Catálogos para el builder (si el controlador no los envió, intentamos cargarlos aquí)
    try {
        $__cats = $catalogosBuilder ?? \App\Models\Catalogo::with(['items' => function($q){
            $q->where('activo', 1)->orderBy('orden')->orderBy('nombre');
        }])->get(['id','nombre','slug'])->map(function($c){
            return [
                'id'     => $c->id,
                'nombre' => $c->nombre,
                'slug'   => $c->slug,
                'items'  => $c->items->pluck('nombre')->values()->all(),
            ];
        })->all();
    } catch (\Throwable $e) {
        $__cats = [];
    }

    // 6) Enviamos al front en base64 para evitar problemas de comillas/entidades
    $initialB64   = base64_encode(json_encode($formInit, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
    $catalogsB64  = base64_encode(json_encode($__cats,  JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
@endphp

<div
  x-data="formBuilder(
            JSON.parse(atob($el.dataset.initial || 'e30=')),
            JSON.parse(atob($el.dataset.catalogs || 'e30='))
          )"
  x-init="init()"
  data-initial="{{ $initialB64 }}"
  data-catalogs="{{ $catalogsB64 }}"
  class="row"
>

  <!-- Panel izquierdo -->
  <div class="col-md-4">
    <div class="card mb-3">
      <div class="card-header bg-dark text-white">
        <i class="bi bi-ui-checks-grid"></i> Constructor de formulario
      </div>
      <div class="card-body">
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="dividirPorSecciones" x-model="useSections" @change="updateHidden()">
          <label class="form-check-label" for="dividirPorSecciones">Dividido por secciones</label>
        </div>
        <div class="form-check mb-2">
          <input class="form-check-input" type="checkbox" id="dividirPorPasos" x-model="useSteps" @change="updateHidden()">
          <label class="form-check-label" for="dividirPorPasos">Dividido por pasos</label>
        </div>

        <div class="d-grid gap-2 mb-3">
          <button class="btn btn-outline-success btn-sm" type="button" @click="addSection">+ Agregar Sección</button>
        </div>

        <div id="componentsPanel">
          <template x-for="(item, index) in components" :key="index">
            <div class="btn btn-outline-secondary btn-sm mb-2 me-2"
                 x-text="item.label"
                 draggable="true"
                 @dragstart="handleDragStart($event, item)">
            </div>
          </template>
        </div>
      </div>
    </div>
  </div>

  <!-- Panel derecho -->
  <div class="col-md-8">
    <div class="card">
      <div class="card-header bg-primary text-white">
        <i class="bi bi-list-columns-reverse"></i> Formulario
      </div>
      <div class="card-body">
        <template x-if="$data.state && $data.state.sections && $data.state.sections.length">
          <div id="fieldContainer">
            <template x-for="(section, sIndex) in state.sections" :key="sIndex">
              <div class="mb-3 border rounded p-2" @dragover.prevent @drop="handleDrop($event, sIndex)">
                <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                  <input class="form-control form-control-sm me-2" style="min-width:220px" x-model="section.name" @input="updateHidden()" />
                  <div class="d-flex align-items-center gap-3">
                    <div class="form-check me-2">
                      <input class="form-check-input" type="checkbox" :id="'repeatable_' + sIndex" x-model="section.repeatable" @change="updateHidden()">
                      <label class="form-check-label" :for="'repeatable_' + sIndex">Repetible</label>
                    </div>

                    <div class="form-check me-2">
                      <input class="form-check-input" type="checkbox" :id="'activable_' + sIndex" x-model="section.activable" @change="updateHidden()">
                      <label class="form-check-label" :for="'activable_' + sIndex">Activable durante el trámite</label>
                    </div>

                    <button class="btn btn-sm btn-outline-danger" type="button" @click="removeSection(sIndex)">Eliminar sección</button>
                  </div>
                </div>

                <template x-for="(field, index) in section.fields" :key="index">
                  <div class="list-group-item d-flex justify-content-between align-items-center" style="cursor: grab;">
                    <span x-text="field.label"></span>
                    <div>
                      <button class="btn btn-sm btn-outline-primary me-1" type="button" @click="editField(sIndex, index)">Editar</button>
                      <button class="btn btn-sm btn-outline-danger" type="button" @click="removeField(sIndex, index)">Eliminar</button>
                    </div>
                  </div>
                </template>

                <div class="text-center text-muted mt-2" style="font-size: 0.85em;">Arrastrá aquí campos para agregarlos</div>
              </div>
            </template>
          </div>
        </template>

        <template x-if="!$data.state || !$data.state.sections || !$data.state.sections.length">
          <p class="text-muted">No hay campos aún. Agregá uno desde la izquierda.</p>
        </template>

        <div class="mt-3">
          <label for="jsonOutput" class="form-label">Vista JSON del formulario</label>
          <textarea id="jsonOutput" class="form-control" rows="10" readonly x-text="JSON.stringify(state, null, 2)"></textarea>
        </div>

        <!-- Diagrama -->
        <div class="mt-3">
          <label class="form-label">Vista flujo del formulario (diagrama)</label>
          <div id="flowCanvas"
               class="bg-light border rounded"
               style="height: 420px; min-height: 420px;"
               x-init="renderFlow()"></div>
          <small class="text-muted d-block mt-2">
            <strong>Doble clic</strong> en un campo para editar. <strong>Clic derecho</strong> (o <kbd>Supr</kbd>) para eliminar. Zoom con la rueda; arrastrar para mover.
          </small>
        </div>

        <!-- Secciones activables -->
        <div class="mt-4">
          <label class="form-label">Secciones activables (plantillas para requerimientos)</label>
          <ul class="list-group">
            <template x-for="(s, idx) in (state.sections || []).filter(x => x.activable)" :key="'act-'+idx">
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><i class="bi bi-plug me-1"></i> <span x-text="s.name"></span></span>
                <span class="badge bg-secondary">Plantilla activable</span>
              </li>
            </template>
            <template x-if="!(state.sections || []).some(x => x.activable)">
              <li class="list-group-item text-muted">No hay secciones activables definidas.</li>
            </template>
          </ul>
          <small class="text-muted d-block mt-1">Estas secciones no se muestran en el diagrama inicial y podrán habilitarse durante el trámite para pedir info/documentación adicional.</small>
        </div>
      </div>
    </div>
  </div>

  {{-- Modal edición --}}
  <div class="modal fade" id="editModal" tabindex="-1" x-ref="modal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content" x-show="selectedField !== null" x-transition>
        <div class="modal-header">
          <h5 class="modal-title">Editar campo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="selectedField = null"></button>
        </div>

        <div class="modal-body" x-show="selectedField !== null">
          <template x-if="selectedField !== null">
            <div>
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Etiqueta</label>
                  <input type="text" class="form-control"
                         x-model="state.sections[selectedSection].fields[selectedField].label"
                         @input="updateHidden()">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nombre interno</label>
                  <input type="text" class="form-control"
                         x-model="state.sections[selectedSection].fields[selectedField].name"
                         @input="updateHidden()">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-6">
                  <label class="form-label">Validación</label>
                  <select class="form-select"
                          x-model="state.sections[selectedSection].fields[selectedField].validation"
                          @change="updateHidden()">
                    <option value="none">Sin validación</option>
                    <option value="required">Obligatorio</option>
                    <option value="email">Email</option>
                    <option value="number">Número</option>
                  </select>
                </div>
                <div class="col-md-6 d-flex align-items-end gap-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           x-model="state.sections[selectedSection].fields[selectedField].required"
                           @change="updateHidden()" id="isRequired">
                    <label class="form-check-label" for="isRequired">Requerido</label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox"
                           x-model="state.sections[selectedSection].fields[selectedField].certificado"
                           @change="updateHidden()" id="inCert">
                    <label class="form-check-label" for="inCert">Aparece en certificado</label>
                  </div>
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label class="form-label">Deriva a sección (general)</label>
                  <select class="form-select"
                          :key="'gen-'+selectedSection+'-'+selectedField"  <!-- clave para rehidratación -->
                          x-model="state.sections[selectedSection].fields[selectedField].condition"
                          @change="updateHidden()">
                    <option value="">(sin derivación)</option>
                    <template x-for="(sec, i) in state.sections" :key="i">
                      <option :value="sec.name" x-text="sec.name"></option>
                    </template>
                    <!-- <<< nuevo: ir a confirmar -->
                    <option value="__CONFIRM__">Ir a Confirmar (fin)</option>
                  </select>
                </div>
              </div>

              <!-- Opciones + reglas por opción -->
              <template x-if="['select','radio','checkbox'].includes(state.sections[selectedSection].fields[selectedField].type)">
                <div class="mt-3">
                  <!-- <<< nuevo: usar catálogo -->
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox"
                           id="useCatalog"
                           x-model="state.sections[selectedSection].fields[selectedField].useCatalog"
                           @change="onToggleUseCatalog()">
                    <label class="form-check-label" for="useCatalog">Usar catálogo</label>
                  </div>

                  <div class="mb-2" x-show="state.sections[selectedSection].fields[selectedField].useCatalog">
                    <label class="form-label">Catálogo</label>
                    <select class="form-select"
                            :key="'cat-'+selectedSection+'-'+selectedField"  <!-- fuerza hidratación -->
                            x-model="state.sections[selectedSection].fields[selectedField].catalogSlug"
                            @change="applyCatalogOptions()">
                      <option value="">— Seleccionar —</option>
                      <template x-for="c in catalogs" :key="c.slug">
                        <option :value="c.slug" x-text="c.nombre"></option>
                      </template>
                    </select>
                    <small class="text-muted">Se guardará la referencia al catálogo y se precargarán sus opciones actuales.</small>

                    <!-- NUEVO: Derivaciones por opción (catálogo) -->
                    <div class="mt-3">
                      <label class="form-label">Derivaciones por opción (catálogo)</label>
                      <template x-for="(opt, i) in catalogOptionsForSelected()" :key="'copt-'+selectedSection+'-'+selectedField+'-'+i">
                        <div class="mb-2 d-flex flex-wrap align-items-center gap-2">
                          <small class="text-muted">Si elige</small>
                          <span class="badge bg-light text-dark" x-text="opt"></span>
                          <small class="text-muted">→ ir a</small>
                          <select class="form-select form-select-sm w-auto"
                                  :key="'cgo-'+selectedSection+'-'+selectedField+'-'+i"
                                  x-model="state.sections[selectedSection].fields[selectedField].conditions[opt]"
                                  @change="updateHidden()">
                            <option value="">(seguir normal)</option>
                            <template x-for="(sec, idx) in state.sections" :key="idx">
                              <option :value="sec.name" x-text="sec.name"></option>
                            </template>
                            <option value="__CONFIRM__">Confirmar (fin de formulario)</option>
                          </select>
                        </div>
                      </template>
                    </div>
                  </div>

                  <!-- Manual de opciones (se oculta si hay catálogo) -->
                  <div x-show="!state.sections[selectedSection].fields[selectedField].useCatalog">
                    <label class="form-label">Opciones</label>
                    <template x-for="(opt, i) in state.sections[selectedSection].fields[selectedField].options" :key="'mopt-'+i">
                      <div class="mb-2">
                        <div class="d-flex gap-2 align-items-center">
                          <input class="form-control"
                                 @focus="$event.target.dataset.prev = state.sections[selectedSection].fields[selectedField].options[i]"
                                 @input="handleOptionEdit(i, $event)"
                                 x-model="state.sections[selectedSection].fields[selectedField].options[i]">
                          <button class="btn btn-outline-danger btn-sm"
                                  @click.prevent="
                                    state.sections[selectedSection].fields[selectedField].conditions &&
                                    delete state.sections[selectedSection].fields[selectedField].conditions[opt];
                                    state.sections[selectedSection].fields[selectedField].options.splice(i,1);
                                    updateHidden();
                                  ">×</button>
                        </div>

                        <div class="d-flex gap-2 align-items-center mt-1"
                             x-data="{
                               get f(){ return state.sections[selectedSection].fields[selectedField] },
                               get map(){
                                 if (!this.f.conditions || Array.isArray(this.f.conditions)) this.f.conditions = {};
                                 return this.f.conditions;
                               },
                               get key(){ return String(opt ?? '').trim() },
                               get dest(){ return this.map[this.key] ?? '' },
                               set dest(v){ this.map[this.key] = v; updateHidden(); }
                             }">
                          <small class="text-muted">Si elige</small>
                          <span class="badge bg-light text-dark" x-text="opt || '—'"></span>
                          <small class="text-muted">→ ir a</small>
                          <select class="form-select form-select-sm w-auto"
                                  :key="'mgo-'+selectedSection+'-'+selectedField+'-'+i"
                                  x-model="dest"
                                  @change="updateHidden()">
                            <option value="">(seguir normal)</option>
                            <template x-for="(sec, idx) in state.sections" :key="idx">
                              <option :value="sec.name" x-text="sec.name"></option>
                            </template>
                            <option value="__CONFIRM__">Confirmar (fin de formulario)</option>
                          </select>
                        </div>

                      </div>
                    </template>
                    <button class="btn btn-outline-success btn-sm"
                            @click.prevent="state.sections[selectedSection].fields[selectedField].options.push(''); updateHidden()">+ Agregar opción</button>
                  </div>
                </div>
              </template>

              <!-- Archivo -->
              <template x-if="state.sections[selectedSection].fields[selectedField].type === 'file'">
                <div class="mt-3">
                  <label class="form-label">Tamaño máximo (MB)</label>
                  <input type="number" class="form-control"
                         x-model.number="state.sections[selectedSection].fields[selectedField].maxSize"
                         @input="updateHidden()">
                  <label class="form-label mt-2">Tipos aceptados (separados por coma)</label>
                  <input type="text" class="form-control"
                         x-model="state.sections[selectedSection].fields[selectedField].accept"
                         @input="updateHidden()">
                  <div class="form-check mt-2">
                    <input class="form-check-input" type="checkbox"
                           x-model="state.sections[selectedSection].fields[selectedField].multiple"
                           @change="updateHidden()" id="fileMultiple">
                    <label class="form-check-label" for="fileMultiple">Permitir múltiples archivos</label>
                  </div>
                </div>
              </template>

              <!-- API -->
              <template x-if="state.sections[selectedSection].fields[selectedField].type === 'api'">
                <div class="mt-3">
                  <label class="form-label">Método</label>
                  <select class="form-select"
                          x-model="state.sections[selectedSection].fields[selectedField].apiMethod"
                          @change="updateHidden()">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                  </select>
                  <label class="form-label mt-2">URL</label>
                  <input class="form-control"
                         x-model="state.sections[selectedSection].fields[selectedField].apiUrl"
                         @input="updateHidden()">
                  <label class="form-label mt-2">Headers (JSON)</label>
                  <textarea class="form-control" rows="3"
                            x-model="state.sections[selectedSection].fields[selectedField].apiHeaders"
                            @input="updateHidden()"></textarea>
                </div>
              </template>

              <!-- Richtext -->
              <template x-if="state.sections[selectedSection].fields[selectedField].type === 'richtext'">
                <div class="mt-3">
                  <label class="form-label">Contenido enriquecido</label>
                  <div id="editorjs" class="border rounded p-2" style="min-height:220px;"></div>
                </div>
              </template>
            </div>
          </template>
        </div>

        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cerrar</button>
          <button class="btn btn-primary" type="button"
                  @click="selectedField = null; updateHidden()" data-bs-dismiss="modal">Guardar</button>
        </div>
      </div>
    </div>
  </div>

  {{-- Hidden que envía el JSON al back --}}
  <input type="hidden" name="formulario_json" x-ref="formularioJson" :value="JSON.stringify(state)">
</div>

<!-- Trix -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.umd.min.js"></script>

<!-- EditorJS -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@2.27.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@2.6.2"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@1.9.0"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/image@2.8.1"></script>
<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@2.5.3"></script>

<!-- Diagrama -->
<script src="https://unpkg.com/cytoscape@3.26.0/dist/cytoscape.min.js"></script>
<script src="https://unpkg.com/dagre@0.8.5/dist/dagre.min.js"></script>
<script src="https://unpkg.com/cytoscape-dagre@2.5.0/cytoscape-dagre.js"></script>

<script>
  // Registrar extensión dagre si es necesario
  try { if (window.cytoscape && window.cytoscapeDagre) { cytoscape.use(window.cytoscapeDagre); } } catch(e) {}

  // --- JS helpers para reparar mojibake en el cliente ---
  function jsMaybeFixUtf(s){
    try {
      if (typeof s === 'string' && /[ÃÂâÊÎÔÛ]/.test(s)) {
        // Latin1 -> UTF-8 (hack clásico)
        return decodeURIComponent(escape(s));
      }
    } catch(e) {}
    return s;
  }
  function fixStateEncodingDeep(v){
    if (Array.isArray(v)) return v.map(fixStateEncodingDeep);
    if (v && typeof v === 'object') {
      const o = {};
      for (const k in v) {
        const nk = typeof k === 'string' ? jsMaybeFixUtf(k) : k;
        o[nk] = fixStateEncodingDeep(v[k]);
      }
      return o;
    }
    if (typeof v === 'string') return jsMaybeFixUtf(v);
    return v;
  }

  function formBuilder(initialRaw, catalogsRaw) {
    try {
      let initial;
      try { initial = typeof initialRaw === 'string' ? JSON.parse(initialRaw) : initialRaw; }
      catch { initial = null; }

      let base = (initial && typeof initial === 'object' && Array.isArray(initial.sections))
        ? initial : { sections: [ { name: 'Inicio del trámite', fields: [] } ] };

      // Repara en cliente si llegó con mojibake
      base = fixStateEncodingDeep(base);

      const catalogs = Array.isArray(catalogsRaw) ? catalogsRaw : [];

      return {
        useSections: true,
        useSteps: true,
        selectedField: null,
        selectedSection: 0,
        editor: null,

        state: base,
        catalogs,

        cy: null,
        _flowTimer: null,
        _lastTap: { t: 0, id: null },
        _selectedNodeId: null,
        _formEl: null,

        components: [
          { type: 'text',     label: 'Respuesta breve',          name: 'respuesta_breve' },
          { type: 'textarea', label: 'Párrafo',                  name: 'parrafo' },
          { type: 'select',   label: 'Lista desplegable',        name: 'lista' },
          { type: 'file',     label: 'Archivo',                  name: 'archivo' },
          { type: 'date',     label: 'Fecha',                    name: 'fecha' },
          { type: 'api',      label: 'Campo API',                name: 'api_field' },
          { type: 'code',     label: 'Código personalizado',     name: 'codigo' },
          { type: 'radio',    label: 'Opción múltiple',          name: 'radio' },
          { type: 'checkbox', label: 'Casillas de verificación', name: 'checkbox' },
          { type: 'search',   label: 'Campo de búsqueda',        name: 'busqueda' },
          { type: 'richtext', label: 'Texto enriquecido',        name: 'richtext' }
        ],

        init() {
          // Escucha envío del formulario para sincronizar justo antes de POST
          this._formEl = this.$root?.closest('form') || document.querySelector('form');
          if (this._formEl) {
            this._formEl.addEventListener('submit', () => this._syncHiddenEverywhere(), { passive: true });
          }

          // --- normaliza condiciones y catálogos cargados desde BD
          this.normalizeState();

          this.updateHidden();
          this.renderFlow();
          const tabBtn = document.getElementById('formulario-tab');
          if (tabBtn) tabBtn.addEventListener('shown.bs.tab', () => this._refit());
          window.addEventListener('resize', () => this._refit());
          const container = document.getElementById('flowCanvas');
          if (container && 'IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries, obs) => {
              if (entries[0]?.isIntersecting) { this._refit(); obs.disconnect(); }
            });
            io.observe(container);
          }
          document.addEventListener('keydown', (e) => {
            if ((e.key === 'Delete' || e.key === 'Backspace') && this._selectedNodeId) {
              this._handleDeleteNode(this._selectedNodeId);
            }
          });
        },

        // --- normaliza el estado para que el UI se hidrate bien
        normalizeState() {
          try {
            for (const sec of (this.state.sections || [])) {
              for (const f of (sec.fields || [])) {
                // conditions siempre objeto
                if (Array.isArray(f.conditions) || !f.conditions) f.conditions = {};

                // catálogo: completar slug desde objeto si faltara
                if (f.useCatalog) {
                  if ((!f.catalogSlug || !String(f.catalogSlug).trim()) && f.catalog?.slug) {
                    f.catalogSlug = f.catalog.slug;
                  }
                  const cat = (this.catalogs || []).find(c => c.slug === f.catalogSlug);
                  if (cat) {
                    f.catalog = { id: cat.id, slug: cat.slug, nombre: cat.nombre };
                    // Opciones visibles del combo (aunque no edites manualmente)
                    f.options = Array.isArray(cat.items) ? [...cat.items] : (Array.isArray(f.options) ? f.options : []);
                  }
                }
              }
            }
          } catch(e) { console.warn('normalizeState fallo', e); }
          this.updateHidden();
        },

        // Devuelve las opciones del catálogo seleccionado
        catalogOptionsForSelected(){
          const f = this.state.sections[this.selectedSection]?.fields[this.selectedField];
          if (!f || !f.useCatalog) return [];
          const cat = (this.catalogs || []).find(c => c.slug === f.catalogSlug);
          return Array.isArray(cat?.items) ? cat.items : [];
        },

        // Sincroniza hidden local y *todos* los hidden del form padre (y corrige encoding antes)
        _syncHiddenEverywhere() {
          const fixed = fixStateEncodingDeep(this.state);
          const payload = JSON.stringify(fixed);
          try {
            document.querySelectorAll('input[name="formulario_json"]').forEach(el => { el.value = payload; });
          } catch(e) {}
          if (this.$refs && this.$refs.formularioJson) this.$refs.formularioJson.value = payload;
          try { window.dispatchEvent(new CustomEvent('mld:formulario-updated', { detail: payload })); } catch(e) {}
        },

        updateHidden() {
          // Sanear en cliente y propagar
          this._syncHiddenEverywhere();
          this.scheduleFlow();
        },

        addSection() {
          (this.state.sections ??= []).push({ name: 'Nueva sección', fields: [], repeatable: false, activable: false });
          this.updateHidden();
        },

        removeSection(index) {
          this.state.sections.splice(index, 1);
          this.updateHidden();
        },

        addFieldToSection(item, sIndex) {
          const field = {
            ...item,
            required: false,
            certificado: false,
            help: '',
            media: '',
            content: '',
            condition: '',
            validation: 'none',
            // Soporte catálogo
            useCatalog: false,
            catalogSlug: '',
            catalog: null
          };
          if (item.type === 'file') {
            field.maxSize  = 5;
            field.accept   = 'image/png, image/jpg, application/pdf';
            field.multiple = false;
          }
          if (item.type === 'api') {
            field.apiUrl     = '';
            field.apiMethod  = 'GET';
            field.apiHeaders = '{}';
          }
          if (['select','radio','checkbox'].includes(item.type)) {
            field.options = [];
            field.conditions = {};
          }
          (this.state.sections[sIndex].fields ??= []).push(field);
          this.updateHidden();
        },

        removeField(sectionIndex, index) {
          this.state.sections[sectionIndex].fields.splice(index, 1);
          this.updateHidden();
        },

        editField(sectionIndex, index) {
          this.selectedSection = sectionIndex;
          this.selectedField   = index;

          // Asegura estado consistente antes de pintar el modal
          this.normalizeState();

          this.$nextTick(() => {
            const field = this.state.sections[sectionIndex].fields[index];
            if (field.type === 'richtext') {
              const holder = document.getElementById('editorjs');
              if (holder) holder.innerHTML = '';
              if (this.editor && this.editor.destroy) this.editor.destroy();

              this.editor = new EditorJS({
                holder: 'editorjs',
                autofocus: true,
                tools: { header: Header, list: List, embed: Embed, image: { class: ImageTool } },
                data: safeParse(field.content),
                onChange: async () => {
                  const output = await this.editor.save();
                  this.state.sections[this.selectedSection].fields[this.selectedField].content = JSON.stringify(output);
                  this.updateHidden();
                }
              });
            }
            this.openModal();
          });
        },

        onToggleUseCatalog() {
          const f = this.state.sections[this.selectedSection].fields[this.selectedField];
          if (f.useCatalog) {
            if ((!f.catalogSlug || !String(f.catalogSlug).trim()) && f.catalog?.slug) {
              f.catalogSlug = f.catalog.slug;
            }
            this.applyCatalogOptions();
          }
          this.updateHidden();
        },

        applyCatalogOptions() {
          const f = this.state.sections[this.selectedSection].fields[this.selectedField];
          if (!f) return;
          if (!f.useCatalog || !f.catalogSlug) return this.updateHidden();

          const cat = (this.catalogs || []).find(c => c.slug === f.catalogSlug);
          if (!cat) return this.updateHidden();

          f.catalog  = { id: cat.id, slug: cat.slug, nombre: cat.nombre };
          f.options  = Array.isArray(cat.items) ? [...cat.items] : [];
          this.updateHidden();
        },

        // Remapea la clave en conditions cuando cambia el texto de la opción
        handleOptionEdit(i, ev) {
          const f = this.state.sections[this.selectedSection].fields[this.selectedField];
          const prev = ev.target.dataset.prev ?? '';
          const now  = f.options[i] ?? '';
          if (prev && prev !== now && f.conditions && Object.prototype.hasOwnProperty.call(f.conditions, prev)) {
            f.conditions[now] = f.conditions[prev];
            delete f.conditions[prev];
          }
          ev.target.dataset.prev = now;
          this.updateHidden();
        },

        // *** HELPERS para leer/escribir condiciones de una opción ***
        getCond(opt) {
          const f = this.state.sections[this.selectedSection]?.fields[this.selectedField];
          if (!f) return '';
          const key = String(opt ?? '').trim();
          if (!key) return '';
          return (f.conditions && Object.prototype.hasOwnProperty.call(f.conditions, key))
            ? (f.conditions[key] ?? '')
            : '';
        },
        setCond(opt, val) {
          const f = this.state.sections[this.selectedSection]?.fields[this.selectedField];
          if (!f) return;
          const key = String(opt ?? '').trim();
          if (!key) return;
          if (!f.conditions) f.conditions = {};
          const v = String(val ?? '');
          if (!v) { delete f.conditions[key]; }
          else { f.conditions[key] = v; }
          this.updateHidden();
        },

        openModal() {
          const el = this.$refs?.modal;
          if (!el) return;
          if (!window.bootstrap || !window.bootstrap.Modal) {
            el.classList.add('show'); el.style.display = 'block';
            el.removeAttribute('aria-hidden'); el.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open'); return;
          }
          let inst = (typeof window.bootstrap.Modal.getOrCreateInstance === 'function')
            ? window.bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static' })
            : (window.bootstrap.Modal.getInstance(el) || new window.bootstrap.Modal(el, { backdrop: 'static' }));
          inst.show();
        },

        handleDragStart(event, item) {
          event.dataTransfer.setData('application/json', JSON.stringify(item));
        },

        handleDrop(event, sIndex) {
          const item = JSON.parse(event.dataTransfer.getData('application/json'));
          this.addFieldToSection(item, sIndex);
          this.updateHidden();
        },

        // Diagrama
        scheduleFlow() {
          clearTimeout(this._flowTimer);
          this._flowTimer = setTimeout(() => this.renderFlow(), 200);
        },

        renderFlow() {
          const container = document.getElementById('flowCanvas');
          if (!container) return;

          const elements = this._buildFlowElementsForDiagram();

          if (!this.cy) {
            this.cy = cytoscape({
              container, elements, wheelSensitivity: 0.2, boxSelectionEnabled: false, autoungrabify: true,
              style: [
                { selector: 'node.section', style: { 'shape':'round-rectangle','background-color':'#cfd8dc','border-width':1,'border-color':'#90a4ae','label':'data(label)','font-size':12,'text-valign':'center','text-halign':'center','color':'#0f172a','text-outline-width':0,'text-wrap':'wrap','text-max-width':220,'width':'label','height':'label','padding':'12px' } },
                { selector: 'node.input',   style: { 'shape':'round-rectangle','background-color':'#60a5fa','border-width':0,'label':'data(label)','color':'#0f172a','text-outline-width':0,'font-weight':600,'text-valign':'center','text-halign':'center','text-wrap':'wrap','text-max-width':220,'width':'label','height':'label','padding':'10px' } },
                { selector: 'node.choice',  style: { 'shape':'round-rectangle','background-color':'#22c55e','label':'data(label)','color':'#0f172a','text-outline-width':0,'font-weight':600,'text-valign':'center','text-halign':'center','text-wrap':'wrap','text-max-width':220,'width':'label','height':'label','padding':'10px' } },
                { selector: 'node.special', style: { 'shape':'round-rectangle','background-color':'#f59e0b','label':'data(label)','color':'#0f172a','font-weight':700,'text-valign':'center','text-halign':'center','text-wrap':'wrap','text-max-width':240,'width':'label','height':'label','padding':'12px' } },
                { selector: 'edge.flow',    style: { 'width':2,'line-color':'#94a3b8','target-arrow-color':'#94a3b8','target-arrow-shape':'triangle','curve-style':'bezier' } },
                { selector: 'edge.cond',    style: { 'width':2,'line-color':'#ef4444','target-arrow-color':'#ef4444','target-arrow-shape':'triangle','line-style':'dashed','curve-style':'bezier','label':'data(label)','font-size':10,'color':'#0f172a','text-background-color':'#fff','text-background-opacity':0.7,'text-background-padding':2 } },
              ]
            });

            this.cy.on('tap', 'node', (evt) => {
              const id = evt.target.id();
              this._selectedNodeId = id;
              const now = Date.now();
              if (this._lastTap && this._lastTap.id === id && (now - this._lastTap.t) < 350) { this._handleDblClickNode(id); this._lastTap = { t: 0, id: null }; }
              else { this._lastTap = { t: now, id }; }
            });

            this.cy.on('tap', (evt) => { if (evt.target === this.cy) this._selectedNodeId = null; });
            this.cy.on('cxttap', 'node', (evt) => { this._handleDeleteNode(evt.target.id()); });

          } else {
            this.cy.json({ elements });
          }

          // Layout dagre con separación mayor
          const layout = this.cy.layout({
            name: (typeof cytoscape !== 'undefined' && cytoscape.prototype && cytoscape.prototype.layout && window.cytoscapeDagre) ? 'dagre' : 'breadthfirst',
            nodeSep: 90,
            rankSep: 140,
            edgeSep: 24,
            rankDir: 'TB',
            spacingFactor: 0.9,
            animate: false,
            padding: 32
          });
          layout.run();
          this._refit();
        },

        _refit() {
          try { if (!this.cy) return; this.cy.resize(); this.cy.fit(this.cy.elements(), 48); this.cy.center(); }
          catch (e) { console.warn('refit falló', e); }
        },

        _handleDblClickNode(id) {
          if (!id.startsWith('f-')) return;
          const parts = id.split('-'); const sIdx = parseInt(parts[1], 10); const fIdx = parseInt(parts[2], 10);
          if (Number.isInteger(sIdx) && Number.isInteger(fIdx)) this.editField(sIdx, fIdx);
        },

        _handleDeleteNode(id) {
          if (!id.startsWith('f-')) return;
          const parts = id.split('-'); const sIdx = parseInt(parts[1], 10); const fIdx = parseInt(parts[2], 10);
          if (!Number.isInteger(sIdx) || !Number.isInteger(fIdx)) return;
          if (confirm('¿Eliminar este campo del formulario?')) { this.removeField(sIdx, fIdx); this.scheduleFlow(); }
        },

        // Construcción de elementos (incluye "Confirmar" si hay reglas que lo apunten)
        _buildFlowElementsForDiagram() {
          const els = [];
          const all = (this.state && Array.isArray(this.state.sections)) ? this.state.sections : [];
          const sections = all.filter(s => !s.activable);

          const idxByName = new Map();
          sections.forEach((s, i) => idxByName.set((s.name || '').trim(), i));

          let usesConfirm = false;

          sections.forEach((s, sIdx) => {
            els.push({ data: { id: `sec-${sIdx}`, label: s.name || `Sección ${sIdx+1}` }, classes: 'section' });
            const fields = Array.isArray(s.fields) ? s.fields : [];
            let prevId = `sec-${sIdx}`;
            fields.forEach((f, fIdx) => {
              const id = `f-${sIdx}-${fIdx}`;
              const label = f.label || f.name || `Campo ${fIdx+1}`;
              const type = (f.type || '').toLowerCase();
              let klass = 'input';
              if (['select','radio','checkbox','date'].includes(type)) klass = 'choice';
              if (['file','api','code','richtext'].includes(type))   klass = 'special';
              els.push({ data: { id, label }, classes: klass });
              els.push({ data: { id: `e-${prevId}-${id}`, source: prevId, target: id }, classes: 'flow' });
              prevId = id;

              if (f.condition) {
                if (f.condition === '__CONFIRM__') {
                  usesConfirm = true;
                  els.push({ data: { id: `cg-${id}-confirm`, source: id, target: `confirm`, label: '' }, classes: 'cond' });
                } else {
                  const toIdx = idxByName.get((f.condition || '').trim());
                  if (typeof toIdx === 'number') {
                    els.push({ data: { id: `cg-${id}-sec-${toIdx}`, source: id, target: `sec-${toIdx}`, label: '' }, classes: 'cond' });
                  }
                }
              }
              if (f.conditions && typeof f.conditions === 'object') {
                for (const [opt, secName] of Object.entries(f.conditions)) {
                  if (secName === '__CONFIRM__') {
                    usesConfirm = true;
                    els.push({ data: { id: `c-${id}-confirm-${opt}`, source: id, target: `confirm`, label: String(opt) }, classes: 'cond' });
                  } else {
                    const toIdx = idxByName.get((secName || '').trim());
                    if (typeof toIdx === 'number') {
                      els.push({ data: { id: `c-${id}-sec-${toIdx}-${opt}`, source: id, target: `sec-${toIdx}`, label: String(opt) }, classes: 'cond' });
                    }
                  }
                }
              }
            });
          });

          if (usesConfirm) {
            els.push({ data: { id: 'confirm', label: 'Confirmar' }, classes: 'special' });
          }

          return els;
        }
      };

    } catch (e) {
      console.error('formBuilder init error', e, { initialRaw });
      return { useSections: true, useSteps: true, selectedField: null, selectedSection: 0, editor: null, state: { sections: [] }, components: [], init(){}, updateHidden(){}, addSection(){}, removeSection(){}, addFieldToSection(){}, removeField(){}, editField(){}, handleDragStart(){}, handleDrop(){} };
    }
  }

  function safeParse(maybeJson) {
    if (!maybeJson) return {};
    try { return JSON.parse(maybeJson); } catch { return {}; }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
