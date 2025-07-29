@extends('layouts.app-funcionario')

@section('content')
<div class="container">
    <h2 class="mb-4">{{ isset($tramite) ? 'Editar Trámite' : 'Crear Nuevo Trámite' }}</h2>

    {{-- Tabs --}}
    <ul class="nav nav-tabs mb-4" id="tabTramite" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                type="button" role="tab" aria-controls="general" aria-selected="true">General</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="formulario-tab" data-bs-toggle="tab" data-bs-target="#formulario"
                type="button" role="tab" aria-controls="formulario" aria-selected="false">Formulario</button>
        </li>
    </ul>

    <form method="POST"
          action="{{ isset($tramite) ? route('funcionario.tramite.update', $tramite->id) : route('funcionario.tramite.store') }}">
        @csrf
        @if(isset($tramite))
            @method('PUT')
        @endif

        <div class="tab-content" id="tabTramiteContent">
            {{-- Tab General --}}
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tramite->nombre ?? '') }}" required>
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $tramite->descripcion ?? '') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="tipo" class="form-label">Tipo</label>
                    <input type="text" name="tipo" class="form-control" value="{{ old('tipo', $tramite->tipo ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="estatus" class="form-label">Estado Inicial</label>
                    <input type="text" name="estatus" class="form-control" value="{{ old('estatus', $tramite->estatus ?? '') }}">
                </div>

                <div class="mb-3">
                    <label for="mensaje" class="form-label">Mensaje</label>
                    <textarea name="mensaje" class="form-control" rows="2">{{ old('mensaje', $tramite->mensaje ?? '') }}</textarea>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="publicado" value="1" {{ old('publicado', $tramite->publicado ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Publicado</label>
                </div>

                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="disponible" value="1" {{ old('disponible', $tramite->disponible ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Disponible</label>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="mostrar_inicio" value="1" {{ old('mostrar_inicio', $tramite->mostrar_inicio ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Mostrar en Inicio</label>
                </div>
            </div>

            {{-- Tab Formulario --}}
{{-- Tab Formulario --}}
<div class="tab-pane fade" id="formulario" role="tabpanel" aria-labelledby="formulario-tab">
    <h5 class="mb-3">Constructor de Formulario</h5>

    <div x-data="formBuilder()" class="row">
        <!-- Panel de tipos de campo -->
        <div class="col-md-3">
            <h6>Campos disponibles</h6>
            <div class="d-grid gap-2">
                <button class="btn btn-outline-secondary" @click="addField('text')">Texto corto</button>
                <button class="btn btn-outline-secondary" @click="addField('textarea')">Párrafo</button>
                <button class="btn btn-outline-secondary" @click="addField('checkbox')">Casillas (checkbox)</button>
                <button class="btn btn-outline-secondary" @click="addField('radio')">Opción múltiple</button>
                <button class="btn btn-outline-secondary" @click="addField('select')">Lista desplegable</button>
                <button class="btn btn-outline-secondary" @click="addField('code')">Código personalizado</button>
                <button class="btn btn-outline-secondary" @click="addField('api')">Conexión API</button>
            </div>
        </div>

        <!-- Zona constructor -->
        <div class="col-md-9">
            <template x-for="(field, index) in fields" :key="index">
                <div class="card mb-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong x-text="field.label || 'Campo sin etiqueta'"></strong>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-primary me-1" @click="editField(index)">Editar</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeField(index)">Eliminar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Modal para editar campos -->
        <div class="modal fade" id="fieldModal" tabindex="-1" aria-hidden="true" style="display: none;" x-ref="modal">
            <div class="modal-dialog">
                <div class="modal-content" x-show="currentField !== null">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar campo</h5>
                        <button type="button" class="btn-close" @click="closeModal()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2">
                            <label>Etiqueta</label>
                            <input type="text" class="form-control" x-model="currentField.label">
                        </div>
                        <template x-if="['select', 'radio'].includes(currentField.type)">
                            <div class="mb-2">
                                <label>Opciones (separadas por coma)</label>
                                <input type="text" class="form-control" x-model="currentField.options">
                            </div>
                        </template>
                        <template x-if="currentField.type === 'api'">
                            <div class="mb-2">
                                <label>URL de API</label>
                                <input type="text" class="form-control" x-model="currentField.api_url">
                                <label class="mt-2">Credencial / Token</label>
                                <input type="text" class="form-control" x-model="currentField.api_key">
                            </div>
                        </template>
                        <template x-if="currentField.type === 'code'">
                            <div class="mb-2">
                                <label>Código personalizado</label>
                                <textarea class="form-control" rows="4" x-model="currentField.code"></textarea>
                            </div>
                        </template>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- JSON serializado -->
        <input type="hidden" name="estructura" :value="JSON.stringify(fields)">
    </div>

    <script>
    function formBuilder() {
        return {
            fields: [],
            currentField: null,
            addField(type) {
                const base = { label: '', type, options: '', api_url: '', api_key: '', code: '' };
                this.fields.push(base);
            },
            removeField(index) {
                this.fields.splice(index, 1);
            },
            editField(index) {
                this.currentField = this.fields[index];
                new bootstrap.Modal(this.$refs.modal).show();
            },
            closeModal() {
                this.currentField = null;
                bootstrap.Modal.getInstance(this.$refs.modal).hide();
            }
        };
    }
    </script>
</div>


        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">{{ isset($tramite) ? 'Actualizar' : 'Guardar' }}</button>
            <a href="{{ route('funcionario.tramite_config') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
