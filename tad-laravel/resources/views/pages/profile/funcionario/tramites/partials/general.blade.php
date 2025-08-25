@php
  // Si vienen desde el controlador
  $dependencias = $dependencias ?? [];
  $categorias   = $categorias   ?? [];
  $oficinas     = $oficinas     ?? [];
  $ubicaciones  = $ubicaciones  ?? [];

  // Si ya existe el trámite, preferimos leer desde general_json
  $g = is_array($tramite->general_json ?? null) ? $tramite->general_json : [];
@endphp

<div class="row g-3">

  {{-- Título y descripción corta --}}
  <div class="col-md-8">
    <label class="form-label">Nombre del trámite <span class="text-danger">*</span></label>
    <input type="text" name="nombre" class="form-control"
           value="{{ old('nombre', $tramite->nombre ?? '') }}" required>
    <small class="text-muted">Este nombre se usa en listados y en la ficha pública.</small>
  </div>

  <div class="col-md-4">
    <label class="form-label">Descripción (corta)</label>
    <input type="text" name="descripcion" class="form-control"
           value="{{ old('descripcion', $tramite->descripcion ?? '') }}"
           placeholder="Resumen breve que aparece en listados">
  </div>

  <hr class="my-3">

  {{-- Tutorial + cabecera operativa --}}
  <div class="col-12">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Tutorial (texto enriquecido, podés insertar imágenes o videos)</label>
        <textarea name="tutorial_html" class="form-control rtx" rows="8">{!!
          old('tutorial_html', data_get($g,'tutorial_html'))
        !!}</textarea>
      </div>

      <div class="col-md-6">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Modalidad</label>
            @php $modalidad = old('modalidad', data_get($g,'modalidad')); @endphp
            <select name="modalidad" class="form-select">
              <option value="">—</option>
              @foreach (['Presencial','Online','Presencial/Online'] as $m)
                <option value="{{ $m }}" @selected($modalidad===$m)>{{ $m }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Implica costo</label>
            @php $costo = old('implica_costo', data_get($g,'implica_costo')); @endphp
            <select name="implica_costo" class="form-select">
              <option value="">—</option>
              @foreach (['Con costo','Sin costo'] as $c)
                <option value="{{ $c }}" @selected($costo===$c)>{{ $c }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Teléfono oficina</label>
            <input type="text" name="telefono_oficina" class="form-control"
                   value="{{ old('telefono_oficina', data_get($g,'telefono_oficina')) }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Horario de atención</label>
            <input type="text" name="horario_atencion" class="form-control"
                   value="{{ old('horario_atencion', data_get($g,'horario_atencion')) }}">
          </div>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Detalle de costo</label>
        <textarea name="detalle_costo_html" class="form-control rtx" rows="6">{!!
          old('detalle_costo_html', data_get($g,'detalle_costo_html'))
        !!}</textarea>
      </div>
    </div>
  </div>

  <hr class="my-3">

  {{-- Catálogos --}}
  <div class="col-md-6">
    <label class="form-label">Dependencia <span class="text-danger">*</span></label>
    @php $depId = old('dependencia_id', data_get($g,'dependencia.id')); @endphp
    <select name="dependencia_id" class="form-select" required>
      <option value="">— Seleccionar —</option>
      @foreach($dependencias as $opt)
        @php
          $id  = is_array($opt) ? ($opt['id'] ?? null) : ($opt->id ?? null);
          $nom = is_array($opt) ? ($opt['nombre'] ?? '') : ($opt->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected((string)$depId === (string)$id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="dependencia_nombre" id="depNombre"
           value="{{ old('dependencia_nombre', data_get($g,'dependencia.nombre')) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Categoría <span class="text-danger">*</span></label>
    @php $catId = old('categoria_id', data_get($g,'categoria.id')); @endphp
    <select name="categoria_id" class="form-select" required>
      <option value="">— Seleccionar —</option>
      @foreach($categorias as $opt)
        @php
          $id  = is_array($opt) ? ($opt['id'] ?? null) : ($opt->id ?? null);
          $nom = is_array($opt) ? ($opt['nombre'] ?? '') : ($opt->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected((string)$catId === (string)$id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="categoria_nombre" id="catNombre"
           value="{{ old('categoria_nombre', data_get($g,'categoria.nombre')) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Oficina responsable <span class="text-danger">*</span></label>
    @php $ofiId = old('oficina_id', data_get($g,'oficina.id')); @endphp
    <select name="oficina_id" class="form-select" required>
      <option value="">— Seleccionar —</option>
      @foreach($oficinas as $opt)
        @php
          $id  = is_array($opt) ? ($opt['id'] ?? null) : ($opt->id ?? null);
          $nom = is_array($opt) ? ($opt['nombre'] ?? '') : ($opt->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected((string)$ofiId === (string)$id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="oficina_nombre" id="ofiNombre"
           value="{{ old('oficina_nombre', data_get($g,'oficina.nombre')) }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Ubicación oficina <span class="text-danger">*</span></label>
    @php $ubiId = old('ubicacion_id', data_get($g,'ubicacion.id')); @endphp
    <select name="ubicacion_id" class="form-select" required>
      <option value="">— Seleccionar —</option>
      @foreach($ubicaciones as $opt)
        @php
          $id  = is_array($opt) ? ($opt['id'] ?? null) : ($opt->id ?? null);
          $nom = is_array($opt) ? ($opt['nombre'] ?? '') : ($opt->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected((string)$ubiId === (string)$id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="ubicacion_nombre" id="ubiNombre"
           value="{{ old('ubicacion_nombre', data_get($g,'ubicacion.nombre')) }}">
  </div>

  <hr class="my-3">

  {{-- Contenido público --}}
  <div class="col-md-12">
    <label class="form-label">Descripción del trámite</label>
    <textarea name="descripcion_html" class="form-control rtx" rows="8">{!!
      old('descripcion_html', data_get($g,'descripcion_html'))
    !!}</textarea>
  </div>

  <div class="col-md-12">
    <label class="form-label">Requisitos <span class="text-danger">*</span></label>
    <textarea name="requisitos_html" class="form-control rtx" rows="10">{!!
      old('requisitos_html', data_get($g,'requisitos_html'))
    !!}</textarea>
  </div>

  <div class="col-md-12">
    <label class="form-label">Pasos para realizar el trámite <span class="text-danger">*</span></label>
    <textarea name="pasos_html" class="form-control rtx" rows="10">{!!
      old('pasos_html', data_get($g,'pasos_html'))
    !!}</textarea>
  </div>
</div>

{{-- ====== Scripts del editor (TinyMCE con fotos + videos) ====== --}}
@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/tinymce@6.8.3/tinymce.min.js" referrerpolicy="origin"></script>
  <script>
    // Completar *_nombre a partir del option seleccionado
    document.addEventListener('change', (e) => {
      const sel = e.target;
      if (sel.tagName !== 'SELECT') return;
      const txt = sel.options[sel.selectedIndex]?.text ?? '';
      if (sel.name === 'dependencia_id') document.getElementById('depNombre').value = txt;
      if (sel.name === 'categoria_id')   document.getElementById('catNombre').value = txt;
      if (sel.name === 'oficina_id')     document.getElementById('ofiNombre').value = txt;
      if (sel.name === 'ubicacion_id')   document.getElementById('ubiNombre').value = txt;
    });

    document.addEventListener('DOMContentLoaded', () => {
      tinymce.init({
        selector: 'textarea.rtx',
        height: 260,
        menubar: false,
        branding: false,
        convert_urls: false,
        paste_data_images: true,
        language: 'es',
        plugins: 'link lists image media table code',
        toolbar: 'bold italic underline | bullist numlist | outdent indent | link image media | alignleft aligncenter alignright | removeformat | code',

        // Permitir video y source
        extended_valid_elements: 'video[controls|width|height|poster|preload|autoplay|muted|loop|playsinline|src],source[src|type]',

        // Subida de imágenes (pegar, arrastrar o botón)
        images_upload_handler: (blobInfo) => new Promise((resolve, reject) => {
          const xhr = new XMLHttpRequest();
          xhr.open('POST', '{{ route('tramites.media') }}');
          xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
          xhr.onload = () => {
            if (xhr.status < 200 || xhr.status >= 300) return reject('HTTP ' + xhr.status);
            try { const { url } = JSON.parse(xhr.responseText); url ? resolve(url) : reject('Respuesta sin URL'); }
            catch { reject('Respuesta inválida'); }
          };
          const fd = new FormData();
          fd.append('file', blobInfo.blob(), blobInfo.filename());
          xhr.send(fd);
        }),

        // File picker para imágenes y videos
        file_picker_types: 'image media',
        file_picker_callback: (cb, value, meta) => {
          if (meta.filetype !== 'image' && meta.filetype !== 'media') return;
          const input = document.createElement('input');
          input.type  = 'file';
          input.accept = meta.filetype === 'image' ? 'image/*' : 'video/mp4,video/webm,video/ogg';
          input.onchange = () => {
            const fd = new FormData();
            fd.append('file', input.files[0]);
            fetch('{{ route('tramites.media') }}', {
              method: 'POST',
              headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
              body: fd
            })
            .then(r => r.json())
            .then(({ url }) => cb(url))
            .catch(() => alert('No se pudo subir el archivo.'));
          };
          input.click();
        },

        content_style:
          "body{font-family:system-ui,-apple-system,'Segoe UI',Roboto,'Helvetica Neue',Arial,'Noto Sans',sans-serif;font-size:.95rem;}"
      });
    });
  </script>
@endpush
