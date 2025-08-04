<!-- resources/views/components/tramite-formulario.blade.php -->
<div class="ml-[26%] w-[72%] mt-6">

    <!-- Inputs principales -->
    <div class="grid grid-cols-2 gap-4 mb-4">
        <div>
            <label class="block text-sm font-semibold mb-1">Nombre de la ficha *</label>
            <input type="text" class="w-full border rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
        </div>

        <div class="flex items-end justify-end space-x-2">
            <button class="bg-white border border-blue-700 text-blue-700 px-4 py-2 rounded-md hover:bg-blue-50">Clonar trámite</button>
            <button class="bg-white border border-blue-700 text-blue-700 px-4 py-2 rounded-md hover:bg-blue-50">Vista previa</button>
        </div>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-semibold mb-1">Descripción *</label>
        <textarea class="w-full border rounded-md px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-600" rows="3"></textarea>
    </div>

    <!-- Switch publicar -->
    <div class="flex items-center space-x-3 mb-6">
        <input type="checkbox" id="publicar" class="form-checkbox h-5 w-5 text-blue-600">
        <label for="publicar" class="text-sm text-gray-700">Publicar trámite<br>
            <span class="text-xs text-gray-500">El ciudadano podrá encontrar la ficha en la plataforma</span>
        </label>
    </div>

    <!-- Tabs de sección -->
    <div class="mb-2">
        <label class="block text-sm font-semibold mb-1">Secciones</label>
        <select class="border rounded-md px-3 py-2">
            <option selected>01 Datos generales</option>
        </select>
    </div>

    <!-- Contenido de Datos Generales -->
    <div class="bg-white rounded-lg p-6 shadow">
        <h2 class="text-lg font-semibold mb-4">Datos generales</h2>

        <div class="grid grid-cols-2 gap-4 mb-4">
            <!-- Tutorial -->
            <div>
                <label class="block text-sm font-semibold mb-1">Tutorial</label>
                <textarea class="w-full border rounded-md px-3 py-2"></textarea>
            </div>

            <!-- Modalidad -->
            <div>
                <label class="block text-sm font-semibold mb-1">Modalidad</label>
                <select class="w-full border rounded-md px-3 py-2">
                    <option>Presencial</option>
                    <option>Online</option>
                </select>
            </div>

            <!-- Implica costo -->
            <div>
                <label class="block text-sm font-semibold mb-1">Implica costo *</label>
                <select class="w-full border rounded-md px-3 py-2">
                    <option>No</option>
                    <option>Sí</option>
                </select>
            </div>

            <!-- Costo -->
            <div>
                <label class="block text-sm font-semibold mb-1">Costo</label>
                <textarea class="w-full border rounded-md px-3 py-2"></textarea>
            </div>

            <!-- Detalle de Costo -->
            <div>
                <label class="block text-sm font-semibold mb-1">Detalle de Costo</label>
                <textarea class="w-full border rounded-md px-3 py-2"></textarea>
            </div>

            <!-- Teléfono oficina -->
            <div>
                <label class="block text-sm font-semibold mb-1">Teléfono oficina</label>
                <input type="text" class="w-full border rounded-md px-3 py-2">
            </div>
        </div>
    </div>
</div>
