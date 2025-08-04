<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $tramite->nombre ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion', $tramite->descripcion ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo</label>
    <input type="text" name="tipo" class="form-control" value="{{ old('tipo', $tramite->tipo ?? '') }}">
</div>

<div class="mb-3">
    <label for="estatus" class="form-label">Estado Inicial</label>
    <input type="text" name="estatus" class="form-control" value="{{ old('estatus', $tramite->estatus ?? '') }}">
</div>

<div class="mb-3">
    <label for="mensaje" class="form-label">Mensaje</label>
    <textarea name="mensaje" class="form-control" rows="2">{{ old('mensaje', $tramite->mensaje ?? '') }}</textarea>
</div>

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" name="publicado" value="1" {{ old('publicado', $tramite->publicado ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Publicado</label>
</div>

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" name="disponible" value="1" {{ old('disponible', $tramite->disponible ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Disponible</label>
</div>

<div class="form-check mb-4">
    <input class="form-check-input" type="checkbox" name="mostrar_inicio" value="1" {{ old('mostrar_inicio', $tramite->mostrar_inicio ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Mostrar en Inicio</label>
</div>
