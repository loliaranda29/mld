import React, { useState } from "react";

export default function CampoFormulario({ campo, onChange, onEliminar }) {
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
            value={campo.nombre}
            onChange={(e) => handleChange("nombre", e.target.value)}
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>

        <div>
          <label className="block text-xs font-medium text-gray-600">Tipo</label>
          <select
            value={campo.tipo}
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
            checked={campo.requerido}
            onChange={(e) => handleChange("requerido", e.target.checked)}
          />
          <label className="text-sm text-gray-700">Requerido</label>
        </div>
      </div>

      {/* Condicionales */}
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

      {/* Pistas */}
      <div className="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label className="block text-xs font-medium text-gray-600">Texto de ayuda</label>
          <input
            type="text"
            value={campo.pistaTexto || ""}
            onChange={(e) => handleChange("pistaTexto", e.target.value)}
            placeholder="Ej: Ingresá tu número de documento"
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600">Enlace de ayuda</label>
          <input
            type="text"
            value={campo.pistaLink || ""}
            onChange={(e) => handleChange("pistaLink", e.target.value)}
            placeholder="https://ayuda.lujandecuyo.gob.ar/tramiteX"
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600">Video explicativo</label>
          <input
            type="text"
            value={campo.pistaVideo || ""}
            onChange={(e) => handleChange("pistaVideo", e.target.value)}
            placeholder="https://youtu.be/video"
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
        <div>
          <label className="block text-xs font-medium text-gray-600">Imagen de referencia</label>
          <input
            type="text"
            value={campo.pistaImagen || ""}
            onChange={(e) => handleChange("pistaImagen", e.target.value)}
            placeholder="https://.../imagen.png"
            className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-1 text-sm"
          />
        </div>
      </div>

      {/* Vista previa */}
      <div className="mt-6 border-t pt-4">
        <h5 className="text-sm font-semibold text-gray-600 mb-2">Vista previa</h5>
        {campo.tipo === "texto" && (
          <input
            type="text"
            placeholder={campo.pistaTexto || "Ingresá un valor"}
            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
          />
        )}
        {campo.tipo === "numero" && (
          <input
            type="number"
            placeholder={campo.pistaTexto || "Ingresá un número"}
            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
          />
        )}
        {campo.tipo === "archivo" && (
          <input
            type="file"
            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
          />
        )}
        {campo.tipo === "select" && (
          <select className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option>Seleccione una opción</option>
            <option>Opción 1</option>
            <option>Opción 2</option>
          </select>
        )}
        {campo.pistaImagen && (
          <img src={campo.pistaImagen} alt="Referencia" className="mt-2 max-h-32 object-contain" />
        )}
        {campo.pistaVideo && (
          <div className="mt-2">
            <iframe
              width="100%"
              height="200"
              src={campo.pistaVideo.replace("watch?v=", "embed/")}
              title="Video explicativo"
              frameBorder="0"
              allowFullScreen
            ></iframe>
          </div>
        )}
      </div>
    </div>
  );
}
