import React from 'react';
import Layout from '../components/layout/Layout';
import { Input } from '../components/ui/input';
import { Button } from '../components/ui/button';
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const navigate = useNavigate();

  const handleSubmit = (e) => {
    e.preventDefault();

    // 🔐 Aquí deberíamos hacer el login real
    const usuario = {
      rol: 'ciudadano',
      nombre: 'María González' // 🧑‍💼 simulación para mostrar en el dashboard
    };
    localStorage.setItem('usuario', JSON.stringify(usuario));
    navigate('/inicio');
  };

  return (
    <Layout>
      <div className="max-w-md mx-auto mt-20 bg-white p-8 rounded-2xl shadow">
        <h2 className="text-2xl font-bold mb-6 text-center">Iniciar Sesión</h2>
        <form className="space-y-4" onSubmit={handleSubmit}>
          <Input type="text" placeholder="DNI o correo electrónico" />
          <Input type="password" placeholder="Contraseña" />
          <Button type="submit" className="w-full">Ingresar</Button>
        </form>
      </div>
    </Layout>
  );
};

export default Login;
