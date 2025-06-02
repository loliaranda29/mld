import React from 'react';
import Layout from './Layout';
import { Button } from './src/components/ui/button';
import { useNavigate } from 'react-router-dom';

const NotFound = () => {
  const navigate = useNavigate();

  return (
    <Layout>
      <div className="text-center py-20">
        <h1 className="text-4xl font-bold mb-4">404 - Página no encontrada</h1>
        <p className="text-gray-600 mb-6">La página que buscás no existe o fue movida.</p>
        <Button onClick={() => navigate('/')}>Volver al inicio</Button>
      </div>
    </Layout>
  );
};

export default NotFound;
