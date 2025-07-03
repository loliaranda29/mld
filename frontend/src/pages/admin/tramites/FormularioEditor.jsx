import React from "react";
import CampoEditor from "../../../features/tramites/components/CampoEditor";

/**
 * Editor para un formulario dividido por secciones.
 * Cada sección puede tener múltiples campos configurables.
 */
export default function FormularioEditor({ secciones, setSecciones }) {
  const agregarSeccion = () => {
    setSecciones([
      ...secciones,
      {
        id: Date.now(),
        titulo: "",
        campos: []
      }
    ]);
  };

  const actualizarTitulo = (index, nuevoTitulo) => {
    const nuevas = [...secciones];
    nuevas[index].titulo = nuevoTitulo;
    setSecciones(nuevas);
  };

  const agregarCampo = (index) => {
    const nuevas = [...secciones];
    nuevas[index].campos.push({
      id: Date.now(),
      tipo: "texto",
      etiqueta: "",
      obligatorio: false
    });
    setSecciones(nuevas);
  };

  const actualizarCampo = (index, campoIndex, campoActualizado) => {
    const nuevas = [...secciones];
    nuevas[index].campos[campoIndex] = campoActualizado;
    setSecciones(nuevas);
  };

  const eliminarCampo = (index, campoIndex) => {
    const nuevas = [...secciones];
    nuevas[index].campos = nuevas[index].campos.filter((_, i) => i !== campoIndex);
    setSecciones(nuevas);
  };

  const eliminarSeccion = (index) => {
    const nuevas = secciones.filter((_, i) => i !== index);
    setSecciones(nuevas);
  };

  return (
    <div className="space-y-6">
      {secciones.map((seccion, index) => (
        <div key={seccion.id} className="border p-4 rounded-lg bg-white shadow-sm">
          <div className="flex justify-between items-center mb-4">
            <input
              type="text"
              placeholder="Título de la sección"
              value={seccion.titulo}
              onChange={(e) => actualizarTitulo(index, e.target.value)}
              className="text-lg font-semibold w-full border p-2 rounded"
            />
            <button
              onClick={() => eliminarSeccion(index)}
              className="text-sm text-red-600 hover:underline ml-4"
            >
              Eliminar sección
            </button>
          </div>

          {seccion.campos.map((campo, campoIndex) => (
            <CampoEditor
              key={campo.id}
              campo={campo}
              onChange={(actualizado) => actualizarCampo(index, campoIndex, actualizado)}
              onEliminar={() => eliminarCampo(index, campoIndex)}
            />
          ))}

          <button
            onClick={() => agregarCampo(index)}
            className="mt-2 text-sm text-[#248B89] font-medium hover:underline"
          >
            + Agregar campo
          </button>
        </div>
      ))}

      <button
        onClick={agregarSeccion}
        className="bg-[#248B89] text-white px-4 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
      >
        + Agregar Sección
      </button>
    </div>
  );
}
