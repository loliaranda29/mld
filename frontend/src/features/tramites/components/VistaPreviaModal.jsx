import React from 'react';

const CampoPreview = ({ campo }) => {
  return (
    <div className="mb-4">
      <label className="block text-sm font-semibold text-gray-700 mb-1">{campo.etiqueta}</label>
      {campo.tipo === 'texto' && <input type="text" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === 'numero' && <input type="number" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === 'fecha' && <input type="date" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === 'archivo' && <input type="file" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === 'select' && (
        <select className="border px-3 py-2 rounded w-full">
          {(campo.opciones || []).map((opt, i) => (
            <option key={i} value={opt}>{opt}</option>
          ))}
        </select>
      )}
      {campo.pistaTexto && <p className="text-xs text-gray-500 mt-1">{campo.pistaTexto}</p>}
    </div>
  );
};

export default function VistaPreviaModal({ abierto, onClose, secciones }) {
  if (!abierto) return null;

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50">
      <div className="fixed inset-0 bg-white overflow-y-auto p-6">
        <div className="flex justify-between items-center mb-6">
          <h2 className="text-2xl font-bold">Vista previa del trámite</h2>
          <button onClick={onClose} className="text-red-500 font-semibold">✖️ Cerrar</button>
        </div>

        {secciones.length === 0 && (
          <p className="text-gray-500">No hay secciones definidas.</p>
        )}

        {secciones.map((seccion, idx) => (
          <div key={idx} className="mb-8">
            <h3 className="text-lg font-semibold text-[#248B89] mb-2">{seccion.titulo}</h3>
            <div className="bg-gray-50 p-4 rounded shadow-sm">
              {seccion.campos.map((campo) => (
                <CampoPreview key={campo.id} campo={campo} />
              ))}
            </div>
          </div>
        ))}
      </div>
    </div>
  );
}

