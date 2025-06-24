import React from "react";
import { FaFileAlt, FaComments, FaWallet, FaCalendarAlt, FaUser } from "react-icons/fa";
import Layout from "../../components/layout/Layout";
import Card from "../../components/ui/card";
import { Link } from "react-router-dom";

const accesos = [
  { titulo: "Mis Trámites", icono: <FaFileAlt size={32} />, link: "/mis-tramites" },
  { titulo: "Mensajes", icono: <FaComments size={32} />, link: "/mensajes" },
  { titulo: "Mi Wallet", icono: <FaWallet size={32} />, link: "/wallet" },
  { titulo: "Mis Turnos", icono: <FaCalendarAlt size={32} />, link: "/turnos" },
  { titulo: "Mi Perfil", icono: <FaUser size={32} />, link: "/perfil" },
];

const CiudadanoDashboard = () => {
    const usuario = JSON.parse(localStorage.getItem('usuario'));
    const nombre = usuario?.nombre || 'ciudadano';
  return (
    <Layout>
      <div className="p-6">
        <h1 className="text-2xl font-bold mb-4">¡Hola, {nombre}!</h1>
        <p className="text-gray-600 mb-8">Accedé a tus funcionalidades:</p>

        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
          {accesos.map((item, idx) => (
            <Link to={item.link} key={idx}>
              <Card className="flex flex-col items-center justify-center text-center py-6">
                <div className="mb-2 text-blue-600">{item.icono}</div>
                <span className="font-medium">{item.titulo}</span>
              </Card>
            </Link>
          ))}
        </div>
      </div>
    </Layout>
  );
};

export default CiudadanoDashboard;
