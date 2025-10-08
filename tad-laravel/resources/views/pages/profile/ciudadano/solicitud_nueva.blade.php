@extends('layouts.app')

@section('content')
<div class="container py-4"
     x-data="wizardForm(@js($sections))"
     x-init="init()"
     x-effect="$refs.answersJson && ($refs.answersJson.value = JSON.stringify(model))">

  {{-- CABECERA --}}
  <div class="card mb-3">
    <div class="card-body d-flex justify-content-between align-items-center">
      <div>
        <div class="h5 mb-1">{{ $tramite->nombre }}</div>
        <div class="text-muted small">
          @if(!empty($tramite->oficina_nombre))
            <span class="me-2"><i class="bi bi-building"></i> {{ $tramite->oficina_nombre }}</span>
          @endif
          <span class="me-2"><i class="bi bi-calendar3"></i> {{ now()->format('d/m/Y') }}</span>
        </div>
      </div>
      <span class="badge bg-warning text-dark">Borrador</span>
    </div>
  </div>

  {{-- Sin secciones --}}
  <template x-if="!steps.length">
    <div class="alert alert-warning">Este trámite todavía no tiene formulario configurado.</div>
  </template>

  {{-- Wizard --}}
  <template x-if="steps.length">
   <form method="POST"
      action="{{ route('profile.solicitudes.store') }}"
      enctype="multipart/form-data"
      @submit="return beforeSubmit($event)">
    @csrf
    <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
    <input type="hidden" name="answers_json" x-ref="answersJson">

      <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
          <div>
            Paso <span x-text="displayStep()"></span> de <span x-text="totalStepsDisplay()"></span>
          </div>

          <div class="d-flex flex-wrap gap-2">
            <template x-for="(s, i) in steps" :key="i">
              <button type="button"
                      class="btn btn-sm"
                      :class="i===current ? 'btn-primary' : 'btn-outline-primary'"
                      @click="goTo(i)"
                      x-text="s.name || ('Sección '+(i+1))"></button>
            </template>
            <button type="button"
                    class="btn btn-sm"
                    :class="isReview() ? 'btn-primary' : 'btn-outline-primary'"
                    @click="goToReview()">
              Repaso final
            </button>
          </div>
        </div>

        <div class="card-body">
          {{-- Sección actual --}}
          <template x-if="!isReview() && steps[current]">
            <div>
              <h5 class="mb-3" x-text="steps[current].name || ('Sección '+(current+1))"></h5>

              <template x-for="(f, i) in (steps[current].fields || [])" :key="i">
                <div class="mb-3">
                  {{-- Espejo: siempre viaja form[name] (excepto file) --}}
                  <template x-if="(f.type||'text').toLowerCase() !== 'file'">
                    <input type="hidden"
                           :name="`form[${keyFor(f, current, i)}]`"
                           :value="Array.isArray(getVal(f, current, i)) ? JSON.stringify(getVal(f, current, i)) : (getVal(f, current, i) ?? '')">
                  </template>

                  <label class="form-label d-flex align-items-center gap-2">
                    <span x-text="f.label || ('Campo '+(i+1))"></span>
                    <span class="badge bg-danger" x-show="!!f.required">obligatorio</span>
                  </label>

                  {{-- TEXT --}}
                  <template x-if="(f.type||'').toLowerCase()==='text'">
                    <input type="text" class="form-control"
                           :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                           :value="getVal(f, current, i)"
                           @input="setVal(f, $event.target.value, current, i)">
                  </template>

                  {{-- TEXTAREA --}}
                  <template x-if="(f.type||'').toLowerCase()==='textarea'">
                    <textarea class="form-control"
                              :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                              rows="3"
                              @input="setVal(f, $event.target.value, current, i)"
                              x-text="getVal(f, current, i)"></textarea>
                  </template>

                  {{-- SELECT --}}
                  <template x-if="(f.type||'').toLowerCase()==='select'">
                    <select class="form-select"
                            :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                            @change="onSelectChange(f, $event.target.value, current, i)"
                            x-init="$nextTick(()=>onSelectChange(f, getVal(f, current, i), current, i))">
                      <option value="">— Seleccionar —</option>
                      <template x-for="opt in (f.options||[])" :key="opt">
                        <option :value="opt" x-text="opt" :selected="getVal(f, current, i)===opt"></option>
                      </template>
                    </select>
                    <small class="text-muted" x-show="f.condition || (f.conditions && Object.keys(f.conditions||{}).length)">
                      Esta respuesta puede derivar a otra sección.
                    </small>
                  </template>

                  {{-- DATE --}}
                  <template x-if="(f.type||'').toLowerCase()==='date'">
                    <input type="date" class="form-control"
                           :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                           :value="getVal(f, current, i) || ''"
                           @input="setVal(f, $event.target.value, current, i)">
                  </template>

                  {{-- NUMBER --}}
                  <template x-if="(f.type||'').toLowerCase()==='number'">
                    <input type="number" class="form-control"
                           :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                           :value="getVal(f, current, i) || ''"
                           @input="setVal(f, $event.target.value, current, i)">
                  </template>

                  {{-- CHECKBOX simple --}}
                  <template x-if="(f.type||'').toLowerCase()==='checkbox' && !(f.options && f.options.length)">
                    <div class="form-check">
                      <input type="checkbox" class="form-check-input"
                             :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                             :checked="!!getVal(f, current, i)"
                             @change="setVal(f, $event.target.checked, current, i)">
                      <label class="form-check-label" x-text="f.help || 'Seleccionar'"></label>
                    </div>
                  </template>

                  {{-- CHECKBOX multiple --}}
                  <template x-if="(f.type||'').toLowerCase()==='checkbox' && (f.options && f.options.length)">
                    <div>
                      <template x-for="opt in (f.options||[])" :key="opt">
                        <div class="form-check">
                          <input type="checkbox" class="form-check-input"
                                 :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                                 :checked="Array.isArray(getVal(f, current, i)) ? getVal(f, current, i).includes(opt) : false"
                                 @change="
                                   const cur = Array.isArray(getVal(f, current, i)) ? [...getVal(f, current, i)] : [];
                                   if ($event.target.checked && !cur.includes(opt)) cur.push(opt);
                                   if (!$event.target.checked) cur.splice(cur.indexOf(opt),1);
                                   setVal(f, cur, current, i);
                                 ">
                          <label class="form-check-label" x-text="opt"></label>
                        </div>
                      </template>
                    </div>
                  </template>

                  {{-- FILE --}}
                  <template x-if="(f.type||'').toLowerCase()==='file'">
                    <input type="file" class="form-control"
                           :name="fileInputName(f, current, i)"
                           :multiple="!!f.multiple"
                           @change="setFileVal(f, $event.target.files, current, i)">
                  </template>

                  {{-- RICHTEXT --}}
                  <template x-if="(f.type||'').toLowerCase()==='richtext'">
                    <textarea class="form-control" rows="6"
                              :name="'_dummy_'+(f.name||('s'+current+'_f'+i))"
                              @input="setVal(f, $event.target.value, current, i)"
                              x-text="getVal(f, current, i)"></textarea>
                  </template>

                  <template x-if="f.help">
                    <div class="form-text" x-text="f.help"></div>
                  </template>

                  <div class="text-danger small mt-1" x-show="!!f.required && !isFieldValid(f, getVal(f, current, i))">
                    Este campo es obligatorio.
                  </div>
                </div>
              </template>
            </div>
          </template>

          {{-- Repaso final --}}
          <template x-if="isReview()">
            <div>
              <h5 class="mb-3">Repaso final</h5>
              <template x-for="(sec, si) in steps" :key="si">
                <div class="mb-3">
                  <div class="fw-bold mb-2" x-text="sec.name || ('Sección '+(si+1))"></div>
                  <div class="list-group">
                    <template x-for="(f, fi) in (sec.fields||[])" :key="fi">
                      <div class="list-group-item">
                        <div class="small text-muted d-flex align-items-center gap-2">
                          <span x-text="f.label || (f.name || 'Campo')"></span>
                          <span class="badge bg-danger" x-show="!!f.required">obligatorio</span>
                        </div>
                        <div class="fw-semibold">
                          <template x-if="(f.type||'').toLowerCase()==='file'">
                            <span>(El/los archivo/s se adjuntarán)</span>
                          </template>
                          <template x-if="(f.type||'text').toLowerCase()!=='file'">
                            <span x-text="pretty(getVal(f, si, fi))"></span>
                          </template>
                        </div>
                      </div>
                    </template>
                  </div>
                </div>
              </template>

              <div class="alert" :class="globalValid ? 'alert-success' : 'alert-warning'">
                <template x-if="!globalValid">
                  <div>Faltan completar campos obligatorios. Volvé y completalos para poder enviar.</div>
                </template>
                <template x-if="globalValid">
                  <div>Todo listo. Podés enviar tu trámite.</div>
                </template>
              </div>
            </div>
          </template>
        </div>

        <div class="card-footer d-flex justify-content-between">
          {{-- Navegación / Acciones --}}
<div class="d-flex gap-2 mt-3">
    <button type="button" class="btn btn-outline-secondary" @click="prev()" :disabled="stepIndex===0">
        ← Anterior
    </button>

    <button type="button" class="btn btn-primary" @click="next()" x-show="stepKey!=='review'">
        Siguiente →
    </button>

    {{-- Solo en Repaso final aparece el submit real --}}
    <button type="submit"
            class="btn btn-success"
            x-show="stepKey==='review'"
            :disabled="!isValidToSubmit">
        Enviar solicitud
    </button>
</div>

        </div>
      </div>
      {{-- (overlay eliminado) --}}
    </form>
  </template>
</div>
@endsection

@push('scripts')
<script>
function wizardForm(initialSections){
  return {
    steps: Array.isArray(initialSections) ? initialSections : [],
    current: 0,
    model: {},
    sectionValid: false,
    globalValid: false,

    init(){
      (this.steps||[]).forEach((s,si)=>{
        (s.fields||[]).forEach((f,fi)=>{
          const key = this.keyFor(f, si, fi);
          if (this.model[key] === undefined) this.model[key] = f.value ?? '';
        });
      });
      this.renderHiddenInputs();
      this.recalcValidity();
    },

    // ---- helpers de clave/valor
    keyFor(f, si, fi){ return (f && f.name) ? String(f.name) : `s${si}_f${fi}`; },
    getVal(f, si, fi){ const k = this.keyFor(f, si??this.current, fi??0); return this.model[k] ?? ''; },
    setVal(f, v, si, fi){
      const k = this.keyFor(f, si??this.current, fi??0);
      this.model[k] = v;
      this.renderHiddenInputs();
      this.recalcValidity();
    },
    fileInputName(f, si, fi){ return `files[${this.keyFor(f, si ?? this.current, fi ?? 0)}]${f.multiple ? '[]' : ''}`; },
    setFileVal(f, fileList, si, fi){
      const k = this.keyFor(f, si??this.current, fi??0);
      // Guardamos una marca válida (+ nombres para el repaso)
      const names = Array.from(fileList || []).map(x => x.name);
      this.model[k] = { __file__: true, names };
      this.renderHiddenInputs();
      this.recalcValidity();
    },

    pretty(v){
      if (Array.isArray(v)) return v.join(', ');
      if (v === true) return 'Sí';
      if (v === false) return 'No';
      const s = String(v ?? '').trim();
      return s.length ? s : '—';
    },

    // ---- navegación / validación
    onSelectChange(f, value, si, fi){
      this.setVal(f, value, si, fi);
      const condMap = (f.conditions && typeof f.conditions === 'object') ? f.conditions : {};
      const direct   = f.condition || '';

      let goto = '';
      if (value && condMap[value]) goto = condMap[value];
      else if (direct) goto = direct;

      if (goto){
        const idx = (this.steps || []).findIndex(s => (s.name||'').trim() === String(goto).trim());
        if (idx >= 0) this.current = idx;
      }
      this.recalcValidity();
    },

    isFieldValid(f, v){
      if (!f || !f.required) return true;
      const t = (f.type||'text').toLowerCase();

      if (t === 'file') {
        // Consideramos válido si seleccionaron algo (marcado por setFileVal)
        if (v == null) return false;
        if (typeof v === 'object' && v.__file__) return true;
        if (typeof v === 'string') return v.trim() !== '';
        if (Array.isArray(v)) return v.length > 0;
        return !!v;
      }

      if (Array.isArray(v)) return v.length > 0;
      return String(v ?? '').trim() !== '';
    },

    sectionHasMissingRequired(si = this.current){
      if (this.isReview()) return false;
      const sec = this.steps[si] || {};
      for (const [fi, f] of (sec.fields || []).entries()){
        if (!this.isFieldValid(f, this.getVal(f, si, fi))) return true;
      }
      return false;
    },

    missingRequiredGlobal(){
      for (let si = 0; si < this.steps.length; si++){
        if (this.sectionHasMissingRequired(si)) return true;
      }
      return false;
    },

    recalcValidity(){
      this.sectionValid = !this.sectionHasMissingRequired(this.current);
      this.globalValid  = !this.missingRequiredGlobal();
    },

    prevStep(){
      if (this.isReview()){ this.current = this.steps.length - 1; this.recalcValidity(); return; }
      if (this.current > 0) this.current--;
      this.recalcValidity();
    },
    nextStep(){
      if (this.isReview()) return;
      if (this.current < this.steps.length - 1) this.current++;
      this.recalcValidity();
    },
    goTo(i){ this.current = Math.max(0, Math.min(i, this.steps.length - 1)); this.recalcValidity(); },
    goToReview(){ this.current = this.steps.length; this.recalcValidity(); },
    isReview(){ return this.current === this.steps.length; },

    displayStep(){ return this.isReview() ? (this.steps.length + 1) : (this.current + 1); },
    totalStepsDisplay(){ return this.steps.length + 1; },

    // ---- hidden inputs form[...]
    renderHiddenInputs(){
      const holder = this.$refs.answers; if (!holder) return;
      const parts = [];
      Object.entries(this.model).forEach(([k, v])=>{
        if (!(v && typeof v === 'object' && v.__file__)) { // no inyectamos archivos en form[...]
          const safe = Array.isArray(v) ? JSON.stringify(v) : String(v ?? '');
          parts.push(`<input type="hidden" name="form[${this.escape(k)}]" value="${this.escape(safe)}">`);
        }
      });
      holder.innerHTML = parts.join('');
    },
    escape(s){ return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;'); },

    beforeSubmit(e) {
      // 1) Solo permitimos submit en la pestaña de repaso
      if (this.stepKey !== 'review') {
        e.preventDefault();
        this.next(); // avanza a siguiente paso
        return false;
      }

      // 2) Serializar respuestas al hidden answers_json
      const flat = {};
      this.sections.forEach((sec, si) => {
        (sec.fields || []).forEach((f, fi) => {
          const name = f.name || `s${si}_f${fi}`;
          flat[name] = (typeof f.value === 'undefined') ? null : f.value;
        });
      });
      if (this.$refs.answersJson) {
        this.$refs.answersJson.value = JSON.stringify(flat);
      }

      // 3) NO cancelar el submit si estamos en review
      return true;
    },
  
  }
}

// Fallback vanilla: serializa visibles a answers_json
document.addEventListener('DOMContentLoaded', function(){
  const form = document.querySelector('form[enctype="multipart/form-data"]'); if(!form) return;
  let answersHidden = form.querySelector('input[name="answers_json"]');
  if (!answersHidden){
    answersHidden = document.createElement('input');
    answersHidden.type = 'hidden';
    answersHidden.name = 'answers_json';
    form.appendChild(answersHidden);
  }
  form.addEventListener('submit', function(){
    const model = {};
    const fields = form.querySelectorAll('input, textarea, select');
    fields.forEach(el=>{
      if (el.disabled) return;
      if (el.type === 'file') return;
      let key = (el.getAttribute('name') || '').trim();
      if (!key) return;
      if (key.startsWith('_dummy_')) key = key.substring('_dummy_'.length);
      let val;
      if (el.tagName === 'SELECT') val = el.value;
      else if (el.type === 'checkbox'){
        const group = form.querySelectorAll(`input[type="checkbox"][name="${el.getAttribute('name')}"]`);
        if (group.length > 1){
          if (!Array.isArray(model[key])) model[key] = [];
          if (el.checked && !model[key].includes(el.value)) model[key].push(el.value);
          return;
        } else { val = !!el.checked; }
      } else if (el.type === 'radio'){ if (!el.checked) return; val = el.value; }
      else { val = el.value; }
      model[key] = val;
    });
    try { answersHidden.value = JSON.stringify(model); } catch { answersHidden.value = '{}'; }
  }, { capture: true });
});
</script>
@endpush
