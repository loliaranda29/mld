<div x-data="formBuilder()" x-init="init()" class="row">
    <!-- Panel izquierdo -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">
                <i class="bi bi-ui-checks-grid"></i> Constructor de formulario
            </div>
            <div class="card-body">
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="dividirPorSecciones" x-model="useSections">
                    <label class="form-check-label" for="dividirPorSecciones">Dividido por secciones</label>
                </div>
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" id="dividirPorPasos" x-model="useSteps">
                    <label class="form-check-label" for="dividirPorPasos">Dividido por pasos</label>
                </div>
                <div class="d-grid gap-2 mb-3">
                    <button class="btn btn-outline-success btn-sm" @click="addSection">+ Agregar Sección</button>
                </div>
                <div id="componentsPanel">
                    <template x-for="(item, index) in components" :key="index">
                        <div class="btn btn-outline-secondary btn-sm mb-2 me-2" 
                             x-text="item.label"
                             :data-type="item.type"
                             :data-name="item.name"
                             :data-label="item.label"
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
                <template x-if="form.sections.length">
                    <div id="fieldContainer">
                        <template x-for="(section, sIndex) in form.sections" :key="sIndex">
                            <div class="mb-3 border rounded p-2" @dragover.prevent @drop="handleDrop($event, sIndex)">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <input class="form-control form-control-sm me-2" x-model="section.name" />
                                    <button class="btn btn-sm btn-outline-danger" @click="removeSection(sIndex)">Eliminar sección</button>
                                </div>
                                <template x-for="(field, index) in section.fields" :key="index">
                                    <div class="list-group-item d-flex justify-content-between align-items-center" style="cursor: grab;">
                                        <span x-text="field.label"></span>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary me-1" @click="editField(sIndex, index)">Editar</button>
                                            <button class="btn btn-sm btn-outline-danger" @click="removeField(sIndex, index)">Eliminar</button>
                                        </div>
                                    </div>
                                </template>
                                <div class="text-center text-muted mt-2" style="font-size: 0.85em;">Arrastrá aquí campos para agregarlos</div>
                            </div>
                        </template>
                    </div>
                </template>
                <template x-if="!form.sections.length">
                    <p class="text-muted">No hay campos aún. Agregá uno desde la izquierda.</p>
                </template>

                <div class="mt-3">
                    <label for="jsonOutput" class="form-label">Vista JSON del formulario</label>
                    <textarea id="jsonOutput" class="form-control" rows="10" readonly x-text="JSON.stringify(form, null, 2)"></textarea>
                </div>

                <div class="mt-3">
                    <label class="form-label">Vista flujo del formulario</label>
                    <div id="flowPreview" class="bg-light border rounded p-3">
                        <template x-if="form.sections.length">
                            <div class="d-flex flex-wrap gap-4">
                                <template x-for="(section, sIndex) in form.sections" :key="sIndex">
                                    <div class="border border-primary rounded p-2" style="min-width: 200px;">
                                        <strong x-text="section.name"></strong>
                                        <ul class="list-unstyled mt-2">
                                            <template x-for="(field, fIndex) in section.fields" :key="fIndex">
                                                <li>
                                                    - <span x-text="field.label"></span>
                                                    <template x-if="field.options">
                                                        <ul class="ms-3">
                                                            <template x-for="(opt, i) in field.options" :key="i">
                                                                <li>
                                                                    <span x-text="opt"></span>
                                                                    <template x-if="field.conditions && field.conditions[opt]">
                                                                        <span class="text-muted"> → <em x-text="field.conditions[opt]"></em></span>
                                                                    </template>
                                                                </li>
                                                            </template>
                                                        </ul>
                                                    </template>
                                                </li>
                                            </template>
                                        </ul>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de edición -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true" x-ref="modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content" x-show="selectedField !== null" x-transition>
                <div class="modal-header">
                    <h5 class="modal-title">Editar Campo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" @click="selectedField = null"></button>
                </div>
                <div class="modal-body" x-show="selectedField !== null">
                    <label class="form-label">Etiqueta</label>
                    <input type="text" class="form-control" x-model="form.sections[selectedSection].fields[selectedField].label">

                    <label class="form-label mt-3">Nombre Interno</label>
                    <input type="text" class="form-control" x-model="form.sections[selectedSection].fields[selectedField].name">

                    <label class="form-label mt-3">Validación</label>
                    <select class="form-select" x-model="form.sections[selectedSection].fields[selectedField].validation">
                        <option value="none">Sin validación</option>
                        <option value="required">Obligatorio</option>
                        <option value="email">Email</option>
                        <option value="number">Número</option>
                    </select>

                    <template x-if="['select', 'radio', 'checkbox'].includes(form.sections[selectedSection].fields[selectedField].type)">
                        <div class="mt-3">
                            <label class="form-label">Opciones</label>
                            <template x-for="(option, i) in form.sections[selectedSection].fields[selectedField].options" :key="i">
                                <div class="d-flex mb-2 align-items-center">
                                    <input class="form-control form-control-sm me-2" x-model="form.sections[selectedSection].fields[selectedField].options[i]">
                                    <button class="btn btn-sm btn-outline-danger" @click="form.sections[selectedSection].fields[selectedField].options.splice(i, 1)">×</button>
                                </div>
                            </template>
                            <button class="btn btn-sm btn-outline-success" @click="form.sections[selectedSection].fields[selectedField].options.push('')">+ Agregar opción</button>
                        </div>
                    </template>

                    <template x-if="form.sections[selectedSection].fields[selectedField].type === 'file'">
                        <div class="mt-3">
                            <label class="form-label">Tamaño máximo (MB)</label>
                            <input type="number" class="form-control mb-2" x-model="form.sections[selectedSection].fields[selectedField].maxSize">
                            <label class="form-label">Tipos aceptados</label>
                            <input type="text" class="form-control" placeholder="image/png, application/pdf..." x-model="form.sections[selectedSection].fields[selectedField].accept">
                        </div>
                    </template>

                    <template x-if="form.sections[selectedSection].fields[selectedField].type === 'api'">
                        <div class="mt-3">
                            <label class="form-label">Método</label>
                            <select class="form-select mb-2" x-model="form.sections[selectedSection].fields[selectedField].apiMethod">
                                <option value="GET">GET</option>
                                <option value="POST">POST</option>
                            </select>
                            <label class="form-label">URL de la API</label>
                            <input type="text" class="form-control mb-2" x-model="form.sections[selectedSection].fields[selectedField].apiUrl">
                            <label class="form-label">Credenciales / Headers (JSON)</label>
                            <textarea class="form-control mb-2" rows="3" x-model="form.sections[selectedSection].fields[selectedField].apiHeaders"></textarea>
                        </div>
                    </template>

                    <template x-if="form.sections[selectedSection].fields[selectedField].type === 'code'">
                        <div class="mt-3">
                            <label class="form-label">Código personalizado</label>
                            <textarea class="form-control" rows="5" x-model="form.sections[selectedSection].fields[selectedField].code"></textarea>
                        </div>
                    </template>

                    <label class="form-label mt-3">Pista (texto)</label>
                    <input type="text" class="form-control mb-2" x-model="form.sections[selectedSection].fields[selectedField].help">

                    <label class="form-label">Pista (imagen o video)</label>
                    <input type="text" class="form-control" placeholder="URL de imagen o video" x-model="form.sections[selectedSection].fields[selectedField].media">

                    <label class="form-label mt-3">Condición / Deriva a sección</label>
                    <select class="form-select" x-model="form.sections[selectedSection].fields[selectedField].condition">
                        <option value="">-- Ninguna --</option>
                        <template x-for="(section, index) in form.sections" :key="index">
                            <option :value="section.name" x-text="section.name"></option>
                        </template>
                    </select>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary" @click="selectedField = null" data-bs-dismiss="modal">Guardar</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function formBuilder() {
        return {
            useSections: true,
            useSteps: true,
            selectedField: null,
            selectedSection: 0,
            form: {
                sections: [
                    {
                        name: 'Inicio del trámite',
                        fields: []
                    }
                ]
            },
            components: [
                { type: 'text', label: 'Respuesta breve', name: 'respuesta_breve' },
                { type: 'textarea', label: 'Párrafo', name: 'parrafo' },
                { type: 'select', label: 'Lista desplegable', name: 'lista' },
                { type: 'file', label: 'Archivo', name: 'archivo' },
                { type: 'date', label: 'Fecha', name: 'fecha' },
                { type: 'api', label: 'Campo API', name: 'api_field' },
                { type: 'code', label: 'Código personalizado', name: 'codigo' },
                { type: 'radio', label: 'Opción múltiple', name: 'radio' },
                { type: 'checkbox', label: 'Casillas de verificación', name: 'checkbox' },
                { type: 'search', label: 'Campo de búsqueda', name: 'busqueda' },
                { type: 'richtext', label: 'Texto enriquecido', name: 'richtext' }
            ],
            addSection() {
                this.form.sections.push({ name: 'Nueva sección', fields: [] });
            },
            removeSection(index) {
                this.form.sections.splice(index, 1);
            },
            addFieldToSection(item, sIndex) {
                const field = {
                    ...item,
                    required: false,
                    certificado: false,
                    help: '',
                    media: '',
                    condition: '',
                    validation: 'none'
                };
                if (item.type === 'file') {
                    field.maxSize = 5;
                    field.accept = ['image/png', 'image/jpg', 'application/pdf'];
                }
                if (item.type === 'api') {
                    field.apiUrl = '';
                    field.apiMethod = 'GET';
                    field.apiHeaders = '{}';
                    field.apiResultField = '';
                }
                if (["select", "radio", "checkbox"].includes(item.type)) {
                    field.options = [];
                }
                this.form.sections[sIndex].fields.push(field);
            },
            removeField(sectionIndex, index) {
                this.form.sections[sectionIndex].fields.splice(index, 1);
            },
            editField(sectionIndex, index) {
                this.selectedSection = sectionIndex;
                this.selectedField = index;
                new bootstrap.Modal(this.$refs.modal).show();
            },
            handleDragStart(event, item) {
                event.dataTransfer.setData('application/json', JSON.stringify(item));
            },
            handleDrop(event, sIndex) {
                const item = JSON.parse(event.dataTransfer.getData('application/json'));
                this.addFieldToSection(item, sIndex);
            },
            init() {
                // Inicialización si es necesario
            }
        }
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
