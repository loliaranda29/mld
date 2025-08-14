@extends('layouts.app-funcionario')

@section('title', 'Configuración')

@section('profile_content')
<div class="container-fluid mt-3">
  {{-- Breadcrumb --}}
  <nav class="mb-3" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
    <ol class="breadcrumb mb-0">
      <li class="breadcrumb-item"><a href="{{ route('home.index') }}">Inicio</a></li>
      <li class="breadcrumb-item active">Configuración</li>
    </ol>
  </nav>

  <div class="row">

    {{-- Contenido --}}
    <div class="col-lg-9">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">Editar configuración general</h5>
          <button form="form-config" class="btn btn-primary">Guardar cambios</button>
        </div>
        <div class="card-body">
          <form id="form-config" action="{{ route('configuracion.guardar') }}" method="POST">
            @csrf

            <div class="row g-4">
              {{-- Idioma --}}
              <div class="col-md-6">
                <label class="form-label">Idioma</label>
                <select class="form-select" name="idioma" required>
                  @foreach($idiomas as $key => $label)
                    <option value="{{ $key }}" @selected($config['idioma']===$key)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>

              {{-- Configuración de template --}}
              <div class="col-md-6">
                <label class="form-label d-block">Configuración de template</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="template_enabled" name="template_enabled" value="1"
                         @checked($config['template_enabled'])>
                  <label class="form-check-label" for="template_enabled">Activar configuración de template</label>
                </div>
              </div>

              {{-- Trámites internos --}}
              <div class="col-md-6">
                <label class="form-label d-block">Trámites internos</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="tramites_internos" name="tramites_internos" value="1"
                         @checked($config['tramites_internos'])>
                  <label class="form-check-label" for="tramites_internos">Permitir trámites internos</label>
                </div>
              </div>

              {{-- Mostrar todos los campos --}}
              <div class="col-md-6">
                <label class="form-label d-block">Mostrar todos los campos y archivos</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="mostrar_todos_campos" name="mostrar_todos_campos" value="1"
                         @checked($config['mostrar_todos_campos'])>
                  <label class="form-check-label" for="mostrar_todos_campos">Aunque no hayan sido completados</label>
                </div>
              </div>

              {{-- Editar formulario --}}
              <div class="col-md-6">
                <label class="form-label d-block">Editar formulario</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="editar_formulario" name="editar_formulario" value="1"
                         @checked($config['editar_formulario'])>
                  <label class="form-check-label" for="editar_formulario">Permitir que el funcionario edite la solicitud</label>
                </div>
              </div>

              {{-- Módulo empresas --}}
              <div class="col-md-6">
                <label class="form-label d-block">Activar módulo de empresas</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="modulo_empresas" name="modulo_empresas" value="1"
                         @checked($config['modulo_empresas'])>
                  <label class="form-check-label" for="modulo_empresas">Módulo activo</label>
                </div>
              </div>

              {{-- Apoderados --}}
              <div class="col-md-6">
                <label class="form-label d-block">Activar Apoderados</label>
                <div class="form-check form-switch">
                  <input class="form-check-input" type="checkbox" id="apoderados" name="apoderados" value="1"
                         @checked($config['apoderados'])>
                  <label class="form-check-label" for="apoderados">Permitir apoderados</label>
                </div>
              </div>

              {{-- Momento cobro --}}
              <div class="col-md-6">
                <label class="form-label">Momento en que se almacenan configuraciones de cobro</label>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="mc_enviar" name="momento_cobro" value="al_enviar"
                         @checked($config['momento_cobro']==='al_enviar')>
                  <label class="form-check-label" for="mc_enviar">Al enviar la solicitud</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" id="mc_llegar" name="momento_cobro" value="al_llegar_etapa"
                         @checked($config['momento_cobro']==='al_llegar_etapa')>
                  <label class="form-check-label" for="mc_llegar">Al llegar a la etapa de pago</label>
                </div>
              </div>

              {{-- Campos autocompletables (chips) --}}
              <div class="col-12">
                <label class="form-label">Campos autocompletables disponibles</label>
                <select name="autocomplete_fields[]" class="form-select" multiple size="8">
                  @foreach($campos as $key=>$label)
                    <option value="{{ $key }}" @selected(in_array($key,$config['autocomplete_fields'] ?? []))>{{ $label }}</option>
                  @endforeach
                </select>
                <small class="text-muted">Mantén presionada CTRL/⌘ para seleccionar múltiples.</small>
              </div>

              {{-- Identificador de usuario --}}
              <div class="col-12">
                <label class="form-label">Campo para identificar al usuario</label>
                <select name="identificador_usuario" class="form-select">
                  @foreach($campos as $key=>$label)
                    <option value="{{ $key }}" @selected(($config['identificador_usuario'] ?? '') === $key)>{{ $label }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </form>

          <hr class="my-4">

          {{-- Días inhábiles --}}
          <div class="row g-3">
            <div class="col-12">
              <h5 class="mb-2">Días inhábiles</h5>
              <p class="text-muted">Especifica días no laborables, feriados o fechas exactas.</p>
            </div>

            <div class="col-12">
              <div class="table-responsive">
                <table class="table align-middle">
                  <thead class="table-light">
                    <tr>
                      <th>Fecha</th>
                      <th class="text-end">Acciones</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse(($config['dias_inhabiles'] ?? []) as $dia)
                      <tr>
                        <td>{{ \Carbon\Carbon::parse($dia)->format('d/m/Y') }}</td>
                        <td class="text-end">
                          <form action="{{ route('configuracion.inhabiles.del', $dia) }}" method="POST"
                                onsubmit="return confirm('¿Eliminar {{ \Carbon\Carbon::parse($dia)->format('d/m/Y') }}?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-link text-decoration-none" title="Eliminar">
                              <i class="bi bi-trash"></i>
                            </button>
                          </form>
                        </td>
                      </tr>
                    @empty
                      <tr><td colspan="2" class="text-center text-muted py-4">Sin fechas cargadas</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-md-6">
              <form class="d-flex gap-2" action="{{ route('configuracion.inhabiles.add') }}" method="POST">
                @csrf
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-calendar3"></i></span>
                  <input type="date" class="form-control" name="dia" required>
                </div>
                <button class="btn btn-outline-primary">Agregar fecha</button>
              </form>
            </div>
          </div>

        </div>{{-- card-body --}}
      </div>{{-- card --}}
    </div>
  </div>
</div>

<style>
  .breadcrumb a { font-weight:600; color:#000; }
</style>
@endsection
