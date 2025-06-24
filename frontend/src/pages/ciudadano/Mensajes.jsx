import React from "react";
import Layout from "../../components/layout/Layout";

export default function MisMensajes() {
  return (
    <Layout>
      <div className="p-6">
        <h2 className="text-2xl font-bold mb-4">Mensajes</h2>
        <p className="text-gray-600">Aquí podrás ver los mensajes enviados por la municipalidad.</p>
        {/* TODO: reemplazar con listado real */}
      </div>
    </Layout>
  );
}
