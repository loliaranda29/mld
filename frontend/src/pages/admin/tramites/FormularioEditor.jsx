import React, { useState } from "react";
import CampoEditor from "./CampoEditor";

export default function FormularioEditor({ secciones, setSecciones }) {
  const agregarSeccion = () => {
    setSecciones([
      ...secciones,
      {
        id: Date.now(),
        titulo: "",
        campos: []
      }
    ]);
  };

  const actualizarSeccion = (index, seccionActualizada) => {
    const nuevasSecciones = [...secciones];
    nuevasSecciones[index] = seccionActualizada;
    setSecciones(nuevasSecciones);
  };

  const eliminarSeccion = (index) => {
    const nuevasSecciones = secciones.filter((_, i) => i !== index);
    setSecciones(nuevasSecciones);
  };

  return (
    <div className="space-y-6">
      {secciones.map((seccion, index) => (
        <div key={seccion.id} className="border p-4 rounded-lg bg-white shadow">
          <div className="flex justify-between items-center">
            <input
              type="text"
              placeholder="Título de la sección"
              value={seccion.titulo}
              onChange={(e) =>
                actualizarSeccion(index, { ...seccion, titulo: e.target.value })
              }
              className="text-lg font-semibold w-full p-2 mb-4 border rounded"
            />
            <button
              onClick={() => eliminarSeccion(index)}
              className="text-red-500 text-sm"
            >
              Eliminar sección
            </button>
          </div>
          <CampoEditor
            campos={seccion.campos}
            onChange={(camposActualizados) =>
              actualizarSeccion(index, { ...seccion, campos: camposActualizados })
            }
          />
        </div>
      ))}
      <button
        onClick={agregarSeccion}
        className="bg-[#248B89] text-white px-4 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
      >
        + Agregar Sección
      </button>
    </div>
  );
}
