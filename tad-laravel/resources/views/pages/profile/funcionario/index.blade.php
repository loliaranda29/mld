@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Panel de Funcionario</h1>
    <p>Bienvenido al perfil de funcionario.</p>

    <pre class="bg-gray-100 p-4 rounded mt-4 text-sm">
        <h2>{{ $funcionario->nombre }}</h2>
<p>Nivel: {{ $funcionario->nivel }}</p>
<p>Rol: {{ $funcionario->rol }}</p>
    </pre>
</div>
@endsection

