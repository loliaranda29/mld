@php
  // Estos arrays idealmente vienen del controlador. Dejamos fallback por si no se pasan.
  $dependencias = $dependencias ?? [];
  $categorias   = $categorias   ?? [];
  $oficinas     = $oficinas     ?? [];
  $ubicaciones  = $ubicaciones  ?? [];
@endphp

{{-- Styles del editor --}}
@push('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
@endpush

<div class="row g-3">

  {{-- Título (ya lo tenés en tu form) + switches a la derecha se mantienen en el layout existente --}}
  <div class="row g-3">
  <div class="col-md-8">
    <label class="form-label">Nombre del trámite <span class="text-danger">*</span></label>
    <input type="text"
           name="nombre"
           class="form-control"
           value="{{ old('nombre', $tramite->nombre ?? '') }}"
           required>
    <div class="form-text">Este nombre se usa en listados y en la ficha pública.</div>
  </div>

  <div class="col-md-4">
    <label class="form-label">Descripción (corta)</label>
    <input type="text"
           name="descripcion"
           class="form-control"
           value="{{ old('descripcion', $tramite->descripcion ?? '') }}"
           placeholder="Resumen breve que aparece en listados">
  </div>
</div>

<hr class="my-3">

  {{-- Bloque encabezado de ficha --}}
  <div class="col-12">
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Tutorial (texto enriquecido, podés insertar imágenes o videos)</label>
        <textarea class="form-control js-editor" name="tutorial_html">{{ old('tutorial_html', $tramite->tutorial_html ?? '') }}</textarea>
      </div>

      <div class="col-md-6">
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Modalidad</label>
            @php $modalidad = old('modalidad', $tramite->modalidad ?? ''); @endphp
            <select name="modalidad" class="form-select">
              <option value="">—</option>
              <option value="Presencial"        @selected($modalidad==='Presencial')>Presencial</option>
              <option value="Online"            @selected($modalidad==='Online')>Online</option>
              <option value="Presencial/Online" @selected($modalidad==='Presencial/Online')>Presencial/Online</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Implica costo</label>
            @php $costo = old('implica_costo', $tramite->implica_costo ?? ''); @endphp
            <select name="implica_costo" class="form-select">
              <option value="">—</option>
              <option value="Con costo" @selected($costo==='Con costo')>Con costo</option>
              <option value="Sin costo" @selected($costo==='Sin costo')>Sin costo</option>
            </select>
          </div>

          <div class="col-md-6">
            <label class="form-label">Teléfono oficina</label>
            <input name="telefono_oficina" type="text" class="form-control"
                   value="{{ old('telefono_oficina', $tramite->telefono_oficina ?? '') }}">
          </div>

          <div class="col-md-6">
            <label class="form-label">Horario de atención</label>
            <input name="horario_atencion" type="text" class="form-control"
                   value="{{ old('horario_atencion', $tramite->horario_atencion ?? '') }}">
          </div>
        </div>
      </div>

      <div class="col-12">
        <label class="form-label">Detalle de costo</label>
        <textarea class="form-control js-editor" name="detalle_costo_html">{{ old('detalle_costo_html', $tramite->detalle_costo_html ?? '') }}</textarea>
      </div>
    </div>
  </div>

  <hr class="my-3">

  {{-- Selects: Dependencia, Categoría, Oficina, Ubicación --}}
  <div class="col-md-6">
    <label class="form-label">Dependencia <span class="text-danger">*</span></label>
    <select name="dependencia_id" class="form-select">
      <option value="">— Seleccionar —</option>
      @foreach ($dependencias as $dep)
        @php
          $id  = is_array($dep) ? ($dep['id'] ?? null) : ($dep->id ?? null);
          $nom = is_array($dep) ? ($dep['nombre'] ?? '') : ($dep->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected(old('dependencia_id', $tramite->dependencia_id ?? null) == $id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="dependencia_nombre" id="depNombre"
           value="{{ old('dependencia_nombre', $tramite->dependencia_nombre ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Categoría <span class="text-danger">*</span></label>
    <select name="categoria_id" class="form-select">
      <option value="">— Seleccionar —</option>
      @foreach ($categorias as $cat)
        @php
          $id  = is_array($cat) ? ($cat['id'] ?? null) : ($cat->id ?? null);
          $nom = is_array($cat) ? ($cat['nombre'] ?? '') : ($cat->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected(old('categoria_id', $tramite->categoria_id ?? null) == $id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="categoria_nombre" id="catNombre"
           value="{{ old('categoria_nombre', $tramite->categoria_nombre ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Oficina responsable <span class="text-danger">*</span></label>
    <select name="oficina_id" class="form-select">
      <option value="">— Seleccionar —</option>
      @foreach ($oficinas as $ofi)
        @php
          $id  = is_array($ofi) ? ($ofi['id'] ?? null) : ($ofi->id ?? null);
          $nom = is_array($ofi) ? ($ofi['nombre'] ?? '') : ($ofi->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected(old('oficina_id', $tramite->oficina_id ?? null) == $id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="oficina_nombre" id="ofiNombre"
           value="{{ old('oficina_nombre', $tramite->oficina_nombre ?? '') }}">
  </div>

  <div class="col-md-6">
    <label class="form-label">Ubicación oficina <span class="text-danger">*</span></label>
    <select name="ubicacion_id" class="form-select">
      <option value="">— Seleccionar —</option>
      @foreach ($ubicaciones as $ubi)
        @php
          $id  = is_array($ubi) ? ($ubi['id'] ?? null) : ($ubi->id ?? null);
          $nom = is_array($ubi) ? ($ubi['nombre'] ?? '') : ($ubi->nombre ?? '');
        @endphp
        <option value="{{ $id }}" @selected(old('ubicacion_id', $tramite->ubicacion_id ?? null) == $id)>{{ $nom }}</option>
      @endforeach
    </select>
    <input type="hidden" name="ubicacion_nombre" id="ubiNombre"
           value="{{ old('ubicacion_nombre', $tramite->ubicacion_nombre ?? '') }}">
  </div>

  <hr class="my-3">

  {{-- Bloques mostrados en la ficha pública --}}
  <div class="col-md-6">
    <label class="form-label">Descripción del trámite</label>
    <textarea class="form-control js-editor" name="descripcion_html">{{ old('descripcion_html', $tramite->descripcion_html ?? '') }}</textarea>
  </div>

  <div class="col-md-6">
    <label class="form-label">Requisitos</label>
    <textarea class="form-control js-editor" name="requisitos_html">{{ old('requisitos_html', $tramite->requisitos_html ?? '') }}</textarea>
  </div>

  <div class="col-md-6">
    <label class="form-label">Pasos para realizar el trámite</label>
    <textarea class="form-control js-editor" name="pasos_html">{{ old('pasos_html', $tramite->pasos_html ?? '') }}</textarea>
  </div>
</div>

{{-- Scripts del editor --}}
@push('scripts')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
  <script>
    // Helper para obtener el texto del option y mandarlo a *_nombre
    document.addEventListener('change', (e) => {
      const t = e.target;
      if (t.name === 'dependencia_id') {
        document.getElementById('depNombre').value = t.options[t.selectedIndex]?.text ?? '';
      }
      if (t.name === 'categoria_id') {
        document.getElementById('catNombre').value = t.options[t.selectedIndex]?.text ?? '';
      }
      if (t.name === 'oficina_id') {
        document.getElementById('ofiNombre').value = t.options[t.selectedIndex]?.text ?? '';
      }
      if (t.name === 'ubicacion_id') {
        document.getElementById('ubiNombre').value = t.options[t.selectedIndex]?.text ?? '';
      }
    });

    // Inicializar Summernote (imágenes y videos)
    const uploadUrl = "{{ route('tramites.media.upload') }}";
    const csrf     = "{{ csrf_token() }}";

    function initEditor(el){
      $(el).summernote({
        height: 180,
        placeholder: 'Escribe aquí...',
        toolbar: [
          ['style', ['bold','italic','underline','clear']],
          ['para',  ['ul','ol','paragraph']],
          ['insert',['link','picture','video']],
          ['view',  ['codeview','help']]
        ],
        callbacks: {
          onImageUpload: function(files) {
            for (let i = 0; i < files.length; i++) {
              uploadFile(files[i], (url) => {
                $(el).summernote('insertImage', url);
              });
            }
          },
          onMediaDelete: function(target) {
            // opcional: podrías borrar el archivo del server
          },
          onFileUpload: function(file) { // algunos builds lo disparan
            uploadFile(file, (url) => {
              $(el).summernote('createLink', {text: 'Archivo', url});
            });
          }
        }
      });
    }

    function uploadFile(file, cb){
      const data = new FormData();
      data.append('file', file);
      data.append('_token', csrf);
      fetch(uploadUrl, { method: 'POST', body: data })
        .then(r => r.json())
        .then(j => cb(j.url))
        .catch(() => alert('No se pudo subir el archivo'));
    }

    document.addEventListener('DOMContentLoaded', () => {
      document.querySelectorAll('.js-editor').forEach(initEditor);
    });
  </script>
@endpush
