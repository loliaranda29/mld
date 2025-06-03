import React from "react";
import { Link } from "react-router-dom";
import Layout from "../../../components/layout/Layout";

const tramitesMock = [
  { id: 1, nombre: "Licencia de Conducir", estado: "Publicado" },
  { id: 2, nombre: "Habilitación Comercial", estado: "Borrador" },
  { id: 3, nombre: "Permiso de Construcción", estado: "Publicado" },
];

export default function ListadoTramites() {
  return (
    <Layout>
      <div className="p-6">
        <div className="flex justify-between items-center mb-6">
          <h1 className="text-2xl font-bold text-gray-800">Gestión de Trámites</h1>
          <Link
            to="/admin/tramites/editor"
            className="bg-[#248B89] text-white px-4 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
          >
            + Nuevo Trámite
          </Link>
        </div>

        <table className="w-full bg-white rounded-lg shadow overflow-hidden">
          <thead className="bg-gray-100 text-left">
            <tr>
              <th className="p-3">Nombre</th>
              <th className="p-3">Estado</th>
              <th className="p-3">Acciones</th>
            </tr>
          </thead>
          <tbody>
            {tramitesMock.map((tramite) => (
              <tr key={tramite.id} className="border-t">
                <td className="p-3 text-gray-800">{tramite.nombre}</td>
                <td className="p-3">
                  <span className={`px-2 py-1 rounded text-sm font-medium ${
                    tramite.estado === "Publicado" ? "bg-green-100 text-green-800" : "bg-yellow-100 text-yellow-800"
                  }`}>
                    {tramite.estado}
                  </span>
                </td>
                <td className="p-3 space-x-2">
                  <Link to={`/admin/tramites/editor/${tramite.id}`} className="text-blue-600 hover:underline text-sm">Editar</Link>
                  <button className="text-red-600 hover:underline text-sm">Eliminar</button>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}
