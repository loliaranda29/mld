<div x-data="configuracionTramite()" x-init="init()" class="row g-4">
    <!-- Panel izquierdo con tabs -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-dark text-white">Configuración general</div>
            <div class="card-body">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="pill" href="#config">Configuraciones</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#prefolio">Configuración de prefolio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="pill" href="#folio">Configuración de folio</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Panel derecho dinámico -->
    <div class="col-md-8">
        <div class="tab-content">
            <!-- Configuraciones -->
            <div class="tab-pane fade show active" id="config">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuraciones</div>
                    <div class="card-body">
                        <label class="form-label">Nombre del botón Iniciar trámite</label>
                        <input type="text" class="form-control mb-3" x-model="configuracion.nombreBoton">

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.requiereNivel2">
                            <label class="form-check-label">Requiere nivel 2</label>
                        </div>

                        <label class="form-label">Dependencia</label>
                        <select class="form-select mb-3" x-model="configuracion.dependencia">
                            <option>Secretaría de Gobierno</option>
                            <option>Secretaría de Hacienda</option>
                        </select>

                        <label class="form-label">Subdependencia</label>
                        <select class="form-select mb-3" x-model="configuracion.subdependencia">
                            <option>Dirección de Catastro</option>
                            <option>Dirección de Comercio</option>
                        </select>

                        <label class="form-label">URL del trámite</label>
                        <input type="text" class="form-control mb-3" x-model="configuracion.urlTramite">

                        <button class="btn btn-sm btn-success" @click="guardarConfig()">Guardar configuración</button>
                    </div>
                </div>
            </div>

            <!-- Prefolio -->
            <div class="tab-pane fade" id="prefolio">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuración de prefolio</div>
                    <div class="card-body">
                        <button class="btn btn-sm btn-outline-primary mb-3" @click="agregarCampoPrefolio()">Agregar campo</button>
                        <div class="mb-3" x-text="previewPrefolio"></div>
                        <template x-for="(campo, i) in prefolio" :key="i">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" x-model="campo.valor">
                                <button class="btn btn-outline-danger" @click="prefolio.splice(i, 1)">X</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Folio -->
            <div class="tab-pane fade" id="folio">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuración de folio</div>
                    <div class="card-body">
                        <label class="form-label">Tipo de separador</label>
                        <select class="form-select mb-3" x-model="folioSeparador">
                            <option>-</option>
                            <option>/</option>
                            <option> </option>
                        </select>
                        <button class="btn btn-sm btn-outline-primary mb-3" @click="agregarCampoFolio()">Agregar campo</button>
                        <div class="mb-3" x-text="previewFolio"></div>
                        <template x-for="(campo, i) in folio" :key="i">
                            <div class="card mb-2 p-2">
                                <label>Tipo:</label>
                                <select class="form-select mb-1" x-model="campo.tipo">
                                    <option>Alfanumérico</option>
                                    <option>Número consecutivo</option>
                                    <option>Fecha</option>
                                </select>
                                <input type="text" class="form-control mb-1" x-model="campo.valor">
                                <button class="btn btn-sm btn-outline-danger" @click="folio.splice(i, 1)">Eliminar</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function configuracionTramite() {
    return {
        configuracion: {
            nombreBoton: '',
            requiereNivel2: false,
            dependencia: '',
            subdependencia: '',
            urlTramite: ''
        },
        prefolio: [],
        folio: [],
        folioSeparador: '-',

        guardarConfig() {
            localStorage.setItem('tramite_config', JSON.stringify(this.configuracion));
            localStorage.setItem('tramite_prefolio', JSON.stringify(this.prefolio));
            localStorage.setItem('tramite_folio', JSON.stringify({ campos: this.folio, separador: this.folioSeparador }));
        },
        cargarConfig() {
            const cfg = localStorage.getItem('tramite_config');
            if (cfg) this.configuracion = JSON.parse(cfg);
            const pre = localStorage.getItem('tramite_prefolio');
            if (pre) this.prefolio = JSON.parse(pre);
            const fol = localStorage.getItem('tramite_folio');
            if (fol) {
                const obj = JSON.parse(fol);
                this.folio = obj.campos;
                this.folioSeparador = obj.separador;
            }
        },
        agregarCampoPrefolio() {
            if (this.prefolio.length < 5) this.prefolio.push({ valor: '' });
        },
        agregarCampoFolio() {
            if (this.folio.length < 5) this.folio.push({ tipo: 'Alfanumérico', valor: '' });
        },
        get previewPrefolio() {
            return this.prefolio.map(p => p.valor).join('-');
        },
        get previewFolio() {
            return this.folio.map(p => p.valor).join(this.folioSeparador);
        },
        init() {
            this.cargarConfig();
        }
    }
}
</script>
