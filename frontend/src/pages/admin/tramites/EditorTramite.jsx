import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import Layout from "../../../components/layout/Layout";
import PasoTramite from "./PasoTramite";
import EtapasEditor from "./EtapasEditor";
import ConfirmModal from "../../../components/ui/ConfirmModal";
import { useToast } from "../../../context/ToastContext";

export default function EditorTramite() {
  const [tab, setTab] = useState("pasos");
  const [nombre, setNombre] = useState("");
  const [descripcion, setDescripcion] = useState("");
  const [pasos, setPasos] = useState([]);
  const [etapas, setEtapas] = useState([]);
  const [etapaInicial, setEtapaInicial] = useState(null);
  const [showModal, setShowModal] = useState(false);

  const navigate = useNavigate();
  const { showToast } = useToast();

  const agregarPaso = () => {
    const nuevoPaso = {
      numero: pasos.length + 1,
      nombre: "",
      instrucciones: "",
      campos: [],
    };
    setPasos([...pasos, nuevoPaso]);
  };

  const actualizarPaso = (index, pasoActualizado) => {
    const nuevosPasos = [...pasos];
    nuevosPasos[index] = pasoActualizado;
    setPasos(nuevosPasos);
  };

  const eliminarPaso = (index) => {
    const nuevosPasos = pasos
      .filter((_, i) => i !== index)
      .map((p, i) => ({ ...p, numero: i + 1 }));
    setPasos(nuevosPasos);
  };

  const handleGuardar = async () => {
    const tramite = {
      nombre,
      descripcion,
      pasos,
      etapas,
      etapaInicial
    };

    try {
      const res = await fetch("https://tu-api.com/api/tramites", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(tramite),
      });

      if (!res.ok) throw new Error("Error al guardar");

      showToast("success", "Trámite guardado correctamente");
      setTimeout(() => {
        navigate("/admin/tramites");
      }, 2000);
    } catch (err) {
      console.error(err);
      showToast("error", "Ocurrió un error al guardar el trámite");
    }
  };

  return (
    <Layout>
      <div className="p-6 max-w-5xl mx-auto">
        <h1 className="text-2xl font-bold text-gray-800 mb-6">Nuevo Trámite</h1>

        <div className="space-y-6">
          {/* Nombre y descripción */}
          <div>
            <label className="block text-sm font-medium text-gray-700">Nombre del Trámite</label>
            <input
              type="text"
              value={nombre}
              onChange={(e) => setNombre(e.target.value)}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700">Descripción</label>
            <textarea
              value={descripcion}
              onChange={(e) => setDescripcion(e.target.value)}
              className="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-4 py-2 focus:ring-[#248B89] focus:border-[#248B89]"
              rows={3}
            ></textarea>
          </div>

          {/* Tabs */}
          <div className="flex space-x-4 border-b pb-2">
            <button
              onClick={() => setTab("pasos")}
              className={`px-4 py-2 text-sm font-medium border-b-2 transition ${
                tab === "pasos"
                  ? "border-[#248B89] text-[#248B89]"
                  : "border-transparent text-gray-600 hover:text-[#248B89]"
              }`}
            >
              Pasos y Campos
            </button>
            <button
              onClick={() => setTab("etapas")}
              className={`px-4 py-2 text-sm font-medium border-b-2 transition ${
                tab === "etapas"
                  ? "border-[#248B89] text-[#248B89]"
                  : "border-transparent text-gray-600 hover:text-[#248B89]"
              }`}
            >
              Etapas del Trámite
            </button>
          </div>

          {/* Contenido de tabs */}
          {tab === "pasos" && (
            <div>
              {pasos.map((paso, index) => (
                <PasoTramite
                  key={index}
                  paso={paso}
                  onChange={(actualizado) => actualizarPaso(index, actualizado)}
                  onEliminar={() => eliminarPaso(index)}
                />
              ))}
              <button
                onClick={agregarPaso}
                className="mt-4 px-4 py-2 border border-[#248B89] text-[#248B89] rounded-md font-semibold hover:bg-[#e7f1f0]"
              >
                + Agregar Paso
              </button>
            </div>
          )}

          {tab === "etapas" && (
            <EtapasEditor etapas={etapas} setEtapas={setEtapas} etapaInicial={etapaInicial} setEtapaInicial={setEtapaInicial} />
          )}

          {/* Botón Guardar */}
          <div className="pt-6">
            <button
              onClick={() => setShowModal(true)}
              className="bg-[#248B89] text-white px-6 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
            >
              Guardar Trámite
            </button>
          </div>
        </div>

        {/* Modal de Confirmación */}
        <ConfirmModal
          open={showModal}
          onCancel={() => setShowModal(false)}
          onConfirm={() => {
            setShowModal(false);
            handleGuardar();
          }}
          title="Confirmar guardado"
          message="¿Estás seguro de que querés guardar este trámite?"
        />
      </div>
    </Layout>
  );
}
