"use client";

import { useState } from "react";
import { usePage, router, Link } from "@inertiajs/react";
import {
    Search,
    UserPlus,
    ShieldCheck,
    ChevronRight,
    Users,
    X,
} from "lucide-react";
import CiudadanoLayout from "../Ciudadano";

export default function Representantes() {
    const { props } = usePage();
    const { representadoPor = [] } = props;

    const [cuit, setCuit] = useState("");
    const [result, setResult] = useState(null);
    const [loading, setLoading] = useState(false);
    const [activeTab, setActiveTab] = useState("apoderados");
    const [showForm, setShowForm] = useState(false);
    const [formData, setFormData] = useState({
        opcionRadio: "",
        opcionCheckbox: false,
        archivo: null,
        fecha: "",
    });

    // Datos de ejemplo
    const apoderados = [
        {
            id: 1,
            nombre: "Daiana Lucero",
            cuit: "27-43418342-7",
            vencimiento: "2025-12-31",
            estatus: "activo",
        },
        {
            id: 2,
            nombre: "Martin Bueno",
            cuit: "20-42914004-0",
            vencimiento: "2024-06-30",
            estatus: "pendiente",
        },
        {
            id: 3,
            nombre: "Ana Pérez",
            cuit: "23-12345678-9",
            vencimiento: "2026-03-15",
            estatus: "activo",
        },
        {
            id: 4,
            nombre: "Juan López",
            cuit: "20-87654321-0",
            vencimiento: "2024-11-10",
            estatus: "rechazado",
        },
    ];

    // Modal estado
    const [modalOpen, setModalOpen] = useState(false);
    const [modalData, setModalData] = useState(null);

    // 🔍 Búsqueda simulada por CUIT
    const handleSearch = (e) => {
        e.preventDefault();
        if (!cuit) return;

        setLoading(true);
        setTimeout(() => {
            const personasSimuladas = [
                { cuit: "27434183427", nombre: "Daiana Lucero" },
                { cuit: "20429140014", nombre: "Martin Bueno" },
            ];

            const encontrado = personasSimuladas.find((p) => p.cuit === cuit);
            setResult(encontrado || null);
            setLoading(false);
            setShowForm(false);
        }, 500);
    };

    // Seleccionar persona y mostrar formulario
    const handleSeleccionar = () => setShowForm(true);

    // Cambios en formulario
    const handleChange = (e) => {
        const { name, value, type, checked, files } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]:
                type === "checkbox"
                    ? checked
                    : type === "file"
                    ? files[0]
                    : value,
        }));
    };

    // Submit formulario
    const handleSubmitForm = (e) => {
        e.preventDefault();
        console.log("Datos del formulario:", formData);
        alert("Formulario enviado! Revisa consola.");
    };

    // Abrir modal de detalle
    const openModal = (apoderado) => {
        setModalData(apoderado);
        setModalOpen(true);
    };

    return (
        <CiudadanoLayout>
            <div className="bg-gradient-to-br from-slate-50 via-sky-50/30 to-blue-50/20 rounded-xl p-4 sm:p-6 space-y-6">
                <div className="bg-white border border-gray-200 shadow-sm rounded-xl p-4 sm:p-6">
                    {/* HEADER */}
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div>
                            <h2 className="text-xl sm:text-2xl font-bold text-[#176261]">
                                Apoderados
                            </h2>
                            <p className="text-[#298e8c]/80 text-sm">
                                Designá personas que puedan representarte o
                                consultá quién te ha apoderado.
                            </p>
                        </div>
                    </div>

                    {/* TABS */}
                    <div className="flex gap-3 mb-6 border-b border-gray-200">
                        <button
                            onClick={() => setActiveTab("apoderados")}
                            className={`px-4 py-2 text-sm font-semibold transition-all rounded-t-lg ${
                                activeTab === "apoderados"
                                    ? "bg-[#2ba29f]/10 text-[#176261] border-b-2 border-[#2ba29f]"
                                    : "text-[#298e8c]/70 hover:text-[#176261]"
                            }`}
                        >
                            Mis Apoderados
                        </button>
                        <button
                            onClick={() => setActiveTab("representadoPor")}
                            className={`px-4 py-2 text-sm font-semibold transition-all rounded-t-lg ${
                                activeTab === "representadoPor"
                                    ? "bg-[#2ba29f]/10 text-[#176261] border-b-2 border-[#2ba29f]"
                                    : "text-[#298e8c]/70 hover:text-[#176261]"
                            }`}
                        >
                            Personas que me Apoderaron
                        </button>
                    </div>

                    {/* TAB 1: MIS APODERADOS */}
                    {activeTab === "apoderados" && (
                        <div>
                            {/* FORMULARIO DE BÚSQUEDA */}
                            <form
                                onSubmit={handleSearch}
                                className="relative mb-4"
                            >
                                <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-[#298e8c]/60 w-5 h-5" />
                                <input
                                    type="text"
                                    value={cuit}
                                    onChange={(e) => setCuit(e.target.value)}
                                    placeholder="Ingresá el CUIT de la persona..."
                                    className="w-full ps-10 pe-28 py-2.5 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2ba29f] text-sm text-[#176261]"
                                />
                                <button
                                    type="submit"
                                    className="absolute right-2 top-1/2 -translate-y-1/2 bg-[#2ba29f] hover:bg-[#298e8c] text-white px-4 py-1.5 rounded-md text-sm transition-all"
                                >
                                    {loading ? "Buscando..." : "Buscar"}
                                </button>
                            </form>

                            {/* RESULTADO DE BÚSQUEDA */}
                            {result && !showForm && (
                                <div className="border border-gray-200 rounded-lg p-4 bg-white shadow-sm mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                                    {/* Circulo con iniciales */}
                                    <div className="w-12 h-12 rounded-full bg-[#2ba29f] flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                        {result.nombre
                                            .split(" ")
                                            .map((n) => n[0])
                                            .join("")
                                            .toUpperCase()}
                                    </div>

                                    {/* Info de la persona */}
                                    <div className="flex-1">
                                        <p className="text-sm text-[#298e8c]/70">
                                            Resultado:
                                        </p>
                                        <h3 className="text-lg font-semibold text-[#176261]">
                                            {result.nombre}
                                        </h3>
                                        <p className="text-sm text-[#176261]/70 mb-3">
                                            CUIT: {result.cuit}
                                        </p>
                                    </div>

                                    {/* Botón seleccionar */}
                                    <div className="w-full sm:w-auto flex justify-end">
                                        <button
                                            onClick={handleSeleccionar}
                                            className="inline-flex items-center gap-2 bg-[#2ba29f] text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-[#298e8c] transition text-sm"
                                        >
                                            <UserPlus className="w-4 h-4" />{" "}
                                            Seleccionar
                                        </button>
                                    </div>
                                </div>
                            )}

                            {/* FORMULARIO A COMPLETAR */}
                            {showForm && result && (
                                <form
                                    onSubmit={handleSubmitForm}
                                    className="border border-gray-200 rounded-lg p-4 bg-white shadow-sm mb-4 space-y-4"
                                >
                                    <h3 className="text-lg font-semibold text-[#176261] mb-2">
                                        Adjuntar poder
                                    </h3>
                                    {/* Archivo */}
                                    <div>
                                        <label className="block text-sm text-[#176261]/80 mb-1">
                                            Adjuntar archivo:
                                        </label>
                                        <div className="relative w-full">
                                            <button
                                                type="button"
                                                onClick={() =>
                                                    document
                                                        .getElementById(
                                                            "archivoInput"
                                                        )
                                                        .click()
                                                }
                                                className="w-full sm:w-auto bg-[#2ba29f] hover:bg-[#298e8c] text-white px-4 py-2 rounded-lg text-sm font-medium transition flex items-center justify-center gap-2"
                                            >
                                                Subir Archivo
                                            </button>
                                            <input
                                                type="file"
                                                id="archivoInput"
                                                name="archivo"
                                                onChange={handleChange}
                                                className="hidden"
                                            />
                                        </div>
                                        <p className="text-xs text-[#298e8c]/70 mt-1">
                                            Cargar documento legible, a color,
                                            en formato PDF, no mayor a 50 MB
                                        </p>
                                    </div>

                                    <hr className="my-2 border-gray-200" />

                                    <h3 className="text-lg font-semibold text-[#176261] mb-2">
                                        Permisos
                                    </h3>
                                    <div className="space-y-2">
                                        <p className="text-sm text-[#176261]/80 mb-1">
                                            Selecciona la opción
                                            correspondiente:
                                        </p>
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                name="opcionRadio"
                                                value="si"
                                                checked={
                                                    formData.opcionRadio ===
                                                    "si"
                                                }
                                                onChange={handleChange}
                                            />
                                            {result.nombre} podrá actuar
                                            legalmente para realizar todos los
                                            trámites
                                        </label>
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                name="opcionRadio"
                                                value="no"
                                                checked={
                                                    formData.opcionRadio ===
                                                    "no"
                                                }
                                                onChange={handleChange}
                                            />
                                            Especificar los trámites que{" "}
                                            {result.nombre} podrá realizar
                                        </label>
                                    </div>

                                    <div>
                                        <label className="flex items-center gap-2">
                                            <input
                                                type="checkbox"
                                                name="opcionCheckbox"
                                                checked={
                                                    formData.opcionCheckbox
                                                }
                                                onChange={handleChange}
                                            />
                                            Puede modificar mis datos
                                        </label>
                                    </div>

                                    <div>
                                        <label className="block text-sm text-[#176261]/80 mb-1">
                                            Vigencia del poder:
                                        </label>
                                        <input
                                            type="date"
                                            name="fecha"
                                            value={formData.fecha}
                                            onChange={handleChange}
                                            className="rounded-lg border border-gray-200 px-2 py-1 text-sm w-full sm:w-1/2"
                                        />
                                    </div>

                                    <button
                                        type="submit"
                                        className="bg-[#2ba29f] hover:bg-[#298e8c] text-white px-4 py-2 rounded-lg text-sm font-medium transition"
                                    >
                                        Enviar
                                    </button>
                                </form>
                            )}

                            {/* TABLA de apoderados si NO se ve el formulario */}
                            {!showForm && (
                                <div className="overflow-x-auto mt-4">
                                    <table className="min-w-full border border-gray-200 rounded-lg">
                                        <thead className="bg-[#2ba29f]/20">
                                            <tr>
                                                <th className="px-4 py-2 text-left text-sm font-semibold text-[#176261]">
                                                    Nombre
                                                </th>
                                                <th className="px-4 py-2 text-left text-sm font-semibold text-[#176261]">
                                                    CUIT
                                                </th>
                                                <th className="px-4 py-2 text-left text-sm font-semibold text-[#176261]">
                                                    Vencimiento
                                                </th>
                                                <th className="px-4 py-2 text-left text-sm font-semibold text-[#176261]">
                                                    Estatus
                                                </th>
                                                <th className="px-4 py-2 text-center text-sm font-semibold text-[#176261]">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="bg-white divide-y divide-gray-200">
                                            {apoderados.length ? (
                                                apoderados.map((p) => (
                                                    <tr
                                                        key={p.id}
                                                        className="hover:bg-gray-50 transition"
                                                    >
                                                        <td className="px-4 py-2 text-sm text-[#176261] font-medium">
                                                            {p.nombre}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm text-[#298e8c]/80">
                                                            {p.cuit}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm text-[#298e8c]/80">
                                                            {p.vencimiento ||
                                                                "-"}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm">
                                                            <span
                                                                className={`px-2 py-1 rounded-full text-xs font-semibold ${
                                                                    p.estatus ===
                                                                    "activo"
                                                                        ? "bg-green-100 text-green-800"
                                                                        : p.estatus ===
                                                                          "pendiente"
                                                                        ? "bg-yellow-100 text-yellow-600"
                                                                        : "bg-red-100 text-red-800"
                                                                }`}
                                                            >
                                                                {p.estatus ||
                                                                    "pendiente"}
                                                            </span>
                                                        </td>
                                                        <td className="px-4 py-2 text-center">
                                                            <button
                                                                onClick={() =>
                                                                    openModal(p)
                                                                }
                                                                className="bg-[#2ba29f] hover:bg-[#298e8c] text-white px-3 py-1 rounded-lg text-xs font-medium transition"
                                                            >
                                                                Ver detalle
                                                            </button>
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td
                                                        colSpan={5}
                                                        className="px-4 py-4 text-center text-sm text-[#298e8c]/70 italic"
                                                    >
                                                        No designaste a ningún
                                                        apoderado aún.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            )}

                            {/* MODAL */}
                            {modalOpen && modalData && (
                                <Modal
                                    modalData={modalData}
                                    setModalOpen={setModalOpen}
                                />
                            )}
                        </div>
                    )}

                    {/* TAB 2: Personas que me apoderaron */}
                    {activeTab === "representadoPor" && (
                        <div>
                            <h3 className="text-lg font-semibold text-[#176261] mb-3 flex items-center gap-2">
                                <Users className="w-5 h-5 text-[#298e8c]" />
                                Personas que me apoderaron
                            </h3>

                            {apoderados.length ? (
                                <div className="overflow-x-auto">
                                    <table className="w-full border border-gray-200 rounded-lg bg-white">
                                        <thead className="bg-[#f0fdfa] text-[#176261] text-sm font-semibold">
                                            <tr>
                                                <th className="px-4 py-2 text-left">
                                                    Nombre
                                                </th>
                                                <th className="px-4 py-2 text-left">
                                                    CUIT
                                                </th>
                                                <th className="px-4 py-2 text-left">
                                                    Vencimiento
                                                </th>
                                                <th className="px-4 py-2 text-left">
                                                    Estatus
                                                </th>
                                                <th className="px-4 py-2 text-left">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody className="text-sm text-[#176261]">
                                            {apoderados.map((p) => (
                                                <tr
                                                    key={p.id}
                                                    className="border-t border-gray-200 hover:bg-gray-50 transition"
                                                >
                                                    <td className="px-4 py-2">
                                                        {p.nombre}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        {p.cuit}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        {p.vencimiento || "-"}
                                                    </td>
                                                    <td
                                                        className={`px-4 py-2 font-semibold ${
                                                            p.estatus ===
                                                            "activo"
                                                                ? "text-green-600"
                                                                : "text-yellow-600"
                                                        }`}
                                                    >
                                                        {p.estatus ||
                                                            "pendiente"}
                                                    </td>
                                                    <td className="px-4 py-2">
                                                        <button
                                                            onClick={() => {
                                                                setModalData(p);
                                                                setModalOpen(
                                                                    true
                                                                );
                                                            }}
                                                            className="bg-[#2ba29f] hover:bg-[#298e8c] text-white px-3 py-1 rounded-lg text-xs font-medium transition"
                                                        >
                                                            Ver detalle
                                                        </button>
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            ) : (
                                <p className="text-sm text-[#298e8c]/70 italic">
                                    Nadie te ha apoderado todavía.
                                </p>
                            )}
                        </div>
                    )}
                    {/* MODAL */}
                    {modalOpen && modalData && (
                        <Modal
                            modalData={modalData}
                            setModalOpen={setModalOpen}
                        />
                    )}
                </div>
            </div>
        </CiudadanoLayout>
    );
}

const Modal = ({ modalData, setModalOpen }) => {
    return (
        <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
            <div className="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md relative animate-fadeIn">
                {/* Botón cerrar */}
                <button
                    className="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition"
                    onClick={() => setModalOpen(false)}
                >
                    <X className="w-6 h-6" />
                </button>

                {/* Header con iniciales */}
                <div className="flex items-center gap-4 mb-4">
                    <div className="w-14 h-14 rounded-full bg-[#2ba29f] flex items-center justify-center text-white font-bold text-xl">
                        {modalData.nombre
                            .split(" ")
                            .map((n) => n[0])
                            .join("")
                            .toUpperCase()}
                    </div>
                    <h3 className="text-xl font-semibold text-[#176261]">
                        {modalData.nombre}
                    </h3>
                </div>

                <hr className="border-gray-200 mb-4" />

                {/* Contenido del modal */}
                <div className="space-y-3 text-sm text-[#176261]">
                    <div className="flex justify-between">
                        <span className="font-medium">CUIT:</span>
                        <span>{modalData.cuit}</span>
                    </div>
                    <div className="flex justify-between">
                        <span className="font-medium">Vencimiento:</span>
                        <span>{modalData.vencimiento || "-"}</span>
                    </div>
                    <div className="flex justify-between">
                        <span className="font-medium">Estatus:</span>
                        <span
                            className={`font-semibold ${
                                modalData.estatus === "activo"
                                    ? "text-green-600"
                                    : "text-yellow-600"
                            }`}
                        >
                            {modalData.estatus || "pendiente"}
                        </span>
                    </div>
                </div>

                {/* Footer con botón */}
                <div className="mt-6 flex justify-end">
                    <button
                        className="bg-[#2ba29f] hover:bg-[#298e8c] text-white px-4 py-2 rounded-lg font-medium transition"
                        onClick={() => setModalOpen(false)}
                    >
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    );
};
