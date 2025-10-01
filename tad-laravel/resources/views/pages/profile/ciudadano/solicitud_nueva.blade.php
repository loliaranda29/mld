@extends('layouts.app')

@section('content')
<div class="container py-4"
     x-data="wizardForm(@js($sections))"
     x-init="init()">

    <h3 class="mb-3">{{ $tramite->nombre }}</h3>

    {{-- si no hay secciones configuradas --}}
    <template x-if="!steps.length">
        <div class="alert alert-warning">
            Este trámite todavía no tiene formulario configurado.
        </div>
    </template>

    {{-- WIZARD --}}
    <template x-if="steps.length">
        <form method="POST"
              action="{{ route('profile.solicitudes.store') }}"
              enctype="multipart/form-data"
              x-on:submit="beforeSubmit">
            @csrf

            {{-- se envía el trámite que se está iniciando --}}
            <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">

            {{-- aquí se irán creando los inputs ocultos form[xxx] --}}
            <div x-ref="answers"></div>

            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong x-text="steps[current].name || ('Sección '+(current+1))"></strong>
                </div>
                <div class="card-body">

                    {{-- campos de la sección actual --}}
                    <template x-for="(f, i) in (steps[current].fields || [])" :key="i">
                        <div class="mb-3">
                            <label class="form-label" x-text="f.label || f.name"></label>

                            {{-- texto / textarea --}}
                            <template x-if="!['select','file','date'].includes((f.type||'').toLowerCase())">
                                <input class="form-control"
                                       :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                                       :value="getVal(f)"
                                       @input="setVal(f, $event.target.value)">
                            </template>

                            <template x-if="(f.type||'').toLowerCase()==='textarea'">
                                <textarea class="form-control"
                                          :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                                          rows="3"
                                          @input="setVal(f, $event.target.value)"
                                          x-text="getVal(f)"></textarea>
                            </template>

                            {{-- select --}}
                            <template x-if="(f.type||'').toLowerCase()==='select'">
                                <select class="form-select"
                                        :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                                        @change="onSelectChange(f, $event.target.value)"
                                        x-init="$nextTick(()=>onSelectChange(f, getVal(f)))">
                                    <option value="">— Seleccionar —</option>
                                    <template x-for="opt in (f.options||[])" :key="opt">
                                        <option :value="opt" x-text="opt" :selected="getVal(f)===opt"></option>
                                    </template>
                                </select>
                                <small class="text-muted" x-show="f.condition || (f.conditions && Object.keys(f.conditions||{}).length)">
                                    Esta respuesta puede derivar a otra sección.
                                </small>
                            </template>

                            {{-- fecha --}}
                            <template x-if="(f.type||'').toLowerCase()==='date'">
                                <input type="date" class="form-control"
                                       :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                                       :value="getVal(f) || ''"
                                       @input="setVal(f, $event.target.value)">
                            </template>

                            {{-- archivo --}}
                            <template x-if="(f.type||'').toLowerCase()==='file'">
                                <input type="file"
                                       class="form-control"
                                       :multiple="!!f.multiple"
                                       :name="fileInputName(f)"
                                       @change="setFileVal(f, $event.target.files)">
                            </template>
                        </div>
                    </template>
                </div>

                <div class="card-footer d-flex justify-content-between">
                    <div>
                        <a class="btn btn-light" href="{{ route('profile.tramites.ficha', $tramite->id) }}">Volver</a>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-secondary"
                                :disabled="current===0"
                                @click="prevStep">Anterior</button>

                        <button type="button" class="btn btn-primary"
                                x-show="current < steps.length - 1"
                                @click="nextStep">Siguiente</button>

                        <button type="submit" class="btn btn-success"
                                x-show="current === steps.length - 1">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
    </template>
</div>

<script>
function wizardForm(sectionsFromServer) {
    return {
        steps: Array.isArray(sectionsFromServer) ? sectionsFromServer : [],
        current: 0,
        // objeto con respuestas { name: value }
        model: {},

        init() {
            // precargar valores (si el admin definió defaults en el futuro)
            (this.steps || []).forEach((s, si) => {
                (s.fields || []).forEach((f, fi) => {
                    const key = this.keyFor(f, si, fi);
                    if (this.model[key] === undefined) this.model[key] = f.value ?? '';
                });
            });
            this.renderHiddenInputs();
        },

        keyFor(f, si, fi) {
            return (f && f.name) ? String(f.name) : `s${si}_f${fi}`;
        },

        getVal(f, si, fi) {
            const k = this.keyFor(f, si ?? this.current, fi ?? 0);
            return this.model[k] ?? '';
        },

        setVal(f, v, si, fi) {
            const k = this.keyFor(f, si ?? this.current, fi ?? 0);
            this.model[k] = v;
            this.renderHiddenInputs();
        },

        fileInputName(f, si, fi) {
            // El backend espera files[name] (y múltiple si corresponde)
            return `files[${this.keyFor(f, si ?? this.current, fi ?? 0)}]${f.multiple ? '[]' : ''}`;
        },

        setFileVal(f, fileList, si, fi) {
            // Nada que hacer acá: los archivos viajan por el input file.
            // Solo reflejamos un "placeholder" en model para no perder el resto.
            const k = this.keyFor(f, si ?? this.current, fi ?? 0);
            this.model[k] = '[file]';
            this.renderHiddenInputs();
        },

        // Derivaciones al cambiar un select
        onSelectChange(f, value) {
            this.setVal(f, value);
            const condMap = (f.conditions && typeof f.conditions === 'object') ? f.conditions : {};
            const direct   = f.condition || '';

            let goto = '';
            if (value && condMap[value]) {
                goto = condMap[value];
            } else if (direct) {
                goto = direct;
            }

            if (!goto) return;

            if (goto === '__CONFIRM__') {
                // ir al último paso
                this.current = this.steps.length - 1;
                return;
            }
            // buscar índice de la sección destino por nombre
            const idx = (this.steps || []).findIndex(s => (s.name||'').trim() === String(goto).trim());
            if (idx >= 0) this.current = idx;
        },

        prevStep() {
            if (this.current > 0) this.current--;
        },

        nextStep() {
            if (this.current < this.steps.length - 1) this.current++;
        },

        // crea inputs ocultos tipo form[clave] con los valores actuales (excepto archivos)
        renderHiddenInputs() {
            const holder = this.$refs.answers;
            if (!holder) return;
            const parts = [];
            Object.entries(this.model).forEach(([k, v]) => {
                // Los archivos viajan por <input type="file">, acá solo mando los demás
                if (v !== '[file]') {
                    const safe = String(v ?? '');
                    parts.push(`<input type="hidden" name="form[${this.escape(k)}]" value="${this.escape(safe)}">`);
                }
            });
            holder.innerHTML = parts.join('');
        },

        escape(s) {
            return String(s)
              .replace(/&/g, '&amp;')
              .replace(/</g, '&lt;')
              .replace(/>/g, '&gt;')
              .replace(/"/g, '&quot;')
              .replace(/'/g, '&#39;');
        },

        beforeSubmit() {
            // asegurar que los ocultos estén actualizados justo antes de enviar
            this.renderHiddenInputs();
            // NO preventDefault: dejamos que el navegador haga el POST
        }
    }
}
</script>
@endsection
