<div class="bg-white shadow-md rounded p-6">
  <h1 class="text-xl font-semibold mb-4">Listado de trámites</h1>

  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-4">
    <select class="border border-gray-300 rounded px-3 py-2 text-sm">
      <option>Todos</option>
      <option>Publicados</option>
      <option>No publicados</option>
    </select>

    <label class="inline-flex items-center text-sm">
      <input type="checkbox" class="form-checkbox h-4 w-4 text-blue-600">
      <span class="ml-2">Ver solo los publicados en el inicio</span>
    </label>

    <input type="text" placeholder="Buscar" class="border border-gray-300 rounded px-3 py-2 text-sm w-full md:w-1/3">
  </div>

  <div class="overflow-x-auto">
    <table class="w-full text-sm text-left border border-gray-200">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2 border">Mostrar en página de inicio</th>
          <th class="p-2 border">Nombre</th>
          <th class="p-2 border">Descripción</th>
          <th class="p-2 border">Fecha de creación</th>
          <th class="p-2 border">Disponible en línea</th>
          <th class="p-2 border">Trámite publicado</th>
          <th class="p-2 border">Aceptar solicitudes</th>
          <th class="p-2 border">Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr class="hover:bg-gray-50">
          <td class="p-2 border text-center"><input type="checkbox"></td>
          <td class="p-2 border">Asistencia presencial para el trámite de Licencias de Conducir</td>
          <td class="p-2 border">Ayuda para cargar trámites de licencia de conducir en oficinas municipales.</td>
          <td class="p-2 border">21/07/2025<br>08:40:05 hrs</td>
          <td class="p-2 border"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Activo</span></td>
          <td class="p-2 border"><span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">Activo</span></td>
          <td class="p-2 border"><span class="bg-gray-200 text-gray-700 px-2 py-1 rounded-full text-xs">Inactivo</span></td>
          <td class="p-2 border flex gap-2">
            <button class="text-blue-600 hover:underline text-xs">Editar</button>
            <button class="text-red-600 hover:underline text-xs">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <div class="text-center mt-4">
    <button class="bg-white border border-gray-300 px-4 py-2 rounded text-sm hover:bg-gray-100">Mostrar más</button>
  </div>
</div>

