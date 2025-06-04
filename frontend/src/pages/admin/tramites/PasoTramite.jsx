import React, { useState } from "react";
import CampoFormulario from "./CampoFormulario";

export default function PasoTramite({ paso, onChange, onEliminar }) {
  const handleChange = (campo, valor) => {
    onChange({ ...paso, [campo]: valor });
  };

  const agregarCampo = () => {
    const nuevoCampo = {
      nombre: "",
      tipo: "texto",
      requerido: false,
    };
    handleChange("campos", [...(paso.campos || []), nuevoCampo]);
  };

  const actualizarCampo = (index, campoActualizado) => {
    const nuevosCampos = [...paso.campos];
    nuevosCampos[index] = campoActualizado;
    handleChange("campos", nuevosCampos);
  };

  const eliminarCampo = (index) => {
    const nuevosCampos = paso.campos.filter((_, i) => i !== index);
    handleChange("campos", nuevosCampos);
  };

  return (
    <div className="border border-gray-300 rounded-lg p-4 mb-4 bg-white shadow-sm">
      <div className="flex justify-between items-center mb-3">
        <h3 className="text-lg font-semibold text-[#111827]">Paso #{paso.numero}</h3>
        <button
          onClick={onEliminar}
          className="text-sm text-red-600 hover:underline"
        >
          Eliminar paso
        </button>
      </div>

      <div className="space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700">Nombre del paso</label>
          <input
            type="text"
            value={paso.nombre}
            onChange={(e) => handleChange("nombre", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700">Instrucciones</label>
          <textarea
            value={paso.instrucciones}
            onChange={(e) => handleChange("instrucciones", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
            rows={3}
          ></textarea>
        </div>

        <div>
          <h4 className="text-sm font-semibold text-gray-700">Campos</h4>
          {(paso.campos || []).map((campo, index) => (
            <CampoFormulario
              key={index}
              campo={campo}
              onChange={(actualizado) => actualizarCampo(index, actualizado)}
              onEliminar={() => eliminarCampo(index)}
            />
          ))}
          <button
            onClick={agregarCampo}
            className="mt-2 text-sm text-[#248B89] font-medium hover:underline"
          >
            + Agregar campo
          </button>
        </div>
      </div>
    </div>
  );
}
