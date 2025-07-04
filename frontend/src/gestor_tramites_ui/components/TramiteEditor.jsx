// src/components/TramiteEditor.jsx

import React, { useState } from 'react';

const TramiteEditor = () => {
  const [tramites, setTramites] = useState([
    {
      id: 'madre-1',
      nombre: 'Licencia de Conducir',
      hijos: [
        { id: 'sub-1', nombre: 'Renovación' },
        { id: 'sub-2', nombre: 'Duplicado' },
      ],
    },
  ]);

  const agregarSubtramite = (idMadre) => {
    const nuevoNombre = prompt('Nombre del subtrámite:');
    if (!nuevoNombre) return;

    const nuevoTramites = tramites.map((t) =>
      t.id === idMadre
        ? {
            ...t,
            hijos: [
              ...t.hijos,
              {
                id: `sub-${Date.now()}`,
                nombre: nuevoNombre,
              },
            ],
          }
        : t
    );
    setTramites(nuevoTramites);
  };

  const renderArbol = () => {
    return tramites.map((madre) => (
      <div key={madre.id} className="mb-4">
        <div className="font-bold">{madre.nombre}</div>
        <div className="ml-4">
          {madre.hijos.map((sub) => (
            <div key={sub.id} className="ml-2">
              └─ {sub.nombre}
            </div>
          ))}
        </div>
        <button
          onClick={() => agregarSubtramite(madre.id)}
          className="mt-2 text-sm text-blue-500 hover:underline"
        >
          + Agregar subtrámite
        </button>
      </div>
    ));
  };

  return (
    <div className="p-4">
      <h2 className="text-lg font-semibold mb-4">Editor de Trámites</h2>
      {renderArbol()}
    </div>
  );
};

export default TramiteEditor;
