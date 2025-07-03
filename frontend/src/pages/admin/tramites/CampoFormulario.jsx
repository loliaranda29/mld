
import React from "react";

export default function CampoFormulario({ campo, onChange, onEliminar }) {
  if (!campo || typeof campo !== "object") {
    return <div className="text-red-600">Error: campo inválido</div>;
  }

  const handleChange = (prop, value) => {
    onChange({ ...campo, [prop]: value });
  };

  return (
    <div className="border p-4 rounded-md shadow-sm bg-gray-50 mb-3">
      <div className="flex justify-between items-center mb-3">
        <h4 className="text-sm font-semibold text-gray-700">Campo</h4>
        <button onClick={onEliminar} className="text-xs text-red-600 hover:underline">
          Eliminar
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label className="block text-xs font-medium text-gray-600">Nombre</label>
          <input
            type="text"
            value={campo.nombre || ""}
            onChange={(e) => handleChange("nombre", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>

        <div>
          <label className="block text-xs font-medium text-gray-600">Tipo</label>
          <select
            value={campo.tipo || "texto"}
            onChange={(e) => handleChange("tipo", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          >
            <option value="texto">Texto</option>
            <option value="numero">Número</option>
            <option value="select">Select</option>
            <option value="archivo">Archivo</option>
          </select>
        </div>

        <div className="flex items-center gap-2 mt-5">
          <input
            type="checkbox"
            checked={campo.requerido || false}
            onChange={(e) => handleChange("requerido", e.target.checked)}
          />
          <label className="text-sm text-gray-700">Requerido</label>
        </div>

      {/* Campo condicional */}
      <div className="mt-4">
        <label className="block text-xs font-medium text-gray-600">Condición (opcional)</label>
        <input
          type="text"
          value={campo.condicion || ""}
          onChange={(e) => handleChange("condicion", e.target.value)}
          placeholder="Ej: solo si campoX = 'sí'"
          className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
        />
      </div>

      {/* Código personalizado */}
      <div className="mt-4">
        <label className="block text-xs font-medium text-gray-600">Código personalizado (JS)</label>
        <textarea
          value={campo.codigo || ""}
          onChange={(e) => handleChange("codigo", e.target.value)}
          placeholder="function validarCampo(valor) { return valor.length > 3 }"
          rows={3}
          className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm font-mono"
        ></textarea>
      </div>

      {/* API externa */}
      <div className="mt-4">
        <label className="block text-xs font-medium text-gray-600">API externa (opcional)</label>
        <input
          type="text"
          value={campo.api || ""}
          onChange={(e) => handleChange("api", e.target.value)}
          placeholder="https://api.lujan.gov.ar/datos-ciudadano"
          className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
        />
      </div>

      {/* Pistas adicionales */}
      <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block text-xs font-medium text-gray-600">Texto de ayuda</label>
          <input
            type="text"
            value={campo.pistaTexto || ""}
            onChange={(e) => handleChange("pistaTexto", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600">Enlace de ayuda</label>
          <input
            type="text"
            value={campo.pistaLink || ""}
            onChange={(e) => handleChange("pistaLink", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
      </div>
    </div>
    </div>
  );
}
