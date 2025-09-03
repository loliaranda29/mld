{{-- resources/views/pages/profile/funcionario/tramites/partials/documento.blade.php --}} 
<div id="tab-documento" x-data="documentoSalidaBuilder()" x-init="init()" class="row g-3">

  {{-- ========= IZQUIERDA: Herramientas ========= --}}
  <div class="col-lg-4">
    <div class="card h-100">
      <div class="card-header bg-dark text-white">Documento de salida</div>

      <div class="card-body p-0">
        <ul class="nav nav-tabs px-3 pt-3" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" type="button" data-bs-toggle="tab" data-bs-target="#doc-estructura" role="tab">
              Estructura
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" type="button" data-bs-toggle="tab" data-bs-target="#doc-catalogo" role="tab">
              Catálogo
            </button>
          </li>
        </ul>

        <div class="tab-content p-3">

          {{-- ===== ESTRUCTURA (más visual) ===== --}}
          <div class="tab-pane fade show active" id="doc-estructura" role="tabpanel">
            <div class="mb-3">
              <h6 class="text-uppercase text-muted mb-2">Columnas</h6>
              <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('col-1')">
                  <div class="d-flex align-items-center gap-2">
                    <span class="border border-2 border-secondary-subtle w-100 p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <small class="text-muted">1 columna</small>
                  </div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('col-2')">
                  <div class="d-flex align-items-center gap-2 w-100">
                    <span class="flex-fill border border-2 border-secondary-subtle p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <span class="flex-fill border border-2 border-secondary-subtle p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <small class="ms-2 text-muted">2 columnas</small>
                  </div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('col-3')">
                  <div class="d-flex align-items-center gap-2 w-100">
                    <span class="flex-fill border border-2 border-secondary-subtle p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <span class="flex-fill border border-2 border-secondary-subtle p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <span class="flex-fill border border-2 border-secondary-subtle p-2" style="outline:2px dashed #cfd8dc;"></span>
                    <small class="ms-2 text-muted">3 columnas</small>
                  </div>
                </button>
              </div>
            </div>

            <div>
              <h6 class="text-uppercase text-muted mb-2">Estilos</h6>
              <div class="d-grid gap-2">
                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('h1')">
                  <div class="fw-bold fs-5">Titular</div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('texto')">
                  <div class="text-muted">Texto</div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('separador')">
                  <div class="w-100 py-1 bg-light border rounded text-center">Separador de secciones</div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('linea')">
                  <div class="w-100"><hr class="m-0"></div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('imagen')">
                  <div class="text-muted">Imagen</div>
                </button>

                <button type="button" class="btn btn-outline-secondary" @click="agregarBloque('salto')">
                  <div class="w-100 py-1 bg-secondary-subtle border rounded text-center">Salto de página</div>
                </button>
              </div>
            </div>
          </div>

          {{-- ===== CATÁLOGO ===== --}}
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
                      <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('folio')">Folio de la solicitud</button>
                      <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('descripcion')">Descripción</button>
                      <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('titulo')">Título</button>
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
                        <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo(campo)" x-text="labelCampo(campo)"></button>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Formulario ciudadano (dinámico) --}}
              <div class="accordion-item">
                <h2 class="accordion-header" id="g2">
                  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#c2">
                    Formulario ciudadano
                  </button>
                </h2>
                <div id="c2" class="accordion-collapse collapse" data-bs-parent="#accCatalogo">
                  <div class="accordion-body p-2">
                    <div class="d-grid gap-2">
                      <template x-if="!camposFormulario.length">
                        <div class="text-muted small">
                          No se detectaron campos del formulario. Guardá/abrí la pestaña “Formulario” o verificá el JSON.
                        </div>
                      </template>

                      <template x-for="item in camposFormulario" :key="item.key">
                        <button type="button" class="btn btn-outline-primary btn-sm text-start" @click="agregarBloqueCatalogo(item.key)">
                          <span class="d-block fw-semibold" x-text="item.label"></span>
                          <small class="text-muted" x-text="item.grupo || 'Formulario ciudadano'"></small>
                        </button>
                      </template>
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
                      <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('dictamenes')">Dictámenes</button>
                      <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo('observaciones')">Observaciones de emisión</button>
                    </div>
                  </div>
                </div>
              </div>

              {{-- Firmas --}}
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
                        <button type="button" class="btn btn-outline-primary btn-sm" @click="agregarBloqueCatalogo(campo)" x-text="labelCampo(campo)"></button>
                      </template>
                    </div>
                  </div>
                </div>
              </div>

            </div>{{-- /accordion --}}
          </div>{{-- /tab catálogo --}}
        </div>{{-- /tab-content --}}
      </div>
    </div>
  </div>

  {{-- ========= DERECHA: Editor / Preview ========= --}}
  <div class="col-lg-8">
    <div class="card h-100">
      <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <span>Editor visual</span>
        <div>
          <button type="button" class="btn btn-sm btn-outline-light me-2" @click="persistir()">💾 Guardar</button>
          <button type="button" class="btn btn-sm btn-outline-light" @click="cargar()">📂 Cargar</button>
        </div>
      </div>

      <div class="card-body bg-light">

        {{-- Banda amarilla superior + hoja A4 centrada --}}
        <div class="doc-sheet rounded border mb-3 bg-white mx-auto shadow-sm">
          <div class="doc-band"></div>

          <div class="p-3">

            {{-- ===== WRAPPER CANVAS (Sortable raíz) ===== --}}
            <ul class="list-unstyled m-0 p-0" x-ref="canvas">

              <template x-for="(bloque, i) in bloques" :key="i">
                <li class="position-relative border rounded p-0 mb-3">

                  {{-- ======= TOOLBAR CONTEXTUAL (raíz) ======= --}}
                  <div class="d-flex align-items-center gap-3 px-3 py-2 bg-light border-bottom rounded-top">
                    <div class="small text-muted">Alineación</div>
                    <div class="btn-group btn-group-sm" role="group">
                      <button type="button" class="btn btn-outline-secondary" :class="{'active': bloque.align==='start'}"  @click="setAlign(i,'start')">≡</button>
                      <button type="button" class="btn btn-outline-secondary" :class="{'active': bloque.align==='center'}" @click="setAlign(i,'center')">≣</button>
                      <button type="button" class="btn btn-outline-secondary" :class="{'active': bloque.align==='end'}"    @click="setAlign(i,'end')">≡→</button>
                    </div>

                    <div class="vr mx-2"></div>

                    <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" @click="eliminar(i)">🗑</button>

                    <div class="vr mx-2 d-none d-md-inline"></div>

                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" :id="'cont-'+i" x-model="bloque.isContainer">
                      <label class="form-check-label small" :for="'cont-'+i">Contenedor</label>
                    </div>

                    <div class="ms-auto d-flex align-items-center gap-3">
                      <div class="d-flex flex-column">
                        <small class="text-muted">Anchura máxima</small>
                        <input type="range" min="20" max="100" step="1" x-model.number="bloque.maxWidth" style="width:200px">
                      </div>
                      <div class="d-flex flex-column">
                        <small class="text-muted">Altura máxima</small>
                        <input type="range" min="20" max="100" step="1" x-model.number="bloque.maxHeight" style="width:200px">
                      </div>
                    </div>
                  </div>

                  {{-- ======= CONTENIDO DEL BLOQUE (preview visual raíz) ======= --}}
                  <div class="p-3"
                       :class="{'text-start': bloque.align==='start','text-center':bloque.align==='center','text-end':bloque.align==='end'}"
                       :style="previewBoxStyle(bloque)">

                    {{-- IMAGEN --}}
                    <template x-if="bloque.tipo==='imagen'">
                      <div>
                        <div class="mb-2 d-flex flex-wrap gap-2">
                          <input type="file" class="form-control form-control-sm" accept="image/*"
                                 @change="onPickImage($event, i)" style="max-width:260px">
                          <input type="text" class="form-control form-control-sm" placeholder="URL de imagen"
                                 x-model="bloque.src" @change="touchPreview(i)" style="max-width:260px">
                        </div>
                        <div class="border bg-white d-inline-block" style="min-height:120px; min-width:240px;">
                          <img :src="bloque.src || ''" alt="" style="max-width:100%; height:auto" x-show="!!bloque.src">
                          <div class="text-muted small p-3" x-show="!bloque.src">Sin imagen (cargá archivo o URL)</div>
                        </div>
                      </div>
                    </template>

                    {{-- 1 COLUMNA (raíz) --}}
                    <template x-if="bloque.tipo==='col-1'">
                      <div class="p-0">
                        <ul class="list-unstyled m-0 p-3 dropzone"
                            :data-path="`bloques.${i}.children.0`">
                          {{-- hijos de esta columna --}}
                          <template x-for="(child, j) in itemsAt(`bloques.${i}.children.0`)" :key="`c1-${i}-${j}`">
                            <li class="position-relative border rounded p-0 mb-2">
                              <div class="d-flex align-items-center gap-2 px-3 py-2 bg-light border-bottom rounded-top">
                                <div class="small text-muted">Alineación</div>
                                <div class="btn-group btn-group-sm" role="group">
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='start'}"  @click="setAlignAt(`bloques.${i}.children.0`, j,'start')">≡</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='center'}" @click="setAlignAt(`bloques.${i}.children.0`, j,'center')">≣</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='end'}"    @click="setAlignAt(`bloques.${i}.children.0`, j,'end')">≡→</button>
                                </div>
                                <div class="vr mx-2"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" @click="eliminarAt(`bloques.${i}.children.0`, j)">🗑</button>
                                <div class="ms-auto d-flex align-items-center gap-2">
                                  <small class="text-muted">W</small>
                                  <input type="range" min="20" max="100" step="1" x-model.number="child.maxWidth" style="width:120px">
                                  <small class="text-muted">H</small>
                                  <input type="range" min="20" max="100" step="1" x-model.number="child.maxHeight" style="width:120px">
                                </div>
                              </div>

                              <div class="p-3"
                                   :class="{'text-start': child.align==='start','text-center':child.align==='center','text-end':child.align==='end'}"
                                   :style="previewBoxStyle(child)">

                                {{-- IMAGEN (hijo) --}}
                                <template x-if="child.tipo==='imagen'">
                                  <div>
                                    <div class="mb-2 d-flex flex-wrap gap-2">
                                      <input type="file" class="form-control form-control-sm" accept="image/*"
                                             @change="onPickImageAt($event, `bloques.${i}.children.0`, j)" style="max-width:260px">
                                      <input type="text" class="form-control form-control-sm" placeholder="URL de imagen"
                                             x-model="child.src" @change="touchPreview(j)" style="max-width:260px">
                                    </div>
                                    <div class="border bg-white d-inline-block" style="min-height:120px; min-width:240px;">
                                      <img :src="child.src || ''" alt="" style="max-width:100%; height:auto" x-show="!!child.src">
                                      <div class="text-muted small p-3" x-show="!child.src">Sin imagen (cargá archivo o URL)</div>
                                    </div>
                                  </div>
                                </template>

                                {{-- SUBCOLUMNAS dentro de hijos (soporta una profundidad más) --}}
                                <template x-if="child.tipo==='col-1'">
                                  <div class="p-0">
                                    <ul class="list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.0`"></ul>
                                  </div>
                                </template>
                                <template x-if="child.tipo==='col-2'">
                                  <div class="d-flex gap-2">
                                    <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.0`"></ul>
                                    <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.1`"></ul>
                                  </div>
                                </template>
                                <template x-if="child.tipo==='col-3'">
                                  <div class="d-flex gap-2">
                                    <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.0`"></ul>
                                    <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.1`"></ul>
                                    <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                                        :data-path="`bloques.${i}.children.0.${j}.children.2`"></ul>
                                  </div>
                                </template>

                                {{-- TITULAR (hijo) --}}
                                <template x-if="child.tipo==='h1'">
                                  <input type="text" class="form-control form-control-lg fw-bold"
                                         x-model="child.contenido" placeholder="Escribí un titular…">
                                </template>

                                {{-- TEXTO (hijo) --}}
                                <template x-if="child.tipo==='texto'">
                                  <textarea class="form-control" rows="3" x-model="child.contenido" placeholder="Escribí texto…"></textarea>
                                </template>

                                {{-- SEPARADOR (hijo) --}}
                                <template x-if="child.tipo==='separador'">
                                  <div class="bg-light border rounded px-3 py-2 text-muted small">Separador de secciones</div>
                                </template>

                                {{-- LINEA (hijo) --}}
                                <template x-if="child.tipo==='linea'">
                                  <hr class="m-1">
                                </template>

                                {{-- SALTO (hijo) --}}
                                <template x-if="child.tipo==='salto'">
                                  <div class="bg-secondary-subtle text-dark px-2 py-1 rounded small d-inline-block">Salto de página</div>
                                </template>

                                {{-- CAMPOS DE CATÁLOGO (hijo) --}}
                                <template x-if="isCampoCatalogo(child.tipo)">
                                  <div class="border rounded p-2 bg-white d-inline-block">
                                    <span class="badge text-bg-info me-2">Campo</span>
                                    <strong x-text="labelDeCampo(child.tipo)"></strong>
                                  </div>
                                </template>

                              </div>
                            </li>
                          </template>
                        </ul>
                      </div>
                    </template>

                    {{-- 2 COLUMNAS (raíz) --}}
                    <template x-if="bloque.tipo==='col-2'">
                      <div class="d-flex gap-2">
                        <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                            :data-path="`bloques.${i}.children.0`">
                          <template x-for="(child, j) in itemsAt(`bloques.${i}.children.0`)" :key="`c2a-${i}-${j}`">
                            <li class="position-relative border rounded p-0 mb-2">
                              <div class="d-flex align-items-center gap-2 px-3 py-2 bg-light border-bottom rounded-top">
                                <div class="small text-muted">Alineación</div>
                                <div class="btn-group btn-group-sm" role="group">
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='start'}"  @click="setAlignAt(`bloques.${i}.children.0`, j,'start')">≡</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='center'}" @click="setAlignAt(`bloques.${i}.children.0`, j,'center')">≣</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='end'}"    @click="setAlignAt(`bloques.${i}.children.0`, j,'end')">≡→</button>
                                </div>
                                <div class="vr mx-2"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" @click="eliminarAt(`bloques.${i}.children.0`, j)">🗑</button>
                              </div>

                              <div class="p-3"
                                   :class="{'text-start': child.align==='start','text-center':child.align==='center','text-end':child.align==='end'}"
                                   :style="previewBoxStyle(child)">

                                {{-- tipos hijo (ver arriba) --}}
                                <template x-if="child.tipo==='imagen'">
                                  <div>
                                    <div class="mb-2 d-flex flex-wrap gap-2">
                                      <input type="file" class="form-control form-control-sm" accept="image/*"
                                             @change="onPickImageAt($event, `bloques.${i}.children.0`, j)" style="max-width:260px">
                                      <input type="text" class="form-control form-control-sm" placeholder="URL de imagen"
                                             x-model="child.src" @change="touchPreview(j)" style="max-width:260px">
                                    </div>
                                    <div class="border bg-white d-inline-block" style="min-height:120px; min-width:240px;">
                                      <img :src="child.src || ''" alt="" style="max-width:100%; height:auto" x-show="!!child.src">
                                      <div class="text-muted small p-3" x-show="!child.src">Sin imagen (cargá archivo o URL)</div>
                                    </div>
                                  </div>
                                </template>

                                <template x-if="child.tipo==='h1'">
                                  <input type="text" class="form-control form-control-lg fw-bold"
                                         x-model="child.contenido" placeholder="Escribí un titular…">
                                </template>
                                <template x-if="child.tipo==='texto'">
                                  <textarea class="form-control" rows="3" x-model="child.contenido" placeholder="Escribí texto…"></textarea>
                                </template>
                                <template x-if="child.tipo==='separador'">
                                  <div class="bg-light border rounded px-3 py-2 text-muted small">Separador de secciones</div>
                                </template>
                                <template x-if="child.tipo==='linea'"><hr class="m-1"></template>
                                <template x-if="child.tipo==='salto'">
                                  <div class="bg-secondary-subtle text-dark px-2 py-1 rounded small d-inline-block">Salto de página</div>
                                </template>
                                <template x-if="isCampoCatalogo(child.tipo)">
                                  <div class="border rounded p-2 bg-white d-inline-block">
                                    <span class="badge text-bg-info me-2">Campo</span>
                                    <strong x-text="labelDeCampo(child.tipo)"></strong>
                                  </div>
                                </template>

                              </div>
                            </li>
                          </template>
                        </ul>

                        <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                            :data-path="`bloques.${i}.children.1`">
                          <template x-for="(child, j) in itemsAt(`bloques.${i}.children.1`)" :key="`c2b-${i}-${j}`">
                            <li class="position-relative border rounded p-0 mb-2">
                              <div class="d-flex align-items-center gap-2 px-3 py-2 bg-light border-bottom rounded-top">
                                <div class="small text-muted">Alineación</div>
                                <div class="btn-group btn-group-sm" role="group">
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='start'}"  @click="setAlignAt(`bloques.${i}.children.1`, j,'start')">≡</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='center'}" @click="setAlignAt(`bloques.${i}.children.1`, j,'center')">≣</button>
                                  <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='end'}"    @click="setAlignAt(`bloques.${i}.children.1`, j,'end')">≡→</button>
                                </div>
                                <div class="vr mx-2"></div>
                                <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" @click="eliminarAt(`bloques.${i}.children.1`, j)">🗑</button>
                              </div>

                              <div class="p-3"
                                   :class="{'text-start': child.align==='start','text-center':child.align==='center','text-end':child.align==='end'}"
                                   :style="previewBoxStyle(child)">

                                <template x-if="child.tipo==='imagen'">
                                  <div>
                                    <div class="mb-2 d-flex flex-wrap gap-2">
                                      <input type="file" class="form-control form-control-sm" accept="image/*"
                                             @change="onPickImageAt($event, `bloques.${i}.children.1`, j)" style="max-width:260px">
                                      <input type="text" class="form-control form-control-sm" placeholder="URL de imagen"
                                             x-model="child.src" @change="touchPreview(j)" style="max-width:260px">
                                    </div>
                                    <div class="border bg-white d-inline-block" style="min-height:120px; min-width:240px;">
                                      <img :src="child.src || ''" alt="" style="max-width:100%; height:auto" x-show="!!child.src">
                                      <div class="text-muted small p-3" x-show="!child.src">Sin imagen (cargá archivo o URL)</div>
                                    </div>
                                  </div>
                                </template>

                                <template x-if="child.tipo==='h1'">
                                  <input type="text" class="form-control form-control-lg fw-bold"
                                         x-model="child.contenido" placeholder="Escribí un titular…">
                                </template>
                                <template x-if="child.tipo==='texto'">
                                  <textarea class="form-control" rows="3" x-model="child.contenido" placeholder="Escribí texto…"></textarea>
                                </template>
                                <template x-if="child.tipo==='separador'">
                                  <div class="bg-light border rounded px-3 py-2 text-muted small">Separador de secciones</div>
                                </template>
                                <template x-if="child.tipo==='linea'"><hr class="m-1"></template>
                                <template x-if="child.tipo==='salto'">
                                  <div class="bg-secondary-subtle text-dark px-2 py-1 rounded small d-inline-block">Salto de página</div>
                                </template>
                                <template x-if="isCampoCatalogo(child.tipo)">
                                  <div class="border rounded p-2 bg-white d-inline-block">
                                    <span class="badge text-bg-info me-2">Campo</span>
                                    <strong x-text="labelDeCampo(child.tipo)"></strong>
                                  </div>
                                </template>

                              </div>
                            </li>
                          </template>
                        </ul>
                      </div>
                    </template>

                    {{-- 3 COLUMNAS (raíz) --}}
                    <template x-if="bloque.tipo==='col-3'">
                      <div class="d-flex gap-2">
                        <template x-for="k in [0,1,2]" :key="`col3-${i}-${k}`">
                          <ul class="flex-fill list-unstyled m-0 p-3 dropzone"
                              :data-path="`bloques.${i}.children.${k}`">
                            <template x-for="(child, j) in itemsAt(`bloques.${i}.children.${k}`)" :key="`c3-${i}-${k}-${j}`">
                              <li class="position-relative border rounded p-0 mb-2">
                                <div class="d-flex align-items-center gap-2 px-3 py-2 bg-light border-bottom rounded-top">
                                  <div class="small text-muted">Alineación</div>
                                  <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='start'}"  @click="setAlignAt(`bloques.${i}.children.${k}`, j,'start')">≡</button>
                                    <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='center'}" @click="setAlignAt(`bloques.${i}.children.${k}`, j,'center')">≣</button>
                                    <button type="button" class="btn btn-outline-secondary" :class="{'active': child.align==='end'}"    @click="setAlignAt(`bloques.${i}.children.${k}`, j,'end')">≡→</button>
                                  </div>
                                  <div class="vr mx-2"></div>
                                  <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar" @click="eliminarAt(`bloques.${i}.children.${k}`, j)">🗑</button>
                                </div>

                                <div class="p-3"
                                     :class="{'text-start': child.align==='start','text-center':child.align==='center','text-end':child.align==='end'}"
                                     :style="previewBoxStyle(child)">

                                  <template x-if="child.tipo==='imagen'">
                                    <div>
                                      <div class="mb-2 d-flex flex-wrap gap-2">
                                        <input type="file" class="form-control form-control-sm" accept="image/*"
                                               @change="onPickImageAt($event, `bloques.${i}.children.${k}`, j)" style="max-width:260px">
                                        <input type="text" class="form-control form-control-sm" placeholder="URL de imagen"
                                               x-model="child.src" @change="touchPreview(j)" style="max-width:260px">
                                      </div>
                                      <div class="border bg-white d-inline-block" style="min-height:120px; min-width:240px;">
                                        <img :src="child.src || ''" alt="" style="max-width:100%; height:auto" x-show="!!child.src">
                                        <div class="text-muted small p-3" x-show="!child.src">Sin imagen (cargá archivo o URL)</div>
                                      </div>
                                    </div>
                                  </template>

                                  <template x-if="child.tipo==='h1'">
                                    <input type="text" class="form-control form-control-lg fw-bold"
                                           x-model="child.contenido" placeholder="Escribí un titular…">
                                  </template>
                                  <template x-if="child.tipo==='texto'">
                                    <textarea class="form-control" rows="3" x-model="child.contenido" placeholder="Escribí texto…"></textarea>
                                  </template>
                                  <template x-if="child.tipo==='separador'">
                                    <div class="bg-light border rounded px-3 py-2 text-muted small">Separador de secciones</div>
                                  </template>
                                  <template x-if="child.tipo==='linea'"><hr class="m-1"></template>
                                  <template x-if="child.tipo==='salto'">
                                    <div class="bg-secondary-subtle text-dark px-2 py-1 rounded small d-inline-block">Salto de página</div>
                                  </template>
                                  <template x-if="isCampoCatalogo(child.tipo)">
                                    <div class="border rounded p-2 bg-white d-inline-block">
                                      <span class="badge text-bg-info me-2">Campo</span>
                                      <strong x-text="labelDeCampo(child.tipo)"></strong>
                                    </div>
                                  </template>

                                </div>
                              </li>
                            </template>
                          </ul>
                        </template>
                      </div>
                    </template>

                    {{-- TITULAR --}}
                    <template x-if="bloque.tipo==='h1'">
                      <input type="text" class="form-control form-control-lg fw-bold"
                             x-model="bloque.contenido" placeholder="Escribí un titular…">
                    </template>

                    {{-- TEXTO --}}
                    <template x-if="bloque.tipo==='texto'">
                      <textarea class="form-control" rows="3" x-model="bloque.contenido" placeholder="Escribí texto…"></textarea>
                    </template>

                    {{-- SEPARADOR --}}
                    <template x-if="bloque.tipo==='separador'">
                      <div class="bg-light border rounded px-3 py-2 text-muted small">Separador de secciones</div>
                    </template>

                    {{-- LINEA --}}
                    <template x-if="bloque.tipo==='linea'">
                      <hr class="m-1">
                    </template>

                    {{-- SALTO DE PÁGINA --}}
                    <template x-if="bloque.tipo==='salto'">
                      <div class="bg-secondary-subtle text-dark px-2 py-1 rounded small d-inline-block">Salto de página</div>
                    </template>

                    {{-- CAMPOS DE CATÁLOGO (formulario/perfil/firmas) --}}
                    <template x-if="isCampoCatalogo(bloque.tipo)">
                      <div class="border rounded p-2 bg-white d-inline-block">
                        <span class="badge text-bg-info me-2">Campo</span>
                        <strong x-text="labelDeCampo(bloque.tipo)"></strong>
                      </div>
                    </template>

                  </div>
                </li>
              </template>

            </ul>

            <div class="text-center py-4" x-show="!bloques.length">
              <small class="text-muted">Agregá bloques desde el panel izquierdo.</small>
            </div>
          </div>
        </div>
      </div>

      {{-- JSON para submit --}}
      <input type="hidden" name="documento_salida_json" :value="JSON.stringify(bloques)">
    </div>
  </div>
</div>

@pushOnce('styles')
<style>
  .dropzone{ outline:2px dashed #cfd8dc; min-height:60px; border-radius:.375rem; }
  .bg-warning-subtle{ background:#fff3cd !important; }
  .sortable-ghost{ opacity:.6; }

  /* Apariencia tipo “hoja A4” centrada, como la plataforma actual */
  .doc-sheet{
    max-width: 794px; /* ~A4 @96dpi */
    min-height: 1123px;
    background: #fff;
  }
  .doc-band{
    height: 16px;
    background: #ffe54d;
    border-top-left-radius: .375rem;
    border-top-right-radius: .375rem;
  }
</style>
@endpushOnce

@pushOnce('scripts')
{{-- SortableJS para drag and drop --}}
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<script>
function documentoSalidaBuilder() {
  return {
    /* ======= Estado ======= */
    bloques: [],

    catalogoPerfil: [
      'cuil','nombre','nacimiento','email','telefono_cel','telefono_fijo',
      'codigo_postal','barrio','provincia','municipio','calle','numero','depto','referencias'
    ],
    catalogoFirmas: [
      'qr','id_emisor','fecha_certificacion','emision_data','contrato_emisor',
      'hash_certificado','llave_certificado'
    ],
    camposFormulario: [],

    /* ======= Labels ======= */
    labelCampo(k) {
      const m = {
        cuil:'CUIL', nombre:'Nombre', nacimiento:'Fecha de nacimiento', email:'Email',
        telefono_cel:'Teléfono celular', telefono_fijo:'Teléfono fijo',
        codigo_postal:'Código postal', barrio:'Barrio', provincia:'Provincia',
        municipio:'Municipio', calle:'Calle', numero:'Número', depto:'Depto',
        referencias:'Referencias',
        qr:'QR', id_emisor:'ID Emisor', fecha_certificacion:'Fecha de certificación',
        emision_data:'Datos de emisión',
        contrato_emisor:'Contrato emisor (Blockchain)',
        hash_certificado:'Hash de certificado', llave_certificado:'Llave de certificado'
      };
      return m[k] ?? k;
    },
    labelDeCampo(k){
      const dir = this.camposFormulario.find(x => x.key===k);
      if (dir) return dir.label;
      if (this.catalogoPerfil.includes(k)) return this.labelCampo(k);
      if (this.catalogoFirmas.includes(k)) return this.labelCampo(k);
      return k;
    },

    /* ======= Helpers visuales ======= */
    previewBoxStyle(b) {
      const mw = (b.maxWidth  ?? 100) + '%';
      const mh = (b.maxHeight ?? 100) + '%';
      const style = `max-width:${mw}; max-height:${mh};`;
      return style;
    },
    isCampoCatalogo(t){
      return this.catalogoPerfil.includes(t) ||
             this.catalogoFirmas.includes(t) ||
             this.camposFormulario.some(x => x.key===t);
    },

    /* Helpers para listas anidadas */
    itemsAt(path){
      try { return this.getArrayByPath(path) || []; } catch (e) { return []; }
    },
    setAlignAt(path, idx, where){
      const arr = this.getArrayByPath(path);
      if (arr && arr[idx]) arr[idx].align = where;
    },
    eliminarAt(path, idx){
      const arr = this.getArrayByPath(path);
      if (!arr) return;
      arr.splice(idx,1);
      this.$nextTick(()=> this.rebindNested());
    },
    onPickImageAt(evt, path, idx){
      const file = evt.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => {
        const arr = this.getArrayByPath(path);
        if (arr && arr[idx]) arr[idx].src = e.target.result;
        this.touchPreview(idx);
      };
      reader.readAsDataURL(file);
    },

    /* ======= Acciones (raíz) ======= */
    makeBlock(tipo){
      // base
      const base = {
        tipo,
        contenido: (tipo==='h1') ? '' : '',
        align: 'start',
        isContainer: true,
        maxWidth: 100,
        maxHeight: 100,
        src: '',           // para imagen
        children: []       // por compatibilidad
      };

      // columnas => hijos
      if (tipo === 'col-1') {
        base.children = [ [] ];
      } else if (tipo === 'col-2') {
        base.children = [ [], [] ];
      } else if (tipo === 'col-3') {
        base.children = [ [], [], [] ];
      } else {
        base.children = [];
      }
      return base;
    },

    agregarBloque(tipo) {
      this.bloques.push(this.makeBlock(tipo));
      this.$nextTick(()=>{ this.bindCanvas(); this.rebindNested(); });
    },
    agregarBloqueCatalogo(clave) {
      this.bloques.push({
        tipo: clave,
        contenido:'',
        align:'start',
        isContainer:true,
        maxWidth:100,
        maxHeight:100,
        children:[]
      });
      this.$nextTick(()=>{ this.bindCanvas(); this.rebindNested(); });
    },
    setAlign(i, where){ this.bloques[i].align = where; },
    eliminar(i){ this.bloques.splice(i,1); this.$nextTick(()=> this.rebindNested()); },
    onPickImage(evt, idx){
      const file = evt.target.files?.[0];
      if (!file) return;
      const reader = new FileReader();
      reader.onload = e => { this.bloques[idx].src = e.target.result; this.touchPreview(idx); };
      reader.readAsDataURL(file);
    },
    touchPreview(_i){ /* forzar re-render si hace falta */ },

    /* ======= Persistencia local ======= */
    persistir(){ localStorage.setItem('documentoSalida', JSON.stringify(this.bloques)); },
    cargar(){
      const raw = localStorage.getItem('documentoSalida');
      if (raw) { try { this.bloques = JSON.parse(raw); } catch(e){} }
    },

    /* ======= Drag & Drop ======= */
    bindCanvas(){
      const self = this;
      if (!this.$refs.canvas) return;

      // destruye si ya existe
      if (this.$refs.canvas._sortable) {
        try { this.$refs.canvas._sortable.destroy(); } catch(_) {}
      }

      const s = new Sortable(this.$refs.canvas, {
        group: { name: 'blocks', pull: true, put: true },
        animation: 150,
        ghostClass: 'bg-warning-subtle',

        onAdd(evt){
          const idx = evt.newIndex ?? self.bloques.length;
          const moved = evt.item.__data;
          if (moved) {
            self.bloques.splice(idx, 0, moved);
          } else {
            const type = evt.clone?.dataset?.type || evt.item?.dataset?.type || '';
            self.bloques.splice(idx, 0, self.makeBlock(type || 'texto'));
          }
          evt.item.remove();
          self.$nextTick(()=> self.rebindNested());
        },

        onUpdate(evt){
          const from = evt.oldIndex, to = evt.newIndex;
          if (from === undefined || to === undefined) return;
          const m = self.bloques.splice(from,1)[0];
          self.bloques.splice(to,0,m);
        },

        onRemove(evt){
          const removed = self.bloques.splice(evt.oldIndex,1)[0];
          evt.item.__data = removed;
        }
      });

      this.$refs.canvas._sortable = s;
    },

    rebindNested(){
      const self = this;

      // limpia instancias previas
      this.$root.querySelectorAll('ul[data-path]').forEach(ul => {
        if (ul._sortable) {
          try { ul._sortable.destroy(); } catch(e){}
          ul._sortable = null;
        }
      });

      // crea sortables por cada dropzone
      this.$root.querySelectorAll('ul[data-path]').forEach(ul => {
        const pathStr = ul.getAttribute('data-path'); // ej: 'bloques.0.children.1'
        const arrRef = self.getArrayByPath(pathStr);

        const s = new Sortable(ul, {
          group: { name:'blocks', pull:true, put:true },
          animation:150,
          ghostClass:'bg-warning-subtle',

          onAdd(evt){
            const idx = evt.newIndex ?? arrRef.length;
            const moved = evt.item.__data;

            if (moved) {
              arrRef.splice(idx, 0, moved);
            } else {
              const type = evt.clone?.dataset?.type || evt.item?.dataset?.type || '';
              arrRef.splice(idx, 0, self.makeBlock(type || 'texto'));
            }
            evt.item.remove();
            self.$nextTick(()=> self.rebindNested());
          },

          onUpdate(evt){
            const from = evt.oldIndex, to = evt.newIndex;
            if (from === undefined || to === undefined) return;
            const m = arrRef.splice(from,1)[0];
            arrRef.splice(to,0,m);
          },

          onRemove(evt){
            const removed = arrRef.splice(evt.oldIndex,1)[0];
            evt.item.__data = removed;
          }
        });

        ul._sortable = s;
      });
    },

    getArrayByPath(path){
      const tokens = String(path).split('.');
      let ref = this;
      for (const t of tokens){
        if (/^\d+$/.test(t)) ref = ref[+t];
        else ref = ref[t];
      }
      return ref;
    },

    /* ======= Integración con pestaña "Formulario" ======= */
    init(){
      this.cargar();
      this._cargarCamposDesdeHidden();

      // ligar Sortable al cargar
      this.$nextTick(()=>{ this.bindCanvas(); this.rebindNested(); });

      // si cambian los bloques, re-vincular nested (deep watch)
      this.$watch('bloques', () => {
        this.$nextTick(()=> this.rebindNested());
      });

      window.addEventListener('mld:formulario-updated', (e) => {
        try {
          const form = JSON.parse(e.detail || '{}');
          this._buildCatalogoFormulario(form);
        } catch (_) {}
      });
    },
    _cargarCamposDesdeHidden(){
      const input = document.querySelector('input[name="formulario_json"]');
      if (!input) return;
      try {
        const form = JSON.parse(input.value || '{}');
        this._buildCatalogoFormulario(form);
      } catch (_) { this.camposFormulario = []; }
    },
    _buildCatalogoFormulario(form){
      const items = [];
      const pushField = (f, grupo) => {
        if (!f) return;
        const key = f.name || f.key || f.codigo || f.id || null;
        const label = f.label || f.titulo || f.title || f.placeholder || key;
        if (!key) return;
        items.push({ key:String(key), label:String(label||key), grupo:grupo||null });
      };
      const walkArray = (arr,g)=>{ if(Array.isArray(arr)) arr.forEach(n=>walkNode(n,g)); };
      const walkNode = (n,g)=>{
        if(!n || typeof n!=='object') return;
        if (n.type || n.tipo || n.name || n.key) {
          if (!(n.fields || n.campos || n.columns || n.rows || n.children)) {
            return pushField(n,g);
          }
        }
        const gg = n.title || n.titulo || n.label || n.nombre || g;
        walkArray(n.steps,gg); walkArray(n.secciones,gg); walkArray(n.sections,gg);
        walkArray(n.pages,gg); walkArray(n.rows,gg); walkArray(n.columns,gg);
        walkArray(n.children,gg); walkArray(n.fields,gg); walkArray(n.campos,gg);
        walkArray(n.items,gg); walkArray(n.components,gg);
      };
      walkNode(form,null);
      const seen = new Set();
      this.camposFormulario = items.filter(it => !seen.has(it.key) && seen.add(it.key));
    }
  }
}
</script>
@endpushOnce
