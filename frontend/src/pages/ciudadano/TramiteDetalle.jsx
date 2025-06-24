import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Layout from "../../components/layout/Layout";

export default function TramiteDetalle() {
  const { id } = useParams();
  const [tramite, setTramite] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    fetch(`http://localhost:3000/api/tramites/${id}`)
      .then((res) => res.json())
      .then((data) => setTramite(data))
      .catch(console.error)
      .finally(() => setLoading(false));
  }, [id]);

  if (loading) {
    return (
      <Layout>
        <p className="p-6 text-gray-600">Cargando detalle...</p>
      </Layout>
    );
  }

  if (!tramite) {
    return (
      <Layout>
        <p className="p-6 text-red-600">Trámite no encontrado</p>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="p-6 max-w-4xl mx-auto">
        <h1 className="text-2xl font-bold mb-2">{tramite.nombre}</h1>
        <p className="text-gray-600 mb-4">{tramite.descripcion}</p>
        {/* Aquí podés agregar lógica para mostrar pasos, archivos, estado, etc. */}
      </div>
    </Layout>
  );
}
