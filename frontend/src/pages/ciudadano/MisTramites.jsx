import React, { useEffect, useState } from "react";
import Layout from "../../components/layout/Layout";
import { useNavigate } from "react-router-dom";

export default function MisTramites() {
  const [tramites, setTramites] = useState([]);
  const [loading, setLoading] = useState(true);
  const navigate = useNavigate();

  useEffect(() => {
    fetch("http://localhost:3000/api/ciudadano/tramites")
      .then((res) => res.json())
      .then((data) => setTramites(data))
      .catch((err) => console.error("Error al cargar trámites:", err))
      .finally(() => setLoading(false));
  }, []);

  if (loading) {
    return (
      <Layout>
        <div className="p-6 max-w-4xl mx-auto">
          <p className="text-gray-600">Cargando trámites...</p>
        </div>
      </Layout>
    );
  }

  return (
    <Layout>
      <div className="p-6 max-w-4xl mx-auto">
        <h1 className="text-2xl font-bold text-gray-800 mb-4">Mis Trámites</h1>
        {tramites.length === 0 ? (
          <p className="text-gray-500">No tenés trámites iniciados.</p>
        ) : (
          <ul className="space-y-4">
            {tramites.map((t) => (
              <li
                key={t._id}
                className="bg-white p-4 rounded shadow border hover:shadow-md transition cursor-pointer"
                onClick={() => navigate(`/mis-tramites/${t._id}`)}
              >
                <h2 className="text-lg font-semibold text-[#1F2937]">{t.nombre}</h2>
                <p className="text-sm text-gray-600 mb-1">{t.descripcion}</p>
                <p className="text-xs text-gray-500">
                  Estado: <span className="font-medium">{t.estadoActual || "En progreso"}</span>
                  {" — "}
                  Últ. actualización: {t.updatedAt ? new Date(t.updatedAt).toLocaleDateString() : "-"}
                </p>
              </li>
            ))}
          </ul>
        )}
      </div>
    </Layout>
  );
}
