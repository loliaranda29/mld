
import { useState } from 'react'
import { PlusCircle } from 'lucide-react'

function FormularioBuilder() {
  const [secciones, setSecciones] = useState([])

  const agregarSeccion = () => {
    const nuevaSeccion = {
      id: Date.now(),
      titulo: '',
      campos: []
    }
    setSecciones([...secciones, nuevaSeccion])
  }

  const agregarCampo = (seccionId, tipo) => {
    const actualizado = secciones.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: [
              ...s.campos,
              {
                id: Date.now(),
                tipo,
                etiqueta: '',
                obligatorio: false
              }
            ]
          }
        : s
    )
    setSecciones(actualizado)
  }

  const actualizarTituloSeccion = (id, valor) => {
    const actualizado = secciones.map((s) =>
      s.id === id ? { ...s, titulo: valor } : s
    )
    setSecciones(actualizado)
  }

  const actualizarCampo = (seccionId, campoId, campoActualizado) => {
    const actualizado = secciones.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.map((c) =>
              c.id === campoId ? { ...c, ...campoActualizado } : c
            )
          }
        : s
    )
    setSecciones(actualizado)
  }

  return (
    <div className="p-6 max-w-4xl mx-auto">
      <h2 className="text-2xl font-bold text-gray-700 mb-4">🧱 Constructor de Formulario</h2>

      {secciones.map((seccion, i) => (
        <div key={seccion.id} className="bg-white border rounded p-4 mb-6 shadow-sm">
          <input
            type="text"
            value={seccion.titulo}
            onChange={(e) => actualizarTituloSeccion(seccion.id, e.target.value)}
            placeholder="Título de la sección"
            className="w-full mb-4 border px-3 py-2 rounded"
          />

          {seccion.campos.map((campo) => (
            <div key={campo.id} className="mb-3 border p-3 rounded bg-gray-50">
              <label className="block text-sm text-gray-600 mb-1">Etiqueta del campo</label>
              <input
                value={campo.etiqueta}
                onChange={(e) =>
                  actualizarCampo(seccion.id, campo.id, { etiqueta: e.target.value })
                }
                className="w-full mb-2 border px-3 py-1 rounded"
                placeholder="Ej: Fecha de nacimiento"
              />
              <div className="flex items-center justify-between text-sm text-gray-600">
                <span>Tipo: {campo.tipo}</span>
                <label className="flex items-center gap-2">
                  <input
                    type="checkbox"
                    checked={campo.obligatorio}
                    onChange={(e) =>
                      actualizarCampo(seccion.id, campo.id, { obligatorio: e.target.checked })
                    }
                  />
                  Obligatorio
                </label>
              </div>
            </div>
          ))}

          <div className="flex gap-2 mt-2">
            <button
              onClick={() => agregarCampo(seccion.id, 'texto')}
              className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300"
            >
              + Campo de texto
            </button>
            <button
              onClick={() => agregarCampo(seccion.id, 'fecha')}
              className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300"
            >
              + Campo de fecha
            </button>
            <button
              onClick={() => agregarCampo(seccion.id, 'archivo')}
              className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300"
            >
              + Campo de archivo
            </button>
          </div>
        </div>
      ))}

      <button
        onClick={agregarSeccion}
        className="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded"
      >
        <PlusCircle size={18} />
        Agregar sección
      </button>
    </div>
  )
}

export default FormularioBuilder
