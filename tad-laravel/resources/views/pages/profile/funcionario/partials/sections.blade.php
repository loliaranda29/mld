@php
    /**
     * Partial: Render de secciones del formulario en modo lectura (funcionario)
     *
     * Espera:
     * - $sections: array de secciones (cada una con 'name' y 'fields')
     * - $only (opcional): array con los nombres de secciones a renderizar. Si es null, muestra todas.
     *
     * Cada field debería venir con:
     * - 'label' (string) | 'name' (string)
     * - 'type' (string) opcional
     * - 'value' (mixed) el valor ya hidratado desde el controlador
     *
     * Archivos: si el value es array con ['path'|'url','name','mime'], se muestra link(s).
     */
    $only    = $only ?? null;
    $answers = $answers ?? null; // opcional: hash [fieldName => value] para fallback de no-file
@endphp

@if(empty($sections))
  <div class="text-muted">Sin contenido.</div>
@else
  @foreach($sections as $sec)
    @php
      $secName = $sec['name'] ?? 'Sección';
    @endphp

    @if(!$only || (is_array($only) && in_array($secName, $only)))
      <div class="mb-3">
        <div class="fw-semibold mb-2">{{ $secName }}</div>

        @if(!empty($sec['fields']) && is_array($sec['fields']))
          <dl class="row">
            @foreach($sec['fields'] as $field)
              @php
                $label  = $field['label'] ?? ($field['name'] ?? 'Campo');
                $val    = $field['value'] ?? null;
                $type   = $field['type']  ?? 'text';
                $render = '';

                // Fallback: si no hay value y hay $answers, completar SOLO no-file
                if (($val === null || $val === '' || (is_array($val) && !count($val))) && $type !== 'file' && is_array($answers ?? null)) {
                  $nm = $field['_name'] ?? ($field['name'] ?? null);
                  if ($nm && array_key_exists($nm, $answers)) {
                    $val = $answers[$nm];
                  }
                }

                if (is_array($val)) {
                  // ¿archivo(s)?
                  $isAssoc   = array_keys($val) !== range(0, count($val) - 1);
                  $arr       = $isAssoc ? [$val] : $val;
                  $looksFile = isset(($arr[0] ?? [])['path']) || isset(($arr[0] ?? [])['url']);

                  if ($looksFile) {
                    $links = [];
                    foreach ($arr as $ix => $one) {
                      $url  = $one['url']  ?? null;
                      $name = $one['name'] ?? ('Archivo ' . ($ix + 1));
                      $links[] = $url
                        ? '<a href="'.e($url).'" target="_blank" rel="noopener">'.$name.'</a>'
                        : e($name);
                    }
                    $render = implode('<br>', $links);
                  } else {
                    // array de datos (no archivo)
                    $render = e(json_encode($val, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
                  }
                } else {
                  // texto/plano
                  $render = e((string) $val);
                }
              @endphp

              <dt class="col-sm-4">{{ $label }}</dt>
              <dd class="col-sm-8">{!! $render !== '' ? $render : '<span class="text-muted">—</span>' !!}</dd>
            @endforeach
          </dl>
        @else
          <div class="text-muted small">Sin campos.</div>
        @endif
      </div>
    @endif
  @endforeach
@endif
