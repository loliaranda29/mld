@extends('layouts.app-funcionario')

@section('profile_content')
<div class="container">
    <h1 class="h3 mb-3">{{ isset($tramite) ? 'Editar Trámite' : 'Crear Nuevo Trámite' }}</h1>

    <form method="POST"
          action="{{ isset($tramite) ? route('funcionario.tramites.update', $tramite->id)
                                     : route('funcionario.tramites.store') }}"
          x-data="{
            formulario: @js($tramite->formulario_json ?? []),
            etapas:     @js($etapas ?? []),
            bloques:    @js($tramite->documento_json ?? []),
            config:     @js($tramite->config_json ?? ['acepta_solicitudes'=>false,'acepta_pruebas'=>false])
          }">
        @csrf
        @if(isset($tramite)) @method('PUT') @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div class="card-header p-0">
                <ul class="nav nav-tabs" id="tabsTramite" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#tab-general" type="button" role="tab" aria-controls="tab-general" aria-selected="true">General</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="formulario-tab" data-bs-toggle="tab" data-bs-target="#tab-formulario" type="button" role="tab" aria-controls="tab-formulario" aria-selected="false">Formulario</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="etapas-tab" data-bs-toggle="tab" data-bs-target="#tab-etapas" type="button" role="tab" aria-controls="tab-etapas" aria-selected="false">Etapas</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="documento-tab" data-bs-toggle="tab" data-bs-target="#tab-documento" type="button" role="tab" aria-controls="tab-documento" aria-selected="false">Documento</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="configuracion-tab" data-bs-toggle="tab" data-bs-target="#tab-configuracion" type="button" role="tab" aria-controls="tab-configuracion" aria-selected="false">Configuración</button>
                    </li>

                    {{-- NUEVO: pestaña Relaciones --}}
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="relaciones-tab" data-bs-toggle="tab" data-bs-target="#tab-relaciones" type="button" role="tab" aria-controls="tab-relaciones" aria-selected="false">
                            Relaciones
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body pb-5">
                <div class="tab-content mt-3" id="tabsTramiteContent">
                    <div class="tab-pane fade show active" id="tab-general" role="tabpanel" aria-labelledby="general-tab">
                        @includeIf('pages.profile.funcionario.tramites.partials.general')
                    </div>

                    <div class="tab-pane fade" id="tab-formulario" role="tabpanel" aria-labelledby="formulario-tab">
                        @includeIf('pages.profile.funcionario.tramites.partials.formulario')
                    </div>

                    <div class="tab-pane fade" id="tab-etapas" role="tabpanel" aria-labelledby="etapas-tab">
                        @include('pages.profile.funcionario.tramites.partials.etapas', [
                            'tramite' => $tramite ?? null,
                            'etapas'  => $etapas  ?? []
                        ])
                    </div>

                    <div class="tab-pane fade" id="tab-documento" role="tabpanel" aria-labelledby="documento-tab">
                        @includeIf('pages.profile.funcionario.tramites.partials.documento')
                    </div>

                    <div class="tab-pane fade" id="tab-configuracion" role="tabpanel" aria-labelledby="configuracion-tab">
                        @includeIf('pages.profile.funcionario.tramites.partials.configuracion')
                    </div>

                    {{-- NUEVO: contenido de la pestaña Relaciones --}}
                    <div class="tab-pane fade" id="tab-relaciones" role="tabpanel" aria-labelledby="relaciones-tab">
                        <div class="p-2">
                            @include('pages.profile.funcionario.tramites.partials.relaciones', [
                                'tramite' => $tramite ?? null,
                                'todos'   => $todos   ?? collect(),   {{-- lista de otros trámites para selects --}}
                            ])
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botonera sticky: siempre visible al fondo del viewport --}}
        <div class="position-sticky bottom-0 bg-white border-top py-3 mt-3" style="z-index: 1030;">
            <div class="d-flex gap-2 justify-content-end">
                <button type="submit" class="btn btn-success">Guardar</button>
                <a href="{{ route('funcionario.tramite_config') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</div>
@endsection
