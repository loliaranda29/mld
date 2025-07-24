<div class="v-navigation-drawer__content">
  <div class="flex flex-col items-center px-4 py-6 border-b">
    <div class="v-avatar overflow-hidden rounded-full border border-gray-300" style="height: 50px; width: 50px;">
      <img class="object-cover w-full h-full rounded-full" src="https://firebasestorage.googleapis.com/v0/b/os-arg-lujan-de-cuyo.appspot.com/o/users%2FEL7ME2E54uccdpCK6xnC7znfFBd2%2FprofilePicture.jpg?alt=media&amp;token=94664911-a337-4c9f-bab7-f0e0c180bf40" alt="Foto de perfil">
    </div>
    <p class="mt-3 text-sm font-semibold text-gray-800">Alicia Aranda</p>
    <p class="text-xs text-gray-500">Nivel 1 | Funcionario</p>
  </div>

  <nav class="flex-1 py-2">
    <template x-for="(menu, index) in menus" :key="index">
      <div>
        <div
          class="w-full flex justify-between items-center px-4 py-2 cursor-pointer hover:bg-gray-100 transition-colors"
          @click="openMenu === index ? openMenu = null : openMenu = index">
          <div class="flex items-center gap-3">
            <span class="text-gray-500" x-html="menu.iconSvg"></span>
            <span class="text-gray-700 font-medium" x-text="menu.title"></span>
          </div>
          <i :class="openMenu === index ? 'mdi mdi-chevron-down' : 'mdi mdi-chevron-right'" class="text-gray-400"></i>
        </div>

        <ul x-show="openMenu === index" x-collapse class="py-1 pl-10">
          <template x-for="(item, idx) in menu.items" :key="idx">
            <li>
              <template x-if="item.customClick">
                <a href="#" @click.prevent="seccionActiva = item.customClick" class="block py-1 text-gray-600 hover:text-primary-600 transition-colors text-sm">• <span x-text="item.label"></span></a>
              </template>
              <template x-if="!item.customClick">
                <a :href="item.url" class="block py-1 text-gray-600 hover:text-primary-600 transition-colors text-sm" :target="item.external ? '_blank' : null">• <span x-text="item.label"></span></a>
              </template>
            </li>
          </template>
        </ul>
      </div>
    </template>

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
