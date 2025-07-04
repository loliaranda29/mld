import React from 'react';

const TramiteNode = ({ tramite, setTramites }) => {
  return (
    <div className="ml-4 mb-2 border-l-2 border-gray-300 pl-4">
      <div className="flex items-center justify-between">
        <div>
          <strong>{tramite.tipo === 'madre' ? '🧾' : '↳'}</strong> {tramite.nombre}
        </div>
        <div className="space-x-2">
          <button className="text-blue-600 text-sm">Editar</button>
          <button className="text-green-600 text-sm">+ Subtrámite</button>
          <button className="text-red-600 text-sm">Eliminar</button>
        </div>
      </div>
      {tramite.subtramites && tramite.subtramites.map((st) => (
        <TramiteNode key={st.id} tramite={st} setTramites={setTramites} />
      ))}
    </div>
  );
};

export default TramiteNode;