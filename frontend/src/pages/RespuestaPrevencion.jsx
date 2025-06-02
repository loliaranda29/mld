
import { useState } from 'react'

function RespuestaPrevencion({ tramite }) {
  const [respuesta, setRespuesta] = useState('')
  const [archivo, setArchivo] = useState(null)
  const [enviado, setEnviado] = useState(false)

  const handleSubmit = (e) => {
    e.preventDefault()

    // Simular envío
    console.log('Respuesta enviada:', {
      tramiteId: tramite.id,
      respuesta,
      archivo
    })

    setEnviado(true)
  }

  if (!tramite || tramite.estado !== 'En prevención') {
    return (
      <div className="p-6">
        <h2 className="text-xl font-bold text-gray-700 mb-4">Trámite no disponible para respuesta</h2>
      </div>
    )
  }

  return (
    <div className="p-6 max-w-3xl mx-auto">
      <h2 className="text-2xl font-bold text-teal-700 mb-4">Responder prevención</h2>

      <div className="bg-white shadow rounded p-4 mb-6">
        <p className="text-sm text-gray-600 mb-1"><strong>Trámite:</strong> {tramite.nombre}</p>
        <p className="text-sm text-gray-600 mb-1"><strong>Estado:</strong> {tramite.estado}</p>
        <p className="text-sm text-gray-600 mb-4"><strong>Observaciones del operador:</strong> {tramite.observaciones || '---'}</p>
      </div>

      {enviado ? (
        <div className="bg-green-100 text-green-800 p-4 rounded text-sm">
          ✅ Tu respuesta fue enviada correctamente. El municipio revisará tu trámite en las próximas horas.
        </div>
      ) : (
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm text-gray-700 mb-1">Tu respuesta *</label>
            <textarea
              required
              value={respuesta}
              onChange={(e) => setRespuesta(e.target.value)}
              className="w-full border px-3 py-2 rounded"
              rows={4}
              placeholder="Explicá tu corrección o adjuntá nueva información..."
            />
          </div>

          <div>
            <label className="block text-sm text-gray-700 mb-1">Adjuntar documento (opcional)</label>
            <input
              type="file"
              onChange={(e) => setArchivo(e.target.files[0])}
              className="block w-full text-sm"
            />
          </div>

          <button
            type="submit"
            className="bg-teal-600 text-white px-6 py-2 rounded hover:bg-teal-700 transition"
          >
            Enviar respuesta
          </button>
        </form>
      )}
    </div>
  )
}

export default RespuestaPrevencion
