import React from "react";
import Layout from "../../components/layout/Layout";

export default function MisTurnos() {
  return (
    <Layout>
      <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">Mis Turnos</h2>
        <p className="text-gray-600">Tus turnos estarán disponibles aquí.</p>
        {/* TODO: agregar listado / API */}
      </div>
    </Layout>
  );
}
