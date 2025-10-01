@extends('layouts.app')

@section('content')
<div class="container py-4"
     x-data="wizardForm(
        // schema del builder (array con {sections:[...]})
        @json(is_string($tramite->formulario_json) ? json_decode($tramite->formulario_json, true) : ($tramite->formulario_json ?? [])),
        {{ json_encode($tramite->id) }}
     )" x-init="init()">

  <h3 class="mb-3">{{ $tramite->nombre }}</h3>

  <form method="POST" action="{{ route('tramite.update', $tramite->id) }}" enctype="multipart/form-data" @submit="beforeSubmit">
    @csrf
    @method('PUT')

    {{-- hidden requerido por tu update ciudadano --}}
    <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
    <input type="hidden" name="respuestas_json" x-ref="answers">

    {{-- barra de pasos --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
      <template x-for="(s, i) in visibleSteps" :key="'tab-'+i">
        <span class="badge" :class="i === step ? 'bg-primary' : 'bg-secondary'"
              x-text="(i+1)+'. '+(s.name || 'Paso')"></span>
      </template>
    </div>

    {{-- PASO ACTUAL --}}
    <div class="card">
      <div class="card-header">
        <strong x-text="currentSection?.name || 'Paso'"></strong>
      </div>

      <div class="card-body">
        {{-- Campos del paso --}}
        <template x-if="currentFields.length">
          <div class="vstack gap-3">
            <template x-for="(f, idx) in currentFields" :key="idx">
              <div>
                <label class="form-label" x-text="f.label || f.name || 'Campo'"></label>

                {{-- tipos básicos --}}
                <template x-if="['text','date','search'].includes(f.type)">
                  <input class="form-control" type="text"
                         :name="inputName(f)"
                         :required="!!f.required"
                         x-model="answers[inputName(f)]">
                </template>

                <template x-if="f.type === 'textarea'">
                  <textarea class="form-control" rows="3"
                            :name="inputName(f)"
                            :required="!!f.required"
                            x-model="answers[inputName(f)]"></textarea>
                </template>

                {{-- lista / radio / checkbox --}}
                <template x-if="['select'].includes(f.type)">
                  <select class="form-select"
                          :name="inputName(f)"
                          x-model="answers[inputName(f)]">
                    <option value="">-- Seleccionar --</option>
                    <template x-for="opt in (Array.isArray(f.options)?f.options:[])">
                      <option :value="opt" x-text="opt"></option>
                    </template>
                  </select>
                </template>

                <template x-if="f.type === 'radio'">
                  <div class="d-flex flex-wrap gap-3">
                    <template x-for="opt in (Array.isArray(f.options)?f.options:[])">
                      <label class="form-check">
                        <input class="form-check-input" type="radio" :name="inputName(f)" :value="opt"
                               x-model="answers[inputName(f)]">
                        <span class="form-check-label" x-text="opt"></span>
                      </label>
                    </template>
                  </div>
                </template>

                <template x-if="f.type === 'checkbox'">
                  <div class="d-flex flex-column gap-2">
                    <template x-for="opt in (Array.isArray(f.options)?f.options:[])">
                      <label class="form-check">
                        <input class="form-check-input" type="checkbox"
                               :value="opt"
                               @change="toggleCheckbox(inputName(f), opt)">
                        <span class="form-check-label" x-text="opt"></span>
                      </label>
                    </template>
                  </div>
                </template>

                {{-- archivo --}}
                <template x-if="f.type === 'file'">
                  <input class="form-control" type="file"
                         :name="inputName(f)+(f.multiple? '[]':'')"
                         :multiple="!!f.multiple"
                         :accept="f.accept || undefined">
                </template>

                {{-- texto enriquecido solo lectura en este flujo --}}
                <template x-if="f.type === 'richtext'">
                  <div class="border rounded p-2" x-html="tryParseRich(f.content)"></div>
                </template>

              </div>
            </template>
          </div>
        </template>

        {{-- Paso de confirmación --}}
        <template x-if="isConfirm">
          <div>
            <h5 class="mb-3">Confirmar</h5>
            <div class="table-responsive">
              <table class="table table-sm">
                <tbody>
                  <template x-for="(val, key) in answers" :key="key">
                    <tr>
                      <th class="w-25" x-text="key"></th>
                      <td x-text="Array.isArray(val) ? val.join(', ') : val"></td>
                    </tr>
                  </template>
                </tbody>
              </table>
            </div>
            <div class="alert alert-info mb-0">
              Revisá tus datos y presioná <strong>Guardar</strong> para enviar.
            </div>
          </div>
        </template>

      </div>

      <div class="card-footer d-flex justify-content-between">
        <button type="button" class="btn btn-outline-secondary" :disabled="!canBack" @click="prev">Volver</button>

        <div class="d-flex gap-2">
          <button type="button" class="btn btn-primary" x-show="!isConfirm" @click="next">Siguiente</button>
          <button type="submit" class="btn btn-success" x-show="isConfirm">Guardar</button>
        </div>
      </div>
    </div>
  </form>
</div>

<script>
function wizardForm(schema, tramiteId){
  return {
    raw: schema && schema.sections ? schema : { sections: [] },
    // sólo se muestran secciones NO activables
    steps: [],
    step: 0,
    answers: {},

    init(){
      // normalizar
      this.steps = (this.raw.sections || []).filter(s => !s.activable);
      if (!this.steps.length) this.steps = [{ name: 'Inicio del trámite', fields: [] }];
      this.step = 0;
    },

    get visibleSteps(){ return this.steps; },
    get currentSection(){ return this.isConfirm ? { name:'Confirmar', fields: [] } : this.steps[this.step] || null; },
    get currentFields(){ return (this.currentSection?.fields || []); },
    get canBack(){ return this.step > 0; },
    get isConfirm(){ return this.step === this.steps.length; },

    inputName(f){
      const sName = (this.currentSection?.name || 'seccion').trim();
      const base  = (f.name || f.label || 'campo').trim();
      return `${sName}::${base}`;
    },

    toggleCheckbox(key, opt){
      const arr = Array.isArray(this.answers[key]) ? this.answers[key] : [];
      const idx = arr.indexOf(opt);
      if (idx === -1) arr.push(opt); else arr.splice(idx,1);
      this.answers[key] = arr;
    },

    // JSON de EditorJS a HTML mínimo (solo para vista)
    tryParse(value){ try { return JSON.parse(value); } catch { return null; } },
    tryParseRich(content){
      const data = this.tryParse(content);
      if(!data || !Array.isArray(data.blocks)) return '';
      return data.blocks.map(b=>{
        if (b.type==='header')  return `<h5>${b.data.text||''}</h5>`;
        if (b.type==='paragraph') return `<p>${b.data.text||''}</p>`;
        if (b.type==='list'){
          const tag = b.data.style==='ordered' ? 'ol' : 'ul';
          const items = (b.data.items||[]).map(i=>`<li>${i}</li>`).join('');
          return `<${tag}>${items}</${tag}>`;
        }
        return '';
      }).join('');
    },

    // ---------- navegación con ramificación ----------
    next(){
      // Buscar destino por regla general del último campo con condition
      // o por regla por opción (conditions)
      let target = null;

      for (const f of this.currentFields){
        const val = this.answers[this.inputName(f)];
        // Regla por opción (si aplica)
        if (f.conditions && typeof f.conditions==='object' && val){
          const key = Array.isArray(val) ? val[0] : String(val);
          if (key && f.conditions[key]) {
            target = f.conditions[key];
          }
        }
        // Regla general
        if (!target && f.condition){
          target = f.condition;
        }
      }

      if (target === '__CONFIRM__'){
        this.step = this.steps.length; // ir a confirmar
        return;
      }

      if (target){
        const idx = this.steps.findIndex(s => (s.name||'').trim() === String(target).trim());
        if (idx >= 0){ this.step = idx; return; }
      }

      // avance lineal
      if (this.step < this.steps.length) this.step += 1;
    },

    prev(){ if (this.step > 0) this.step -= 1; },

    beforeSubmit(){
      // empaqueta respuestas + snapshot del schema por si hace falta en back
      this.$refs.answers.value = JSON.stringify({
        schema: this.raw,
        values: this.answers
      });
    }
  }
}
</script>
@endsection
