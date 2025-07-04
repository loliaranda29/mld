import React from 'react';

export default function VistaPreviaFormulario({ secciones, onClose }) {
  return (
    <div className="fixed inset-0 bg-black bg-opacity-40 z-50 flex justify-center items-center">
      <div className="bg-white max-w-3xl w-full p-6 rounded shadow-lg overflow-y-auto max-h-[90vh]">
        <div className="flex justify-between mb-4">
          <h2 className="text-xl font-semibold">Vista Previa del Formulario</h2>
          <button onClick={onClose} className="text-red-500 font-bold">X</button>
        </div>

        {secciones.map((seccion, i) => (
          <div key={i} className="mb-6">
            <h3 className="text-lg font-bold text-gray-700 mb-2">{seccion.titulo}</h3>
            {seccion.campos.map((campo) => (
              <div key={campo.id} className="mb-4">
                <label className="block text-sm text-gray-600">{campo.etiqueta}</label>
                {campo.tipo === 'texto' && <input type="text" className="w-full border px-3 py-1 rounded" />}
                {campo.tipo === 'numero' && <input type="number" className="w-full border px-3 py-1 rounded" />}
                {campo.tipo === 'select' && (
                  <select className="w-full border px-3 py-1 rounded">
                    {campo.opciones.map((op, idx) => (
                      <option key={idx}>{op}</option>
                    ))}
                  </select>
                )}
                {campo.tipo === 'archivo' && (
                  <input type="file" multiple accept={campo.archivoTipos.join(',')} />
                )}
              </div>
            ))}
          </div>
        ))}
      </div>
    </div>
  );
}
