@extends('layouts.profile')

@section('sidebar')
    @include('pages.profile.funcionario.partials.sidebar')
@endsection

@section('content')
    <div class="px-6 py-4">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Panel de Funcionario</h1>
        <p class="text-gray-600 mb-6">Bienvenido/a al panel de gestión institucional.</p>

        {{-- Tarjetas de acceso rápido (placeholder) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white shadow rounded p-4 border border-gray-100 hover:shadow-lg transition">
                <h2 class="font-semibold text-lg text-gray-700">Constructor de ficha</h2>
                <p class="text-sm text-gray-500">Acceso al editor de fichas.</p>
            </div>

            <div class="bg-white shadow rounded p-4 border border-gray-100 hover:shadow-lg transition">
                <h2 class="font-semibold text-lg text-gray-700">Bandeja de entrada</h2>
                <p class="text-sm text-gray-500">Ver trámites asignados.</p>
            </div>

            <div class="bg-white shadow rounded p-4 border border-gray-100 hover:shadow-lg transition">
                <h2 class="font-semibold text-lg text-gray-700">Usuarios</h2>
                <p class="text-sm text-gray-500">Gestión de ciudadanos y funcionarios.</p>
            </div>
        </div>
    </div>
@endsection
