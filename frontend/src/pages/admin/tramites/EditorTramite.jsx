import React, { useState } from "react";
import Layout from "../../../components/layout/Layout";

export default function EditorTramite() {
  const [nombre, setNombre] = useState("");
  const [descripcion, setDescripcion] = useState("");

  const handleGuardar = () => {
    alert(`Trámite guardado: ${nombre}`);
  };

  return (
    <Layout>
      <div className="p-6 max-w-3xl mx-auto">
        <h1 className="text-2xl font-bold text-gray-800 mb-6">Nuevo Trámite</h1>

        <div className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">Nombre del Trámite</label>
            <input
              type="text"
              value={nombre}
              onChange={(e) => setNombre(e.target.value)}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea
              value={descripcion}
              onChange={(e) => setDescripcion(e.target.value)}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
              rows={4}
            ></textarea>
          </div>

          <div className="pt-4">
            <button
              onClick={handleGuardar}
              className="bg-[#248B89] text-white px-6 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
            >
              Guardar Trámite
            </button>
          </div>
        </div>
      </div>
    </Layout>
  );
}
