import React from 'react';
import Layout from '../components/layout/Layout';
import { Input } from '../components/ui/input';
import { Button } from '../components/ui/button';

const Login = () => {
  return (
    <Layout>
      <div className="max-w-md mx-auto mt-20 bg-white p-8 rounded-2xl shadow">
        <h2 className="text-2xl font-bold mb-6 text-center">Iniciar Sesión</h2>
        <form className="space-y-4">
          <Input type="text" placeholder="DNI o correo electrónico" />
          <Input type="password" placeholder="Contraseña" />
          <Button type="submit" className="w-full">Ingresar</Button>
        </form>
      </div>
    </Layout>
  );
};

export default Login;
