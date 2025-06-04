import React from "react";
import Select from "react-select";

const responsablesDisponibles = [
  "Mesa de Entrada",
  "Dirección Técnica",
  "Tesorería",
  "Firma Final"
];

export default function EtapasTramite({ etapas, setEtapas }) {
  const agregarEtapa = () => {
    setEtapas([
      ...etapas,
      {
        nombre: "",
        descripcion: "",
        responsables: [],
        permiteVolver: false,
      },
    ]);
  };

  const actualizarEtapa = (index, prop, value) => {
    const nuevas = [...etapas];
    nuevas[index][prop] = value;
    setEtapas(nuevas);
  };

  const eliminarEtapa = (index) => {
    const nuevas = etapas.filter((_, i) => i !== index);
    setEtapas(nuevas);
  };

  return (
    <div className="space-y-6 mt-4">
      {etapas.map((etapa, index) => (
        <div key={index} className="border p-4 rounded-md shadow-sm bg-white">
          <div className="flex justify-between items-center mb-3">
            <h4 className="font-semibold text-gray-700">Etapa {index + 1}</h4>
            <button onClick={() => eliminarEtapa(index)} className="text-xs text-red-600 hover:underline">
              Eliminar
            </button>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label className="block text-sm font-medium text-gray-600">Nombre</label>
              <input
                type="text"
                value={etapa.nombre}
                onChange={(e) => actualizarEtapa(index, "nombre", e.target.value)}
                className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-600">Responsables</label>
              <Select
                isMulti
                options={responsablesDisponibles.map((r) => ({ value: r, label: r }))}
                value={etapa.responsables.map((r) => ({ value: r, label: r }))}
                onChange={(selected) =>
                  actualizarEtapa(index, "responsables", selected.map((s) => s.value))
                }
              />
            </div>

            <div className="col-span-2">
              <label className="block text-sm font-medium text-gray-600">Descripción</label>
              <textarea
                rows={2}
                value={etapa.descripcion}
                onChange={(e) => actualizarEtapa(index, "descripcion", e.target.value)}
                className="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 text-sm"
              />
            </div>

            <div className="col-span-2 flex items-center gap-2">
              <input
                type="checkbox"
                checked={etapa.permiteVolver}
                onChange={(e) => actualizarEtapa(index, "permiteVolver", e.target.checked)}
              />
              <label className="text-sm text-gray-700">Permitir volver a esta etapa desde una posterior</label>
            </div>
          </div>
        </div>
      ))}

      <button
        onClick={agregarEtapa}
        className="px-4 py-2 border border-[#248B89] text-[#248B89] rounded-md font-semibold hover:bg-[#e7f1f0]"
      >
        + Agregar Etapa
      </button>
    </div>
  );
}
