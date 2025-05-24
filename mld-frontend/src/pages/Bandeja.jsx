import { useState } from 'react'
import { useEffect } from 'react'
import TramiteModal from '../components/TramiteModal'


const pestañas = ['Todos', 'Abiertos', 'Cerrados']
const estados = [
  'En proceso',
  'Pendiente de pago',
  'Verificando pago',
  'Aprobado',
  'Rechazado',
]

// 🚧 Datos simulados
const mockTramites = [
  {
    id: 1,
    nombre: 'Habilitación comercial',
    estado: 'En proceso',
    cuil: '20123456789',
    fecha: '2024-05-01',
  },
  {
    id: 2,
    nombre: 'Subsidio de energía',
    estado: 'Aprobado',
    cuil: '20234567890',
    fecha: '2024-05-05',
  },
  {
    id: 3,
    nombre: 'Licencia de obra',
    estado: 'Rechazado',
    cuil: '20345678901',
    fecha: '2024-05-10',
  },
  {
    id: 4,
    nombre: 'Renovación de habilitación',
    estado: 'Pendiente de pago',
    cuil: '20123456789',
    fecha: '2024-05-08',
  },
]

function Bandeja() {
  const [tabActiva, setTabActiva] = useState('Todos')
  const [busqueda, setBusqueda] = useState('')
  const [estadoSeleccionado, setEstadoSeleccionado] = useState('')
  const [cuil, setCuil] = useState('')
  const [fechaInicio, setFechaInicio] = useState('')
  const [fechaFin, setFechaFin] = useState('')

  const tramitesFiltrados = mockTramites.filter((t) => {
    const coincideTab =
      tabActiva === 'Todos' ||
      (tabActiva === 'Abiertos' &&
        ['En proceso', 'Pendiente de pago', 'Verificando pago'].includes(t.estado)) ||
      (tabActiva === 'Cerrados' && ['Aprobado', 'Rechazado'].includes(t.estado))

    const coincideBusqueda = t.nombre.toLowerCase().includes(busqueda.toLowerCase())
    const coincideEstado = estadoSeleccionado ? t.estado === estadoSeleccionado : true
    const coincideCUIL = cuil ? t.cuil.includes(cuil) : true
    const coincideFecha =
      (!fechaInicio || t.fecha >= fechaInicio) && (!fechaFin || t.fecha <= fechaFin)

    return coincideTab && coincideBusqueda && coincideEstado && coincideCUIL && coincideFecha
  })

  return (
    <div>
      <h2 className="text-xl font-semibold text-gray-700 mb-4">📥 Bandeja de Entrada</h2>

      {/* Pestañas */}
      <div className="flex space-x-4 mb-6 border-b">
        {pestañas.map((pestana) => (
          <button
            key={pestana}
            onClick={() => setTabActiva(pestana)}
            className={`pb-2 px-4 font-medium ${
              tabActiva === pestana
                ? 'border-b-2 border-teal-600 text-teal-700'
                : 'text-gray-500 hover:text-teal-600'
            }`}
          >
            {pestana}
          </button>
        ))}
      </div>

      {/* Filtros */}
      <div className="bg-white p-4 rounded shadow mb-6 flex flex-wrap gap-4 items-end">
        <div>
          <label className="block text-sm text-gray-600 mb-1">Buscar trámite</label>
          <input
            type="text"
            value={busqueda}
            onChange={(e) => setBusqueda(e.target.value)}
            className="border px-3 py-1 rounded w-52"
            placeholder="Ej: habilitación comercial"
          />
        </div>

        <div>
          <label className="block text-sm text-gray-600 mb-1">Estado</label>
          <select
            value={estadoSeleccionado}
            onChange={(e) => setEstadoSeleccionado(e.target.value)}
            className="border px-3 py-1 rounded w-40"
          >
            <option value="">Todos</option>
            {estados.map((est) => (
              <option key={est} value={est}>
                {est}
              </option>
            ))}
          </select>
        </div>

        <div>
          <label className="block text-sm text-gray-600 mb-1">CUIL / Correo</label>
          <input
            type="text"
            value={cuil}
            onChange={(e) => setCuil(e.target.value)}
            className="border px-3 py-1 rounded w-52"
            placeholder="Ej: 20123456789"
          />
        </div>

        <div>
          <label className="block text-sm text-gray-600 mb-1">Desde</label>
          <input
            type="date"
            value={fechaInicio}
            onChange={(e) => setFechaInicio(e.target.value)}
            className="border px-3 py-1 rounded"
          />
        </div>

        <div>
          <label className="block text-sm text-gray-600 mb-1">Hasta</label>
          <input
            type="date"
            value={fechaFin}
            onChange={(e) => setFechaFin(e.target.value)}
            className="border px-3 py-1 rounded"
          />
        </div>
      </div>

      {/* Tabla de trámites */}
      <div className="bg-white rounded shadow overflow-auto">
        <table className="min-w-full table-auto">
          <thead className="bg-gray-100 text-gray-700 text-sm">
            <tr>
              <th className="px-4 py-2 text-left">#</th>
              <th className="px-4 py-2 text-left">Trámite</th>
              <th className="px-4 py-2 text-left">Estado</th>
              <th className="px-4 py-2 text-left">CUIL</th>
              <th className="px-4 py-2 text-left">Fecha</th>
            </tr>
          </thead>
          <tbody className="text-sm text-gray-800">
            {tramitesFiltrados.map((t) => (
              <tr key={t.id} className="border-b">
                <td className="px-4 py-2">{t.id}</td>
                <td className="px-4 py-2">{t.nombre}</td>
                <td className="px-4 py-2">{t.estado}</td>
                <td className="px-4 py-2">{t.cuil}</td>
                <td className="px-4 py-2">{t.fecha}</td>
              </tr>
            ))}
            {tramitesFiltrados.length === 0 && (
              <tr>
                <td colSpan={5} className="text-center py-6 text-gray-400">
                  No hay trámites que coincidan con la búsqueda.
                </td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </div>
  )
}

export default Bandeja
