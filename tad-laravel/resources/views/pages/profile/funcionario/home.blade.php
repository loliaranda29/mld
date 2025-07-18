@extends('layouts.profile')

@section('content')
<div class="flex gap-6 px-6 py-6">

    {{-- Panel lateral izquierdo con perfil --}}
    <div class="w-64 bg-white p-4 shadow rounded text-center flex-shrink-0">
        <img src="{{ asset('img/user.png') }}" alt="Foto" class="w-24 h-24 mx-auto rounded-full mb-3">
        <p class="font-semibold text-gray-800">{{ Auth::user()->name ?? 'Funcionario' }}</p>
        <p class="text-sm text-gray-500">Nivel 1</p>
        <p class="text-sm text-blue-600">Funcionario</p>
        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-700 transition">
                Cerrar sesión
            </button>
        </form>
    </div>

    {{-- Botonera de acceso rápido --}}
    <div class="flex-1">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Panel de Funcionario</h1>
        <p class="text-gray-600 mb-6">Bienvenido/a al panel de gestión institucional.</p>

        @php
            $opciones = [
                ['titulo' => 'Listado de trámites', 'ruta' => '#'],
                ['titulo' => 'Bandeja de entrada', 'ruta' => '#'],
                ['titulo' => 'Configuración', 'ruta' => '#'],
                ['titulo' => 'Play List', 'ruta' => '#'],
                ['titulo' => 'Inspectores', 'ruta' => '#'],
                ['titulo' => 'Reportes', 'ruta' => '#'],
                ['titulo' => 'Citas', 'ruta' => '#'],
                ['titulo' => 'Usuarios', 'ruta' => '#'],
                ['titulo' => 'Catálogos', 'ruta' => '#'],
                ['titulo' => 'Filtros', 'ruta' => '#'],
                ['titulo' => 'Estadísticas', 'ruta' => '#'],
                ['titulo' => 'Registro de cambios', 'ruta' => '#'],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach ($opciones as $opcion)
                <a href="{{ $opcion['ruta'] }}" class="block">
                    <div class="bg-white shadow rounded-lg py-6 px-4 text-center hover:shadow-lg transition">
                        <p class="text-teal-700 font-semibold">{{ $opcion['titulo'] }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
