@extends('layouts.app-funcionario')

@section('title', 'Subcatálogos')

@section('profile_content')
@php
  // Nombre del catálogo (soporta array u objeto)
  $catName = null;
  if (isset($catalogo)) {
    if (is_array($catalogo))      $catName = $catalogo['nombre'] ?? null;
    elseif (is_object($catalogo)) $catName = $catalogo->nombre ?? null;
  }
@endphp

<div class="container-fluid mt-4">
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  @if(session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Breadcrumbs + acciones --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('catalogos.index') }}">Catálogos</a></li>
        <li class="breadcrumb-item">
          <a href="{{ route('catalogos.show', $catalogoId) }}">{{ $catName ?? $catalogoId }}</a>
        </li>
        <li class="breadcrumb-item active" aria-current="page">Subcatálogos</li>
      </ol>
    </nav>

    <div class="d-flex gap-2">
      <a href="{{ route('catalogos.show', $catalogoId) }}" class="btn btn-outline-secondary">
        Regresar
      </a>
      {{-- Abre modal con dos opciones: alta manual o CSV --}}
      <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCarga">
        Cargar nuevos datos
      </button>
    </div>
  </div>

  {{-- Mensaje info --}}
  <div class="alert alert-light border mb-3">
    Si realizás alguna modificación recordá volver a la
    <a href="{{ route('catalogos.show', $catalogoId) }}">página inicial</a>
    para sincronizar tu catálogo.
  </div>

  {{-- Tabla --}}
  <div class="card shadow-sm">
    <div class="table-responsive">
      <table class="table align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Nombre</th>
            <th style="width: 220px;">Slug</th>
            <th style="width: 280px;">Id</th>
            <th style="width: 120px;" class="text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subcatalogos as $it)
            @php
              // Compatibilidad array / objeto
              $id     = is_array($it) ? ($it['id'] ?? null) : ($it->id ?? null);
              $nombre = is_array($it) ? ($it['nombre'] ?? '') : ($it->nombre ?? '');
              $meta   = is_array($it) ? ($it['meta'] ?? []) : ($it->meta ?? []);
              if (is_string($meta)) { $meta = json_decode($meta, true) ?: []; }
              $slugV  = is_array($meta) ? ($meta['slug'] ?? null) : null;
              $slugV  = $slugV ?: \Illuminate\Support\Str::slug($nombre);
            @endphp
            <tr>
              <td>{{ $nombre }}</td>
              <td class="text-muted">{{ $slugV }}</td>
              <td class="text-muted"><span class="small">{{ $id }}</span></td>
              <td class="text-center">
                {{-- Ver --}}
                <a href="{{ route('catalogos.sub.show', [$catalogoId, $id]) }}"
                   class="btn btn-link p-0 m-0 text-primary me-3" title="Ver">
                  <i class="bi bi-eye"></i>
                </a>
                {{-- Copiar ID --}}
                <button type="button" class="btn btn-link p-0 m-0 text-primary me-3"
                        title="Copiar ID" onclick="copyToClipboard('{{ $id }}')">
                  <i class="bi bi-clipboard"></i>
                </button>
                {{-- Eliminar --}}
                <form action="{{ route('catalogos.sub.destroy', [$catalogoId, $id]) }}"
                      method="POST" class="d-inline"
                      onsubmit="return confirm('¿Eliminar «{{ $nombre ?: 'este subcatálogo' }}»?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-link p-0 m-0 text-danger" title="Eliminar">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-muted py-4">Sin subcatálogos</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Paginación simple (compatible con paginator o arrays) --}}
    @php
      if (is_object($subcatalogos) && method_exists($subcatalogos, 'total')) {
        $total   = $subcatalogos->total();
        $page    = $subcatalogos->currentPage();
        $perPage = $subcatalogos->perPage();
      } else {
        $total   = $total   ?? (is_countable($subcatalogos) ? count($subcatalogos) : 0);
        $page    = $page    ?? 1;
        $perPage = $perPage ?? max(10, $total);
      }
      $from = $total ? (($page - 1) * $perPage) + 1 : 0;
      $to   = min($page * $perPage, $total);
      $prev = max(1, $page - 1);
      $next = $total > $page * $perPage ? $page + 1 : $page;
    @endphp

    <div class="card-footer d-flex justify-content-between align-items-center">
      <div class="d-flex align-items-center gap-2">
        <span class="text-muted">Filas por página:</span>
        <select class="form-select form-select-sm" style="width: 70px"
                onchange="location.href='?per_page='+this.value+'&page=1'">
          @foreach([10,20,50] as $n)
            <option value="{{ $n }}" @selected(($perPage ?? 10) == $n)>{{ $n }}</option>
          @endforeach
        </select>
      </div>

      <div class="d-flex align-items-center gap-3">
        <span class="text-muted small">{{ $from }}–{{ $to }} de {{ $total }}</span>
        <div class="btn-group">
          <a class="btn btn-outline-secondary btn-sm"
             href="?per_page={{ $perPage }}&page={{ $prev }}"
             @disabled($page == 1)>
            <i class="bi bi-chevron-left"></i>
          </a>
          <a class="btn btn-outline-secondary btn-sm"
             href="?per_page={{ $perPage }}&page={{ $next }}"
             @disabled($page == $next)>
            <i class="bi bi-chevron-right"></i>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Modal: Cargar nuevos datos (manual o CSV) --}}
<div class="modal fade" id="modalCarga" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Subir datos del catálogo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>

      <div class="modal-body">
        <ul class="nav nav-pills mb-3" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-manual"
                    data-bs-toggle="pill" data-bs-target="#pane-manual" type="button" role="tab">
              Agregar un término
            </button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-csv"
                    data-bs-toggle="pill" data-bs-target="#pane-csv" type="button" role="tab">
              Subir lista de términos
            </button>
          </li>
        </ul>

        <div class="tab-content">
          {{-- Alta manual --}}
          <div class="tab-pane fade show active" id="pane-manual" role="tabpanel" aria-labelledby="tab-manual">
            <form action="{{ route('catalogos.sub.store', $catalogoId) }}" method="POST" id="formManual">
              @csrf
              <div class="row g-3">
                <div class="col-md-6">
                  <label class="form-label">Valor de la opción <span class="text-danger">*</span></label>
                  <input type="text" name="nombre" id="inpNombre" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ruta dinámica de la opción</label>
                  <input type="text" name="slug" id="inpSlug" class="form-control" placeholder="se genera desde el nombre">
                  <div class="form-text">Se usa como identificador/slug.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Ícono</label>
                  <input type="text" name="icono" class="form-control" placeholder="ej: mdi-domain">
                  <div class="form-text">
                    <a href="https://pictogrammers.github.io/@mdi/font/6.5.95/" target="_blank" rel="noopener">Lista de íconos</a>
                  </div>
                </div>
                <div class="col-md-3">
                  <label class="form-label">Orden</label>
                  <input type="number" name="orden" class="form-control" min="0">
                </div>
                <div class="col-md-3 d-flex align-items-center gap-3">
  {{-- Activo --}}
  <input type="hidden" name="activo" value="0">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="activo" id="chkActivo" value="1" checked>
    <label class="form-check-label" for="chkActivo">Activo</label>
  </div>

  {{-- Jerárquico --}}
  <input type="hidden" name="jerarquico" value="0">
  <div class="form-check">
    <input class="form-check-input" type="checkbox" name="jerarquico" id="chkJer" value="1">
    <label class="form-check-label" for="chkJer">Jerárquico</label>
  </div>
</div>

              </div>
            </form>
          </div>

          {{-- CSV --}}
          <div class="tab-pane fade" id="pane-csv" role="tabpanel" aria-labelledby="tab-csv">
            <form action="{{ route('catalogos.sub.upload', $catalogoId) }}" method="POST" enctype="multipart/form-data" id="formCsv">
              @csrf
              <div class="mb-3">
                <label class="form-label">Archivo CSV</label>
                <input type="file" name="csv" class="form-control" accept=".csv,.txt" required>
                <div class="form-text">
                  Con cabecera: <code>nombre,slug,icono,orden,activo,jerarquico</code>.
                  Todas opcionales salvo <code>nombre</code>.
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>

      {{-- Footer del modal con 2 botones submit (uno por pestaña) --}}
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="submit" form="formManual" class="btn btn-primary" id="btnGuardarManual">Guardar</button>
        <button type="submit" form="formCsv" class="btn btn-primary d-none" id="btnGuardarCsv">Guardar</button>
      </div>
    </div>  {{-- .modal-content --}}
  </div>    {{-- .modal-dialog --}}
</div>      {{-- #modalCarga --}}

{{-- Scripts --}}
<script>
  // Cambia qué botón "Guardar" se ve según la pestaña activa
  document.querySelectorAll('button[data-bs-toggle="pill"]').forEach(function (el) {
    el.addEventListener('shown.bs.tab', function (ev) {
      const isManual = ev.target.getAttribute('data-bs-target') === '#pane-manual';
      document.getElementById('btnGuardarManual').classList.toggle('d-none', !isManual);
      document.getElementById('btnGuardarCsv').classList.toggle('d-none', isManual);
    });
  });

  // Copiar texto al portapapeles
  function copyToClipboard(text) {
    navigator.clipboard.writeText(text)
      .then(() => alert('ID copiado al portapapeles'))
      .catch(() => alert('No se pudo copiar'));
  }

  // Auto-slug desde nombre
  (function () {
    const nombre = document.getElementById('inpNombre');
    const slug   = document.getElementById('inpSlug');
    function toSlug(s){
      return String(s||'').normalize('NFD').replace(/[\u0300-\u036f]/g,'')
        .toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-+|-+$/g,'');
    }
    nombre?.addEventListener('input', () => { if (!slug.value) slug.value = toSlug(nombre.value); });
  })();
</script>
@endsection
