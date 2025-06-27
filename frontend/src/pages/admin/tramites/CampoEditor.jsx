import React, { useState } from 'react';

const CampoEditor = ({ campo, onChange, onEliminar }) => {
  const [tipo, setTipo] = useState(campo.tipo || 'texto');

  const handleTipoChange = (e) => {
    const nuevoTipo = e.target.value;
    setTipo(nuevoTipo);
    onChange({ ...campo, tipo: nuevoTipo });
  };

  const renderOpciones = () => {
    if (tipo === 'select') {
      return (
        <div>
          <label>Opciones (separadas por coma):</label>
          <input
            type="text"
            value={campo.opciones?.join(',') || ''}
            onChange={(e) =>
              onChange({ ...campo, opciones: e.target.value.split(',') })
            }
          />
        </div>
      );
    }
    return null;
  };

  const renderAvanzado = () => {
    if (tipo === 'codigo' || tipo === 'api') {
      return (
        <div>
          <label>{tipo === 'codigo' ? 'Código personalizado:' : 'Configuración de API:'}</label>
          <textarea
            rows={5}
            value={campo.config || ''}
            onChange={(e) => onChange({ ...campo, config: e.target.value })}
          />
        </div>
      );
    }
    return null;
  };

  return (
    <div className="border p-4 rounded-md shadow-sm mb-4">
      <div className="flex justify-between items-center mb-2">
        <strong>Campo</strong>
        <button onClick={onEliminar} className="text-red-600 font-bold">X</button>
      </div>

      <label>Etiqueta:</label>
      <input
        type="text"
        value={campo.etiqueta || ''}
        onChange={(e) => onChange({ ...campo, etiqueta: e.target.value })}
      />

      <label>Tipo:</label>
      <select value={tipo} onChange={handleTipoChange}>
        <option value="texto">Texto</option>
        <option value="numero">Número</option>
        <option value="fecha">Fecha</option>
        <option value="hora">Hora</option>
        <option value="archivo">Archivo</option>
        <option value="select">Select</option>
        <option value="codigo">Código</option>
        <option value="api">API</option>
      </select>

      <label>
        <input
          type="checkbox"
          checked={campo.obligatorio || false}
          onChange={(e) => onChange({ ...campo, obligatorio: e.target.checked })}
        />
        Obligatorio
      </label>

      <label>Pista:</label>
      <input
        type="text"
        value={campo.pista || ''}
        onChange={(e) => onChange({ ...campo, pista: e.target.value })}
      />

      {renderOpciones()}
      {renderAvanzado()}
    </div>
  );
};

export default CampoEditor;
