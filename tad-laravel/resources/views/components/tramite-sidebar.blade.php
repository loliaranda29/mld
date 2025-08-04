<div class="w-[22%] fixed">
    <div class="rounded-lg shadow-md bg-white overflow-hidden">
        
        <!-- Header azul -->
        <div class="flex items-center gap-2 px-4 py-3 bg-[#0c2d57] text-white">
            @include('components.icons.tramite') {{-- SVG del ícono del encabezado --}}
            <span class="text-sm font-medium">Trámite sin publicar</span>
        </div>

        <!-- Switch disponible en línea -->
        <div class="px-4 py-3 border-b border-gray-200">
            <label class="flex items-start gap-3">
                <input type="checkbox" class="toggle toggle-sm mt-1">
                <div>
                    <p class="font-medium text-sm text-gray-800">Disponible en línea</p>
                    <p class="text-xs text-gray-600">
                        El funcionario podrá establecer los requisitos necesarios para habilitar el trámite en línea.
                    </p>
                </div>
            </label>
        </div>

        <!-- Item Configuración general -->
        <div class="px-4 py-3 flex justify-between items-center hover:bg-gray-100 cursor-pointer border-b border-gray-200">
            <div class="flex items-center gap-3">
                @include('components.icons.settings') {{-- ⚙️ --}}
                <span class="text-sm text-gray-700">Configuración general</span>
            </div>
            <div class="flex items-center gap-2">
                <x-icons.circle-exclamation class="text-red-600 w-4 h-4" />
                <x-icons.chevron-right class="text-gray-400 w-4 h-4" />
            </div>
        </div>

    </div>
</div>

