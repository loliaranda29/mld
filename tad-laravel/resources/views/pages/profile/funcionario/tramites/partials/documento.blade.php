{{-- resources/views/pages/profile/funcionario/tramites/partials/documento.blade.php --}}
<div id="tab-documento" x-data="documentoSalidaBuilder()" x-init="init()" class="row g-3">

  {{-- IZQUIERDA: Herramientas --}}
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-dark text-white">Documento de salida</div>
      <div class="card-body p-0">
        <ul class="nav nav-tabs px-3 pt-3" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#doc-estructura" type="button" role="tab">
              Estructura
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#doc-catalogo" type="button" role="tab">
              Catálogo
            </button>
          </li>
        </ul>

        <div class="tab-content p-3">
          {{-- PESTAÑA: ESTRUCTURA --}}
          <div class="tab-pane fade show active" id="doc-estructura" role="tabpanel">
            <div class="mb-3">
              <h6 class="text-uppercase text-muted mb-2">Columnas</h6>
              <div class="d-grid gap-2">
                <button class="btn btn-outline-secondary" @click="agregarBloque('col-1')">1 columna</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('col-2')">2 columnas</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('col-3')">3 columnas</button>
              </div>
            </div>

            <div>
              <h6 class="text-uppercase text-muted mb-2">Estilos</h6>
              <div class="d-grid gap-2">
                <button class="btn btn-outline-secondary" @click="agregarBloque('h1')">H1 (Titular)</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('texto')">Texto</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('separador')">Separador de secciones</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('linea')">Línea de división</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('imagen')">Imagen</button>
                <button class="btn btn-outline-secondary" @click="agregarBloque('salto')">Salto de página</button>
              </div>
            </div>
          </div>

          {{-- PESTAÑA: CATÁLOGO --}}
          <div class="tab-pane fade" id="doc-catalogo" role="tabpanel">
            <div class="accordion" id="accCatalogo">

              {{-- Guía --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g0">
                  <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#c0">
                    Guía – Catálogo del trámite
                  </button>
                </h2>
                <div id="c0" class="accordion-collapse collapse show" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('folio')">Folio de la solicitud</button>
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('descripcion')">Descripción</button>
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('titulo')">Título</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Perfil ciudadano --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g1">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c1">
                    Perfil ciudadano
                  </button>
                </h2>
                <div id="c1" class="accordion-collapse collapse" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <template x-for="campo in catalogoPerfil" :key="campo">
                        <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo(campo)" x-text="labelCampo(campo)"></button>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Formulario ciudadano --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                    Formulario ciudadano
                  </button>
                </h2>
                <div id="c2" class="accordion-collapse collapse" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('nota_importante')">IMPORTANTE</button>
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('selector_tramite')">Seleccione tipo de trámite</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Campo abierto --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g3">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c3">
                    Campo abierto
                  </button>
                </h2>
                <div id="c3" class="accordion-collapse collapse" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('dictamenes')">Dictámenes</button>
                      <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('observaciones')">Observaciones de emisión</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Firmas / Blockchain --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g4">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c4">
                    Firmas
                  </button>
                </h2>
                <div id="c4" class="accordion-collapse collapse" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <template x-for="campo in catalogoFirmas" :key="campo">
                        <button class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo(campo)" x-text="labelCampo(campo)"></button>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

            </div> {{-- /accordion --}}
          </div> {{-- /tab-pane catálogo --}}
        </div> {{-- /tab-content --}}
      </div> {{-- /card-body --}}
    </div> {{-- /card --}}
  </div> {{-- /col --}}

  {{-- DERECHA: Editor / Preview --}}
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span>Editor visual</span>
        <div>
          <button class="btn btn-sm btn-outline-light me-2" @click="persistir()">💾 Guardar</button>
          <button class="btn btn-sm btn-outline-light" @click="cargar()">📂 Cargar</button>
        </div>
      </div>

      <div class="card-body bg-light">
        {{-- Banda amarilla superior como en el sistema original --}}
        <div class="rounded border mb-3 bg-white">
          <div class="p-2" style="background:#ffe54d;"></div>
          <div class="p-3">
            <template x-for="(bloque, i) in bloques" :key="i">
              <div class="border rounded p-2 mb-2">
                <div class="d-flex justify-content-between align-items-center">
                  <strong x-text="labelBloque(bloque)"></strong>
                  <div>
                    <button class="btn btn-sm btn-outline-secondary me-1" @click="editar(i)">⚙️</button>
                    <button class="btn btn-sm btn-outline-danger" @click="eliminar(i)">🗑️</button>
                  </div>
                </div>

                {{-- Campos simples --}}
                <template x-if="bloque.tipo === 'h1'">
                  <input type="text" class="form-control mt-2" x-model="bloque.contenido" placeholder="Titular...">
                </template>
                <template x-if="bloque.tipo === 'texto'">
                  <textarea class="form-control mt-2" rows="3" x-model="bloque.contenido" placeholder="Texto..."></textarea>
                </template>

                {{-- Opciones comunes --}}
                <div class="row g-2 mt-2" x-show="bloque.editando">
                  <div class="col-md-6">
                    <label class="form-label mb-1">Clase CSS</label>
                    <input type="text" class="form-control" x-model="bloque.css" placeholder="text-center text-muted">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label mb-1">Min alto (px)</label>
                    <input type="number" class="form-control" x-model.number="bloque.min">
                  </div>
                  <div class="col-md-3">
                    <label class="form-label mb-1">Max alto (px)</label>
                    <input type="number" class="form-control" x-model.number="bloque.max">
                  </div>
                </div>
              </div>
            </template>

            <div class="text-center py-4" x-show="!bloques.length">
              <small class="text-muted">Agregá bloques desde el panel izquierdo.</small>
            </div>
          </div>
        </div>
      </div>

      {{-- Persistencia JSON (para el submit del trámite) --}}
      <input type="hidden" name="documento_salida_json" :value="JSON.stringify(bloques)">
    </div>
  </div>

</div>


@pushOnce('scripts')
<script>
function documentoSalidaBuilder() {
  return {
    bloques: [],

    catalogoPerfil: [
      'cuil','nombre','nacimiento','email','telefono_cel','telefono_fijo',
      'codigo_postal','barrio','provincia','municipio','calle','numero','depto','referencias'
    ],
    catalogoFirmas: [
      'qr','id_emisor','fecha_certificacion','emision_data','contrato_emisor',
      'hash_certificado','llave_certificado'
    ],

    labelCampo(k) {
      const m = {
        cuil:'CUIL', nombre:'Nombre', nacimiento:'Fecha de nacimiento', email:'Email de contacto',
        telefono_cel:'Teléfono celular', telefono_fijo:'Teléfono fijo',
        codigo_postal:'Código postal', barrio:'Barrio', provincia:'Provincia',
        municipio:'Municipio', calle:'Calle', numero:'Número exterior', depto:'Depto.',
        referencias:'Referencias',
        qr:'QR', id_emisor:'ID Emisor', fecha_certificacion:'Fecha de certificación',
        emision_data:'certificateTemplate.emisionData',
        contrato_emisor:'Contrato inteligente emisor de credencial en Blockchain',
        hash_certificado:'Hash único de certificado', llave_certificado:'Llave única de certificado'
      };
      return m[k] ?? k;
    },

    labelBloque(b) {
      const t = b.tipo;
      if (t?.startsWith('col-')) return `${t.replace('col-','')} columna(s)`;
      const map = { h1:'Titular (H1)', texto:'Texto', separador:'Separador', linea:'Línea', imagen:'Imagen', salto:'Salto de página' };
      return map[t] ?? this.labelCampo(t) ?? t;
    },

    // Acciones
    agregarBloque(tipo) {
      const base = { tipo, contenido:'', css:'', min:null, max:null, editando:false };
      this.bloques.push(base);
    },
    agregarBloqueCatalogo(clave) {
      this.bloques.push({ tipo: clave, contenido:'', css:'', min:null, max:null, editando:false });
    },
    editar(i){ this.bloques[i].editando = !this.bloques[i].editando; },
    eliminar(i){ this.bloques.splice(i,1); },

    // Persistencia
    persistir(){
      localStorage.setItem('documentoSalida', JSON.stringify(this.bloques));
    },
    cargar(){
      const raw = localStorage.getItem('documentoSalida');
      if (raw) this.bloques = JSON.parse(raw);
    },

    init(){ this.cargar(); }
  }
}
</script>
@endpushOnce
