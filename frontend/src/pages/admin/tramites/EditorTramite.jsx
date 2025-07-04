import React from "react";
import { useNavigate } from "react-router-dom";
import { useState } from "react";
import Layout from "../../../components/layout/Layout";
import FormularioBuilder from "../../../features/tramites/components/FormularioBuilder";
import EtapasEditor from "../../../features/tramites/EtapasEditor";
import ConfirmModal from "../../../components/ui/ConfirmModal";
import { useToast } from "../../../context/ToastContext";

// Modal de vista previa
function VistaPreviaModal({ abierto, onClose, secciones }) {
  if (!abierto) return null;

  const CampoPreview = ({ campo }) => (
    <div className="mb-4">
      <label className="block text-sm font-semibold text-gray-700 mb-1">{campo.etiqueta}</label>
      {campo.tipo === "texto" && <input type="text" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === "numero" && <input type="number" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === "fecha" && <input type="date" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === "archivo" && <input type="file" className="border px-3 py-2 rounded w-full" />}
      {campo.tipo === "select" && (
        <select className="border px-3 py-2 rounded w-full">
          {(campo.opciones || []).map((opt, i) => (
            <option key={i} value={opt}>{opt}</option>
          ))}
        </select>
      )}
      {campo.pistaTexto && <p className="text-xs text-gray-500 mt-1">{campo.pistaTexto}</p>}
    </div>
  );

  return (
    <div className="fixed inset-0 bg-black bg-opacity-50 z-50">
      <div className="fixed inset-0 bg-white overflow-y-auto p-6">
        <div className="flex justify-between items-center mb-6">
          <h2 className="text-2xl font-bold">Vista previa del trámite</h2>
          <button onClick={onClose} className="text-red-500 font-semibold">✖️ Cerrar</button>
        </div>
        {secciones.length === 0 ? (
          <p className="text-gray-500">No hay secciones definidas.</p>
        ) : (
          secciones.map((seccion, idx) => (
            <div key={idx} className="mb-8">
              <h3 className="text-lg font-semibold text-[#248B89] mb-2">{seccion.titulo}</h3>
              <div className="bg-gray-50 p-4 rounded shadow-sm">
                {seccion.campos.map((campo) => (
                  <CampoPreview key={campo.id} campo={campo} />
                ))}
              </div>
            </div>
          ))
        )}
      </div>
    </div>
  );
}

export default function EditorTramite() {
  const [tab, setTab] = useState("formulario");
  const [nombre, setNombre] = useState("");
  const [descripcion, setDescripcion] = useState("");
  const [formulario, setFormulario] = useState([]);
  const [etapas, setEtapas] = useState([]);
  const [etapaInicial, setEtapaInicial] = useState(null);
  const [showModal, setShowModal] = useState(false);
  const [mostrarVistaPrevia, setMostrarVistaPrevia] = useState(false);

  const navigate = useNavigate();
  const { showToast } = useToast();

  const handleGuardar = async () => {
    const tramite = {
      nombre,
      descripcion,
      formulario,
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
              onClick={() => setTab("formulario")}
              className={`px-4 py-2 text-sm font-medium border-b-2 transition ${
                tab === "formulario"
                  ? "border-[#248B89] text-[#248B89]"
                  : "border-transparent text-gray-600 hover:text-[#248B89]"
              }`}
            >
              Formulario
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
          {tab === "formulario" && (
            <FormularioBuilder secciones={formulario} setSecciones={setFormulario} />
          )}

          {tab === "etapas" && (
            <EtapasEditor
              etapas={etapas}
              setEtapas={setEtapas}
              etapaInicial={etapaInicial}
              setEtapaInicial={setEtapaInicial}
            />
          )}

          {/* Botones */}
          <div className="pt-6 flex gap-4">
            <button
              onClick={() => setShowModal(true)}
              className="bg-[#248B89] text-white px-6 py-2 rounded-md font-semibold hover:bg-[#1f706e]"
            >
              Guardar Trámite
            </button>
            <button
              onClick={() => setMostrarVistaPrevia(true)}
              className="bg-gray-200 text-gray-800 px-6 py-2 rounded-md font-semibold hover:bg-gray-300"
            >
              Vista Previa
            </button>
          </div>
        </div>

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

        <VistaPreviaModal
          abierto={mostrarVistaPrevia}
          onClose={() => setMostrarVistaPrevia(false)}
          secciones={formulario}
        />
      </div>
    </Layout>
  );
}
