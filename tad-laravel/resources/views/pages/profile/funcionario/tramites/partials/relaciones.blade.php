<div class="row g-3">

  {{-- Padre --}}
  <div class="col-md-6">
    <label class="form-label">Trámite padre</label>
    <select name="parent_id" class="form-select">
      <option value="">Sin padre</option>
      @foreach($todos as $t)
        @continue(optional($tramite)->id === $t->id) {{-- no puede ser su propio padre --}}
        <option value="{{ $t->id }}"
          @selected(old('parent_id', optional($tramite)->parent_id) == $t->id)>
          {{ $t->nombre }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Si elegís uno, este trámite pasa a ser “hijo” del seleccionado.</div>
  </div>

  {{-- Hijos --}}
  <div class="col-md-6">
    <label class="form-label">Subtrámites (hijos) de este trámite</label>
    @php
      $hijosSel = collect(old('hijos', optional($tramite)->hijos?->pluck('id') ?? []));
    @endphp
    <select name="hijos[]" class="form-select" multiple size="8">
      @foreach($todos as $t)
        @continue(optional($tramite)->id === $t->id)
        <option value="{{ $t->id }}" @selected($hijosSel->contains($t->id))>
          {{ $t->nombre }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Los seleccionados se marcarán con este trámite como su padre.</div>
  </div>

  {{-- Vínculos laterales --}}
  <div class="col-12">
    <label class="form-label">Vínculos (relaciones laterales)</label>
    @php
      $relSel = collect(old('relacionados', optional($tramite)->relacionados?->pluck('id') ?? []));
    @endphp
    <select name="relacionados[]" class="form-select" multiple size="8">
      @foreach($todos as $t)
        @continue(optional($tramite)->id === $t->id)
        <option value="{{ $t->id }}" @selected($relSel->contains($t->id))>
          {{ $t->nombre }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Sirven para navegar entre trámites relacionados o complementarios.</div>
  </div>

</div>
