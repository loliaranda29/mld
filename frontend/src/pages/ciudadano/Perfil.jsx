import React from "react";
import Layout from "../../components/layout/Layout";

export default function PerfilCiudadano() {
  const usuario = JSON.parse(localStorage.getItem("usuario"));
  
  return (
    <Layout>
      <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">Mi Perfil</h2>
        <div className="bg-white rounded-lg shadow p-6">
          <p><strong>Nombre:</strong> {usuario?.nombre || "No disponible"}</p>
          <p><strong>DNI:</strong> {usuario?.dni || "12345678"}</p>
          <p><strong>Email:</strong> {usuario?.email || "ciudadano@ejemplo.com"}</p>
          {/* Podés agregar más datos simulados si querés */}
        </div>
      </div>
    </Layout>
  );
}
