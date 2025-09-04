<div x-data="configuracionTramite()" x-init="init()" class="row g-4"
    @isset($tramite)
    data-preview-url="{{ route('funcionario.tramites.config.folio.preview', $tramite->id) }}"
    data-gen-url="{{ route('funcionario.tramites.config.folio.generar', $tramite->id) }}"
    data-reset-url="{{ route('funcionario.tramites.config.folio.reset', $tramite->id) }}"
    @endisset
>
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

    <!-- Panel derecho con contenidos -->
    <div class="col-md-8">
        <div class="tab-content">
            <!-- Configuraciones -->
            <div class="tab-pane fade show active" id="config">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuraciones</div>
                    <div class="card-body">
                        <!-- Nombre del botón -->
                        <label class="form-label">Nombre del botón Iniciar trámite</label>
                        <input type="text" class="form-control mb-3" x-model="configuracion.nombreBoton">

                        <!-- Nivel 2 -->
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.requiereNivel2">
                            <label class="form-check-label">¿El usuario requiere ser nivel 2?</label>
                        </div>

                        <!-- Agregar dependencia -->
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.usarDependencia">
                            <label class="form-check-label">Agregar dependencia</label>
                        </div>

                        <div class="row g-3" x-show="configuracion.usarDependencia">
                            <div class="col-md-6">
                                <label class="form-label">Selecciona una dependencia</label>
                                <input type="text" class="form-control" x-model="configuracion.dependencia" placeholder="Ej: Habilitaciones">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Seleccionar subdependencia</label>
                                <input type="text" class="form-control" x-model="configuracion.subdependencia" placeholder="Ej: Comercio">
                            </div>
                        </div>

                        <!-- URL de la ficha -->
                        <hr class="my-4">
                        <h6 class="mb-2">URL de la ficha del trámite</h6>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">URL de la ficha</label>
                                <input type="text" class="form-control" value="{{ url('/tramites') }}" disabled>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" x-model="configuracion.urlFichaPersonalizada">
                                    <label class="form-check-label">¿Configurar una URL diferente para la ficha del trámite?</label>
                                </div>
                            </div>
                            <div class="col-12" x-show="configuracion.urlFichaPersonalizada">
                                <label class="form-label">URL personalizada / campo único</label>
                                <input type="text" class="form-control" x-model="configuracion.urlTramite" placeholder="slug único o URL completa">
                                <small class="text-muted">* Verifica que el campo utilizado sea único.</small>
                            </div>
                        </div>

                        <!-- Formulario / Proceso externo -->
                        <hr class="my-4">
                        <h6 class="mb-2">Formulario</h6>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.procesoExterno">
                            <label class="form-check-label">El proceso se llevará en otra plataforma</label>
                        </div>
                        <div class="row g-3" x-show="configuracion.procesoExterno">
                            <div class="col-md-8">
                                <label class="form-label">Link externo</label>
                                <input type="text" class="form-control" x-model="configuracion.enlaceExterno" placeholder="https://...">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" x-model="configuracion.enviarDatosUsuarioExterno">
                                    <label class="form-check-label">¿Enviar todos los datos del usuario?</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.agregarCheckboxConfirmacion">
                            <label class="form-check-label">Agregar casilla de verificación de confirmación para el formulario</label>
                        </div>
                        <div class="mb-3" x-show="configuracion.agregarCheckboxConfirmacion">
                            <label class="form-label">Texto para la casilla de confirmación en el formulario</label>
                            <textarea class="form-control" rows="3" x-model="configuracion.textoCheckboxConfirmacion"></textarea>
                        </div>

                        <div class="form-check form-switch mb-4">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.pedirFirmaElectronica">
                            <label class="form-check-label">Pedir firma electrónica</label>
                        </div>

                        <!-- Vigencia -->
                        <hr class="my-4">
                        <h6 class="mb-2">Vigencia</h6>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.esPermiso" disabled>
                            <label class="form-check-label">Este trámite es tipo permiso</label>
                        </div>
                        <div class="alert alert-warning py-2">
                            Para completar esta opción primero debes configurar tu formulario con campos tipo fecha.
                        </div>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.expiracionHabilitada">
                            <label class="form-check-label">Expiración del certificado</label>
                        </div>
                        <div class="row g-3" x-show="configuracion.expiracionHabilitada">
                            <div class="col-md-3">
                                <label class="form-label">Cantidad</label>
                                <input type="number" min="1" class="form-control" x-model.number="configuracion.expiracionCantidad">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Unidad</label>
                                <select class="form-select" x-model="configuracion.expiracionUnidad">
                                    <option value="dias">Días</option>
                                    <option value="meses">Meses</option>
                                    <option value="anios">Años</option>
                                </select>
                            </div>
                        </div>

                        <!-- Legal -->
                        <hr class="my-4">
                        <h6 class="mb-2">Legal</h6>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.avisoPrivacidadHabilitado">
                            <label class="form-check-label">Añadir aviso de privacidad</label>
                        </div>
                        <div class="mb-3" x-show="configuracion.avisoPrivacidadHabilitado">
                            <label class="form-label">URL del aviso de privacidad</label>
                            <input type="text" class="form-control" x-model="configuracion.urlAvisoPrivacidad" placeholder="https://...">
                        </div>

                        <!-- Desistir -->
                        <hr class="my-4">
                        <h6 class="mb-2">Desistir solicitud</h6>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.permitirDesistir">
                            <label class="form-check-label">Usuario puede desistir la solicitud</label>
                        </div>

                        <!-- Solicitudes externas -->
                        <hr class="my-4">
                        <h6 class="mb-2">Solicitudes externas</h6>

                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" x-model="configuracion.recibirSolicitudesExternas">
                            <label class="form-check-label">Recibir solicitudes externas</label>
                        </div>
                        <div class="mb-2" x-show="configuracion.recibirSolicitudesExternas">
                            <label class="form-label">Selecciona el campo de donde tomar el valor*</label>
                            <input type="text" class="form-control" x-model="configuracion.campoSolicitudesExternas" placeholder="nombre_del_campo">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prefolio -->
            <div class="tab-pane fade" id="prefolio">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuración de prefolio</div>
                    <div class="card-body">
                        <p class="text-muted small mb-2">
                            El prefolio se antepone al folio. Puedes combinar texto libre (por ejemplo “MLD”) y campos dinámicos (ej: año).
                        </p>
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click="agregarCampoPrefolio('Texto')">+ Texto</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click="agregarCampoPrefolio('Año')">+ Año</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" @click="limpiarPrefolio()">Limpiar</button>
                        </div>

                        <div class="mb-3">
                            <strong>Vista previa:</strong>
                            <div class="border rounded p-2 mt-1 bg-light" x-text="previewPrefolio()"></div>
                        </div>

                        <template x-for="(campo, i) in prefolio" :key="campo._k">
                            <div class="input-group mb-2">
                                <select class="form-select" style="max-width: 180px" x-model="campo.tipo" @change="onChange">
                                    <option>Texto</option>
                                    <option>Año</option>
                                </select>
                                <input type="text" class="form-control" x-model="campo.valor" :disabled="campo.tipo!=='Texto'" placeholder="Valor (si es Texto)" @input="onChange">
                                <button type="button" class="btn btn-outline-danger" @click="eliminarCampoPrefolio(i)">Eliminar</button>
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
                        <select class="form-select mb-3" x-model="folioSeparador" @change="onChange">
                            <option>-</option>
                            <option>/</option>
                            <option> </option>
                        </select>

                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click="agregarCampoFolio('Alfanumérico')">+ Alfanumérico</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click="agregarCampoFolio('Número consecutivo')">+ Número consecutivo</button>
                            <button type="button" class="btn btn-sm btn-outline-primary me-2" @click="agregarCampoFolio('Fecha')">+ Fecha</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" @click="limpiarFolio()">Limpiar</button>
                        </div>

                        <!-- Acciones servidor -->
                        @isset($tramite)
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-secondary" @click="previewFolioServer()">Vista previa (servidor)</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" @click="testConsecutivoServer()">Test con consecutivo</button>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="resetConsecutivo()">Reiniciar numeración</button>
                        </div>
                        <div x-show="serverPreview" class="alert alert-info py-2" x-text="'Servidor: ' + serverPreview"></div>
                        @endisset

                        <div class="mb-3">
                            <strong>Vista previa:</strong>
                            <div class="border rounded p-2 mt-1 bg-light" x-text="previewFolio()"></div>
                        </div>

                        <template x-for="(campo, i) in folio" :key="campo._k">
                            <div class="card mb-2 p-2">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-4">
                                        <label class="form-label mb-0">Tipo</label>
                                        <select class="form-select" x-model="campo.tipo" @change="onChange">
                                            <option>Alfanumérico</option>
                                            <option>Número consecutivo</option>
                                            <option>Fecha</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-0">Valor</label>
                                        <input type="text" class="form-control" x-model="campo.valor" :placeholder="placeholderPorTipo(campo.tipo)" @input="onChange" :disabled="campo.tipo!=='Alfanumérico'">
                                        <small class="text-muted" x-show="campo.tipo!=='Alfanumérico'">Este campo se genera automáticamente.</small>
                                    </div>
                                    <div class="col-md-2 d-grid">
                                        <button type="button" class="btn btn-outline-danger" @click="eliminarCampoFolio(i)">Eliminar</button>
                                    </div>

                                    <!-- Controles extra -->
                                    <div class="col-12 d-flex justify-content-end gap-1 mt-1">
                                        <button type="button" class="btn btn-light btn-sm" @click="moveUp(i)" title="Subir">▲</button>
                                        <button type="button" class="btn btn-light btn-sm" @click="moveDown(i)" title="Bajar">▼</button>
                                        <button type="button" class="btn btn-light btn-sm" @click="duplicar(i)" title="Duplicar">⎘</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@verbatim
<script>
function configuracionTramite() {
  return {
    /* =================== Estado =================== */
    configuracion: {
      // existentes
      nombreBoton: '',
      requiereNivel2: false,
      dependencia: '',
      subdependencia: '',
      urlTramite: '',
      // NUEVOS (tomados del sistema original)
      usarDependencia: false,
      urlFichaPersonalizada: false,
      procesoExterno: false,
      enlaceExterno: '',
      enviarDatosUsuarioExterno: false,
      agregarCheckboxConfirmacion: false,
      textoCheckboxConfirmacion: '',
      pedirFirmaElectronica: false,
      esPermiso: false, // mostrado deshabilitado
      expiracionHabilitada: false,
      expiracionCantidad: null,
      expiracionUnidad: 'dias',
      avisoPrivacidadHabilitado: false,
      urlAvisoPrivacidad: '',
      permitirDesistir: false,
      recibirSolicitudesExternas: false,
      campoSolicitudesExternas: ''
    },
    prefolio: [],
    folio: [],
    folioSeparador: '-',
    serverPreview: '',

    /* =================== Utils =================== */
    _k() { return (Date.now().toString(36) + Math.random().toString(36).slice(2,6)); },
    _findHidden(name){
      const form = this.$root.closest('form');
      return form ? form.querySelector(`input[name="${name}"]`) : null;
    },
    _unescapeHtml(raw){
      const txt = document.createElement('textarea');
      txt.innerHTML = raw ?? '';
      const v = txt.value;
      txt.remove();
      return v;
    },
    _safeParse(raw){
      if (!raw) return null;
      let txt = String(raw);
      try {
        // a veces llega doble-encoded desde atributos
        let v = JSON.parse(this._unescapeHtml(txt));
        if (typeof v === 'string') {
          try { v = JSON.parse(this._unescapeHtml(v)); } catch(_) {}
        }
        return v;
      } catch(_) { return null; }
    },
    _coerceArrays(){
      if (!Array.isArray(this.prefolio)) this.prefolio = [];
      if (!Array.isArray(this.folio)) this.folio = [];
      if (typeof this.folioSeparador !== 'string') this.folioSeparador = '-';
    },
    _getParentConfig(){
      try {
        const formCmp = this.$root.closest('form')?.__x;
        const cfg = formCmp?.$data?.config;
        if (cfg && typeof cfg === 'object') {
          return JSON.parse(JSON.stringify(cfg));
        }
      } catch(_) {}
      return null;
    },
    _csrf(){ return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''; },
    _urls(){ return { preview:this.$root.dataset.previewUrl, gen:this.$root.dataset.genUrl, reset:this.$root.dataset.resetUrl }; },
    hasServer(){ const u=this._urls(); return !!(u.preview && u.gen && u.reset); },
    async _post(url, body={}){
      const res = await fetch(url, {
        method:'POST',
        headers: { 'Content-Type':'application/json', 'X-CSRF-TOKEN': this._csrf() },
        body: JSON.stringify(body)
      });
      return await res.json();
    },

    /* ============ Carga inicial (prioridad: backend) ============ */
    cargarConfig(){
      this._coerceArrays();

      // 1) Backend (hidden config_json)
      const hCfg = this._findHidden('config_json');
      let cfg = hCfg && hCfg.value ? this._safeParse(hCfg.value) : null;

      // 2) Si todavía no hay nada, intentamos desde el estado Alpine del padre
      if (!cfg || (typeof cfg === 'object' && Object.keys(cfg).length === 0)) {
        cfg = this._getParentConfig();
      }

      // 3) Fallback final: localStorage
      if (!cfg || (typeof cfg === 'object' && Object.keys(cfg).length === 0)) {
        try {
          const a = localStorage.getItem('tramite_config');
          if (a) cfg = this._safeParse(a) || cfg;
          const b = localStorage.getItem('tramite_prefolio');
          if (b) this.prefolio = this._safeParse(b) || this.prefolio;
          const c = localStorage.getItem('tramite_folio');
          if (c) {
            const of = this._safeParse(c);
            if (of && typeof of === 'object') {
              this.folio = Array.isArray(of.campos) ? of.campos : this.folio;
              this.folioSeparador = of.separador || this.folioSeparador;
            }
          }
        } catch(_) {}
      }

      // Normalización de llaves legacy
      cfg = cfg || {};
      if (cfg.nombre_boton && !cfg.nombreBoton) cfg.nombreBoton = String(cfg.nombre_boton);
      if (cfg.url_tramite && !cfg.urlTramite)  cfg.urlTramite  = String(cfg.url_tramite);
      if (cfg.requiere_nivel2 !== undefined && cfg.requiereNivel2 === undefined) {
        cfg.requiereNivel2 = (cfg.requiere_nivel2 === true || cfg.requiere_nivel2 === '1' || cfg.requiere_nivel2 === 1);
      }

      // Mezcla sin pisar otras claves
      this.configuracion = Object.assign(
        {
          nombreBoton:'', requiereNivel2:false, dependencia:'', subdependencia:'', urlTramite:'',
          usarDependencia:false, urlFichaPersonalizada:false,
          procesoExterno:false, enlaceExterno:'', enviarDatosUsuarioExterno:false,
          agregarCheckboxConfirmacion:false, textoCheckboxConfirmacion:'',
          pedirFirmaElectronica:false,
          esPermiso:false, expiracionHabilitada:false, expiracionCantidad:null, expiracionUnidad:'dias',
          avisoPrivacidadHabilitado:false, urlAvisoPrivacidad:'',
          permitirDesistir:false,
          recibirSolicitudesExternas:false, campoSolicitudesExternas:''
        },
        this.configuracion,
        {
          nombreBoton:    cfg.nombreBoton    ?? this.configuracion.nombreBoton,
          requiereNivel2: cfg.requiereNivel2 ?? this.configuracion.requiereNivel2,
          dependencia:    cfg.dependencia    ?? this.configuracion.dependencia,
          subdependencia: cfg.subdependencia ?? this.configuracion.subdependencia,
          urlTramite:     cfg.urlTramite     ?? this.configuracion.urlTramite,

          usarDependencia:               cfg.usarDependencia               ?? this.configuracion.usarDependencia,
          urlFichaPersonalizada:         cfg.urlFichaPersonalizada         ?? this.configuracion.urlFichaPersonalizada,

          procesoExterno:                cfg.procesoExterno                ?? this.configuracion.procesoExterno,
          enlaceExterno:                 cfg.enlaceExterno                 ?? this.configuracion.enlaceExterno,
          enviarDatosUsuarioExterno:     cfg.enviarDatosUsuarioExterno     ?? this.configuracion.enviarDatosUsuarioExterno,

          agregarCheckboxConfirmacion:   cfg.agregarCheckboxConfirmacion   ?? this.configuracion.agregarCheckboxConfirmacion,
          textoCheckboxConfirmacion:     cfg.textoCheckboxConfirmacion     ?? this.configuracion.textoCheckboxConfirmacion,

          pedirFirmaElectronica:         cfg.pedirFirmaElectronica         ?? this.configuracion.pedirFirmaElectronica,

          esPermiso:                     cfg.esPermiso                     ?? this.configuracion.esPermiso,
          expiracionHabilitada:          cfg.expiracionHabilitada          ?? this.configuracion.expiracionHabilitada,
          expiracionCantidad:            cfg.expiracionCantidad            ?? this.configuracion.expiracionCantidad,
          expiracionUnidad:              cfg.expiracionUnidad              ?? this.configuracion.expiracionUnidad,

          avisoPrivacidadHabilitado:     cfg.avisoPrivacidadHabilitado     ?? this.configuracion.avisoPrivacidadHabilitado,
          urlAvisoPrivacidad:            cfg.urlAvisoPrivacidad            ?? this.configuracion.urlAvisoPrivacidad,

          permitirDesistir:              cfg.permitirDesistir              ?? this.configuracion.permitirDesistir,

          recibirSolicitudesExternas:    cfg.recibirSolicitudesExternas    ?? this.configuracion.recibirSolicitudesExternas,
          campoSolicitudesExternas:      cfg.campoSolicitudesExternas      ?? this.configuracion.campoSolicitudesExternas
        }
      );

      if (Array.isArray(cfg.prefolio)) {
        this.prefolio = cfg.prefolio.map(p => ({ _k: this._k(), tipo: p.tipo || (p.valor ? 'Texto' : 'Texto'), valor: p.valor || '' }));
      }
      if (cfg.folio && typeof cfg.folio === 'object') {
        if (Array.isArray(cfg.folio.campos)) {
          this.folio = cfg.folio.campos.map(p => ({ _k: this._k(), tipo: p.tipo || 'Alfanumérico', valor: p.valor || '' }));
        }
        if (typeof cfg.folio.separador === 'string') {
          this.folioSeparador = cfg.folio.separador;
        }
      }
    },

    /* ============ Serialización → backend (config_json) ============ */
    _payloadConfig(base){
      base = (base && typeof base === 'object' && !Array.isArray(base)) ? base : {};

      // base existentes
      base.nombreBoton     = this.configuracion.nombreBoton;
      base.requiereNivel2  = !!this.configuracion.requiereNivel2;
      base.dependencia     = this.configuracion.dependencia;
      base.subdependencia  = this.configuracion.subdependencia;
      base.urlTramite      = this.configuracion.urlTramite;

      // nuevos flags/valores
      base.usarDependencia             = !!this.configuracion.usarDependencia;
      base.urlFichaPersonalizada       = !!this.configuracion.urlFichaPersonalizada;

      base.procesoExterno              = !!this.configuracion.procesoExterno;
      base.enlaceExterno               = this.configuracion.enlaceExterno || '';
      base.enviarDatosUsuarioExterno   = !!this.configuracion.enviarDatosUsuarioExterno;

      base.agregarCheckboxConfirmacion = !!this.configuracion.agregarCheckboxConfirmacion;
      base.textoCheckboxConfirmacion   = this.configuracion.textoCheckboxConfirmacion || '';

      base.pedirFirmaElectronica       = !!this.configuracion.pedirFirmaElectronica;

      base.esPermiso                   = !!this.configuracion.esPermiso;
      base.expiracionHabilitada        = !!this.configuracion.expiracionHabilitada;
      base.expiracionCantidad          = this.configuracion.expiracionCantidad ?? null;
      base.expiracionUnidad            = this.configuracion.expiracionUnidad || 'dias';

      base.avisoPrivacidadHabilitado   = !!this.configuracion.avisoPrivacidadHabilitado;
      base.urlAvisoPrivacidad          = this.configuracion.urlAvisoPrivacidad || '';

      base.permitirDesistir            = !!this.configuracion.permitirDesistir;

      base.recibirSolicitudesExternas  = !!this.configuracion.recibirSolicitudesExternas;
      base.campoSolicitudesExternas    = this.configuracion.campoSolicitudesExternas || '';

      // prefolio/folio
      base.prefolio        = this.prefolio.map(p => ({ tipo: p.tipo || 'Texto', valor: (p.tipo === 'Texto' ? (p.valor || '') : '') }));
      base.folio           = {
        campos: this.folio.map(p => ({ tipo: p.tipo || 'Alfanumérico', valor: (p.tipo === 'Alfanumérico' ? (p.valor || '') : '') })),
        separador: this.folioSeparador
      };
      return base;
    },

    _syncAllHiddens(){
      // 1) Hidden config_json
      const hCfg = this._findHidden('config_json');
      if (hCfg) {
        const base = this._safeParse(hCfg.value) || {};
        try { hCfg.value = JSON.stringify(this._payloadConfig(base)); } catch(_) {}
      }
      // 2) Reflejar al estado Alpine del formulario padre
      try {
        const formCmp = this.$root.closest('form')?.__x;
        if (formCmp?.$data) {
          formCmp.$data.config = this._payloadConfig(formCmp.$data.config || {});
          window.dispatchEvent(new CustomEvent('mld:config-updated', { detail: JSON.stringify(formCmp.$data.config) }));
        }
      } catch(_) {}

      // Compat opcional con otros hiddens si existieran
      const hPre = this._findHidden('prefolio_json');
      if (hPre) { try { hPre.value = JSON.stringify(this.prefolio.map(p => ({ tipo: p.tipo, valor:p.valor }))); } catch(_) {} }
      const hFol = this._findHidden('folio_json');
      if (hFol) { try { hFol.value = JSON.stringify({ campos: this.folio.map(p => ({ tipo:p.tipo, valor:p.valor })), separador: this.folioSeparador }); } catch(_) {} }
    },

    /* ============ Handlers ============ */
    onChange(){ this._syncAllHiddens(); },

    agregarCampoPrefolio(tipo='Texto'){
      this._coerceArrays();
      this.prefolio = [...this.prefolio, { _k:this._k(), tipo, valor:'' }];
      this.$nextTick(() => this.onChange());
    },
    eliminarCampoPrefolio(i){
      this._coerceArrays();
      const arr = this.prefolio.slice();
      arr.splice(i,1);
      this.prefolio = arr;
      this.$nextTick(() => this.onChange());
    },
    limpiarPrefolio(){
      this.prefolio = [];
      this.$nextTick(() => this.onChange());
    },

    agregarCampoFolio(tipo='Alfanumérico'){
      this._coerceArrays();
      this.folio = [...this.folio, { _k:this._k(), tipo, valor:'' }];
      this.$nextTick(() => this.onChange());
    },
    eliminarCampoFolio(i){
      this._coerceArrays();
      const arr = this.folio.slice();
      arr.splice(i,1);
      this.folio = arr;
      this.$nextTick(() => this.onChange());
    },
    limpiarFolio(){
      this.folio = [];
      this.$nextTick(() => this.onChange());
    },

    moveUp(i){ if(i<=0) return; const a=this.folio.slice(); [a[i-1],a[i]]=[a[i],a[i-1]]; this.folio=a; this.$nextTick(()=>this.onChange()); },
    moveDown(i){ if(i>=this.folio.length-1) return; const a=this.folio.slice(); [a[i+1],a[i]]=[a[i],a[i+1]]; this.folio=a; this.$nextTick(()=>this.onChange()); },
    duplicar(i){ const a=this.folio.slice(); const c=Object.assign({}, a[i], {_k:this._k()}); a.splice(i+1,0,c); this.folio=a; this.$nextTick(()=>this.onChange()); },

    placeholderPorTipo(tipo){
      if (tipo === 'Alfanumérico') return 'Ej: ABC-{AÑO}-{MES2}';
      if (tipo === 'Número consecutivo') return 'Se genera automáticamente';
      if (tipo === 'Fecha') return 'Se genera automáticamente';
      return '';
    },

    previewPrefolio(){
      if (!this.prefolio.length) return '(sin prefolio)';
      const parts = this.prefolio.map(p => p.tipo === 'Año' ? new Date().getFullYear() : String(p.valor || '').trim()).filter(Boolean);
      return parts.join('');
    },

    previewFolio(){
      if (!this.folio.length) return '(sin folio)';
      const parts = this.folio.map(p => {
        if (p.tipo === 'Alfanumérico') return String(p.valor || '').trim();
        if (p.tipo === 'Número consecutivo') return '0001';
        if (p.tipo === 'Fecha') return (new Date()).toISOString().slice(0,10).replace(/-/g,'');
        return '';
      }).filter(Boolean);
      return parts.join(this.folioSeparador);
    },

    /* ===== server actions ===== */
    async previewFolioServer(){ if(!this.hasServer()) return; this._syncAllHiddens(); const u=this._urls(); const r=await this._post(u.preview,{}); if(r?.ok){ this.serverPreview=r.preview; } },
    async testConsecutivoServer(){ if(!this.hasServer()) return; this._syncAllHiddens(); const u=this._urls(); const r=await this._post(u.gen,{}); if(r?.ok){ this.serverPreview=r.folio; } },
    async resetConsecutivo(){ if(!this.hasServer()) return; if(!confirm('¿Reiniciar numeración de este trámite?')) return; const u=this._urls(); const r=await this._post(u.reset,{}); if(r?.ok){ this.serverPreview='Numeración reiniciada'; } },

    /* ============ Init ============ */
    init(){
      // Esperamos a que Alpine del form padre setee los :value de los hiddens
      this.$nextTick(() => {
        this.cargarConfig();
        this._syncAllHiddens();
        // Reintento corto por si el hidden se pobló un pelín después
        setTimeout(() => { this.cargarConfig(); this._syncAllHiddens(); }, 50);
      });

      // watchers profundos → cualquier cambio se refleja al backend y al padre
      this.$watch('configuracion', () => this._syncAllHiddens(), { deep: true });
      this.$watch('prefolio',      () => this._syncAllHiddens(), { deep: true });
      this.$watch('folio',         () => this._syncAllHiddens(), { deep: true });
      this.$watch('folioSeparador',() => this._syncAllHiddens());
    }
  }
}
</script>
@endverbatim
