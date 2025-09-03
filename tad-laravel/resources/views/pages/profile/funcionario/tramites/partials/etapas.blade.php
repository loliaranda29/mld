<div x-data="gestorEtapas()" x-init="init()" class="row" @keydown.enter.prevent data-initial='@json($etapas ?? [])'>
    <!-- Tipos de etapa -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Tipos de Etapas</div>
            <div class="card-body">
                <template x-for="tipo in tiposEtapa" :key="tipo.codigo">
                    <button type="button" class="btn btn-outline-primary w-100 mb-2 text-start"
                            @click.prevent="agregarEtapa(tipo)">
                        <i :class="tipo.icono" class="me-1"></i>
                        <span x-text="tipo.nombre"></span>
                    </button>
                </template>
            </div>
        </div>
    </div>

    <!-- Etapas del trámite + JSON + flujo -->
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">Etapas del Trámite</div>
            <div class="card-body">
                <ul id="sortableEtapas" class="list-group mb-3">
                    <template x-for="(etapa, index) in etapas" :key="etapa.uid">
                        <li class="list-group-item" :data-id="etapa.uid">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong>#<span x-text="index + 1"></span> - <span x-text="etapa.tipo.nombre"></span></strong><br>
                                    <small class="text-muted" x-text="etapa.descripcion"></small>
                                </div>
                                <div class="ms-auto d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            @click.prevent="etapa.show = !etapa.show; $nextTick(()=>attachSubSortable())">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                            @click.prevent="editarCondiciones(index)">
                                        <i class="bi bi-code-slash"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                            @click.prevent="eliminarEtapa(index)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div x-show="etapa.show" class="mt-3 border-top pt-3">
                                <div class="mb-2">
                                    <label class="form-label">Nombre</label>
                                    <input type="text" class="form-control" x-model="etapa.nombre">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">Descripción</label>
                                    <input type="text" class="form-control" x-model="etapa.descripcion">
                                </div>
                                <div class="row mb-2">
                                    <div class="col">
                                        <label class="form-label">Tiempo de vida</label>
                                        <input type="number" class="form-control" x-model.number="etapa.tiempoVida">
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Tipo de días</label>
                                        <select class="form-select" x-model="etapa.tipoDia">
                                            <option value="habil">Hábiles</option>
                                            <option value="natural">Naturales</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- ===== OPCIÓN GLOBAL: Adjuntos en documentos de salida ===== -->
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" :id="'swAdjSalida-'+etapa.uid"
                                           x-model="etapa.adjuntosEnSalida">
                                    <label class="form-check-label" :for="'swAdjSalida-'+etapa.uid">
                                        Adjuntos en documentos de salida
                                        <i class="bi bi-info-circle ms-1" data-bs-toggle="tooltip"
                                           title="Si está activo, los archivos/adjuntos correspondientes se incluirán en los documentos emitidos en esta etapa."></i>
                                    </label>
                                </div>
                                <!-- ===== /OPCIÓN GLOBAL ===== -->

                                <!-- ===== EMISIÓN DE DOCUMENTO: opciones específicas ===== -->
                                <template x-if="etapa.tipo.codigo === 'documento'">
                                    <div class="mb-3 border rounded p-3">
                                        <div class="form-check form-switch mb-2">
                                            <input class="form-check-input" type="checkbox" :id="'swNotifDoc-'+etapa.uid"
                                                   x-model="etapa.notificarConclusion">
                                            <label class="form-check-label" :for="'swNotifDoc-'+etapa.uid">
                                                Notificar al usuario de la conclusión de la etapa
                                            </label>
                                        </div>

                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" :id="'swObsOperador-'+etapa.uid"
                                                   x-model="etapa.permitirObsOperador">
                                            <label class="form-check-label" :for="'swObsOperador-'+etapa.uid">
                                                Permitir al operador agregar observaciones
                                            </label>
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Elija lo que se va a emitir <span class="text-danger">*</span></label>
                                            <select class="form-select" x-model="etapa.tipoEmision">
                                                <option value="certificado">Certificado</option>
                                                <option value="informe">Informe</option>
                                                <option value="constancia">Constancia</option>
                                            </select>
                                        </div>
                                    </div>
                                </template>
                                <!-- ===== /EMISIÓN DE DOCUMENTO ===== -->

                                <!-- Subetapas: gestión visual y flujo -->
                                <template x-if="etapa.tipo.codigo === 'subetapa'">
                                    <div class="mt-3 border p-2">
                                        <label class="form-label">Subetapas</label>

                                        <!-- Tipo de flujo subetapas -->
                                        <div class="d-flex align-items-center gap-4 mb-2">
                                            <span class="text-muted small">Selecciona el tipo de flujo</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" :name="'flujo-'+etapa.uid"
                                                       value="secuencial" x-model="etapa.sub_flujoTipo">
                                                <label class="form-check-label">Secuencial</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" :name="'flujo-'+etapa.uid"
                                                       value="simultaneo" x-model="etapa.sub_flujoTipo">
                                                <label class="form-check-label">Simultáneo</label>
                                            </div>
                                            <i class="bi bi-info-circle small text-muted"
                                               data-bs-toggle="tooltip"
                                               title="Secuencial: se ejecutan en el orden de la lista (arrastrá para reordenar). Simultáneo: se ejecutan en paralelo."></i>
                                        </div>

                                        <div class="d-flex flex-wrap gap-3" :id="'sublist-'+etapa.uid">
                                            <template x-for="(sub, si) in etapa.subetapas" :key="si">
                                                <div class="border p-2 rounded bg-light position-relative" style="min-width:250px;" :data-index="si">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="fw-bold">
                                                            <span class="badge bg-secondary me-1" x-show="etapa.sub_flujoTipo==='secuencial'">#<span x-text="si+1"></span></span>
                                                            <span x-text="sub.nombre"></span>
                                                        </div>
                                                        <!-- Tuerca -->
                                                        <div class="dropdown">
                                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="dropdown">
                                                                <i class="bi bi-gear"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li><a href="#" class="dropdown-item" @click.prevent="abrirEditorSubetapa(etapa, si)">Editar</a></li>
                                                                <li><a href="#" class="dropdown-item text-danger" @click.prevent="etapa.subetapas.splice(si,1)">Eliminar</a></li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <small class="text-muted" x-text="sub.descripcion"></small>

                                                    <div class="form-check mt-2">
                                                        <input type="checkbox" class="form-check-input"
                                                               x-model="sub.limitarArchivo" :id="'limitar-'+etapa.uid+'-'+si">
                                                        <label class="form-check-label" :for="'limitar-'+etapa.uid+'-'+si">
                                                            Limitar a un archivo
                                                        </label>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                                @click.prevent="agregarSubetapa(etapa); $nextTick(()=>attachSubSortable())">Agregar subetapa</button>
                                    </div>
                                </template>

                                <!-- Observaciones (generales de etapa) -->
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" x-model="etapa.hayPrevencion" :id="'hayPrev-'+etapa.uid">
                                    <label class="form-check-label" :for="'hayPrev-'+etapa.uid">Hay observaciones</label>
                                </div>
                                <div class="row mb-2" x-show="etapa.hayPrevencion">
                                    <div class="col">
                                        <label class="form-label">Máx. observaciones</label>
                                        <input type="number" class="form-control" x-model.number="etapa.maxPrevenciones">
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Duración observación (horas)</label>
                                        <input type="number" class="form-control" x-model.number="etapa.duracionPrevencion">
                                    </div>
                                </div>
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" x-model="etapa.rechazarAuto" :id="'rechAuto-'+etapa.uid">
                                    <label class="form-check-label" :for="'rechAuto-'+etapa.uid">Rechazar automáticamente si no responde</label>
                                </div>

                                <!-- Involucrados -->
                                <div class="mb-3">
                                    <label class="form-label d-block">Involucrados</label>
                                    <button type="button" class="btn btn-sm btn-outline-primary mb-2"
                                            @click.prevent="asignarInvolucrados(etapa)">
                                        Asignar involucrados
                                    </button>
                                    <ul class="list-group">
                                        <template x-for="(inv, i) in etapa.involucrados" :key="i">
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <span x-text="inv.nombre"></span>
                                                <button type="button" class="btn btn-sm btn-link text-danger"
                                                        @click.prevent="etapa.involucrados.splice(i, 1)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    </template>
                </ul>

                <!-- JSON generado -->
                <label class="form-label">JSON generado:</label>
                <textarea class="form-control mb-3" rows="6" readonly
                          x-text="JSON.stringify(etapas, null, 2)"></textarea>

                <!-- Hidden que viaja al backend siempre actualizado -->
                <input type="hidden" name="etapas" x-ref="etapasInput" :value="JSON.stringify(etapas)" />

                <!-- Vista de flujo con SVG -->
                <h5 class="mt-4">Vista de Flujo</h5>
                <div class="bg-white border rounded p-3">
                    <svg x-ref="svg" width="100%" height="600">
                        <g x-ref="g"></g>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal condiciones -->
    <div class="modal fade" id="condicionesModal" tabindex="-1" aria-hidden="true" x-ref="condModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Condiciones (JSON)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <textarea x-model="condicionesJson" class="form-control" rows="8"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click.prevent="guardarCondiciones()">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal editor de Subetapa (sin cambios relevantes) -->
    <div class="modal fade" id="editorSubetapaModal" tabindex="-1" aria-hidden="true" x-ref="subModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 x-text="subEdit?.titulo || 'Editar subetapa'">Editar subetapa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre de la etapa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" x-model="subEdit.nombre">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tiempo de vida <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" x-model.number="subEdit.tiempoVida">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tipo de días <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="subHabil" value="habil" x-model="subEdit.tipoDia">
                                    <label class="form-check-label" for="subHabil">Día(s) hábil(es)</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" id="subNatural" value="natural" x-model="subEdit.tipoDia">
                                    <label class="form-check-label" for="subNatural">Día(s) natural(es)</label>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Descripción de la etapa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" x-model="subEdit.descripcion">
                        </div>

                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="subNotif" x-model="subEdit.notificar">
                                <label class="form-check-label" for="subNotif">Notificar al usuario de la conclusión de la etapa</label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Opción: permitir continuar sin área -->
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="permitirSinArea" x-model="subEdit.permitirSinArea">
                                <label class="form-check-label" for="permitirSinArea">
                                    Permitir continuar sin seleccionar área (saltar esta subetapa)
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6" x-show="subEdit.permitirSinArea">
                            <label class="form-label">Si no se asigna área, saltar a:</label>
                            <select class="form-select" x-model="subEdit.destinoSinArea">
                                <option :value="null">Siguiente etapa por defecto</option>
                                <template x-for="(e, i) in etapas" :key="'dest-'+i">
                                    <option :value="i" x-text="(i+1)+'. '+(e.nombre || e.tipo?.nombre || 'Etapa')"
                                            :disabled="e.uid === subEditOwner?.uid"></option>
                                </template>
                            </select>
                        </div>
                    </div>

                    <hr class="my-3">

                    <!-- Involucrados -->
                    <div class="mb-2 d-flex gap-2">
                        <input type="text" class="form-control" placeholder="Nombre del involucrado" x-model="tmpInv.nombre">
                        <input type="email" class="form-control" placeholder="Email" x-model="tmpInv.email">
                        <select class="form-select" x-model="tmpInv.conclusion">
                            <option value="conclusion">Conclusión</option>
                            <option value="revision">Revisión</option>
                            <option value="dictamen">Dictamen</option>
                        </select>
                        <button type="button" class="btn btn-outline-primary"
                                :disabled="!tmpInv.nombre || !tmpInv.email"
                                @click.prevent="agregarInvolucradoTmp()">Agregar involucrado</button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Nombre del involucrado</th>
                                    <th>Email</th>
                                    <th>Tipo de conclusión</th>
                                    <th class="text-center" style="width:60px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(inv, i) in subEdit.involucrados" :key="i">
                                    <tr>
                                        <td><input type="text" class="form-control form-control-sm" x-model="inv.nombre"></td>
                                        <td><input type="email" class="form-control form-control-sm" x-model="inv.email"></td>
                                        <td>
                                            <select class="form-select form-select-sm" x-model="inv.conclusion">
                                                <option value="conclusion">Conclusión</option>
                                                <option value="revision">Revisión</option>
                                                <option value="dictamen">Dictamen</option>
                                            </select>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-link text-danger" @click.prevent="subEdit.involucrados.splice(i,1)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </template>
                                <tr x-show="!subEdit.involucrados?.length">
                                    <td colspan="4" class="text-center text-muted">Sin involucrados</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click.prevent="guardarEditorSubetapa()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Librerías necesarias -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://d3js.org/d3.v6.min.js"></script>
<script src="https://unpkg.com/dagre-d3@0.6.4/dist/dagre-d3.min.js"></script>

<script>
function gestorEtapas() {
    return {
        etapas: [],
        tiposEtapa: [
            { codigo: 'revision',  nombre: 'Revisión',              icono: 'bi bi-list-check' },
            { codigo: 'pagos',     nombre: 'Pagos',                 icono: 'bi bi-credit-card' },
            { codigo: 'documento', nombre: 'Emisión de documento',  icono: 'bi bi-file-earmark-check' },
            { codigo: 'subetapa',  nombre: 'Subetapas',             icono: 'bi bi-diagram-3' }
        ],
        condicionesJson: '',
        etapaEditando: null,

        // editor subetapas
        subEdit: null,
        subEditOwner: null,
        subEditIndex: null,
        tmpInv: { nombre:'', email:'', conclusion:'conclusion' },

        // sortables por etapa (para subetapas)
        sortablesSub: {},

        _emitEtapas() {
            try {
                const payload = JSON.stringify(this.etapas ?? []);
                if (this.$refs.etapasInput) this.$refs.etapasInput.value = payload;
                document.querySelectorAll('input[name="etapas_json"]').forEach(el => el.value = payload);
                window.dispatchEvent(new CustomEvent('mld:etapas-updated', { detail: payload }));
            } catch (e) { console.error('No se pudo emitir etapas:', e); }
        },

        init() {
            // cargar inicial
            try {
                const raw = this.$el?.dataset?.initial || '[]';
                const parsed = JSON.parse(raw);
                if (Array.isArray(parsed)) this.etapas = parsed;
            } catch (_) {}

            const self = this;
            new Sortable(document.getElementById('sortableEtapas'), {
                animation: 150,
                onEnd(evt) {
                    const movedItem = self.etapas.splice(evt.oldIndex, 1)[0];
                    self.etapas.splice(evt.newIndex, 0, movedItem);
                }
            });

            this.renderFlujo();

            this.$watch(() => JSON.stringify(this.etapas), () => {
                this.renderFlujo();
                this._emitEtapas();
                this.$nextTick(()=>this.attachSubSortable());
            });

            this._emitEtapas();
            this.$nextTick(()=>this.attachSubSortable());
        },

        attachSubSortable() {
            // Crea Sortable en cada contenedor de subetapas (por etapa)
            this.etapas.forEach((etapa, idx) => {
                if (etapa.tipo?.codigo !== 'subetapa') return;
                const id = `sublist-${etapa.uid}`;
                const el = document.getElementById(id);
                if (!el || this.sortablesSub[id]) return;

                this.sortablesSub[id] = new Sortable(el, {
                    animation: 150,
                    draggable: '.border.rounded.bg-light.position-relative',
                    onEnd: (evt) => {
                        const list = this.etapas[idx].subetapas || [];
                        const moved = list.splice(evt.oldIndex, 1)[0];
                        list.splice(evt.newIndex, 0, moved);
                        this.etapas[idx].subetapas = list;
                    }
                });
            });
        },

        agregarEtapa(tipo) {
            const base = {
                uid: Date.now() + Math.random(),
                tipo,
                nombre: tipo.nombre,
                descripcion: 'Descripción de la etapa...',
                tiempoVida: null,
                tipoDia: 'habil',
                // NUEVO: disponible en todas las etapas
                adjuntosEnSalida: false,

                hayPrevencion: false,
                maxPrevenciones: null,
                duracionPrevencion: null,
                rechazarAuto: false,
                involucrados: [],
                condiciones: {},
                show: true
            };

            if (tipo.codigo === 'pagos') base.conceptos = [];
            if (tipo.codigo === 'subetapa') {
                base.subetapas = [];
                base.sub_flujoTipo = 'secuencial';
            }
            if (tipo.codigo === 'documento') {
                base.notificarConclusion   = false;
                base.permitirObsOperador   = false;
                base.tipoEmision           = 'certificado';
            }

            this.etapas.push(base);
            this.$nextTick(()=>this.attachSubSortable());
        },

        eliminarEtapa(index) { this.etapas.splice(index, 1); },

        // pagos
        agregarConcepto(etapa) {
            if (!Array.isArray(etapa.conceptos)) etapa.conceptos = [];
            etapa.conceptos.push({ clave:'', codigo:'', descripcion:'', pago:'', tipo:'', monto:0 });
        },

        // subetapas
        agregarSubetapa(etapa) {
            if (!Array.isArray(etapa.subetapas)) etapa.subetapas = [];
            etapa.subetapas.push({
                nombre: 'Nueva subetapa',
                descripcion: '',
                limitarArchivo: false,
                tiempoVida: 1,
                tipoDia: 'habil',
                notificar: false,
                involucrados: [],
                permitirSinArea: true,
                destinoSinArea: null
            });
        },

        abrirEditorSubetapa(etapa, si) {
            this.subEditOwner = etapa;
            this.subEditIndex = si;
            const src = etapa.subetapas[si] || {};
            this.subEdit = JSON.parse(JSON.stringify({
                nombre: src.nombre || '',
                descripcion: src.descripcion || '',
                limitarArchivo: !!src.limitarArchivo,
                tiempoVida: src.tiempoVida ?? 1,
                tipoDia: src.tipoDia || 'habil',
                notificar: !!src.notificar,
                involucrados: Array.isArray(src.involucrados) ? src.involucrados : [],
                permitirSinArea: src.permitirSinArea !== false,
                destinoSinArea: (src.destinoSinArea ?? null)
            }));
            this.tmpInv = { nombre:'', email:'', conclusion:'conclusion' };
            new bootstrap.Modal(this.$refs.subModal).show();
        },

        agregarInvolucradoTmp() {
            if (!this.subEdit) return;
            this.subEdit.involucrados.push({ ...this.tmpInv });
            this.tmpInv = { nombre:'', email:'', conclusion:'conclusion' };
        },

        guardarEditorSubetapa() {
            try {
                if (!this.subEditOwner || this.subEditIndex === null) return;
                this.subEditOwner.subetapas[this.subEditIndex] = JSON.parse(JSON.stringify(this.subEdit));
                bootstrap.Modal.getInstance(this.$refs.subModal).hide();
                this.renderFlujo();
                this._emitEtapas();
            } catch (e) {
                console.error(e);
                alert('Error guardando la subetapa');
            }
        },

        editarCondiciones(index) {
            this.etapaEditando = index;
            this.condicionesJson = JSON.stringify(this.etapas[index].condiciones || {}, null, 2);
            new bootstrap.Modal(this.$refs.condModal).show();
        },

        guardarCondiciones() {
            try {
                this.etapas[this.etapaEditando].condiciones = JSON.parse(this.condicionesJson);
                this.renderFlujo();
                bootstrap.Modal.getInstance(this.$refs.condModal).hide();
            } catch (error) {
                console.error(error);
                alert('JSON inválido');
            }
        },

        renderFlujo() {
            this.$nextTick(() => {
                if (!this.$refs.svg || !this.$refs.g) return;

                const g = new dagreD3.graphlib.Graph().setGraph({ rankdir: 'TB' });

                // nodos
                this.etapas.forEach((etapa, index) => {
                    g.setNode(etapa.uid, {
                        label: `${index + 1}. ${etapa.tipo.nombre}`,
                        class: 'etapa-node',
                        labelStyle: 'font-weight: bold; fill: #333',
                        style: 'fill: #e3f2fd; stroke: #64b5f6; stroke-width: 2px;'
                    });
                });

                // edges
                this.etapas.forEach((etapa, idx) => {
                    const origenId = etapa.uid;
                    if (etapa.condiciones) {
                        for (const [condicion, destinoIndex] of Object.entries(etapa.condiciones)) {
                            const destinoEtapa = this.etapas.find((_, i) => i == destinoIndex);
                            if (destinoEtapa) {
                                g.setEdge(origenId, destinoEtapa.uid, {
                                    label: condicion,
                                    arrowhead: 'vee',
                                    lineInterpolate: 'basis',
                                    style: 'stroke: #555; stroke-width: 2px;'
                                });
                            }
                        }
                    }

                    // salto “sin área” en subetapas
                    if (etapa.tipo?.codigo === 'subetapa' && Array.isArray(etapa.subetapas)) {
                        etapa.subetapas.forEach(sub => {
                            if (sub.permitirSinArea) {
                                let destinoIdx = sub.destinoSinArea;
                                if (destinoIdx === null || destinoIdx === undefined) destinoIdx = idx + 1;
                                const destino = this.etapas[destinoIdx];
                                if (destino) {
                                    g.setEdge(origenId, destino.uid, {
                                        label: 'Sin área',
                                        arrowhead: 'vee',
                                        lineInterpolate: 'basis',
                                        style: 'stroke-dasharray: 5,5; stroke: #999; stroke-width: 2px;'
                                    });
                                }
                            }
                        });
                    }
                });

                const svg = d3.select(this.$refs.svg);
                const inner = d3.select(this.$refs.g);
                inner.selectAll("*").remove();

                const render = new dagreD3.render();
                render(inner, g);

                const svgGroup = this.$refs.g;
                const bbox = svgGroup.getBBox();
                svg.attr("viewBox", [bbox.x - 20, bbox.y - 20, bbox.width + 40, bbox.height + 40]);
            });
        },

        // placeholder: “Asignar involucrados” a nivel etapa
        asignarInvolucrados(etapa) {
            if (!Array.isArray(etapa.involucrados)) etapa.involucrados = [];
            etapa.involucrados.push({ nombre: 'Funcionario demo', email: 'demo@municipio.gob.ar' });
        }
    };
}
</script>
