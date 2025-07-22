<aside class="v-navigation-drawer v-navigation-drawer--open theme--light bg-white shadow-md" style="width: 260px; min-height: 100vh;">
  <div class="v-navigation-drawer__content">
    <div class="flex flex-col items-center px-4 py-6 border-b">
      <div class="v-avatar overflow-hidden rounded-full border border-gray-300" style="height: 50px; width: 50px;">
        <img class="object-cover w-full h-full rounded-full" src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/users%2FEL7ME2E54uccdpCK6xnC7znfFBd2%2FprofilePicture.jpg?alt=media&amp;token=94664911-a337-4c9f-bab7-f0e0c180bf40" alt="Foto de perfil">
      </div>
      <p class="mt-3 text-sm font-semibold text-gray-800">Alicia Aranda</p>
      <p class="text-xs text-gray-500">Nivel 1 | Funcionario</p>
    </div>

    @php
    $menus = [
      [
        'title' => 'Ventanilla Digital',
        'iconSvg' => view('svg.calendar')->render(),
        'items' => [
          ['url' => url('/ventanilla/procedures?activeTab=0'), 'label' => 'Constructor de ficha'],
          ['url' => url('/ventanilla/procedures?activeTab=1'), 'label' => 'Listado de trámites'],
          ['url' => url('/ventanilla/inbox'), 'label' => 'Bandeja de entrada'],
          ['url' => url('/ventanilla'), 'label' => 'Configuración'],
        ],
        'active' => true
      ],
      [
        'title' => 'Inspectores',
        'iconSvg' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="22px" height="22px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2" /></svg>',
        'items' => [
          ['url' => url('/admin/inspectores?activeTab=0'), 'label' => 'Bandeja de entrada'],
          ['url' => url('/admin/inspectores?activeTab=1'), 'label' => 'Bandeja de asignación'],
          ['url' => url('/admin/inspectores/configuration'), 'label' => 'Configuración'],
        ],
        'active' => false
      ]
    ];
    @endphp

    <nav class="scroll mt-4">
      <div class="v-list v-sheet theme--light">
        @foreach($menus as $menu)
          <div class="v-list-group {{ $menu['active'] ? 'v-list-group--active bg-gray-100' : '' }}">
            <div tabindex="0" aria-expanded="{{ $menu['active'] ? 'true' : 'false' }}" role="button"
                 class="v-list-group__header v-list-item v-list-item--link px-4 py-3 flex items-center justify-between hover:bg-gray-50">
              <div class="flex items-center space-x-3">
                <span class="text-gray-600">{!! $menu['iconSvg'] !!}</span>
                <span class="text-sm font-medium text-gray-800">{{ $menu['title'] }}</span>
              </div>
              <i aria-hidden="true" class="v-icon notranslate mdi mdi-chevron-{{ $menu['active'] ? 'down' : 'right' }} text-gray-400"></i>
            </div>

            @if($menu['active'])
              <div class="v-list-group__items px-4 py-2 space-y-1">
                @foreach($menu['items'] as $item)
                  <a href="{{ $item['url'] }}" class="flex items-center space-x-2 text-sm text-gray-700 hover:text-primary-700">
                    <i class="mdi mdi-circle-small text-xs"></i>
                    <span>{{ $item['label'] }}</span>
                  </a>
                @endforeach
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </nav>
  </div>
</aside>
