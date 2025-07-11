@extends('layouts.profile')

@section('profile_content')
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <div class="row align-items-center">

    <!-- Columna izquierda: Avatar y nombre -->
    <div class="col-md-5 text-center mb-4 mb-md-0">
      <div class="d-flex flex-column align-items-center">
        <!-- Avatar -->
        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 96px; height: 96px;">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 391.8 452.64" fill="#FFF" width="53px" height="53px">
            <path d="M358.27,294.16c-6.08-23.69...Z"></path>
            <path d="M193.28,218.56c54.7,0,98.71-44.14...Z"></path>
          </svg>
        </div>

        <!-- Nombre -->
        <h5 class="fw-bold mb-2">{{ $tramite['titulo'] }}</h5>

        <!-- Email -->
        <div class="mb-3 w-100 px-5">
          <label class="form-label text-muted">Numero</label>
          <input type="text" class="form-control" disabled value="{{ $tramite['numero'] }}">
        </div>
        <div class="mb-3 w-100 px-5">
          <label class="form-label text-muted">Fecha de emision</label>
          <input type="text" class="form-control" disabled value="{{ $tramite['fecha_emision'] }}">
        </div>
        <div class="mb-3 w-100 px-5">
          <label class="form-label text-muted">Tipo</label>
          <input type="text" class="form-control" disabled value="{{ $tramite['tipo'] }}">
        </div>
        <div class="mb-3 w-100 px-5">
          <label class="form-label text-muted">Estatus</label>
          <input type="text" class="form-control" disabled value="{{ $tramite['estatus'] }}">
        </div>


      </div>
    </div>

    <!-- Separador vertical -->
    <div class="col-md-1 d-none d-md-flex justify-content-center">
      <div class="vr" style="height: 100%; width: 1px; background-color: #dee2e6;"></div>
    </div>

    <!-- Columna derecha: Identidad digital -->
    <div class="col-md-6">
      <h6 class="fw-semibold">Identidad digital</h6>
      <p><strong>Conectado a:</strong> Luján de Cuyo</p>
      <p><strong>Wallet:</strong> {{ $tramite['etapa_actual'] ?? 'No conectada' }}</p>
      <p><strong>Llave pública:</strong></p>
      <textarea class="form-control mb-2" rows="2" disabled>{{ $tramite['etapas_totales'] ?? 'No disponible' }}</textarea>
      <button class="btn btn-sm btn-link"><i class="bi bi-clipboard"></i> Copiar</button>
      <button class="btn btn-dark w-100 mt-3"><i class="bi bi-arrow-up-circle me-2"></i> Subir de nivel</button>
    </div>

  </div>
</div>
<div class="card shadow rounded-4 px-4 py-5 mb-4">
  <ul class="nav nav-tabs custom-tabs justify-content-center flex-wrap mb-4" id="interesTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <a class="nav-link active" id="documentos-tab" data-bs-toggle="tab" href="#inmueblesTab" type="button" role="tab">Inmuebles</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#inicioTramiteTab" type="button" role="tab">Inicio del tramite</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#escribanoTab" type="button" role="tab">Escribano</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#transmitenteTab" type="button" role="tab">Transmitente</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#adquirenteTab" type="button" role="tab">Adquirente</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#documentosTab" type="button" role="tab">Documentos</a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#mensajesTab" type="button" role="tab">Mensajes</a>
    </li>
  </ul>
  <div class="tab-content" id="interesTabsContent">
    <div class="tab-pane fade show active" id="inmueblesTab" role="tabpanel">
      <div class="pa-3 col col-12">
        <h4 class="h4-custom"><i aria-hidden="true" class="v-icon notranslate white--text mr-2 mdi mdi-home theme--light"></i>
          Inmueble
        </h4>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Nomenclatura</label>
            <input type="text" name="nomenclatura" class="form-control" value="{{ $user['nomenclatura'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Padrón municipal</label>
            <input type="text" name="padron_municipal" class="form-control" value="{{ $user['padron_municipal'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Documento titular</label>
            <input type="text" name="documento_titular" class="form-control" value="{{ $user['documento_titular'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Apellido titular</label>
            <input type="text" name="apellido_titular" class="form-control" value="{{ $user['apellido_titular'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre titular</label>
            <input type="text" name="nombre_titular" class="form-control" value="{{ $user['nombre_titular'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Superficie terreno</label>
            <input type="text" name="superficie_terreno" class="form-control" value="{{ $user['superficie_terreno'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control" value="{{ $user['calle'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Distrito</label>
            <input type="text" name="distrito" class="form-control" value="{{ $user['distrito'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Zona Urbanística</label>
            <input type="text" name="zona_Urbanistica" class="form-control" value="{{ $user['zona_Urbanistica'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Ordenanza</label>
            <input type="text" name="ordenanza" class="form-control" value="{{ $user['ordenanza'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Deuda</label>
            <input type="text" name="deuda" class="form-control" value="{{ $user['deuda'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Fecha Consulta</label>
            <input type="date" name="fecha_consulta" class="form-control" value="{{ $user['fecha_consulta'] ??''}}" disabled>
          </div>
        </div>
      </form>

    </div>
    <div class="tab-pane fade" id="inicioTramiteTab" role="tabpanel">
      <div class="pa-3 col col-12">
        <h4 class="h4-custom"><i aria-hidden="true" class="v-icon notranslate white--text mr-2 mdi mdi-home theme--light"></i>
          Inicio del trámite
        </h4>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Tipo</label>
            <input type="text" name="tipo" class="form-control" value="{{ $user['tipo'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Trámite relacionado</label>
            <input type="text" name="tramite_relacionado" class="form-control" value="{{ $user['tramite_relacionado'] ??''}}" disabled>
          </div>
          <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Superficies:</h5>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Superficie según título</label>
            <input type="text" name="superficie_segun_titulo" class="form-control" value="{{ $user['superficie_segun_titulo'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Observación sobre superficie según título</label>
            <input type="text" name="observacion_superficie_segun_titulo" class="form-control" value="{{ $user['observacion_superficie_segun_titulo'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Superficie Según Mensura</label>
            <input type="text" name="superficie_segun_mensura" class="form-control" value="{{ $user['superficie_segun_mensura'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Observación sobre superficie según Mensura</label>
            <input type="text" name="observacion_superficie_segun_mensura" class="form-control" value="{{ $user['observacion_superficie_segun_mensura'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Superficie afectada</label>
            <input type="text" name="superficie_afectada" class="form-control" value="{{ $user['superficie_afectada'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Observación sobre superficie afectada</label>
            <input type="text" name="observacion_superficie_afectada" class="form-control" value="{{ $user['observacion_superficie_afectada'] ??''}}" disabled>
          </div>
          <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-semibold">Datos Generales:</h5>
            <p>Datos Catastro Provincia:</p>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Certificado Catastral</label>
            <input type="text" name="certificado_catastral" class="form-control" value="{{ $user['certificado_catastral'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Otros archivos</label>
            <input type="text" name="otros_archivos" class="form-control" value="{{ $user['otros_archivos'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Observaciones</label>
            <input type="text" name="observaciones" class="form-control" value="{{ $user['observaciones'] ??''}}" disabled>
          </div>
        </div>
      </form>

    </div>
    <div class="tab-pane fade" id="escribanoTab" role="tabpanel">
      <div class="pa-3 col col-12">
        <h4 class="h4-custom"><!---->
          Escribano
        </h4>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Matricula</label>
            <input type="text" name="matricula" class="form-control" value="{{ $user['matricula'] ??''}}" disabled>
          </div>
        </div>
      </form>
    </div>
    <div class="tab-pane fade" id="transmitenteTab" role="tabpanel">
      <div class="pa-3 col col-12">
        <h4 class="h4-custom"><!---->
          Transmitente
        </h4>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Porcentaje</label>
            <input type="text" name="porcentaje" class="form-control" value="{{ $user['porcentaje'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">CUIL</label>
            <input type="text" name="cuil" class="form-control" value="{{ $user['cuil'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $user['nombre'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ $user['apellido'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="{{ $user['email'] ??''}}" disabled>
          </div>
        </div>
      </form>

    </div>
    <div class="tab-pane fade" id="adquirenteTab" role="tabpanel">
      <div class="pa-3 col col-12">
        <h4 class="h4-custom"><!---->
          Adquirente
        </h4>
      </div>
      <form method="POST" action="">
        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Porcentaje</label>
            <input type="text" name="porcentaje" class="form-control" value="{{ $user['porcentaje'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">CUIL</label>
            <input type="text" name="cuil" class="form-control" value="{{ $user['cuil'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="{{ $user['nombre'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="{{ $user['apellido'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Email</label>
            <input type="text" name="email" class="form-control" value="{{ $user['email'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Calle</label>
            <input type="text" name="calle" class="form-control" value="{{ $user['calle'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Barrio</label>
            <input type="text" name="barrio" class="form-control" value="{{ $user['barrio'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Altura</label>
            <input type="text" name="altura" class="form-control" value="{{ $user['altura'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Piso</label>
            <input type="text" name="piso" class="form-control" value="{{ $user['piso'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Departamento</label>
            <input type="text" name="departamento" class="form-control" value="{{ $user['departamento'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Manzana</label>
            <input type="text" name="manzana" class="form-control" value="{{ $user['manzana'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Casa</label>
            <input type="text" name="casa" class="form-control" value="{{ $user['casa'] ??''}}" disabled>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Localidad</label>
            <input type="text" name="localidad" class="form-control" value="{{ $user['localidad'] ??''}}" disabled>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

@endsection