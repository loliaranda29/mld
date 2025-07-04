import React from "react";
import Card from "../../components/ui/card";
import { Input } from "../../components/ui/input";
import { Button } from "../../components/ui/button";
import { Select } from "../../components/ui/select";

export default function EtapasEditor({ etapas, setEtapas, etapaInicial, setEtapaInicial }) {
  const agregarEtapa = () => {
    const nueva = {
      nombre: "",
      responsable: "",
      condiciones: "",
      documentos: "",
      puedeVolver: false,
      volverA: "",
      simultanea: false,
    };
    setEtapas([...etapas, nueva]);
  };

  const actualizarEtapa = (index, campo, valor) => {
    const nuevas = [...etapas];
    nuevas[index][campo] = valor;
    setEtapas(nuevas);
  };

  const eliminarEtapa = (index) => {
    const nuevas = etapas.filter((_, i) => i !== index);
    setEtapas(nuevas);
  };

  return (
    <div className="space-y-6">
      {etapas.map((etapa, index) => (
        <Card key={index} className="p-4">
          <div className="mb-4">
            <label className="text-sm font-medium text-gray-700">Nombre de la etapa</label>
            <Input
              value={etapa.nombre}
              onChange={(e) => actualizarEtapa(index, "nombre", e.target.value)}
            />
          </div>

          <div className="mb-4">
            <label className="text-sm font-medium text-gray-700">Responsable</label>
            <Input
              value={etapa.responsable}
              onChange={(e) => actualizarEtapa(index, "responsable", e.target.value)}
              placeholder="Ej: Obras Privadas"
            />
          </div>

          <div className="mb-4">
            <label className="text-sm font-medium text-gray-700">Condiciones especiales</label>
            <Input
              value={etapa.condiciones}
              onChange={(e) => actualizarEtapa(index, "condiciones", e.target.value)}
            />
          </div>

          <div className="mb-4">
            <label className="text-sm font-medium text-gray-700">Documentación requerida</label>
            <Input
              value={etapa.documentos}
              onChange={(e) => actualizarEtapa(index, "documentos", e.target.value)}
            />
          </div>

          <div className="flex items-center mb-2 space-x-4">
            <label className="flex items-center space-x-2 text-sm text-gray-700">
              <input
                type="checkbox"
                checked={etapa.puedeVolver}
                onChange={(e) => actualizarEtapa(index, "puedeVolver", e.target.checked)}
              />
              <span>Permitir volver a otra etapa</span>
            </label>

            {etapa.puedeVolver && (
              <Select
                value={etapa.volverA}
                onChange={(e) => actualizarEtapa(index, "volverA", e.target.value)}
              >
                <option value="">Seleccionar etapa</option>
                {etapas.map((et, i) =>
                  i < index ? (
                    <option key={i} value={et.nombre}>
                      {et.nombre || `Etapa ${i + 1}`}
                    </option>
                  ) : null
                )}
              </Select>
            )}
          </div>

          <div className="mb-4">
            <label className="flex items-center space-x-2 text-sm text-gray-700">
              <input
                type="checkbox"
                checked={etapa.simultanea}
                onChange={(e) => actualizarEtapa(index, "simultanea", e.target.checked)}
              />
              <span>Etapa simultánea</span>
            </label>
          </div>

          <Button variant="destructive" onClick={() => eliminarEtapa(index)}>
            Eliminar etapa
          </Button>
        </Card>
      ))}

      <Button onClick={agregarEtapa} className="bg-[#1f706e]">
        + Agregar etapa
      </Button>
    </div>
  );
}
