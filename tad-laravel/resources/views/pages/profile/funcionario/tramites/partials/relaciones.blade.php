@php
  $tramitesPosiblesPadre  = $tramitesPosiblesPadre  ?? collect();
  $tramitesPosiblesHijo   = $tramitesPosiblesHijo   ?? collect();
  $tramitesParaVincular   = $tramitesParaVincular   ?? collect();
@endphp

<div class="card">
  <div class="card-body">
    {{-- Esta partial NO crea un <form>. Usa el formulario principal de la página --}}
    <div class="row mb-4">
      <div class="col-md-6">
        <label class="form-label">Trámite padre</label>
        <select class="form-select" name="parent_id">
          <option value="">Sin padre</option>
          @foreach($tramitesPosiblesPadre as $t)
            <option value="{{ $t->id }}" @selected(optional($tramite->parent)->id === $t->id)>
              {{ $t->nombre ?? ('#'.$t->id) }}
            </option>
          @endforeach
        </select>
        <small class="text-muted">Si elegís uno, este trámite pasa a ser “hijo” del seleccionado.</small>
      </div>

      <div class="col-md-6">
        <label class="form-label">Subtrámites (hijos) de este trámite</label>
        <select class="form-select" name="children_ids[]" multiple size="8">
          @foreach($tramitesPosiblesHijo as $t)
            <option value="{{ $t->id }}" @selected($tramite->children->contains('id', $t->id))>
              {{ $t->nombre ?? ('#'.$t->id) }}
            </option>
          @endforeach
        </select>
        <small class="text-muted">Los seleccionados se marcarán con este trámite como su padre.</small>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label">Vínculos (relaciones laterales)</label>
      <select class="form-select" name="links_ids[]" multiple size="10">
        @foreach($tramitesParaVincular as $t)
          <option value="{{ $t->id }}" @selected($tramite->vinculos->contains('id', $t->id))>
            {{ $t->nombre ?? ('#'.$t->id) }}
          </option>
        @endforeach
      </select>
      <small class="text-muted">Sirven para navegar entre trámites relacionados o complementarios.</small>
    </div>
  </div>
</div>
