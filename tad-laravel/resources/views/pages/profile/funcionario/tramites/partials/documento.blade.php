<div x-data="documentoSalida()" x-init="init()" class="row">
    <!-- Panel izquierdo: herramientas -->
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header bg-dark text-white">Documento de salida</div>
            <div class="card-body">
                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#estructura">Estructura</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#catalogo">Catálogo</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="estructura">
                        <div class="mb-2"><strong>Columnas</strong></div>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('columna1')">1 columna</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('columna2')">2 columnas</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('columna3')">3 columnas</button>
                        </div>
                        <div class="mb-2"><strong>Estilos</strong></div>
                        <div class="d-flex flex-wrap gap-2">
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('h1')">Titular</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('texto')">Texto</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('linea')">Línea de división</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('imagen')">Imagen</button>
                            <button class="btn btn-outline-secondary btn-sm" @click="agregarBloque('salto')">Salto de página</button>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="catalogo">
                        <div class="accordion" id="catalogoAcordeon">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#cat1">Guía - Catálogo del trámite</button>
                                </h2>
                                <div id="cat1" class="accordion-collapse collapse show">
                                    <div class="accordion-body">
                                        <button class="btn btn-outline-primary btn-sm w-100 mb-1" @click="agregarBloque('folio')">Folio de la solicitud</button>
                                        <button class="btn btn-outline-primary btn-sm w-100 mb-1" @click="agregarBloque('descripcion')">Descripción</button>
                                        <button class="btn btn-outline-primary btn-sm w-100 mb-1" @click="agregarBloque('titulo')">Título</button>
                                        <button class="btn btn-outline-primary btn-sm w-100 mb-1" @click="agregarBloque('dependencia')">Dependencia</button>
                                    </div>
                                </div>
                            </div>
                            <!-- Otros grupos: Perfil ciudadano, Formularios, Firmas, etc. -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Panel derecho: editor visual -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white">Editor visual</div>
            <div class="card-body bg-light" style="min-height: 400px">
                <template x-for="(bloque, i) in bloques" :key="i">
                    <div class="border p-2 rounded mb-2 bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong x-text="bloque.tipo"></strong>
                            <div>
                                <button class="btn btn-sm btn-outline-danger" @click="eliminarBloque(i)">🗑️</button>
                            </div>
                        </div>
                        <div class="mt-2" x-show="bloque.tipo === 'texto'">
                            <textarea class="form-control" x-model="bloque.contenido" rows="3"></textarea>
                        </div>
                        <!-- Otros tipos de contenido pueden ir aquí... -->
                    </div>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function documentoSalidaBuilder() {
    return {
        bloques: [],
        agregarBloque(tipo) {
            this.bloques.push({ tipo: tipo, contenido: '' });
        },
        eliminarBloque(index) {
            this.bloques.splice(index, 1);
        },
        init() {
            // Inicializar si hace falta
        }
    };
}
</script>
