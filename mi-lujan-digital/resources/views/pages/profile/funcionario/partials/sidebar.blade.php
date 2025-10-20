<aside class="w-64 bg-white border-r min-h-screen p-4">
    <div class="mb-6">
        <div class="font-bold">{{ $funcionario->nombre }}</div>
        <div class="text-sm text-gray-600">{{ $funcionario->nivel }}</div>
        <div class="text-xs text-gray-500">{{ $funcionario->rol }}</div>
    </div>

    {{-- Menú dinámico según permisos --}}
    @php
        $menus = [
            'constructor_ficha' => 'Constructor de ficha',
            'bandeja_entrada' => 'Bandeja de entrada',
            'usuarios' => 'Usuarios',
            'estadisticas' => 'Estadísticas',
            // Agregá más según permisos detectados
        ];
    @endphp

    <nav class="space-y-2">
        @foreach ($funcionario->permisos as $permiso)
            @if (array_key_exists($permiso, $menus))
                <a href="#" class="block px-3 py-2 rounded hover:bg-gray-100 text-sm">
                    {{ $menus[$permiso] }}
                </a>
            @endif
        @endforeach

        {{-- Switch para cambiar de perfil --}}
        <form method="POST" action="{{ route('profile.switch') }}">
            @csrf
            <button class="text-xs text-blue-700 underline mt-4 hover:text-blue-900">
                Cambiar a perfil {{ session('perfil_activo') === 'funcionario' ? 'Ciudadano' : 'Funcionario' }}
            </button>
        </form>
    </nav>
</aside>
