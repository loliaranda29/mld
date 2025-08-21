@php
    // Traer lo que venga (array por cast, o string JSON) y normalizar
    $raw = $tramite->formulario_json ?? null;

    if (is_string($raw)) {
        $formInit = json_decode($raw, true);
    } elseif (is_array($raw)) {
        $formInit = $raw;
    } else {
        $formInit = null;
    }

    // Fallback limpio si no hay datos válidos
    if (!is_array($formInit) || !isset($formInit['sections']) || !is_array($formInit['sections'])) {
        $formInit = ['sections' => [ ['name' => 'Inicio del trámite', 'fields' => []] ]];
    }
@endphp

<div
  x-data="formBuilder($el.dataset.initial || '{}')"
  x-init="init()"
  data-initial='@json($formInit, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)'
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

                    <!-- NUEVO: marcar sección como activable durante el trámite -->
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

        <!-- ====== NUEVO: Flujograma tipo “diagrama” (no elimina lo anterior) ====== -->
        <div class="mt-3">
          <label class="form-label">Vista flujo del formulario (diagrama)</label>
          <div id="flowCanvas"
               class="bg-light border rounded"
               style="height: 420px; min-height: 420px;"
               x-init="renderFlow()"></div>
          <small class="text-muted d-block mt-2">
            Zoom con la rueda, arrastrar para mover. Flechas continuas = orden. Flechas punteadas = derivaciones.
          </small>
        </div>
        <!-- ====== /NUEVO ====== -->

        <!-- ====== NUEVO: listado de secciones activables ====== -->
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
        <!-- ====== /NUEVO ====== -->
      </div>
    </div>
  </div>

  {{-- ========== MODAL DE EDICIÓN ========== --}}
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
                <div class="col-md-6">
                  <label class="form-label">Pista (texto)</label>
                  <input type="text" class="form-control"
                         x-model="state.sections[selectedSection].fields[selectedField].help"
                         @input="updateHidden()">
                </div>
                <div class="col-md-6">
                  <label class="form-label">Pista (imagen o video)</label>
                  <input type="text" class="form-control" placeholder="URL de imagen o video"
                         x-model="state.sections[selectedSection].fields[selectedField].media"
                         @input="updateHidden()">
                </div>
              </div>

              <div class="row g-3 mt-2">
                <div class="col-md-12">
                  <label class="form-label">Deriva a sección (general)</label>
                  <select class="form-select"
                          x-model="state.sections[selectedSection].fields[selectedField].condition"
                          @change="updateHidden()">
                    <option value="">(sin derivación)</option>
                    <template x-for="(sec, i) in state.sections" :key="i">
                      <option :value="sec.name" x-text="sec.name"></option>
                    </template>
                  </select>
                </div>
              </div>

              <!-- Opciones + Reglas por opción (select/radio/checkbox) -->
              <template x-if="['select','radio','checkbox'].includes(state.sections[selectedSection].fields[selectedField].type)">
                <div class="mt-3">
                  <label class="form-label">Opciones</label>
                  <template x-for="(opt, i) in state.sections[selectedSection].fields[selectedField].options" :key="i">
                    <div class="mb-2">
                      <div class="d-flex gap-2 align-items-center">
                        <input class="form-control"
                               x-model="state.sections[selectedSection].fields[selectedField].options[i]"
                               @input="updateHidden()">
                        <button class="btn btn-outline-danger btn-sm"
                                @click.prevent="
                                  state.sections[selectedSection].fields[selectedField].conditions &&
                                  delete state.sections[selectedSection].fields[selectedField].conditions[opt];
                                  state.sections[selectedSection].fields[selectedField].options.splice(i,1);
                                  updateHidden();
                                ">×</button>
                      </div>
                      <div class="d-flex gap-2 align-items-center mt-1">
                        <small class="text-muted">Si elige</small>
                        <span class="badge bg-light text-dark" x-text="opt || '—'"></span>
                        <small class="text-muted">→ ir a</small>
                        <select class="form-select form-select-sm w-auto"
                                x-model="(state.sections[selectedSection].fields[selectedField].conditions || (state.sections[selectedSection].fields[selectedField].conditions = {}))[opt]"
                                @change="updateHidden()">
                          <option value="">(seguir normal)</option>
                          <template x-for="(sec, idx) in state.sections" :key="idx">
                            <option :value="sec.name" x-text="sec.name"></option>
                          </template>
                        </select>
                      </div>
                    </div>
                  </template>
                  <button class="btn btn-outline-success btn-sm"
                          @click.prevent="state.sections[selectedSection].fields[selectedField].options.push(''); updateHidden()">+ Agregar opción</button>
                </div>
              </template>

              <!-- Config de archivo -->
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

              <!-- Config de API -->
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

              <!-- EditorJS para richtext -->
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
  {{-- ========== /MODAL ========== --}}

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

<!-- NUEVO: librería para el diagrama -->
<script src="https://unpkg.com/cytoscape@3.26.0/dist/cytoscape.min.js"></script>

<!-- Layout Dagre (opcional pero recomendado) -->
<script src="https://unpkg.com/dagre@0.8.5/dist/dagre.min.js"></script>
<script src="https://unpkg.com/cytoscape-dagre@2.5.0/cytoscape-dagre.js"></script>


<script>
  function formBuilder(initialRaw) {
    try {
      let initial;
      try {
        initial = typeof initialRaw === 'string' ? JSON.parse(initialRaw) : initialRaw;
      } catch (e) {
        console.warn('JSON inicial inválido, usando base por defecto', e, initialRaw);
        initial = null;
      }

      const base = (initial && typeof initial === 'object' && Array.isArray(initial.sections))
        ? initial
        : { sections: [ { name: 'Inicio del trámite', fields: [] } ] };

      return {
        useSections: true,
        useSteps: true,
        selectedField: null,
        selectedSection: 0,
        editor: null,

        // Estado principal
        state: base,

        // === NUEVO: props para el diagrama ===
        cy: null,
        _flowTimer: null,

        // Paleta de componentes
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
        this.updateHidden();
        this.renderFlow();

        // Cuando se muestra el tab “Formulario”, reencuadrar
        const tabBtn = document.getElementById('formulario-tab');
        if (tabBtn) {
          tabBtn.addEventListener('shown.bs.tab', () => this._refit());
        }

        // Reencuadrar en resize
        window.addEventListener('resize', () => this._refit());

        // Al entrar en el viewport por primera vez, reencuadrar
        const container = document.getElementById('flowCanvas');
        if (container && 'IntersectionObserver' in window) {
          const io = new IntersectionObserver((entries, obs) => {
            if (entries[0]?.isIntersecting) {
              this._refit();
              obs.disconnect();
            }
          });
          io.observe(container);
        }
      },


        updateHidden() {
          if (this.$refs && this.$refs.formularioJson) {
            this.$refs.formularioJson.value = JSON.stringify(this.state);
          }
          this.scheduleFlow(); // <- NUEVO
        },

        addSection() {
          // NUEVO: activable por defecto en false
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
            validation: 'none'
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
            field.conditions = {}; // <- reglas por opción
          }
          (this.state.sections[sIndex].fields ??= []).push(field);
          this.updateHidden();
        },

        removeField(sectionIndex, index) {
          this.state.sections[sectionIndex].fields.splice(index, 1);
          this.updateHidden();
        },

        // === Botón Editar ===
        editField(sectionIndex, index) {
          this.selectedSection = sectionIndex;
          this.selectedField   = index;

          this.$nextTick(() => {
            const field = this.state.sections[sectionIndex].fields[index];

            // Re-inicializar EditorJS solo si el campo es richtext
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

            // Abrir modal
            this.openModal();
          });
        },

        openModal() {
          const el = this.$refs?.modal;
          if (!el) {
            console.warn('Modal ref no encontrado');
            return;
          }

          if (!window.bootstrap || !window.bootstrap.Modal) {
            // Fallback si Bootstrap no está disponible
            el.classList.add('show');
            el.style.display = 'block';
            el.removeAttribute('aria-hidden');
            el.setAttribute('aria-modal', 'true');
            document.body.classList.add('modal-open');
            return;
          }

          let inst;
          if (typeof window.bootstrap.Modal.getOrCreateInstance === 'function') {
            inst = window.bootstrap.Modal.getOrCreateInstance(el, { backdrop: 'static' });
          } else {
            inst = window.bootstrap.Modal.getInstance(el) || new window.bootstrap.Modal(el, { backdrop: 'static' });
          }
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

        // ===== NUEVO: Diagrama =====
        scheduleFlow() {
          clearTimeout(this._flowTimer);
          this._flowTimer = setTimeout(() => this.renderFlow(), 200);
        },

        renderFlow() {
          const container = document.getElementById('flowCanvas');
          if (!container) return;

          // NUEVO: construir elementos filtrando secciones activables
          const elements = this._buildFlowElementsForDiagram();

          if (!this.cy) {
            this.cy = cytoscape({
              container,
              elements,
              wheelSensitivity: 0.2,
              boxSelectionEnabled: false,
              autoungrabify: true,
              style: [
                  // Secciones
                  {
                    selector: 'node.section',
                    style: {
                      'shape': 'round-rectangle',
                      'background-color': '#cfd8dc',
                      'border-width': 1,
                      'border-color': '#90a4ae',
                      'label': 'data(label)',
                      'font-size': 12,
                      'text-valign': 'center',
                      'text-halign': 'center',
                      // ← texto SIEMPRE oscuro
                      'color': '#0f172a',
                      'text-outline-width': 0,
                      'width': 'label',
                      'height': 'label',
                      'padding': '8px 12px'
                    }
                  },
                  // Entradas
                  {
                    selector: 'node.input',
                    style: {
                      'shape': 'round-rectangle',
                      'background-color': '#60a5fa',
                      'border-width': 0,
                      'label': 'data(label)',
                      // ← texto oscuro (antes estaba en blanco)
                      'color': '#0f172a',
                      'text-outline-width': 0,
                      'font-weight': 600,
                      'text-wrap': 'wrap',
                      'text-max-width': 180,
                      'width': 'label',
                      'height': 'label',
                      'padding': '8px 12px'
                    }
                  },
                  // Elección
                  {
                    selector: 'node.choice',
                    style: {
                      'shape': 'round-rectangle',
                      'background-color': '#22c55e',
                      'label': 'data(label)',
                      // ← texto oscuro (antes estaba en blanco)
                      'color': '#0f172a',
                      'text-outline-width': 0,
                      'font-weight': 600,
                      'text-wrap': 'wrap',
                      'text-max-width': 180,
                      'width': 'label',
                      'height': 'label',
                      'padding': '8px 12px'
                    }
                  },
                  // Especiales
                  {
                    selector: 'node.special',
                    style: {
                      'shape': 'round-rectangle',
                      'background-color': '#f59e0b',
                      'label': 'data(label)',
                      // ya era oscuro, dejo el mismo tono
                      'color': '#0f172a',
                      'text-outline-width': 0,
                      'font-weight': 700,
                      'text-wrap': 'wrap',
                      'text-max-width': 220,
                      'width': 'label',
                      'height': 'label',
                      'padding': '8px 12px'
                    }
                  },
                  // Flujo normal
                  {
                    selector: 'edge.flow',
                    style: {
                      'width': 2,
                      'line-color': '#94a3b8',
                      'target-arrow-color': '#94a3b8',
                      'target-arrow-shape': 'triangle',
                      'curve-style': 'bezier'
                    }
                  },
                  // Condiciones/derivaciones
                  {
                    selector: 'edge.cond',
                    style: {
                      'width': 2,
                      'line-color': '#ef4444',
                      'target-arrow-color': '#ef4444',
                      'target-arrow-shape': 'triangle',
                      'line-style': 'dashed',
                      'curve-style': 'bezier',
                      'label': 'data(label)',
                      'font-size': 10,
                      'color': '#0f172a',                 // ← también texto oscuro en las etiquetas de aristas
                      'text-background-color': '#fff',
                      'text-background-opacity': 0.7,
                      'text-background-padding': 2
                    }
                  }
                ]

            });
          } else {
            this.cy.json({ elements });
          }

          const layout = this.cy.layout({
          name: 'breadthfirst',
          directed: true,
          nodeDimensionsIncludeLabels: true,
          padding: 16,           // padding más chico
          spacingFactor: 0.6,    // líneas ~a la mitad
          animate: false,
          roots: this.cy.collection('node.section')
        });
        layout.run();

        // Muy importante: encuadrar y centrar tras posicionar
        this._refit();
        },

        _refit() {
        try {
          if (!this.cy) return;
          // recalcular tamaño por si el contenedor cambió
          this.cy.resize();
          // ajustar zoom para que entre todo, con un padding cómodo
          this.cy.fit(this.cy.elements(), 48);  // padding en px
          // centrar por si quedó con pan residual
          this.cy.center();
        } catch (e) {
          console.warn('refit falló', e);
        }
      },

        // ====== ORIGINAL (sin filtro) ======
        _buildFlowElements() {
          const els = [];
          const sections = (this.state && Array.isArray(this.state.sections)) ? this.state.sections : [];

          const sectionIndexByName = new Map();
          sections.forEach((s, i) => sectionIndexByName.set((s.name || '').trim(), i));

          sections.forEach((s, sIdx) => {
            els.push({
              data: { id: `sec-${sIdx}`, label: s.name || `Sección ${sIdx+1}` },
              classes: 'section'
            });

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

              // Condición general (derivar siempre)
              if (f.condition) {
                const toIdx = sectionIndexByName.get((f.condition || '').trim());
                if (typeof toIdx === 'number') {
                  els.push({
                    data: {
                      id: `cg-${id}-sec-${toIdx}`,
                      source: id,
                      target: `sec-${toIdx}`,
                      label: ''
                    },
                    classes: 'cond'
                  });
                }
              }

              // Condiciones por opción
              if (f.conditions && typeof f.conditions === 'object') {
                for (const [opt, secName] of Object.entries(f.conditions)) {
                  const toIdx = sectionIndexByName.get((secName || '').trim());
                  if (typeof toIdx === 'number') {
                    els.push({
                      data: {
                        id: `c-${id}-sec-${toIdx}-${opt}`,
                        source: id,
                        target: `sec-${toIdx}`,
                        label: String(opt)
                      },
                      classes: 'cond'
                    });
                  }
                }
              }
            });
          });

          return els;
        },

        // ====== NUEVO: versión para el diagrama (excluye activables) ======
        _buildFlowElementsForDiagram() {
          const els = [];
          const all = (this.state && Array.isArray(this.state.sections)) ? this.state.sections : [];

          // solo las NO activables
          const sections = all.filter(s => !s.activable);

          const sectionIndexByName = new Map();
          sections.forEach((s, i) => sectionIndexByName.set((s.name || '').trim(), i));

          sections.forEach((s, sIdx) => {
            els.push({
              data: { id: `sec-${sIdx}`, label: s.name || `Sección ${sIdx+1}` },
              classes: 'section'
            });

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

              // Condición general (derivar siempre)
              if (f.condition) {
                const toIdx = sectionIndexByName.get((f.condition || '').trim());
                if (typeof toIdx === 'number') {
                  els.push({
                    data: {
                      id: `cg-${id}-sec-${toIdx}`,
                      source: id,
                      target: `sec-${toIdx}`,
                      label: ''
                    },
                    classes: 'cond'
                  });
                }
              }

              // Condiciones por opción
              if (f.conditions && typeof f.conditions === 'object') {
                for (const [opt, secName] of Object.entries(f.conditions)) {
                  const toIdx = sectionIndexByName.get((secName || '').trim());
                  if (typeof toIdx === 'number') {
                    els.push({
                      data: {
                        id: `c-${id}-sec-${toIdx}-${opt}`,
                        source: id,
                        target: `sec-${toIdx}`,
                        label: String(opt)
                      },
                      classes: 'cond'
                    });
                  }
                }
              }
            });
          });

          return els;
        }
        // ===== /NUEVO =====
      };

    } catch (e) {
      console.error('formBuilder init error', e, { initialRaw });
      // Fallback seguro para que Alpine no quede sin componente
      return {
        useSections: true,
        useSteps: true,
        selectedField: null,
        selectedSection: 0,
        editor: null,
        state: { sections: [] },
        components: [],
        init(){},
        updateHidden(){},
        addSection(){},
        removeSection(){},
        addFieldToSection(){},
        removeField(){},
        editField(){},
        handleDragStart(){},
        handleDrop(){},
      };
    }
  }

  function safeParse(maybeJson) {
    if (!maybeJson) return {};
    try { return JSON.parse(maybeJson); } catch { return {}; }
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
