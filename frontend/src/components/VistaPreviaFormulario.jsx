import { useForm, Controller, useWatch } from 'react-hook-form'
import { z } from 'zod'
import { zodResolver } from '@hookform/resolvers/zod'
import { useMemo, useState } from 'react'

// Generador dinámico del esquema de validación
const generarEsquemaZod = (campos) => {
  const schema = {}

  campos.forEach((campo) => {
    let validacion = z.string()
    if (campo.obligatorio) validacion = validacion.min(1, 'Este campo es obligatorio')
    if (campo.tipo === 'email') validacion = z.string().email('Email no válido')
    schema[campo.id] = validacion
  })

  return z.object(schema)
}

function VistaPreviaFormulario({ formulario, idTramite }) {
  const campos = formulario.flatMap((seccion) => seccion.campos)
  const schema = useMemo(() => generarEsquemaZod(campos), [formulario])

  const {
    control,
    handleSubmit,
    formState: { errors },
  } = useForm({
    resolver: zodResolver(schema),
  })

  const respuestas = useWatch({ control })
  const [resultado, setResultado] = useState(null)
  const [error, setError] = useState(null)

  const campoVisible = (campo) => {
    if (!campo.condiciones || campo.condiciones.length === 0) return true

    return campo.condiciones.every((cond) => {
      const valor = respuestas[cond.siCampo]
      return valor === cond.si
    })
  }

  const onSubmit = async (data) => {
    setResultado(null)
    setError(null)

    try {
      const res = await fetch(`http://localhost:4000/api/tramites/${idTramite}/ejecutar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      })

      const json = await res.json()

      if (!res.ok) throw new Error(json.error || 'Error al ejecutar el trámite')

      setResultado(json.resultado)
    } catch (err) {
      setError(err.message)
    }
  }

  return (
    <form onSubmit={handleSubmit(onSubmit)} className="space-y-4">
      {campos.map((campo) => {
        if (!campoVisible(campo)) return null

        return (
          <div key={campo.id}>
            <label>{campo.etiqueta}</label>
            <Controller
              name={campo.id}
              control={control}
              defaultValue=""
              render={({ field }) => {
                switch (campo.tipo) {
                  case 'select':
                    return (
                      <select {...field}>
                        <option value="">Seleccionar</option>
                        {campo.opciones.map((op, i) => (
                          <option key={i} value={op}>{op}</option>
                        ))}
                      </select>
                    )
                  default:
                    return <input type={campo.tipo} {...field} />
                }
              }}
            />
            {errors[campo.id] && (
              <p style={{ color: 'red' }}>{errors[campo.id].message}</p>
            )}
          </div>
        )
      })}

      <button type="submit">Enviar</button>

      {resultado && (
        <div style={{ background: '#ddf', marginTop: '1rem', padding: '1rem' }}>
          <strong>Respuesta:</strong>
          <pre>{JSON.stringify(resultado, null, 2)}</pre>
        </div>
      )}

      {error && (
        <div style={{ background: '#fdd', marginTop: '1rem', padding: '1rem' }}>
          <strong>Error:</strong> {error}
        </div>
      )}
    </form>
  )
}

export default VistaPreviaFormulario
