
import React from "react";

export default function CampoPreview({ campo }) {
  return (
    <div className="mb-4">
      <label className="block text-sm font-medium text-gray-700 mb-1">
        {campo.etiqueta}
        {campo.obligatorio && <span className="text-red-500"> *</span>}
      </label>

      {campo.tipo === "texto" && (
        <input
          type="text"
          disabled
          placeholder="Campo de texto"
          className="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
        />
      )}

      {campo.tipo === "numero" && (
        <input
          type="number"
          disabled
          placeholder="Campo numérico"
          className="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
        />
      )}

      {campo.tipo === "fecha" && (
        <input
          type="date"
          disabled
          className="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
        />
      )}

      {campo.tipo === "archivo" && (
        <input
          type="file"
          disabled
          className="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
        />
      )}

      {campo.tipo === "select" && (
        <select
          disabled
          className="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100"
        >
          <option value="">Seleccione una opción</option>
          {campo.opciones?.map((op, i) => (
            <option key={i} value={op}>
              {op}
            </option>
          ))}
        </select>
      )}
    </div>
  );
}
