
import { useState } from 'react'

function EditorTramite({ tramiteSeleccionado = null, onGuardar, onCancelar }) {
  const [nombre, setNombre] = useState(tramiteSeleccionado?.nombre || '')
  const [descripcion, setDescripcion] = useState(tramiteSeleccionado?.descripcion || '')
  const [publicado, setPublicado] = useState(tramiteSeleccionado?.publicado ?? true)
  const [aceptaSolicitudes, setAceptaSolicitudes] = useState(tramiteSeleccionado?.aceptaSolicitudes ?? true)
  const [mostrarInicio, setMostrarInicio] = useState(tramiteSeleccionado?.mostrarInicio ?? false)

  const handleSubmit = (e) => {
    e.preventDefault()
    const nuevoTramite = {
      id: tramiteSeleccionado?.id || Date.now(),
      nombre,
      descripcion,
      publicado,
      aceptaSolicitudes,
      mostrarInicio,
      fecha: new Date().toISOString().split('T')[0],
    }
    onGuardar(nuevoTramite)
  }

  return (
    <div className="bg-white shadow p-6 rounded w-full max-w-2xl mx-auto">
      <h2 className="text-xl font-bold text-gray-700 mb-4">
        {tramiteSeleccionado ? 'Editar Trámite' : 'Nuevo Trámite'}
      </h2>

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block text-sm font-medium mb-1 text-gray-700">Nombre *</label>
          <input
            required
            value={nombre}
            onChange={(e) => setNombre(e.target.value)}
            className="w-full border px-3 py-2 rounded"
            placeholder="Ej: Habilitación Comercial"
          />
        </div>

        <div>
          <label className="block text-sm font-medium mb-1 text-gray-700">Descripción</label>
          <textarea
            rows={3}
            value={descripcion}
            onChange={(e) => setDescripcion(e.target.value)}
            className="w-full border px-3 py-2 rounded"
            placeholder="Descripción breve del trámite"
          />
        </div>

        <div className="flex items-center gap-6 mt-4">
          <label className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={publicado}
              onChange={() => setPublicado(!publicado)}
            />
            Trámite publicado
          </label>

          <label className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={aceptaSolicitudes}
              onChange={() => setAceptaSolicitudes(!aceptaSolicitudes)}
            />
            Acepta solicitudes
          </label>

          <label className="flex items-center gap-2">
            <input
              type="checkbox"
              checked={mostrarInicio}
              onChange={() => setMostrarInicio(!mostrarInicio)}
            />
            Mostrar en inicio
          </label>
        </div>
        <hr className="my-6" />
        <h3 className="text-lg font-semibold text-gray-700">📄 Ficha para el ciudadano</h3>

        <div className="grid md:grid-cols-2 gap-4 mt-4">
          <div>
            <label className="block text-sm font-medium mb-1 text-gray-700">Tutorial</label>
            <textarea className="w-full border px-3 py-2 rounded" placeholder="Explicación, link o texto..."></textarea>
          </div>

          <div>
            <label className="block text-sm font-medium mb-1 text-gray-700">Modalidad</label>
            <select className="w-full border px-3 py-2 rounded">
              <option value="">Seleccionar</option>
              <option value="presencial">Presencial</option>
              <option value="online">Online</option>
              <option value="mixto">Mixto</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium mb-1 text-gray-700">Implica costo</label>
            <select className="w-full border px-3 py-2 rounded">
              <option value="">Seleccionar</option>
              <option value="si">Sí</option>
              <option value="no">No</option>
            </select>
          </div>

          <div>
            <label className="block text-sm font-medium mb-1 text-gray-700">Teléfono oficina</label>
            <input className="w-full border px-3 py-2 rounded" placeholder="Ej: 4989924" />
          </div>

          <div>
            <label className="block text-sm font-medium mb-1 text-gray-700">Horario de atención</label>
            <input className="w-full border px-3 py-2 rounded" placeholder="Ej: Lu a Vi de 8 a 13 hs" />
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium mb-1 text-gray-700">Descripción del trámite</label>
            <textarea className="w-full border px-3 py-2 rounded" rows={3}></textarea>
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium mb-1 text-gray-700">Requisitos</label>
            <textarea className="w-full border px-3 py-2 rounded" rows={3}></textarea>
          </div>

          <div className="md:col-span-2">
            <label className="block text-sm font-medium mb-1 text-gray-700">Pasos para realizar el trámite</label>
            <textarea className="w-full border px-3 py-2 rounded" rows={3}></textarea>
          </div>
        </div>
        
        <div className="mt-6 flex gap-4 justify-end">
          <button
            type="button"
            onClick={onCancelar}
            className="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200"
          >
            Cancelar
          </button>
          <button
            type="submit"
            className="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700"
          >
            Guardar trámite
          </button>
        </div>
      </form>
    </div>
  )
}

export default EditorTramite
