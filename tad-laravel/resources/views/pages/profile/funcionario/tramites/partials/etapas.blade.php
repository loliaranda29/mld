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
                                            @click.prevent="etapa.show = !etapa.show">
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

                                <!-- Pagos: listado de conceptos -->
                                <template x-if="etapa.tipo.codigo === 'pagos'">
                                    <div class="mb-3 border p-2">
                                        <label class="form-label">Listado de conceptos a cobrar</label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Clave</th>
                                                        <th>Concepto</th>
                                                        <th>Descripción</th>
                                                        <th>Pago</th>
                                                        <th>Tipo</th>
                                                        <th>Monto</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <template x-for="(concepto, ci) in etapa.conceptos" :key="ci">
                                                        <tr>
                                                            <td x-text="concepto.clave"></td>
                                                            <td x-text="concepto.codigo"></td>
                                                            <td x-text="concepto.descripcion"></td>
                                                            <td x-text="concepto.pago"></td>
                                                            <td x-text="concepto.tipo"></td>
                                                            <td x-text="concepto.monto"></td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-danger"
                                                                        @click.prevent="etapa.conceptos.splice(ci, 1)">🗑️
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm"
                                                @click.prevent="agregarConcepto(etapa)">Agregar concepto</button>
                                    </div>
                                </template>

                                <!-- Subetapas: gestión visual -->
                                <template x-if="etapa.tipo.codigo === 'subetapa'">
                                    <div class="mt-3 border p-2">
                                        <label class="form-label">Subetapas</label>
                                        <div class="d-flex flex-wrap gap-3">
                                            <template x-for="(sub, si) in etapa.subetapas" :key="si">
                                                <div class="border p-2 rounded bg-light position-relative">
                                                    <div class="fw-bold" x-text="sub.nombre"></div>
                                                    <small class="text-muted" x-text="sub.descripcion"></small>
                                                    <div class="form-check mt-1">
                                                        <input type="checkbox" class="form-check-input"
                                                               x-model="sub.limitarArchivo" :id="'limitar-'+si">
                                                        <label class="form-check-label" :for="'limitar-'+si">Limitar a un archivo</label>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-link text-danger position-absolute top-0 end-0"
                                                            @click.prevent="etapa.subetapas.splice(si, 1)">🗑️</button>
                                                </div>
                                            </template>
                                        </div>
                                        <button type="button" class="btn btn-outline-primary btn-sm mt-2"
                                                @click.prevent="agregarSubetapa(etapa)">Agregar subetapa</button>
                                    </div>
                                </template>

                                <!-- Observaciones -->
                                <div class="form-check form-switch mb-2">
                                    <input class="form-check-input" type="checkbox" x-model="etapa.hayPrevencion" id="hayPrev">
                                    <label class="form-check-label" for="hayPrev">Hay observaciones</label>
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
                                    <input class="form-check-input" type="checkbox" x-model="etapa.rechazarAuto" id="rechAuto">
                                    <label class="form-check-label" for="rechAuto">Rechazar automáticamente si no responde</label>
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

        // ---- NUEVO: emite cambios para sincronizar con el form padre y el hidden correcto ----
        _emitEtapas() {
            try {
                const payload = JSON.stringify(this.etapas ?? []);
                // Actualizar el hidden local (mantengo tu x-ref y name="etapas")
                if (this.$refs.etapasInput) this.$refs.etapasInput.value = payload;
                // Actualizar los hidden del formulario padre esperados por backend (etapas_json)
                document.querySelectorAll('input[name="etapas_json"]').forEach(el => el.value = payload);
                // Notificar al padre (igual que hace el builder de Formulario)
                window.dispatchEvent(new CustomEvent('mld:etapas-updated', { detail: payload }));
            } catch (e) {
                console.error('No se pudo emitir etapas:', e);
            }
        },

        init() {
            // Cargar estado inicial desde el atributo data-initial
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

            // Observa cambios profundos, refresca flujo y emite sync
            this.$watch(() => JSON.stringify(this.etapas), () => {
                this.renderFlujo();
                this._emitEtapas();
            });

            // Emitir al iniciar (para que el padre tenga el valor inicial)
            this._emitEtapas();
        },

        agregarEtapa(tipo) {
            const base = {
                uid: Date.now() + Math.random(),
                tipo,
                nombre: tipo.nombre,
                descripcion: 'Descripción de la etapa...',
                tiempoVida: null,
                tipoDia: 'habil',
                hayPrevencion: false,
                maxPrevenciones: null,
                duracionPrevencion: null,
                rechazarAuto: false,
                involucrados: [],
                condiciones: {},
                show: true
            };

            if (tipo.codigo === 'pagos') {
                base.conceptos = [];
            }
            if (tipo.codigo === 'subetapa') {
                base.subetapas = [];
            }

            this.etapas.push(base);
        },

        eliminarEtapa(index) {
            this.etapas.splice(index, 1);
        },

        // ==== Apoyos para UI que usabas en la vista ====
        agregarConcepto(etapa) {
            if (!Array.isArray(etapa.conceptos)) etapa.conceptos = [];
            etapa.conceptos.push({
                clave: '',
                codigo: '',
                descripcion: '',
                pago: '',
                tipo: '',
                monto: 0
            });
        },

        agregarSubetapa(etapa) {
            if (!Array.isArray(etapa.subetapas)) etapa.subetapas = [];
            etapa.subetapas.push({
                nombre: 'Nueva subetapa',
                descripcion: '',
                limitarArchivo: false
            });
        },
        // ===============================================

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

                this.etapas.forEach((etapa, index) => {
                    g.setNode(etapa.uid, {
                        label: `${index + 1}. ${etapa.tipo.nombre}`,
                        class: 'etapa-node',
                        labelStyle: 'font-weight: bold; fill: #333',
                        style: 'fill: #e3f2fd; stroke: #64b5f6; stroke-width: 2px;'
                    });
                });

                this.etapas.forEach((etapa) => {
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
        }
    };
}
</script>
