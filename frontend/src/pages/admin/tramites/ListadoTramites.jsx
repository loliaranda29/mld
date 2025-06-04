import React, { useEffect, useState } from "react";
import { useLocation, Link, useNavigate } from "react-router-dom";
import Layout from "../../../components/layout/Layout";

export default function ListadoTramites() {
  const [tramites, setTramites] = useState([]);
  const location = useLocation();
  const navigate = useNavigate();
  const exito = location.state?.exito;

  useEffect(() => {
    fetch("http://localhost:3000/api/tramites")
      .then((res) => res.json())
      .then((data) => setTramites(data))
      .catch((err) => console.error("Error al cargar trámites:", err));
  }, []);

  const eliminarTramite = async (id) => {
    if (!window.confirm("¿Estás seguro de eliminar este trámite?")) return;

    try {
      const res = await fetch(`http://localhost:3000/api/tramites/${id}`, {
        method: "DELETE",
      });

      if (res.ok) {
        setTramites(tramites.filter((t) => t._id !== id));
      } else {
        alert("Error al eliminar el trámite.");
      }
    } catch (err) {
      console.error(err);
      alert("Ocurrió un error.");
    }
  };

  return (
    <Layout>
      <div className="p-6 max-w-5xl mx-auto">
        <div className="flex justify-between items-center mb-4">
          <h1 className="text-2xl font-bold text-gray-800">Trámites Disponibles</h1>
          <Link
            to="/admin/tramites/editor"
            className="bg-[#248B89] text-white px-4 py-2 rounded-md hover:bg-[#1f706e] font-semibold"
          >
            + Nuevo Trámite
          </Link>
        </div>

        {exito && (
          <div className="mb-4 p-3 bg-green-100 text-green-700 rounded border border-green-300">
            ✅ Trámite creado correctamente
          </div>
        )}

        <div className="grid gap-4">
          {tramites.length === 0 ? (
            <p className="text-gray-500">No hay trámites registrados.</p>
          ) : (
            tramites.map((tramite) => (
              <div
                key={tramite._id}
                className="bg-white p-4 rounded shadow border hover:shadow-md transition"
              >
                <h2 className="text-lg font-semibold text-[#1F2937]">{tramite.nombre}</h2>
                <p className="text-sm text-gray-600">{tramite.descripcion}</p>
                <p className="text-sm text-gray-500 mt-1 mb-2">
                  Pasos: {tramite.pasos?.length || 0} — Etapas: {tramite.etapas?.length || 0}
                </p>
                <div className="flex gap-3">
                  <button
                    onClick={() => navigate(`/admin/tramites/editar/${tramite._id}`)}
                    className="text-blue-600 hover:underline text-sm"
                  >
                    ✏️ Editar
                  </button>
                  <button
                    onClick={() => eliminarTramite(tramite._id)}
                    className="text-red-600 hover:underline text-sm"
                  >
                    🗑️ Eliminar
                  </button>
                </div>
              </div>
            ))
          )}
        </div>
      </div>
    </Layout>
  );
}
