import React from 'react';
import Layout from './Layout';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';

const servicios = [
  { titulo: 'Pagá tus Tasas', descripcion: 'Accedé a tus tributos municipales y realizá pagos online.' },
  { titulo: 'Licencia de Conducir', descripcion: 'Solicitá turnos y renová tu licencia de manera digital.' },
  { titulo: 'Turnos Médicos', descripcion: 'Reservá turnos para atención médica en centros municipales.' },
];

const faqs = [
  { pregunta: '¿Cómo inicio un trámite?', respuesta: 'Ingresá con tu usuario y seleccioná el trámite desde el buscador o el listado disponible.' },
  { pregunta: '¿Dónde consulto el estado de mi trámite?', respuesta: 'Desde tu bandeja de usuario podés ver el estado actualizado de todos tus trámites.' },
  { pregunta: '¿Qué necesito para validar mi identidad?', respuesta: 'Podés hacerlo con RENAPER, Mi Argentina o ARCA desde el inicio de sesión.' },
];

const Home = () => {
  return (
    <Layout>
      <div className="mb-6">
        <h1 className="text-2xl font-bold mb-2">Bienvenido a Mi Luján Digital</h1>
        <Input placeholder="Buscar un trámite..." className="w-full max-w-lg" />
      </div>
      <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 mb-8">
        {servicios.map((servicio, index) => (
          <Card key={index} className="p-4 shadow hover:shadow-md cursor-pointer">
            <h2 className="text-lg font-semibold mb-1">{servicio.titulo}</h2>
            <p className="text-sm text-gray-600">{servicio.descripcion}</p>
          </Card>
        ))}
      </div>
      <section>
        <h2 className="text-xl font-bold mb-4">Preguntas Frecuentes y Tutoriales</h2>
        <div className="space-y-4">
          {faqs.map((faq, index) => (
            <div key={index} className="border-l-4 border-blue-500 pl-4">
              <h3 className="font-semibold text-blue-700">{faq.pregunta}</h3>
              <p className="text-sm text-gray-700">{faq.respuesta}</p>
            </div>
          ))}
        </div>
      </section>
    </Layout>
  );
};

export default Home;

