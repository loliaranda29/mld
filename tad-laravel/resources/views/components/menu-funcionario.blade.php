<script src="https://unpkg.com/alpinejs" defer></script>

<aside class="fixed left-0 top-0 w-64 h-screen bg-white shadow-md flex flex-col overflow-y-auto">
  <!-- Perfil Usuario -->
   <div class="v-navigation-drawer__content">
    <div class="flex flex-col items-center px-4 py-6 border-b">
      <div class="v-avatar overflow-hidden rounded-full border border-gray-300" style="height: 50px; width: 50px;">
        <img class="object-cover w-full h-full rounded-full" src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/users%2FEL7ME2E54uccdpCK6xnC7znfFBd2%2FprofilePicture.jpg?alt=media&amp;token=94664911-a337-4c9f-bab7-f0e0c180bf40" alt="Foto de perfil">
      </div>
      <p class="mt-3 text-sm font-semibold text-gray-800">Alicia Aranda</p>
      <p class="text-xs text-gray-500">Nivel 1 | Funcionario</p>
    </div>


  <nav class="flex-1 py-2" x-data="{ openMenu: null }">
    @php
    $menus = [
      [
        'title' => 'Ventanilla Digital',
        'iconSvg' => '<i class="mdi mdi-calendar"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Constructor de ficha'],
          ['url' => '#', 'label' => 'Listado de trámites'],
          ['url' => '#', 'label' => 'Bandeja de entrada'],
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Inspectores',
        'iconSvg' => '<i class="mdi mdi-account"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Bandeja de entrada'],
          ['url' => '#', 'label' => 'Bandeja de asignación'],
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Pagos',
        'iconSvg' => '<i class="mdi mdi-cash"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Lista de pagos'],
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Citas',
        'iconSvg' => '<i class="mdi mdi-calendar-check"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Bandeja de entrada'],
          ['url' => '#', 'label' => 'Bandeja de asignación'],
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Usuarios',
        'iconSvg' => '<i class="mdi mdi-account-group"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Ciudadanos'],
          ['url' => '#', 'label' => 'Institucionales'],
          ['url' => '#', 'label' => 'Funcionarios'],
          ['url' => '#', 'label' => 'Visualizadores'],
          ['url' => '#', 'label' => 'Roles'],
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Catálogos',
        'iconSvg' => '<i class="mdi mdi-book"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Catálogos'],
        ],
      ],
      [
        'title' => 'Filtros',
        'iconSvg' => '<i class="mdi mdi-filter"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Filtros'],
        ],
      ],
      [
        'title' => 'Configuración',
        'iconSvg' => '<i class="mdi mdi-cog"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Configuración'],
        ],
      ],
      [
        'title' => 'Estadísticas',
        'iconSvg' => '<i class="mdi mdi-chart-bar"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Estadísticas'],
        ],
      ],
      [
        'title' => 'Registro de cambios',
        'iconSvg' => '<i class="mdi mdi-timer-refresh-outline"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Registro de cambios'],
        ],
      ],
      [
        'title' => 'Centro de ayuda +',
        'iconSvg' => '<i class="mdi mdi-help-circle"></i>',
        'items' => [
          ['url' => '#', 'label' => 'Centro de ayuda +', 'external' => true],
        ],
      ],
    ];
    @endphp

    <nav class="flex-1 py-2" x-data="{ openMenu: null }">
      @foreach($menus as $index => $menu)
        <div>
          <div
            class="w-full flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-100 transition-colors"
            @click="openMenu === {{ $index }} ? openMenu = null : openMenu = {{ $index }}"
          >
            <div class="flex items-center gap-3">
              <span class="text-gray-500">{!! $menu['iconSvg'] !!}</span>
              <span class="text-gray-700 font-medium">{{ $menu['title'] }}</span>
            </div>
            <i :class="openMenu === {{ $index }} ? 'mdi mdi-chevron-down' : 'mdi mdi-chevron-right'" class="text-gray-400"></i>
          </div>

          <ul x-show="openMenu === {{ $index }}" x-collapse class="py-1 pl-10">
            @foreach($menu['items'] as $item)
              <li>
                <a
                  href="{{ $item['url'] }}"
                  class="block py-1 text-gray-600 hover:text-primary-600 transition-colors text-sm"
                  @if(isset($item['external']) && $item['external']) target="_blank" @endif
                >
                  • {{ $item['label'] }}
                </a>
              </li>
            @endforeach
          </ul>
        </div>
      @endforeach

      <div class="mt-auto border-t px-4 py-4">
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="flex items-center gap-2 text-red-500 hover:text-red-700">
            <i class="mdi mdi-logout-variant text-lg"></i>
            <span class="text-sm">Cerrar sesión</span>
          </button>
        </form>
      </div>
    </nav>
  </div>
</aside>

<div class="ml-64">
  @yield('content')
</div>