import { useState } from 'react'
import VistaPreviaFormulario from '../../../components/VistaPreviaFormulario'

function EditorTramite({ tramiteSeleccionado = null, onGuardar, onCancelar }) {
  const [nombre, setNombre] = useState(tramiteSeleccionado?.nombre || '')
  const [formulario, setFormulario] = useState(tramiteSeleccionado?.formulario || [])
  const [mensaje, setMensaje] = useState(null)

  const guardarTramite = async () => {
    const tramite = {
      nombre,
      formulario,
      api: {
        url: '',
        method: 'POST',
        headers: {},
        bodyMapping: {}
      }
    }

    try {
      const res = await fetch('http://localhost:4000/api/tramites', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(tramite)
      })

      if (!res.ok) throw new Error('Error al guardar el trámite')

      const data = await res.json()
      setMensaje('Trámite guardado con éxito ✅')
      if (onGuardar) onGuardar(data)
    } catch (err) {
      console.error(err)
      setMensaje('❌ Error al guardar el trámite')
    }
  }

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
      id: `campo_${Date.now()}`,
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

  const actualizarCampo = (seccionId, campoId, propiedad, valor) => {
    const actualizado = formulario.map((seccion) =>
      seccion.id === seccionId
        ? {
            ...seccion,
            campos: seccion.campos.map((campo) =>
              campo.id === campoId ? { ...campo, [propiedad]: valor } : campo
            )
          }
        : seccion
    )
    setFormulario(actualizado)
  }

  return (
    <div>
      <h2>Editor de Trámite</h2>
      <input
        type="text"
        value={nombre}
        onChange={(e) => setNombre(e.target.value)}
        placeholder="Nombre del trámite"
      />
      <button onClick={agregarSeccion}>Agregar Sección</button>

      {formulario.map((seccion) => (
        <div key={seccion.id} style={{ border: '1px solid #ccc', padding: '1rem', margin: '1rem 0' }}>
          <input
            type="text"
            value={seccion.titulo}
            onChange={(e) => {
              const actualizado = formulario.map((s) =>
                s.id === seccion.id ? { ...s, titulo: e.target.value } : s
              )
              setFormulario(actualizado)
            }}
            placeholder="Título de la sección"
          />

          <button onClick={() => agregarCampo(seccion.id, 'text')}>+ Texto</button>
          <button onClick={() => agregarCampo(seccion.id, 'email')}>+ Email</button>
          <button onClick={() => agregarCampo(seccion.id, 'select')}>+ Select</button>

          {seccion.campos.map((campo) => (
            <div key={campo.id} style={{ padding: '0.5rem', borderBottom: '1px solid #ccc' }}>
              <input
                type="text"
                value={campo.etiqueta}
                onChange={(e) =>
                  actualizarCampo(seccion.id, campo.id, 'etiqueta', e.target.value)
                }
                placeholder="Etiqueta del campo"
              />
              <label>
                Obligatorio
                <input
                  type="checkbox"
                  checked={campo.obligatorio}
                  onChange={(e) =>
                    actualizarCampo(seccion.id, campo.id, 'obligatorio', e.target.checked)
                  }
                />
              </label>

              {campo.tipo === 'select' && (
                <input
                  type="text"
                  placeholder="Opciones separadas por coma"
                  onBlur={(e) =>
                    actualizarCampo(
                      seccion.id,
                      campo.id,
                      'opciones',
                      e.target.value.split(',').map((o) => o.trim())
                    )
                  }
                />
              )}

              {/* Condiciones de visibilidad */}
              <div>
                <strong>Condiciones de visibilidad</strong>
                <label>Mostrar este campo si el campo anterior tiene el valor:</label>

                <select
                  onChange={(e) =>
                    actualizarCampo(seccion.id, campo.id, 'condiciones', [
                      {
                        siCampo: e.target.value,
                        si: campo.condiciones?.[0]?.si || ''
                      }
                    ])
                  }
                  value={campo.condiciones?.[0]?.siCampo || ''}
                >
                  <option value="">Seleccionar campo</option>
                  {formulario
                    .flatMap((sec) => sec.campos)
                    .filter((c) => c.id !== campo.id)
                    .map((c) => (
                      <option key={c.id} value={c.id}>
                        {c.etiqueta || c.id}
                      </option>
                    ))}
                </select>

                <input
                  type="text"
                  placeholder="Valor que debe tener"
                  value={campo.condiciones?.[0]?.si || ''}
                  onChange={(e) => {
                    const siCampo = campo.condiciones?.[0]?.siCampo || ''
                    actualizarCampo(seccion.id, campo.id, 'condiciones', [
                      { siCampo, si: e.target.value }
                    ])
                  }}
                />
              </div>
            </div>
          ))}
        </div>
      ))}

      <h3>Vista previa</h3>
      <VistaPreviaFormulario formulario={formulario} />

      <button onClick={guardarTramite} style={{ marginTop: '1rem' }}>
        Guardar Trámite
      </button>

      {mensaje && <p>{mensaje}</p>}
    </div>
  )
}

export default EditorTramite
