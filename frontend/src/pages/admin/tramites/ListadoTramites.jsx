
import { useState } from 'react'
import { PlusCircle } from 'lucide-react'
import EditorTramite from './EditorTramite'

const mockTramites = [
  { id: 1, nombre: 'Habilitación comercial', descripcion: 'Trámite habilitación', estado: 'Activo' },
  { id: 2, nombre: 'Subsidio de energía', descripcion: 'Subsidio', estado: 'Inactivo' },
  { id: 3, nombre: 'Licencia de obra', descripcion: 'Obra nueva', estado: 'Activo' },
]

function ListadoTramites() {
  const [tramites, setTramites] = useState(mockTramites)
  const [modoEdicion, setModoEdicion] = useState(false)
  const [tramiteActual, setTramiteActual] = useState(null)

  const handleGuardar = (nuevoTramite) => {
    const actualizados = tramiteActual
      ? tramites.map((t) => (t.id === nuevoTramite.id ? nuevoTramite : t))
      : [...tramites, nuevoTramite]

    setTramites(actualizados)
    setModoEdicion(false)
    setTramiteActual(null)
  }

  const handleNuevo = () => {
    setTramiteActual(null)
    setModoEdicion(true)
  }

  const handleEditar = (tramite) => {
    setTramiteActual(tramite)
    setModoEdicion(true)
  }

  const handleCancelar = () => {
    setModoEdicion(false)
    setTramiteActual(null)
  }

  return (
    <div className="p-6">
      {modoEdicion ? (
        <EditorTramite
          tramiteSeleccionado={tramiteActual}
          onGuardar={handleGuardar}
          onCancelar={handleCancelar}
        />
      ) : (
        <>
          <div className="flex items-center justify-between mb-4">
            <h2 className="text-2xl font-bold text-gray-700">🧾 Trámites configurados</h2>
            <button
              onClick={handleNuevo}
              className="flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded"
            >
              <PlusCircle size={18} />
              Nuevo trámite
            </button>
          </div>

          <div className="bg-white shadow rounded overflow-auto">
            <table className="min-w-full table-auto">
              <thead className="bg-gray-100 text-gray-700 text-sm">
                <tr>
                  <th className="px-4 py-2 text-left">#</th>
                  <th className="px-4 py-2 text-left">Nombre</th>
                  <th className="px-4 py-2 text-left">Estado</th>
                  <th className="px-4 py-2 text-left">Acciones</th>
                </tr>
              </thead>
              <tbody className="text-sm text-gray-800">
                {tramites.map((t) => (
                  <tr key={t.id} className="border-b">
                    <td className="px-4 py-2">{t.id}</td>
                    <td className="px-4 py-2">{t.nombre}</td>
                    <td className="px-4 py-2">
                      <span className={`px-2 py-1 rounded text-xs font-semibold ${
                        t.estado === 'Activo' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600'
                      }`}>
                        {t.estado}
                      </span>
                    </td>
                    <td className="px-4 py-2 space-x-2">
                      <button
                        className="text-sm text-blue-600 hover:underline"
                        onClick={() => handleEditar(t)}
                      >
                        Editar
                      </button>
                      <button className="text-sm text-red-600 hover:underline">Eliminar</button>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </>
      )}
    </div>
  )
}

export default ListadoTramites
