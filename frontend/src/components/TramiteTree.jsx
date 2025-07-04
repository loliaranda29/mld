import React, { useState } from 'react';
import TramiteNode from './TramiteNode';

const TramiteTree = () => {
  const [tramites, setTramites] = useState([
    {
      id: 1,
      nombre: 'Licencia de conducir',
      tipo: 'madre',
      descripcion: '',
      subtramites: [
        { id: 2, nombre: 'Renovación', tipo: 'subtramite', descripcion: '' },
        { id: 3, nombre: 'Duplicado', tipo: 'subtramite', descripcion: '' },
      ],
    },
  ]);

  return (
    <div className="p-4 bg-white shadow-md rounded-md">
      <h2 className="text-xl font-bold mb-4">Gestor de Trámites</h2>
      {tramites.map((t) => (
        <TramiteNode key={t.id} tramite={t} setTramites={setTramites} />
      ))}
    </div>
  );
};

export default TramiteTree;