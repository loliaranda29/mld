@extends('layouts.app')

@section('content')
@php
    $sections = $sections ?? ($schema['sections'] ?? []);
@endphp

<div class="min-vh-100 bg-light py-5"
     x-data="wizardForm(@js($sections))"
     x-init="init()"
>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9 col-xl-8">
                
                {{-- Header --}}
                <div class="text-center mb-5">
                    <h2 class="fw-bold mb-2">{{ $tramite->nombre ?? 'Nuevo trámite' }}</h2>
                    <p class="text-muted">Complete los siguientes pasos para enviar su solicitud</p>
                </div>

                <form method="POST"
                      action="{{ route('profile.solicitudes.store') }}"
                      enctype="multipart/form-data"
                      x-on:submit.prevent="beforeSubmit($event)"
                >
                    @csrf
                    <input type="hidden" name="tramite_id" value="{{ $tramite->id }}">
                    <input type="hidden" x-ref="answersJson" name="answers_json" value="{}">

                    {{-- Progress Steps --}}
                    <div class="mb-5">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <template x-for="(section, idx) in sections" :key="idx">
                                <div class="d-flex flex-column align-items-center flex-fill position-relative">
                                    {{-- Step Circle --}}
                                    <div class="rounded-circle d-flex align-items-center justify-content-center mb-2 position-relative"
                                         :class="idx <= stepIndex ? 'bg-primary text-white' : 'bg-white border border-2 text-muted'"
                                         style="width: 48px; height: 48px; z-index: 2; transition: all 0.3s ease;">
                                        <span class="fw-bold" x-text="idx + 1"></span>
                                    </div>
                                    
                                    {{-- Step Label --}}
                                    <small class="text-center fw-medium" 
                                           :class="idx === stepIndex ? 'text-primary' : 'text-muted'"
                                           style="max-width: 100px; font-size: 0.75rem;"
                                           x-text="section.name || `Paso ${idx + 1}`">
                                    </small>
                                    
                                    {{-- Connector Line --}}
                                    <div x-show="idx < sections.length - 1"
                                         class="position-absolute top-0 start-50 translate-middle-y"
                                         style="height: 2px; width: 100%; margin-top: 24px; margin-left: 24px; z-index: 1;"
                                         :class="idx < stepIndex ? 'bg-primary' : 'bg-secondary opacity-25'">
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        {{-- Progress Bar --}}
                        <div class="progress" style="height: 6px; border-radius: 10px;">
                            <div class="progress-bar bg-primary" 
                                 role="progressbar"
                                 :style="`width:${progress()}%; transition: width 0.4s ease;`"
                                 :aria-valuenow="stepIndex+1"
                                 aria-valuemin="1"
                                 :aria-valuemax="sections.length">
                            </div>
                        </div>
                    </div>

                    {{-- Form Section --}}
                    <template x-if="currentSection()">
                        <div class="card border-0 shadow-sm mb-4" 
                             style="border-radius: 16px; transition: all 0.3s ease;"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 transform scale-95"
                             x-transition:enter-end="opacity-100 transform scale-100">
                            
                            <div class="card-header bg-white border-0 py-4 px-4" style="border-radius: 16px 16px 0 0;">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h5 class="mb-0 fw-semibold" x-text="currentSection().name || `Sección ${stepIndex+1}`"></h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2" 
                                          style="border-radius: 20px;"
                                          x-text="`${stepIndex+1} de ${sections.length}`">
                                    </span>
                                </div>
                            </div>
                            
                            <div class="card-body p-4 p-md-5">
                                <template x-for="(field, idx) in (currentSection().fields || [])" :key="idx">
                                    <div class="mb-4" x-show="isFieldVisible(field)">
                                        <label class="form-label fw-medium mb-2">
                                            <span x-text="field.label || field.name"></span>
                                            <span x-show="field.required" class="text-danger ms-1">*</span>
                                        </label>

                                        {{-- Text inputs --}}
                                        <template x-if="['text','search','code','richtext'].includes((field.type||'text').toLowerCase())">
                                            <input type="text"
                                                   class="form-control form-control-lg"
                                                   style="border-radius: 10px; border: 1px solid #e0e0e0;"
                                                   :name="`form[${field._name||field.name}]`"
                                                   :placeholder="field.placeholder || ''"
                                                   x-model="model[field._name||field.name]">
                                        </template>

                                        {{-- Textarea --}}
                                        <template x-if="(field.type||'')==='textarea'">
                                            <textarea class="form-control form-control-lg"
                                                      style="border-radius: 10px; border: 1px solid #e0e0e0;"
                                                      rows="4"
                                                      :name="`form[${field._name||field.name}]`"
                                                      x-model="model[field._name||field.name]"></textarea>
                                        </template>

                                        {{-- Number --}}
                                        <template x-if="(field.type||'')==='number'">
                                            <input type="number"
                                                   class="form-control form-control-lg"
                                                   style="border-radius: 10px; border: 1px solid #e0e0e0;"
                                                   :name="`form[${field._name||field.name}]`"
                                                   x-model="model[field._name||field.name]">
                                        </template>

                                        {{-- Date --}}
                                        <template x-if="(field.type||'')==='date'">
                                            <input type="date"
                                                   class="form-control form-control-lg"
                                                   style="border-radius: 10px; border: 1px solid #e0e0e0;"
                                                   :name="`form[${field._name||field.name}]`"
                                                   x-model="model[field._name||field.name]">
                                        </template>

                                        {{-- Select --}}
                                        <template x-if="(field.type||'')==='select'">
                                            <select class="form-select form-select-lg"
                                                    style="border-radius: 10px; border: 1px solid #e0e0e0;"
                                                    :name="`form[${field._name||field.name}]`"
                                                    x-model="model[field._name||field.name]"
                                                    x-on:change="onSelectChange(field, $event.target.value)">
                                                <option value="" x-show="!field.required">-- Seleccionar --</option>
                                                <template x-for="(opt, i2) in (field.options || [])" :key="i2">
                                                    <option
                                                        :value="(typeof opt==='object') ? (opt.value ?? opt.label ?? '') : opt"
                                                        x-text="(typeof opt==='object') ? (opt.label ?? opt.value ?? '') : opt">
                                                    </option>
                                                </template>
                                            </select>
                                        </template>

                                        {{-- Single Checkbox --}}
                                        <template x-if="(field.type||'')==='checkbox' && !field.multiple">
                                            <div class="form-check p-3 bg-light" style="border-radius: 10px;">
                                                <input class="form-check-input" 
                                                       type="checkbox"
                                                       style="width: 20px; height: 20px; border-radius: 5px;"
                                                       :name="`form[${field._name||field.name}]`"
                                                       :value="1"
                                                       x-model="model[field._name||field.name]">
                                                <label class="form-check-label ms-2" x-text="field.help || 'Seleccionar'"></label>
                                            </div>
                                        </template>

                                        {{-- Multiple Checkboxes --}}
                                        <template x-if="(field.type||'')==='checkbox' && field.multiple">
                                            <div class="d-flex flex-column gap-2">
                                                <template x-for="(opt, i3) in (field.options || [])" :key="i3">
                                                    <div class="form-check p-3 bg-light" style="border-radius: 10px;">
                                                        <input class="form-check-input" 
                                                               type="checkbox"
                                                               style="width: 20px; height: 20px; border-radius: 5px;"
                                                               :name="`form[${field._name||field.name}][]`"
                                                               :value="(typeof opt==='object') ? (opt.value ?? opt.label ?? '') : opt"
                                                               x-model="model[field._name||field.name]">
                                                        <label class="form-check-label ms-2"
                                                               x-text="(typeof opt==='object') ? (opt.label ?? opt.value ?? '') : opt"></label>
                                                    </div>
                                                </template>
                                            </div>
                                        </template>

                                        {{-- File Upload --}}
                                        <template x-if="(field.type||'')==='file' && !field.multiple">
                                            <div class="position-relative">
                                                <input type="file"
                                                       class="form-control form-control-lg"
                                                       style="border-radius: 10px; border: 2px dashed #e0e0e0; padding: 20px;"
                                                       :name="`files[${field._name||field.name}]`"
                                                       x-on:change="onFileChange($event, field)">
                                            </div>
                                        </template>

                                        <template x-if="(field.type||'')==='file' && field.multiple">
                                            <div class="position-relative">
                                                <input type="file"
                                                       multiple
                                                       class="form-control form-control-lg"
                                                       style="border-radius: 10px; border: 2px dashed #e0e0e0; padding: 20px;"
                                                       :name="`files[${field._name||field.name}][]`"
                                                       x-on:change="onFileChange($event, field)">
                                            </div>
                                        </template>

                                        {{-- Help Text --}}
                                        <small class="text-muted d-block mt-2" 
                                               x-show="field.help"
                                               x-text="field.help || ''">
                                        </small>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Navigation Buttons --}}
                    <div class="d-flex justify-content-between align-items-center gap-3 mb-5">
                        <button type="button" 
                                class="btn btn-lg btn-outline-secondary px-4"
                                style="border-radius: 10px; min-width: 120px;"
                                :disabled="stepIndex === 0"
                                @click="prevStep">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left me-2" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                            </svg>
                            Anterior
                        </button>

                        <div class="d-flex gap-2">
                            <button type="button" 
                                    class="btn btn-lg btn-primary px-5"
                                    style="border-radius: 10px; min-width: 140px;"
                                    x-show="!isLastStep()"
                                    :disabled="!canGoNext()"
                                    @click="nextStep">
                                Siguiente
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right ms-2" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z"/>
                                </svg>
                            </button>

                            <button type="submit" 
                                    class="btn btn-lg btn-success px-5"
                                    style="border-radius: 10px; min-width: 140px;"
                                    x-show="isLastStep()"
                                    :disabled="!canSubmit()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-circle me-2" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                    <path d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z"/>
                                </svg>
                                Enviar solicitud
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control:focus,
    .form-select:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
    }
    
    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn {
        transition: all 0.2s ease;
    }
    
    .btn:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('wizardForm', (sections) => ({
        sections: sections || [],
        stepIndex: 0,
        model: {},

        // NUEVO: mapa de saltos "fieldName::optionValue(normalizado)" => targetSectionIndex
        jumpMap: {},

        init(){
            const used = new Set();
            (this.sections || []).forEach((sec, si) => {
                (sec.fields || []).forEach((f, fi) => {
                    // normalizar nombre único
                    let name = (f.name || '').trim();
                    if (!name || used.has(name)) { name = `s${si}_f${fi}`; }
                    f._name = name;
                    used.add(name);

                    if (!name) return;
                    if ((f.type||'') === 'checkbox' && f.multiple) {
                        this.model[f._name] = [];
                    } else if ((f.type||'') === 'checkbox') {
                        this.model[f._name] = false;
                    } else {
                        this.model[f._name] = '';
                    }
                });
            });

            // NUEVO: construir jumpMap desde las condiciones del schema
            (this.sections || []).forEach((sec, si) => {
                (sec.fields || []).forEach((f) => {
                    if (!f) return;
                    if ((f.type || '') === 'select' && f.conditions && typeof f.conditions === 'object') {
                        Object.entries(f.conditions).forEach(([optionLabel, targetSectionName]) => {
                            const targetIdx = (this.sections || []).findIndex(s => (s.name || '') === targetSectionName);
                            if (targetIdx >= 0) {
                                const key = this._jumpKey(f._name || f.name, optionLabel);
                                this.jumpMap[key] = targetIdx;
                            }
                        });
                    }
                });
            });

            this.syncJson();
        },

        currentSection(){ return (this.sections || [])[this.stepIndex] || null; },
        isLastStep(){ return this.stepIndex >= (this.sections.length - 1); },
        progress(){ if (!this.sections.length) return 0; return Math.round(((this.stepIndex + 1) / this.sections.length) * 100); },

        isFieldVisible(field){
            if (!field) return true;
            if ((!field.condition || field.condition === '') && (!field.conditions || Object.keys(field.conditions||{}).length===0)) {
                return true;
            }
            const dep = field.dependsOn || null;
            if (dep && Object.prototype.hasOwnProperty.call(this.model, dep)) {
                const val = this.model[dep];
                if (field.conditions && typeof field.conditions === 'object') {
                    return Object.prototype.hasOwnProperty.call(field.conditions, val);
                }
            }
            return true;
        },

        nextStep(){ if (this.stepIndex < this.sections.length - 1) { this.stepIndex++; this.syncJson(); } },
        prevStep(){ if (this.stepIndex > 0) { this.stepIndex--; this.syncJson(); } },

        canGoNext(){
            const sec = this.currentSection(); if (!sec) return false;
            for (const f of (sec.fields || [])) {
                if (!f || !f.required) continue;
                if (!this.isFieldVisible(f)) continue;
                const v = this.model[f._name];
                if ((f.type||'') === 'file') continue;
                if ((f.type||'') === 'checkbox' && f.multiple) {
                    if (!Array.isArray(v) || v.length === 0) return false;
                } else if (v === '' || v === null || v === false) {
                    return false;
                }
            }
            return true;
        },

        canSubmit(){ return this.canGoNext(); },

        onFileChange(e, field){
            if (field && (field._name||field.name)) {
                const files = e.target.files;
                this.model[field._name||field.name] = files && files.length ? '[archivo seleccionado]' : '';
                this.syncJson();
            }
        },

        // NUEVO: manejar cambios de <select> y ejecutar salto si hay mapeo
        onSelectChange(field, value){
            const name = field?._name || field?.name;
            if (!name) return;
            this.model[name] = value;
            this.syncJson();

            const key = this._jumpKey(name, value);
            if (Object.prototype.hasOwnProperty.call(this.jumpMap, key)) {
                const target = this.jumpMap[key];
                if (typeof target === 'number' && target >= 0 && target < this.sections.length) {
                    this.stepIndex = target;
                }
            }
        },

        // helper para normalizar clave del salto
        _jumpKey(fieldName, optionValue){
            const norm = (s) => (s ?? '').toString().trim().toLowerCase();
            return `${norm(fieldName)}::${norm(optionValue)}`;
        },

        syncJson(){
            try { if (this.$refs['answersJson']) this.$refs['answersJson'].value = JSON.stringify(this.model || {}); } catch (e) {}
        },

        beforeSubmit(e){
            if (!this.isLastStep()) { this.nextStep(); e.preventDefault(); return false; }
            this.$nextTick(() => { this.syncJson(); e.target.submit(); });
            return false;
        },
    }));
});
</script>
@endsection
