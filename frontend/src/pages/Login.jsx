import { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import logo from './assets/logo_mld.jpg';
import bgImage from './assets/login_background.jpg';
import { Input } from "./components/ui/input";
import { Button } from "./components/ui/button";




function Login() {
  const [tipo, setTipo] = useState('correo');
  const [email, setEmail] = useState('');
  const [cuil, setCuil] = useState('');
  const [password, setPassword] = useState('');
  const [verPassword, setVerPassword] = useState(false);
  const [errores, setErrores] = useState({});
  const navigate = useNavigate();

  const validar = () => {
    const erroresNuevos = {};
    if (tipo === 'correo' && (!email || !/\S+@\S+\.\S+/.test(email))) {
      erroresNuevos.email = 'Ingrese un correo válido.';
    }
    if (tipo === 'cuil' && (!cuil || !/^[0-9]{11}$/.test(cuil))) {
      erroresNuevos.cuil = 'Ingrese un CUIL válido (11 dígitos).';
    }
    if (!password) {
      erroresNuevos.password = 'Ingrese su contraseña.';
    }
    setErrores(erroresNuevos);
    return Object.keys(erroresNuevos).length === 0;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    if (!validar()) return;

    const credenciales = tipo === 'correo' ? { email, password } : { cuil, password };

    try {
      const res = await fetch('http://localhost:3000/api/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(credenciales),
      });

      if (!res.ok) throw new Error('Login fallido');
      const data = await res.json();

      // Guardar token o sesión según lo necesario
      localStorage.setItem('token', data.token);
      navigate('/dashboard');
    } catch (err) {
      alert('Credenciales incorrectas ❌');
    }
  };

  return (
    <div className="min-h-screen flex flex-col lg:flex-row">
      <div className="hidden lg:flex lg:w-1/2 bg-cover" style={{ backgroundImage: `url(${bgImage})` }} />
      <div className="flex flex-col justify-center items-center w-full lg:w-1/2 p-8">
        <img src={logo} alt="Logo" className="w-32 mb-6" />
        <form onSubmit={handleSubmit} className="w-full max-w-sm space-y-4">
          <div className="flex space-x-4 mb-2">
            <Button type="button" variant={tipo === 'correo' ? 'default' : 'outline'} onClick={() => setTipo('correo')}>
              Email
            </Button>
            <Button type="button" variant={tipo === 'cuil' ? 'default' : 'outline'} onClick={() => setTipo('cuil')}>
              CUIL
            </Button>
          </div>

          {tipo === 'correo' ? (
            <Input placeholder="Correo electrónico" value={email} onChange={(e) => setEmail(e.target.value)} />
          ) : (
            <Input placeholder="CUIL" value={cuil} onChange={(e) => setCuil(e.target.value)} />
          )}

          <Input
            type={verPassword ? 'text' : 'password'}
            placeholder="Contraseña"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
          />

          <Button type="submit" className="w-full">Iniciar sesión</Button>
        </form>
      </div>
    </div>
  );
}

export default Login;