
import { useState } from 'react'

function VistaPreviaFormulario({ formulario }) {
  const [respuestas, setRespuestas] = useState({})

  const handleChange = (campoId, valor) => {
    setRespuestas({ ...respuestas, [campoId]: valor })
  }

  const cumpleCondicion = (cond) => {
    const valorReal = respuestas[cond.campoOrigen]
    switch (cond.operador) {
      case '==':
        return valorReal === cond.valor
      case '!=':
        return valorReal !== cond.valor
      default:
        return false
    }
  }

  const seDebeMostrar = (campo) => {
    if (!campo.condiciones || campo.condiciones.length === 0) return true

    // Si hay al menos una condición que indica "mostrar" y se cumple
    for (let cond of campo.condiciones) {
      if (cond.accion === 'mostrar' && cumpleCondicion(cond)) return true
      if (cond.accion === 'ocultar' && cumpleCondicion(cond)) return false
    }
    return true
  }

  return (
    <div className="bg-white p-6 rounded shadow max-w-3xl mx-auto">
      <h2 className="text-xl font-bold text-gray-700 mb-4">👁️ Vista Previa del Formulario</h2>
      {formulario.map((seccion) => (
        <div key={seccion.id} className="mb-6">
          <h3 className="text-lg font-semibold text-gray-800 mb-2">{seccion.titulo}</h3>
          {seccion.campos.map((campo) => (
            seDebeMostrar(campo) && (
              <div key={campo.id} className="mb-4">
                <label className="block text-sm text-gray-600 mb-1">{campo.etiqueta}</label>
                {campo.tipo === 'texto' && (
                  <input
                    type="text"
                    value={respuestas[campo.id] || ''}
                    onChange={(e) => handleChange(campo.id, e.target.value)}
                    className="w-full border px-3 py-2 rounded"
                  />
                )}
                {campo.tipo === 'fecha' && (
                  <input
                    type="date"
                    value={respuestas[campo.id] || ''}
                    onChange={(e) => handleChange(campo.id, e.target.value)}
                    className="w-full border px-3 py-2 rounded"
                  />
                )}
              </div>
            )
          ))}
        </div>
      ))}
    </div>
  )
}

export default VistaPreviaFormulario
