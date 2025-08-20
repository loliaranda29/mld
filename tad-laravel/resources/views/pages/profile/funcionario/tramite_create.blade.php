<hr class="my-4">

<div class="row">
  {{-- TRÁMITE PADRE --}}
  <div class="col-md-6">
    <label class="form-label">Trámite padre</label>
    <select name="parent_id" class="form-select">
      <option value="">— Sin padre —</option>
      @foreach(($tramitesListado ?? []) as $tid => $tnombre)
        @if(isset($tramite) && $tid == $tramite->id) @continue @endif
        <option value="{{ $tid }}"
          @selected(optional($tramite->parent)->id === $tid)>
          {{ $tnombre }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Si elegís uno, este trámite pasa a ser “hijo” del seleccionado.</div>
  </div>

  {{-- HIJOS (SUBTRÁMITES) --}}
  <div class="col-md-6">
    <label class="form-label">Subtrámites (hijos) de este trámite</label>
    <select name="hijos_ids[]" class="form-select" multiple size="6">
      @foreach(($tramitesListado ?? []) as $tid => $tnombre)
        @if(isset($tramite) && $tid == $tramite->id) @continue @endif
        <option value="{{ $tid }}"
          @selected(in_array($tid, $hijosSeleccionados ?? []))>
          {{ $tnombre }}
        </option>
      @endforeach
    </select>
    <div class="form-text">Los seleccionados se marcarán con este trámite como su padre.</div>
  </div>
</div>

<div class="mt-3">
  {{-- VÍNCULOS LATERALES --}}
  <label class="form-label">Vínculos (relaciones laterales)</label>
  <select name="relacionados_ids[]" class="form-select" multiple size="6">
    @foreach(($tramitesListado ?? []) as $tid => $tnombre)
      @if(isset($tramite) && $tid == $tramite->id) @continue @endif
      <option value="{{ $tid }}"
        @selected(in_array($tid, $relacionadosSeleccionados ?? []))>
        {{ $tnombre }}
      </option>
    @endforeach
  </select>
  <div class="form-text">Sirven para navegar entre trámites relacionados o complementarios.</div>
</div>
