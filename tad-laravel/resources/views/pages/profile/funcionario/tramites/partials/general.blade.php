<div class="mb-3">
  <label class="form-label">Nombre</label>
  <input type="text" name="nombre" class="form-control"
         value="{{ old('nombre', $tramite->nombre ?? '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Descripción</label>
  <textarea name="descripcion" rows="4" class="form-control">{{ old('descripcion', $tramite->descripcion ?? '') }}</textarea>
</div>

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Tipo</label>
    <input type="text" name="tipo" class="form-control" value="{{ old('tipo', $tramite->tipo ?? '') }}">
  </div>
  <div class="col-md-4">
    <label class="form-label">Estado inicial</label>
    <input type="text" name="estatus" class="form-control" value="{{ old('estatus', $tramite->estatus ?? '') }}">
  </div>
</div>

<div class="mb-3 mt-3">
  <label class="form-label">Mensaje</label>
  <textarea name="mensaje" rows="3" class="form-control">{{ old('mensaje', $tramite->mensaje ?? '') }}</textarea>
</div>

<div class="form-check">
  <input class="form-check-input" type="checkbox" name="publicado" id="chkPublicado"
         {{ old('publicado', $tramite->publicado ?? false) ? 'checked' : '' }}>
  <label class="form-check-label" for="chkPublicado">Publicado</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="disponible" id="chkDisponible"
         {{ old('disponible', $tramite->disponible ?? false) ? 'checked' : '' }}>
  <label class="form-check-label" for="chkDisponible">Disponible</label>
</div>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="mostrar_inicio" id="chkInicio"
         {{ old('mostrar_inicio', $tramite->mostrar_inicio ?? false) ? 'checked' : '' }}>
  <label class="form-check-label" for="chkInicio">Mostrar en Inicio</label>
</div>
