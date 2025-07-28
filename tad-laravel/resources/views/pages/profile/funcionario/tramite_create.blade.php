@extends('layouts.app-funcionario')

@section('title', 'Nuevo trámite')

@section('profile_content')
<nav class="text-sm text-black mt-4 mb-6">
    <ol class="flex flex-wrap items-center space-x-1">
            <a href="{{ url('/') }}" class="font-bold hover:underline flex items-center space-x-1">
                <i class="bi bi-house"></i>
                <span>Inicio</span>
            </a>
            <a>/</a>
            <a href="#" class="font-bold hover:underline">Ventanilla Digital</a>
            <a>/</a>
            <a href="#" class="font-bold hover:underline">Trámites</a>
            <a>/</a>
            <span class="text-gray-600">Nueva ficha</span>
        
    </ol>
</nav>
<div class="w-full md:w-[300px] bg-white rounded-md shadow p-4 space-y-4">
    {{-- Cabecera --}}
    <div class="flex items-center gap-2 px-3 py-2 bg-[#0c2d57] text-white rounded-md shadow">
        @include('components.icons.tramite')
        <span class="text-sm font-medium">Trámite sin publicar</span>
    </div>

    {{-- Switch de publicación --}}
    <div class="flex items-start gap-2">
        <input type="checkbox" id="publicado" class="mt-1 accent-blue-600">
        <label for="publicado" class="text-sm text-gray-800">
            <span class="font-semibold">Disponible en línea</span><br>
            <span class="text-xs text-gray-500">
                El funcionario podrá establecer los requisitos necesarios para habilitar el trámite en línea.
            </span>
        </label>
    </div>

    {{-- Ítem de menú lateral --}}
    <div class="flex items-center justify-between px-3 py-2 border rounded-md hover:bg-gray-50 cursor-pointer">
        <div class="flex items-center gap-2 text-gray-800">
            @include('components.icons.settings')
            <span class="text-sm">Configuración general</span>
        </div>
        <span class="text-red-600 text-xs">⨯</span>
    </div>
</div>


@endsection
