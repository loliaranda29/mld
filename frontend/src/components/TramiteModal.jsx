import { useState, useEffect } from 'react'

function calcularDiasHabilesRestantes(fechaPrevencionStr) {
  const fechaPrevencion = new Date(fechaPrevencionStr)
  const hoy = new Date()
  const fechaLimite = new Date(fechaPrevencion)
  let dias = 0

  // Calcular fecha límite sumando 5 días hábiles
  while (dias < 5) {
    fechaLimite.setDate(fechaLimite.getDate() + 1)
    const dia = fechaLimite.getDay()
    if (dia !== 0 && dia !== 6) dias++
  }

  // Calcular días hábiles restantes
  let restantes = 0
  const cursor = new Date(hoy)
  cursor.setHours(0, 0, 0, 0)

  while (cursor < fechaLimite) {
    const dia = cursor.getDay()
    if (dia !== 0 && dia !== 6) restantes++
    cursor.setDate(cursor.getDate() + 1)
  }

  if (restantes <= 0) return 'vencido'
  return restantes
}


function TramiteModal({ tramite, onClose }) {
  const [accion, setAccion] = useState('')
  const [observaciones, setObservaciones] = useState('')

useEffect(() => {
  // Limpiar observaciones cuando cambie el trámite
  setObservaciones('')
}, [tramite])

  if (!tramite) return null

  const handleConfirmar = () => {
    if (accion === 'prevenir' && !observaciones.trim()) {
      alert('Debés agregar observaciones para prevenir al ciudadano.')
      return
    }

    console.log('Trámite procesado:', {
      id: tramite.id,
      accion,
      observaciones,
    })

    if (accion === 'prevenir') {
      alert('El ciudadano fue prevenido. Tiene 5 días hábiles para responder.')
    } else {
      alert(`Trámite ${accion === 'aprobado' ? 'aprobado' : 'rechazado'} con éxito`)
    }

    onClose()
  }

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div className="bg-white rounded-lg shadow-lg w-full max-w-lg p-6 relative">
        <h2 className="text-2xl font-bold text-teal-700 mb-4">{tramite.nombre}</h2>

        <p className="text-sm text-gray-600 mb-1"><strong>Estado actual:</strong> {tramite.estado}</p>
        <p className="text-sm text-gray-600 mb-1"><strong>CUIL:</strong> {tramite.cuil}</p>
        <p className="text-sm text-gray-600 mb-4"><strong>Fecha:</strong> {tramite.fecha}</p>
       {tramite.estado === 'En prevención' && tramite.fechaPrevencion && (
          <div className="my-4 p-4 bg-yellow-50 border border-yellow-300 rounded text-sm text-yellow-800">
            ⏳ <strong>Este trámite está en prevención.</strong><br />
            {(() => {
              const restante = calcularDiasHabilesRestantes(tramite.fechaPrevencion)
              return restante === 'vencido'
                ? <span className="text-red-600 font-semibold">⚠ El plazo de respuesta ha vencido.</span>
                : <>Tiempo restante: <strong>{restante} días hábiles</strong></>
            })()}
          </div>
        )}


        <div className="mb-4">
          <label className="block text-sm font-medium mb-1 text-gray-700">Observaciones</label>
          <textarea
            rows={3}
            value={observaciones}
            onChange={(e) => setObservaciones(e.target.value)}
            className="w-full border border-gray-300 rounded px-3 py-2 text-sm"
            placeholder="Motivo de prevención, rechazo o anotación general..."
          />
        </div>

        <div className="flex gap-4 mb-6 flex-wrap">
          <button
            onClick={() => setAccion('aprobado')}
            className={`px-4 py-2 rounded font-semibold ${
              accion === 'aprobado'
                ? 'bg-green-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-green-50'
            }`}
          >
            ✅ Aprobar
          </button>
          <button
            onClick={() => setAccion('rechazado')}
            className={`px-4 py-2 rounded font-semibold ${
              accion === 'rechazado'
                ? 'bg-red-600 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-red-50'
            }`}
          >
            ❌ Rechazar
          </button>
          <button
            onClick={() => setAccion('prevenir')}
            className={`px-4 py-2 rounded font-semibold ${
              accion === 'prevenir'
                ? 'bg-yellow-500 text-white'
                : 'bg-gray-100 text-gray-700 hover:bg-yellow-100'
            }`}
          >
            ⏳ Prevenir al ciudadano
          </button>
        </div>

        {accion && (
          <button
            onClick={handleConfirmar}
            className="w-full bg-teal-600 text-white py-2 rounded hover:bg-teal-700 transition"
          >
            Confirmar acción
          </button>
        )}

        <button
          onClick={onClose}
          className="absolute top-2 right-3 text-gray-400 hover:text-red-600 text-xl"
        >
          &times;
        </button>
      </div>
    </div>
  )
}

export default TramiteModal
