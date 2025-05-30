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
  api: apiConfig
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
  const [apiConfig, setApiConfig] = useState({
  url: '',
  method: 'POST',
  headers: {},
  bodyMapping: {}
})


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
              <div style={{ marginTop: '1rem' }}>
  <strong>Condiciones de visibilidad</strong>
  {campo.condiciones?.map((cond, i) => (
    <div key={i} style={{ display: 'flex', gap: '1rem', marginBottom: '0.5rem' }}>
      <select
        value={cond.siCampo}
        onChange={(e) => {
          const nuevas = [...campo.condiciones]
          nuevas[i].siCampo = e.target.value
          actualizarCampo(seccion.id, campo.id, 'condiciones', nuevas)
        }}
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
        placeholder="Valor esperado"
        value={cond.si}
        onChange={(e) => {
          const nuevas = [...campo.condiciones]
          nuevas[i].si = e.target.value
          actualizarCampo(seccion.id, campo.id, 'condiciones', nuevas)
        }}
      />

      <button
        type="button"
        onClick={() => {
          const nuevas = campo.condiciones.filter((_, j) => j !== i)
          actualizarCampo(seccion.id, campo.id, 'condiciones', nuevas)
        }}
      >
        ❌
      </button>
    </div>
  ))}

  <button
    type="button"
    onClick={() => {
      const nuevas = [...(campo.condiciones || []), { siCampo: '', si: '' }]
      actualizarCampo(seccion.id, campo.id, 'condiciones', nuevas)
    }}
  >
    + Agregar condición
  </button>
</div>

            </div>
          ))}
        </div>
      ))}

      <h3>Vista previa</h3>
      <VistaPreviaFormulario formulario={formulario} />
      <h3>Configuración de API externa</h3>

<div style={{ border: '1px solid #aaa', padding: '1rem', marginBottom: '2rem' }}>
  <label>
    URL del API:
    <input
      type="text"
      value={apiConfig.url}
      onChange={(e) => setApiConfig({ ...apiConfig, url: e.target.value })}
    />
  </label>

  <label>
    Método:
    <select
      value={apiConfig.method}
      onChange={(e) => setApiConfig({ ...apiConfig, method: e.target.value })}
    >
      <option value="POST">POST</option>
      <option value="GET">GET</option>
      <option value="PUT">PUT</option>
      <option value="DELETE">DELETE</option>
    </select>
  </label>

  <h4>Headers personalizados</h4>
  {Object.entries(apiConfig.headers).map(([key, value], i) => (
    <div key={i}>
      <input
        placeholder="Header"
        value={key}
        onChange={(e) => {
          const newHeaders = { ...apiConfig.headers }
          const oldKey = Object.keys(apiConfig.headers)[i]
          delete newHeaders[oldKey]
          newHeaders[e.target.value] = value
          setApiConfig({ ...apiConfig, headers: newHeaders })
        }}
      />
      <input
        placeholder="Valor"
        value={value}
        onChange={(e) => {
          const newHeaders = { ...apiConfig.headers }
          const key = Object.keys(apiConfig.headers)[i]
          newHeaders[key] = e.target.value
          setApiConfig({ ...apiConfig, headers: newHeaders })
        }}
      />
    </div>
  ))}

  <button
    type="button"
    onClick={() =>
      setApiConfig({
        ...apiConfig,
        headers: { ...apiConfig.headers, '': '' }
      })
    }
  >
    + Agregar header
  </button>

            <h4>Body mapping</h4>
            {Object.entries(apiConfig.bodyMapping).map(([key, value], i) => (
              <div key={i}>
                <input
                  placeholder="Campo en API"
                  value={key}
                  onChange={(e) => {
                    const newMap = { ...apiConfig.bodyMapping }
                    const oldKey = Object.keys(apiConfig.bodyMapping)[i]
                    delete newMap[oldKey]
                    newMap[e.target.value] = value
                    setApiConfig({ ...apiConfig, bodyMapping: newMap })
                  }}
                />
                <select
                  value={value}
                  onChange={(e) => {
                    const key = Object.keys(apiConfig.bodyMapping)[i]
                    setApiConfig({
                      ...apiConfig,
                      bodyMapping: { ...apiConfig.bodyMapping, [key]: e.target.value }
                    })
                  }}
                >
                  <option value="">Seleccionar campo</option>
                  {formulario.flatMap((sec) => sec.campos).map((campo) => (
                    <option key={campo.id} value={campo.id}>
                      {campo.etiqueta || campo.id}
                    </option>
                  ))}
                </select>
              </div>
            ))}

            <button
              type="button"
              onClick={() =>
                setApiConfig({
                  ...apiConfig,
                  bodyMapping: { ...apiConfig.bodyMapping, '': '' }
                })
              }
            >
              + Agregar mapeo
            </button>
          </div>


      <button onClick={guardarTramite} style={{ marginTop: '1rem' }}>
        Guardar Trámite
      </button>

      {mensaje && <p>{mensaje}</p>}
    </div>
  )
}

export default EditorTramite
