import { useState } from 'react'
import logo from '../assets/logo_mld.jpg'
import bgImage from '../assets/login_background.jpg'

function Login() {
  const [tipo, setTipo] = useState('correo')
  const [email, setEmail] = useState('')
  const [cuil, setCuil] = useState('')
  const [password, setPassword] = useState('')
  const [verPassword, setVerPassword] = useState(false)
  const [errores, setErrores] = useState({})

  const validar = () => {
    const erroresNuevos = {}
    if (tipo === 'correo' && (!email || !/\S+@\S+\.\S+/.test(email))) {
      erroresNuevos.email = 'Ingrese un correo válido.'
    }
    if (tipo === 'cuil' && (!cuil || !/^[0-9]{11}$/.test(cuil))) {
      erroresNuevos.cuil = 'Ingrese un CUIL válido (11 dígitos).'
    }
    if (!password) {
      erroresNuevos.password = 'Ingrese su contraseña.'
    }
    setErrores(erroresNuevos)
    return Object.keys(erroresNuevos).length === 0
  }

  const handleSubmit = (e) => {
    e.preventDefault()
    if (validar()) {
        if (
            (tipo === 'correo' && email === 'admin@lujan.gob.ar' && password === '123456') ||
            (tipo === 'cuil' && cuil === '20123456789' && password === '123456')
        ) {
            alert('Login exitoso ✅')
            window.location.href = '/dashboard'
        } else {
            alert('Credenciales incorrectas ❌')
        }
        }
  }

  return (
    <div className="min-h-screen flex flex-col lg:flex-row">
      {<div
  className="hidden lg:flex lg:w-1/2 bg-cover bg-center text-white items-center justify-center p-10"
  style={{ backgroundImage: `url(${bgImage})` }}
>
 
</div>
}

      <div className="w-full lg:w-1/2 bg-white flex flex-col justify-center items-center px-6 py-12">
        <img src={logo} alt="Logo Luján" className="w-40 mb-8" />

        <div className="w-full max-w-md space-y-6">
          {/* Selector CUIL / Correo */}
          <div className="flex space-x-6 justify-center">
            <label className="inline-flex items-center">
              <input type="radio" name="tipo" value="cuil" checked={tipo === 'cuil'} onChange={() => setTipo('cuil')} />
              <span className="ml-2">CUIL</span>
            </label>
            <label className="inline-flex items-center">
              <input type="radio" name="tipo" value="correo" checked={tipo === 'correo'} onChange={() => setTipo('correo')} />
              <span className="ml-2">Correo</span>
            </label>
          </div>

          <form className="space-y-4" onSubmit={handleSubmit}>
            {tipo === 'correo' && (
              <div>
                <label className="block mb-1 text-sm font-medium text-gray-700">Correo *</label>
                <input
                  type="email"
                  value={email}
                  onChange={(e) => setEmail(e.target.value)}
                  className={`w-full px-4 py-2 border rounded-md ${errores.email ? 'border-red-500' : 'border-gray-300'} focus:outline-none focus:ring-2 focus:ring-teal-600`}
                  placeholder="ejemplo@lujandecuyo.gob.ar"
                />
                {errores.email && <p className="text-sm text-red-600 mt-1">{errores.email}</p>}
              </div>
            )}

            {tipo === 'cuil' && (
              <div>
                <label className="block mb-1 text-sm font-medium text-gray-700">CUIL *</label>
                <input
                  type="text"
                  value={cuil}
                  onChange={(e) => setCuil(e.target.value)}
                  className={`w-full px-4 py-2 border rounded-md ${errores.cuil ? 'border-red-500' : 'border-gray-300'} focus:outline-none focus:ring-2 focus:ring-teal-600`}
                  placeholder="20123456789"
                />
                {errores.cuil && <p className="text-sm text-red-600 mt-1">{errores.cuil}</p>}
              </div>
            )}

            <div>
              <label className="block mb-1 text-sm font-medium text-gray-700">Contraseña *</label>
              <div className="relative">
                <input
                  type={verPassword ? 'text' : 'password'}
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  className={`w-full px-4 py-2 border rounded-md ${errores.password ? 'border-red-500' : 'border-gray-300'} focus:outline-none focus:ring-2 focus:ring-teal-600 pr-10`}
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  onClick={() => setVerPassword(!verPassword)}
                  className="absolute inset-y-0 right-2 text-sm text-gray-500"
                >
                  👁
                </button>
              </div>
              {errores.password && <p className="text-sm text-red-600 mt-1">{errores.password}</p>}
            </div>

            <div className="flex justify-between items-center">
              <label className="inline-flex items-center text-sm">
                <input type="checkbox" className="mr-2" />
                Recordar contraseña
              </label>
              <a href="#" className="text-sm text-teal-700 hover:underline">
                ¿Se te olvidó tu contraseña?
              </a>
            </div>

            <button
              type="submit"
              className="w-full py-2 bg-teal-700 text-white font-semibold rounded-md hover:bg-teal-800 transition"
            >
              Iniciar sesión
            </button>

            <div className="flex items-center justify-center my-4">
              <hr className="w-1/4 border-gray-300" />
              <span className="mx-4 text-gray-400">o</span>
              <hr className="w-1/4 border-gray-300" />
            </div>

            <button
              type="button"
              className="w-full py-2 border border-teal-600 text-teal-700 font-semibold rounded-md hover:bg-teal-50 transition"
            >
              Ingresar con wallet
            </button>
          </form>
        </div>
      </div>
    </div>
  )
}

export default Login
