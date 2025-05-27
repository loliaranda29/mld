
import { useState } from 'react'

function EditorTramite({ tramiteSeleccionado = null, onGuardar, onCancelar }) {
  const [nombre, setNombre] = useState(tramiteSeleccionado?.nombre || '')
  const [descripcion, setDescripcion] = useState(tramiteSeleccionado?.descripcion || '')
  const [publicado, setPublicado] = useState(tramiteSeleccionado?.publicado ?? true)
  const [aceptaSolicitudes, setAceptaSolicitudes] = useState(tramiteSeleccionado?.aceptaSolicitudes ?? true)
  const [mostrarInicio, setMostrarInicio] = useState(tramiteSeleccionado?.mostrarInicio ?? false)

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
    const actualizado = formulario.map((s) =>
      s.id === seccionId
        ? {
            ...s,
            campos: [...s.campos, {
              id: Date.now(),
              tipo,
              etiqueta: '',
              obligatorio: false,
              pista: ''
            }]
          }
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

  const actualizarTituloSeccion = (seccionId, nuevoTitulo) => {
    const actualizado = formulario.map((s) =>
      s.id === seccionId ? { ...s, titulo: nuevoTitulo } : s
    )
    setFormulario(actualizado)
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    const nuevoTramite = {
      id: tramiteSeleccionado?.id || Date.now(),
      nombre,
      descripcion,
      publicado,
      aceptaSolicitudes,
      mostrarInicio,
      formulario
    }
    console.log('Trámite guardado:', nuevoTramite)
    onGuardar(nuevoTramite)
  }

  return (
    <div className="bg-white shadow p-6 rounded w-full max-w-4xl mx-auto">
      <h2 className="text-xl font-bold text-gray-700 mb-4">
        {tramiteSeleccionado ? 'Editar Trámite' : 'Nuevo Trámite'}
      </h2>

      <form onSubmit={handleSubmit} className="space-y-6">
        <div>
          <label className="block text-sm mb-1 text-gray-700">Nombre *</label>
          <input
            required
            value={nombre}
            onChange={(e) => setNombre(e.target.value)}
            className="w-full border px-3 py-2 rounded"
          />
        </div>

        <div>
          <label className="block text-sm mb-1 text-gray-700">Descripción</label>
          <textarea
            value={descripcion}
            onChange={(e) => setDescripcion(e.target.value)}
            className="w-full border px-3 py-2 rounded"
            rows={2}
          />
        </div>

        <div className="flex gap-4 text-sm">
          <label className="flex items-center gap-2">
            <input type="checkbox" checked={publicado} onChange={() => setPublicado(!publicado)} />
            Publicado
          </label>
          <label className="flex items-center gap-2">
            <input type="checkbox" checked={aceptaSolicitudes} onChange={() => setAceptaSolicitudes(!aceptaSolicitudes)} />
            Acepta solicitudes
          </label>
          <label className="flex items-center gap-2">
            <input type="checkbox" checked={mostrarInicio} onChange={() => setMostrarInicio(!mostrarInicio)} />
            Mostrar en inicio
          </label>
        </div>

        <hr />
        <h3 className="text-lg font-semibold text-gray-700">🧾 Formulario del trámite</h3>

        {formulario.map((seccion) => (
          <div key={seccion.id} className="border rounded p-4 bg-gray-50 mb-4">
            <input
              className="w-full mb-3 border px-3 py-1 rounded"
              placeholder="Título de la sección"
              value={seccion.titulo}
              onChange={(e) => actualizarTituloSeccion(seccion.id, e.target.value)}
            />
            {seccion.campos.map((campo) => (
              <div key={campo.id} className="mb-3 p-3 bg-white border rounded">
                <label className="block text-sm mb-1 text-gray-600">Etiqueta</label>
                <input
                  value={campo.etiqueta}
                  onChange={(e) => actualizarCampo(seccion.id, campo.id, { etiqueta: e.target.value })}
                  className="w-full mb-2 border px-3 py-1 rounded"
                />
                <label className="block text-sm mb-1 text-gray-600">Pista o ayuda</label>
                <textarea
                  value={campo.pista}
                  onChange={(e) => actualizarCampo(seccion.id, campo.id, { pista: e.target.value })}
                  className="w-full mb-2 border px-3 py-1 rounded"
                  placeholder="Explicación, link, imagen o video"
                />
                <div className="flex justify-between text-sm text-gray-600">
                  <span>Tipo: {campo.tipo}</span>
                  <label className="flex items-center gap-2">
                    <input
                      type="checkbox"
                      checked={campo.obligatorio}
                      onChange={(e) => actualizarCampo(seccion.id, campo.id, { obligatorio: e.target.checked })}
                    />
                    Obligatorio
                  </label>
                </div>
              </div>
            ))}
            <div className="flex gap-2 mt-2">
              <button type="button" onClick={() => agregarCampo(seccion.id, 'texto')} className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">
                + Campo de texto
              </button>
              <button type="button" onClick={() => agregarCampo(seccion.id, 'fecha')} className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">
                + Campo de fecha
              </button>
              <button type="button" onClick={() => agregarCampo(seccion.id, 'archivo')} className="text-sm bg-gray-200 px-3 py-1 rounded hover:bg-gray-300">
                + Campo de archivo
              </button>
            </div>
          </div>
        ))}

        <button
          type="button"
          onClick={agregarSeccion}
          className="text-sm bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700"
        >
          + Agregar sección
        </button>

        <div className="mt-6 flex gap-4 justify-end">
          <button type="button" onClick={onCancelar} className="bg-gray-100 text-gray-700 px-4 py-2 rounded hover:bg-gray-200">
            Cancelar
          </button>
          <button type="submit" className="bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700">
            Guardar trámite
          </button>
        </div>
      </form>
    </div>
  )
}

export default EditorTramite
