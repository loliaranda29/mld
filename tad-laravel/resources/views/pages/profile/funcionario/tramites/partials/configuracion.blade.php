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

                        <button type="button" class="btn btn-sm btn-success" @click="guardarConfig()">Guardar configuración</button>
                    </div>
                </div>
            </div>

            <!-- Prefolio -->
            <div class="tab-pane fade" id="prefolio">
                <div class="card">
                    <div class="card-header bg-primary text-white">Configuración de prefolio</div>
                    <div class="card-body">
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" @click="agregarCampoPrefolio()">Agregar campo</button>
                        <div class="mb-3" x-text="previewPrefolio"></div>
                        <template x-for="(campo, i) in prefolio" :key="campo.id ?? i">
                            <div class="input-group mb-2">
                                <input type="text" class="form-control" x-model="campo.valor" @input="onChange()">
                                <button type="button" class="btn btn-outline-danger" @click="eliminarCampoPrefolio(i)">X</button>
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
                        <select class="form-select mb-3" x-model="folioSeparador" @change="onChange()">
                            <option>-</option>
                            <option>/</option>
                            <option> </option>
                        </select>
                        <button type="button" class="btn btn-sm btn-outline-primary mb-3" @click="agregarCampoFolio()">Agregar campo</button>
                        <div class="mb-3" x-text="previewFolio"></div>
                        <template x-for="(campo, i) in folio" :key="campo.id ?? i">
                            <div class="card mb-2 p-2">
                                <label>Tipo:</label>
                                <select class="form-select mb-1" x-model="campo.tipo" @change="onChange()">
                                    <option>Alfanumérico</option>
                                    <option>Número consecutivo</option>
                                    <option>Fecha</option>
                                </select>
                                <input type="text" class="form-control mb-1" x-model="campo.valor" @input="onChange()">
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="eliminarCampoFolio(i)">Eliminar</button>
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
      nombreBoton: '',
      requiereNivel2: false,
      dependencia: '',
      subdependencia: '',
      urlTramite: ''
    },
    prefolio: [],
    folio: [],
    folioSeparador: '-',

    /* =================== Utils =================== */
    _rnd(){ return (Date.now().toString(36) + Math.random().toString(36).slice(2,6)); },
    _unescapeHtml(s){ return (typeof s==='string') ? s.replace(/&quot;/g,'"').replace(/&#39;/g,"'").replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&') : s; },
    _safeParse(raw){
      if (!raw || raw==='null' || raw==='undefined') return null;
      let txt = this._unescapeHtml(String(raw));
      try {
        let v = JSON.parse(txt);
        if (typeof v === 'string') { try { v = JSON.parse(this._unescapeHtml(v)); } catch(_){} }
        return v;
      } catch(_) { return null; }
    },
    _findHidden(name){
      const form = this.$root.closest('form');
      return form ? form.querySelector(`input[name="${name}"]`) : null;
    },
    _coerceArrays(){
      if (!Array.isArray(this.prefolio)) this.prefolio = [];
      if (!Array.isArray(this.folio)) this.folio = [];
      if (typeof this.folioSeparador !== 'string') this.folioSeparador = '-';
    },

    /* ============ Carga inicial (backend + fallback) ============ */
    cargarConfig(){
      // Fallback local (lo que ya funcionaba)
      try { const a = localStorage.getItem('tramite_config'); if (a) Object.assign(this.configuracion, JSON.parse(a)); } catch(_){}
      try { const b = localStorage.getItem('tramite_prefolio'); if (b) this.prefolio = JSON.parse(b); } catch(_){}
      try {
        const c = localStorage.getItem('tramite_folio');
        if (c) { const o = JSON.parse(c); this.folio = o.campos || []; this.folioSeparador = o.separador || this.folioSeparador; }
      } catch(_){}

      // Backend: config_json puede incluir también prefolio/folio
      const hCfg = this._findHidden('config_json');
      if (hCfg && hCfg.value) {
        const cfg = this._safeParse(hCfg.value);
        if (cfg && typeof cfg === 'object') {
          // normalizar llaves comunes a tu shape
          if (cfg.nombre_boton && !cfg.nombreBoton) cfg.nombreBoton = String(cfg.nombre_boton);
          if (cfg.url_tramite && !cfg.urlTramite)  cfg.urlTramite  = String(cfg.url_tramite);
          if (cfg.requiere_nivel2 !== undefined && cfg.requiereNivel2 === undefined) cfg.requiereNivel2 = !!(cfg.requiere_nivel2===true || cfg.requiere_nivel2==='1' || cfg.requiere_nivel2===1);

          this.configuracion = Object.assign({
            nombreBoton:'', requiereNivel2:false, dependencia:'', subdependencia:'', urlTramite:''
          }, this.configuracion, {
            nombreBoton:   cfg.nombreBoton   ?? this.configuracion.nombreBoton,
            requiereNivel2:cfg.requiereNivel2?? this.configuracion.requiereNivel2,
            dependencia:   cfg.dependencia   ?? this.configuracion.dependencia,
            subdependencia:cfg.subdependencia?? this.configuracion.subdependencia,
            urlTramite:    cfg.urlTramite    ?? this.configuracion.urlTramite
          });

          if (Array.isArray(cfg.prefolio)) this.prefolio = cfg.prefolio;
          if (cfg.folio && typeof cfg.folio==='object') {
            if (Array.isArray(cfg.folio.campos)) this.folio = cfg.folio.campos;
            if (typeof cfg.folio.separador==='string') this.folioSeparador = cfg.folio.separador;
          }
        }
      }

      // Si existen hiddens separados, también los tomamos
      const hPre = this._findHidden('prefolio_json');
      const hFol = this._findHidden('folio_json');
      const pre  = hPre ? this._safeParse(hPre.value) : null;
      const fol  = hFol ? this._safeParse(hFol.value) : null;
      if (Array.isArray(pre)) this.prefolio = pre;
      if (fol && typeof fol==='object') {
        if (Array.isArray(fol.campos)) this.folio = fol.campos;
        if (typeof fol.separador==='string') this.folioSeparador = fol.separador;
      }

      // IDs estables para cada item (mejor reactividad + keys)
      this._coerceArrays();
      this.prefolio = this.prefolio.map(p => ({ id: p.id ?? this._rnd(), valor: p.valor ?? '' }));
      this.folio    = this.folio.map(p => ({ id: p.id ?? this._rnd(), tipo: p.tipo ?? 'Alfanumérico', valor: p.valor ?? '' }));

      this._syncAllHiddens(); // reflejar al backend lo que está en pantalla
    },

    /* ============ Sync a backend (hidden inputs) ============ */
    _payloadConfig(base){
      // no pisamos otras claves del config_json (lo que guardan otras pestañas)
      base = (base && typeof base==='object' && !Array.isArray(base)) ? base : {};
      base.nombreBoton    = this.configuracion.nombreBoton;
      base.requiereNivel2 = !!this.configuracion.requiereNivel2;
      base.dependencia    = this.configuracion.dependencia;
      base.subdependencia = this.configuracion.subdependencia;
      base.urlTramite     = this.configuracion.urlTramite;
      base.prefolio       = this.prefolio.map(p => ({ valor: p.valor }));
      base.folio          = { campos: this.folio.map(p => ({ tipo:p.tipo, valor:p.valor })), separador: this.folioSeparador };
      return base;
    },
    _syncAllHiddens(){
      const hCfg = this._findHidden('config_json');
      if (hCfg) {
        const base = this._safeParse(hCfg.value) || {};
        try { hCfg.value = JSON.stringify(this._payloadConfig(base)); } catch(_) {}
      }
      const hPre = this._findHidden('prefolio_json');
      if (hPre) { try { hPre.value = JSON.stringify(this.prefolio.map(p => ({ valor:p.valor }))); } catch(_) {} }
      const hFol = this._findHidden('folio_json');
      if (hFol) { try { hFol.value = JSON.stringify({ campos: this.folio.map(p => ({ tipo:p.tipo, valor:p.valor })), separador: this.folioSeparador }); } catch(_) {} }
    },

    /* ============ Handlers ============ */
    onChange(){ this._syncAllHiddens(); },

    guardarConfig(){
      // Persistencia local opcional + sync backend
      try {
        localStorage.setItem('tramite_config', JSON.stringify(this.configuracion));
        localStorage.setItem('tramite_prefolio', JSON.stringify(this.prefolio.map(p=>({valor:p.valor}))));
        localStorage.setItem('tramite_folio', JSON.stringify({ campos: this.folio.map(p=>({tipo:p.tipo, valor:p.valor})), separador: this.folioSeparador }));
      } catch(_){}
      this._syncAllHiddens();
    },

    /* ============ Acciones ============ */
    agregarCampoPrefolio(){
      this._coerceArrays();
      if (this.prefolio.length >= 5) return;
      const arr = [...this.prefolio, { id:this._rnd(), valor:'' }];
      this.prefolio = arr; // nueva referencia => reactividad garantizada
      this.$nextTick(() => this.onChange());
    },
    eliminarCampoPrefolio(i){
      this._coerceArrays();
      const arr = this.prefolio.slice();
      arr.splice(i,1);
      this.prefolio = arr;
      this.$nextTick(() => this.onChange());
    },
    agregarCampoFolio(){
      this._coerceArrays();
      if (this.folio.length >= 5) return;
      const arr = [...this.folio, { id:this._rnd(), tipo:'Alfanumérico', valor:'' }];
      this.folio = arr;
      this.$nextTick(() => this.onChange());
    },
    eliminarCampoFolio(i){
      this._coerceArrays();
      const arr = this.folio.slice();
      arr.splice(i,1);
      this.folio = arr;
      this.$nextTick(() => this.onChange());
    },

    /* ============ Computados ============ */
    get previewPrefolio(){ return (Array.isArray(this.prefolio)?this.prefolio:[]).map(p => p.valor).join('-'); },
    get previewFolio(){ return (Array.isArray(this.folio)?this.folio:[]).map(p => p.valor).join(this.folioSeparador); },

    /* ============ Init ============ */
    init(){
      this.cargarConfig();
      // watchers profundos → cualquier cambio se refleja al backend
      this.$watch('configuracion', () => this._syncAllHiddens(), { deep: true });
      this.$watch('prefolio',      () => this._syncAllHiddens(), { deep: true });
      this.$watch('folio',         () => this._syncAllHiddens(), { deep: true });
      this.$watch('folioSeparador',() => this._syncAllHiddens());
    }
  }
}
</script>
@endverbatim
