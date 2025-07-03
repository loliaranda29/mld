import React, { useState } from 'react';

/**
 * Editor de campos individuales dentro de una sección del formulario.
 * Se integra con FormularioBuilder.jsx y permite configurar campos dinámicos.
 */
const CampoEditor = ({ campo, onChange, onEliminar }) => {
  const [tipo, setTipo] = useState(campo.tipo || 'texto');

  const handleTipoChange = (e) => {
    const nuevoTipo = e.target.value;
    setTipo(nuevoTipo);
    onChange({ ...campo, tipo: nuevoTipo });
  };

  const handleInputChange = (prop, value) => {
    onChange({ ...campo, [prop]: value });
  };

  const renderOpciones = () => {
    if (tipo === 'select') {
      return (
        <div className="mt-2">
          <label className="block text-xs text-gray-600 mb-1">Opciones (separadas por coma)</label>
          <input
            type="text"
            value={campo.opciones?.join(',') || ''}
            onChange={(e) => handleInputChange('opciones', e.target.value.split(','))}
            className="w-full border px-3 py-1 rounded text-sm"
          />
        </div>
      );
    }
    return null;
  };

  const renderAvanzado = () => {
    if (tipo === 'codigo' || tipo === 'api') {
      return (
        <div className="mt-2">
          <label className="block text-xs text-gray-600 mb-1">
            {tipo === 'codigo' ? 'Código JS personalizado' : 'Configuración de API externa'}
          </label>
          <textarea
            rows={3}
            value={campo.config || ''}
            onChange={(e) => handleInputChange('config', e.target.value)}
            className="w-full border px-3 py-1 rounded text-sm font-mono"
          />
        </div>
      );
    }
    return null;
  };

  return (
    <div className="border p-4 rounded-md shadow-sm mb-4 bg-gray-50">
      <div className="flex justify-between items-center mb-3">
        <strong className="text-sm text-gray-700">🧩 Campo</strong>
        <button onClick={onEliminar} className="text-red-600 text-sm font-medium hover:underline">
          Eliminar
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label className="block text-xs text-gray-600">Etiqueta</label>
          <input
            type="text"
            value={campo.etiqueta || ''}
            onChange={(e) => handleInputChange('etiqueta', e.target.value)}
            className="w-full border px-3 py-1 rounded text-sm"
            placeholder="Ej: Número de documento"
          />
        </div>

        <div>
          <label className="block text-xs text-gray-600">Tipo de campo</label>
          <select
            value={tipo}
            onChange={handleTipoChange}
            className="w-full border px-3 py-1 rounded text-sm"
          >
            <option value="texto">Texto</option>
            <option value="numero">Número</option>
            <option value="fecha">Fecha</option>
            <option value="archivo">Archivo</option>
            <option value="select">Select</option>
            <option value="codigo">Código JS</option>
            <option value="api">API externa</option>
          </select>
        </div>

        <div className="flex items-center gap-2 mt-6">
          <input
            type="checkbox"
            checked={campo.obligatorio || false}
            onChange={(e) => handleInputChange('obligatorio', e.target.checked)}
          />
          <span className="text-sm text-gray-600">Obligatorio</span>
        </div>
      </div>

      {/* Renderizar si es tipo select, código o api */}
      {renderOpciones()}
      {renderAvanzado()}
    </div>
  );
};

export default CampoEditor;
