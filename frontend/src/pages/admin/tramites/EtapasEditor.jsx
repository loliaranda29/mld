import React, { useState } from "react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import Card from "@/components/ui/card";


const EtapasEditor = ({ etapas, setEtapas }) => {
  const agregarEtapa = () => {
    const nuevaEtapa = {
      titulo: "",
      descripcion: "",
      responsables: [""],
    };
    setEtapas([...etapas, nuevaEtapa]);
  };

  const actualizarCampo = (index, campo, valor) => {
    const nuevasEtapas = [...etapas];
    nuevasEtapas[index][campo] = valor;
    setEtapas(nuevasEtapas);
  };

  const actualizarResponsable = (i, j, valor) => {
    const nuevasEtapas = [...etapas];
    nuevasEtapas[i].responsables[j] = valor;
    setEtapas(nuevasEtapas);
  };

  const agregarResponsable = (index) => {
    const nuevasEtapas = [...etapas];
    nuevasEtapas[index].responsables.push("");
    setEtapas(nuevasEtapas);
  };

  const eliminarEtapa = (index) => {
    const nuevasEtapas = etapas.filter((_, i) => i !== index);
    setEtapas(nuevasEtapas);
  };

  return (
    <div className="space-y-4">
      <h3 className="text-lg font-bold">Etapas del trámite</h3>
      {etapas.map((etapa, i) => (
        <Card key={i} className="p-4 space-y-2">
          <Input
            placeholder="Título de la etapa"
            value={etapa.titulo}
            onChange={(e) => actualizarCampo(i, "titulo", e.target.value)}
          />
          <Input
            placeholder="Descripción"
            value={etapa.descripcion}
            onChange={(e) => actualizarCampo(i, "descripcion", e.target.value)}
          />

          <div className="space-y-1">
            {etapa.responsables.map((resp, j) => (
              <Input
                key={j}
                placeholder={`Responsable ${j + 1}`}
                value={resp}
                onChange={(e) => actualizarResponsable(i, j, e.target.value)}
              />
            ))}
            <Button variant="outline" onClick={() => agregarResponsable(i)}>
              + Agregar responsable
            </Button>
          </div>

          <Button variant="destructive" onClick={() => eliminarEtapa(i)}>
            Eliminar etapa
          </Button>
        </Card>
      ))}

      <Button onClick={agregarEtapa}>+ Agregar etapa</Button>
    </div>
  );
};

export default EtapasEditor;
