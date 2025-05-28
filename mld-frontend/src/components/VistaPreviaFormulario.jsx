import { useForm, Controller, useWatch } from 'react-hook-form'
import { z } from 'zod'
import { zodResolver } from '@hookform/resolvers/zod'
import { useMemo } from 'react'

// Función para generar el esquema Zod dinámico
const generarEsquemaZod = (campos) => {
  const schema = {}

  campos.forEach((campo) => {
    let validacion = z.string()
    if (campo.obligatorio) validacion = validacion.min(1, 'Este campo es obligatorio')

    if (campo.tipo === 'email') {
      validacion = z.string().email('Email no válido')
    }

    schema[campo.id] = validacion
  })

  return z.object(schema)
}

function VistaPreviaFormulario({ formulario }) {
  const campos = formulario.flatMap((seccion) => seccion.campos)
  const schema = useMemo(() => generarEsquemaZod(campos), [formulario])

  const {
    control,
    handleSubmit,
    formState: { errors },
    getValues
  } = useForm({
    resolver: zodResolver(schema),
  })

  const respuestas = useWatch({ control })

  // Función para saber si un campo debe mostrarse según condiciones
  const campoVisible = (campo) => {
    if (!campo.condiciones || campo.condiciones.length === 0) return true

    return campo.condiciones.every((cond) => {
      const valorActual = respuestas[cond.siCampo]
      return valorActual === cond.si
    })
  }

  const onSubmit = (data) => {
    console.log('Formulario válido enviado:', data)
    alert('Formulario enviado correctamente ✅')
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
    </form>
  )
}

export default VistaPreviaFormulario
