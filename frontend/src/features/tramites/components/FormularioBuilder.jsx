import React from 'react';
import { useState } from 'react';
import { PlusCircle, Eye } from 'lucide-react';
import CampoEditor from './CampoEditor';
import VistaPreviaFormulario from './VistaPreviaFormulario';

export default function FormularioBuilder({ secciones, setSecciones }) {
  const [mostrarVistaPrevia, setMostrarVistaPrevia] = useState(false);

  const agregarSeccion = () => {
    setSecciones([
      ...secciones,
      { id: Date.now(), titulo: '', campos: [] }
    ]);
  };

  const actualizarSeccion = (index, nuevaSeccion) => {
    const nuevas = [...secciones];
    nuevas[index] = nuevaSeccion;
    setSecciones(nuevas);
  };

  const eliminarSeccion = (index) => {
    setSecciones(secciones.filter((_, i) => i !== index));
  };

  return (
    <div className="space-y-6">
      {secciones.map((seccion, index) => (
        <div key={seccion.id} className="border p-4 rounded shadow bg-white">
          <div className="flex justify-between mb-4">
            <input
              type="text"
              placeholder="Título de la sección"
              value={seccion.titulo}
              onChange={(e) =>
                actualizarSeccion(index, { ...seccion, titulo: e.target.value })
              }
              className="text-lg font-semibold w-full border p-2 rounded"
            />
            <button onClick={() => eliminarSeccion(index)} className="text-red-600 ml-4">🗑️</button>
          </div>

          <CampoEditor
            campos={seccion.campos}
            onChange={(camposActualizados) =>
              actualizarSeccion(index, { ...seccion, campos: camposActualizados })
            }
          />
        </div>
      ))}

      <div className="flex gap-4">
        <button
          onClick={agregarSeccion}
          className="bg-[#248B89] text-white px-4 py-2 rounded hover:bg-[#1f706e] flex items-center gap-2"
        >
          <PlusCircle size={18} /> Agregar Sección
        </button>
        <button
          onClick={() => setMostrarVistaPrevia(true)}
          className="flex items-center gap-2 px-4 py-2 border text-[#248B89] border-[#248B89] rounded hover:bg-[#e7f1f0]"
        >
          <Eye size={18} /> Vista previa
        </button>
      </div>

      {mostrarVistaPrevia && (
        <VistaPreviaFormulario secciones={secciones} onClose={() => setMostrarVistaPrevia(false)} />
      )}
    </div>
  );
}
