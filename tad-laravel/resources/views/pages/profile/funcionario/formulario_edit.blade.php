@extends('layouts.app-funcionario')

@section('profile_content')
<div class="container py-4" x-data="formBuilder()">
    <h2>Constructor de Formulario para: {{ $tramite->nombre }}</h2>

    <div class="row mt-4">
        <!-- Panel de herramientas -->
        <div class="col-md-3">
            <h5>Campos disponibles</h5>
            <div class="d-grid gap-2">
                <button class="btn btn-outline-primary" @click="addField('text')">Texto</button>
                <button class="btn btn-outline-primary" @click="addField('textarea')">Párrafo</button>
                <button class="btn btn-outline-primary" @click="addField('select')">Lista</button>
                <button class="btn btn-outline-primary" @click="addField('file')">Archivo</button>
                <button class="btn btn-outline-primary" @click="addField('api')">API externa</button>
                <button class="btn btn-outline-primary" @click="addField('code')">Código personalizado</button>
            </div>
        </div>

        <!-- Formulario dinámico -->
        <div class="col-md-9">
            <form method="POST" action="{{ route('funcionario.formulario.update', $tramite->id) }}">
                @csrf

                <input type="hidden" name="estructura" :value="JSON.stringify(fields)">

                <template x-for="(field, index) in fields" :key="index">
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <strong x-text="field.label || 'Campo sin nombre'"></strong>
                            <button type="button" class="btn-close" @click="removeField(index)"></button>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <label>Etiqueta</label>
                                <input type="text" class="form-control" x-model="field.label">
                            </div>

                            <template x-if="field.type === 'text' || field.type === 'textarea'">
                                <div class="mb-2">
                                    <label>Nombre del campo</label>
                                    <input type="text" class="form-control" x-model="field.name">
                                </div>
                            </template>

                            <template x-if="field.type === 'select'">
                                <div class="mb-2">
                                    <label>Opciones (separadas por coma)</label>
                                    <input type="text" class="form-control" x-model="field.options">
                                </div>
                            </template>

                            <template x-if="field.type === 'api'">
                                <div>
                                    <label>URL de API</label>
                                    <input type="text" class="form-control mb-2" x-model="field.api_url">
                                    <label>Token / Credencial</label>
                                    <input type="text" class="form-control" x-model="field.api_token">
                                </div>
                            </template>

                            <template x-if="field.type === 'code'">
                                <div>
                                    <label>Código personalizado</label>
                                    <textarea class="form-control" x-model="field.code" rows="4" placeholder="// JS o lógica específica..."></textarea>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>

                <button type="submit" class="btn btn-success mt-3">Guardar Formulario</button>
                <a href="{{ route('funcionario.tramite_config') }}" class="btn btn-secondary mt-3">Cancelar</a>
            </form>
        </div>
    </div>
</div>

<script>
function formBuilder() {
    return {
        fields: @json(json_decode($formulario->estructura ?? '[]')),
        addField(type) {
            const field = { type, label: '', name: '', options: '', api_url: '', api_token: '', code: '' };
            this.fields.push(field);
        },
        removeField(index) {
            this.fields.splice(index, 1);
        }
    }
}
</script>
@endsection
