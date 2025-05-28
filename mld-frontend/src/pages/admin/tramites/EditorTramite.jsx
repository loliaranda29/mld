
import { useState } from 'react'
import VistaPreviaFormulario from '../../../components/VistaPreviaFormulario'

function EditorTramite({ tramiteSeleccionado = null, onGuardar, onCancelar }) {
  const [nombre, setNombre] = useState(tramiteSeleccionado?.nombre || '')
  const [formulario, setFormulario] = useState([])

  const agregarSeccion = () => {
    const nueva = {
      id: Date.now(),
      titulo: '',
      campos: []
    }
    setFormulario([...formulario, nueva])
  }

  const agregarCampo = (seccionId, tipo) => {
    const nuevoCampo = {
      id: Date.now(),
      tipo,
      etiqueta: '',
      obligatorio: false,
      pista: '',
      opciones: tipo === 'select' ? ['Sí', 'No'] : [],
      condiciones: []
    }
    const actualizado = formulario.map((s) =>
      s.id === seccionId
        ? { ...s, campos: [...s.campos, nuevoCampo] }
        : s
    )
    setFormulario(actualizado)
  }

  const actualizarCampo = (seccionId, campoId, cambios) => {
    const actualizado = formulario.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.map((c) =>
              c.id === campoId ? { ...c, ...cambios } : c
            )
          }
        : s
    )
    setFormulario(actualizado)
  }

  const actualizarOpcion = (seccionId, campoId, index, value) => {
    const actualizado = formulario.map((s) => {
      if (s.id !== seccionId) return s
      return {
        ...s,
        campos: s.campos.map((c) => {
          if (c.id !== campoId) return c
          const nuevasOpciones = [...c.opciones]
          nuevasOpciones[index] = value
          return { ...c, opciones: nuevasOpciones }
        })
      }
    })
    setFormulario(actualizado)
  }

  const agregarOpcion = (seccionId, campoId) => {
    actualizarCampo(seccionId, campoId, {
      opciones: [...formulario.find(s => s.id === seccionId).campos.find(c => c.id === campoId).opciones, '']
    })
  }

  const agregarCondicion = (seccionId, campoId) => {
    const nuevaCond = {
      campoOrigen: '',
      operador: '==',
      valor: '',
      accion: 'mostrar',
      campoObjetivo: ''
    }
    const actualizado = formulario.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.map((c) =>
              c.id === campoId
                ? { ...c, condiciones: [...(c.condiciones || []), nuevaCond] }
                : c
            )
          }
        : s
    )
    setFormulario(actualizado)
  }

  const actualizarCondicion = (seccionId, campoId, index, cambios) => {
    const actualizado = formulario.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: s.campos.map((c) =>
              c.id === campoId
                ? {
                    ...c,
                    condiciones: c.condiciones.map((cond, i) =>
                      i === index ? { ...cond, ...cambios } : cond
                    )
                  }
                : c
            )
          }
        : s
    )
    setFormulario(actualizado)
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    const tramite = { id: Date.now(), nombre, formulario }
    console.log('Guardado:', tramite)
    onGuardar(tramite)
  }

  return (
    <div className="p-6 max-w-5xl mx-auto">
      <h2 className="text-2xl font-bold mb-4">⚙️ Editor de Trámite con Select + Condiciones</h2>

      <form onSubmit={handleSubmit} className="space-y-6">
        <input
          type="text"
          value={nombre}
          onChange={(e) => setNombre(e.target.value)}
          placeholder="Nombre del trámite"
          className="w-full border px-3 py-2 rounded"
        />

        {formulario.map((seccion) => (
          <div key={seccion.id} className="bg-gray-50 p-4 rounded border mb-6">
            <input
              value={seccion.titulo}
              onChange={(e) => actualizarCampo(seccion.id, null, { titulo: e.target.value })}
              className="w-full mb-3 border px-3 py-1 rounded"
              placeholder="Título de sección"
            />
            {seccion.campos.map((campo) => (
              <div key={campo.id} className="bg-white p-3 mb-3 rounded border">
                <input
                  value={campo.etiqueta}
                  onChange={(e) => actualizarCampo(seccion.id, campo.id, { etiqueta: e.target.value })}
                  className="w-full mb-2 border px-3 py-1 rounded"
                  placeholder="Etiqueta del campo"
                />
                <textarea
                  value={campo.pista}
                  onChange={(e) => actualizarCampo(seccion.id, campo.id, { pista: e.target.value })}
                  className="w-full mb-2 border px-3 py-1 rounded"
                  placeholder="Pista o ayuda"
                />

                {campo.tipo === 'select' && (
                  <div className="mb-2 space-y-1">
                    <label className="text-sm text-gray-600">Opciones:</label>
                    {campo.opciones.map((op, index) => (
                      <input
                        key={index}
                        value={op}
                        onChange={(e) =>
                          actualizarOpcion(seccion.id, campo.id, index, e.target.value)
                        }
                        className="w-full border px-2 py-1 rounded mb-1"
                        placeholder={`Opción ${index + 1}`}
                      />
                    ))}
                    <button
                      type="button"
                      onClick={() => agregarOpcion(seccion.id, campo.id)}
                      className="text-xs text-blue-600 hover:underline"
                    >
                      + Agregar opción
                    </button>
                  </div>
                )}

                <div className="text-sm text-gray-700 mb-2">Condiciones:</div>
                {(campo.condiciones || []).map((cond, index) => (
                  <div key={index} className="flex flex-wrap gap-2 items-center mb-2 text-sm">
                    <input
                      placeholder="Campo origen"
                      value={cond.campoOrigen}
                      onChange={(e) =>
                        actualizarCondicion(seccion.id, campo.id, index, { campoOrigen: e.target.value })
                      }
                      className="border px-2 py-1 rounded"
                    />
                    <select
                      value={cond.operador}
                      onChange={(e) =>
                        actualizarCondicion(seccion.id, campo.id, index, { operador: e.target.value })
                      }
                      className="border px-2 py-1 rounded"
                    >
                      <option value="==">==</option>
                      <option value="!=">!=</option>
                    </select>
                    <input
                      placeholder="Valor esperado"
                      value={cond.valor}
                      onChange={(e) =>
                        actualizarCondicion(seccion.id, campo.id, index, { valor: e.target.value })
                      }
                      className="border px-2 py-1 rounded"
                    />
                    <select
                      value={cond.accion}
                      onChange={(e) =>
                        actualizarCondicion(seccion.id, campo.id, index, { accion: e.target.value })
                      }
                      className="border px-2 py-1 rounded"
                    >
                      <option value="mostrar">mostrar</option>
                      <option value="ocultar">ocultar</option>
                    </select>
                    <input
                      placeholder="Campo objetivo"
                      value={cond.campoObjetivo}
                      onChange={(e) =>
                        actualizarCondicion(seccion.id, campo.id, index, { campoObjetivo: e.target.value })
                      }
                      className="border px-2 py-1 rounded"
                    />
                  </div>
                ))}

                <button
                  type="button"
                  onClick={() => agregarCondicion(seccion.id, campo.id)}
                  className="text-xs text-blue-600 hover:underline mt-1"
                >
                  + Agregar condición
                </button>
              </div>
            ))}
            <div className="flex gap-2 mt-2">
              <button type="button" onClick={() => agregarCampo(seccion.id, 'texto')} className="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                + Campo de texto
              </button>
              <button type="button" onClick={() => agregarCampo(seccion.id, 'fecha')} className="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                + Campo de fecha
              </button>
              <button type="button" onClick={() => agregarCampo(seccion.id, 'select')} className="bg-gray-200 px-3 py-1 rounded hover:bg-gray-300 text-sm">
                + Campo select
              </button>
            </div>
          </div>
        ))}

        <button
          type="button"
          onClick={agregarSeccion}
          className="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700"
        >
          + Agregar sección
        </button>

        <div className="flex justify-end mt-6">
          <button
            type="submit"
            className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
          >
            Guardar trámite
          </button>
        </div>
      </form>

      <hr className="my-10" />
      <VistaPreviaFormulario formulario={formulario} />
    </div>
  )
}

export default EditorTramite
