"use client";

import { useState } from "react";
import { usePage } from "@inertiajs/react";
import { Users, X, Search } from "lucide-react";
import CiudadanoLayout from "../Ciudadano";

export default function Empresas() {
    const { props } = usePage();
    const { gestionadas = [] } = props;

    const [activeTab, setActiveTab] = useState("misEmpresas");
    const [modalOpen, setModalOpen] = useState(false);
    const [modalData, setModalData] = useState(null);
    const [showForm, setShowForm] = useState(false);

    // Datos de ejemplo
    const misEmpresas = [
        {
            id: 1,
            nombre: "Tech Solutions S.A.",
            cuit: "30-12345678-9",
            vencimiento: "2025-12-31",
            estatus: "activo",
        },
        {
            id: 2,
            nombre: "Alimentos Orgánicos S.R.L.",
            cuit: "27-87654321-0",
            vencimiento: "2024-06-30",
            estatus: "pendiente",
        },
        {
            id: 3,
            nombre: "Distribuidora Central S.A.",
            cuit: "23-11223344-5",
            vencimiento: "2026-03-15",
            estatus: "activo",
        },
    ];

    const openModal = (empresa) => {
        setModalData(empresa);
        setModalOpen(true);
    };

    const [formData, setFormData] = useState({
        razonSocial: "",
        cuit: "",
        telefono: "",
        correo: "",
        fechaInicio: "",
        constanciaIIBB: null,
        constanciaARCA: null,
        direccion: {
            calle: "",
            numero: "",
            depto: "",
            cp: "",
            provincia: "",
            localidad: "",
            referencia: "",
        },
        actaConstitutiva: {
            fecha: "",
            numero: "",
            archivo: null,
            fechaModificacion: "",
            modificacionArchivo: null,
            fechaAsamblea: "",
            actaAsamblea: null,
        },
        representante: {
            cuit: "",
            nombres: "",
            apellido: "",
            telefono: "",
            correo: "",
            poder: null,
        },
    });

    const handleChange = (e) => {
        const { name, value, type, files, dataset } = e.target;
        if (dataset.section) {
            // Para sub-objetos (direccion, actaConstitutiva, representante)
            setFormData((prev) => ({
                ...prev,
                [dataset.section]: {
                    ...prev[dataset.section],
                    [name]: type === "file" ? files[0] : value,
                },
            }));
        } else {
            setFormData((prev) => ({
                ...prev,
                [name]: type === "file" ? files[0] : value,
            }));
        }
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        console.log("Datos del formulario:", formData);
        onSubmit?.(formData);
    };

    return (
        <CiudadanoLayout>
            <div className="bg-gradient-to-br from-slate-50 via-sky-50/30 to-blue-50/20 rounded-xl p-4 sm:p-6 space-y-6">
                <div className="bg-white border border-gray-200 shadow-sm rounded-xl p-4 sm:p-6">
                    {/* HEADER */}
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div>
                            <h2 className="text-xl sm:text-2xl font-bold text-[#176261]">
                                Empresas
                            </h2>
                            <p className="text-[#298e8c]/80 text-sm">
                                Gestioná tus empresas o consultá cuáles estás
                                administrando.
                            </p>
                        </div>
                    </div>

                    {showForm === true ? (
                        <>
                            <button
                                type="button"
                                onClick={() => setShowForm(false)}
                                className="bg-[#2ba29f] hover:bg-[#298e8c] text-white font-medium px-4 py-2 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2 mb-1"
                            >
                                Ver tablas
                            </button>

                            <form
                                onSubmit={handleSubmit}
                                className="bg-white p-6 rounded-xl shadow-sm space-y-6"
                            >
                                <h2 className="text-2xl font-semibold text-[#176261] mb-6">
                                    Registrar Empresa
                                </h2>
                                <p className="text-gray-700 text-base sm:text-lg leading-relaxed md:leading-loose mt-4">
                                    Con este trámite podrás registrar tu empresa
                                    en la plataforma y utilizarla como un perfil
                                    alterno para realizar los trámites que
                                    necesites en nombre de dicha empresa.
                                    <br />
                                    La empresa debe ser registrada por única vez
                                    y debe hacerlo un representante legal de la
                                    misma.
                                    <br />
                                    Una vez registrada, el representante puede
                                    añadir a otros que puedan realizar
                                    solicitudes en nombre de la persona
                                    jurídica.
                                </p>

                                {/* Sección: Datos de la persona jurídica */}
                                <div className="space-y-4">
                                    <h3 className="flex items-center gap-2 text-lg font-semibold text-[#ffffff] bg-gradient-to-r from-[#2ba29f] to-[#1f8275] px-4 py-2 rounded-lg shadow-md">
                                        Datos de la persona jurídica
                                    </h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {[
                                            {
                                                label: "Denominación o razón social de la persona jurídica *",
                                                name: "razonSocial",
                                                type: "text",
                                                help: "Anotar el nombre completo, oficial y legal de la persona jurídica, exactamente como aparece en el acta constitutiva, incluyendo el tipo de sociedad.",
                                            },
                                            {
                                                label: "CUIT de la persona jurídica *",
                                                name: "cuit",
                                                type: "text",
                                                help: "De la persona jurídica a registrar",
                                            },
                                            {
                                                label: "Teléfono *",
                                                name: "telefono",
                                                type: "tel",
                                                help: "De la persona jurídica a registrar",
                                            },
                                            {
                                                label: "Correo electrónico *",
                                                name: "correo",
                                                type: "email",
                                                help: "De la persona jurídica a registrar",
                                            },
                                            {
                                                label: "Fecha de inicio de actividades *",
                                                name: "fechaInicio",
                                                type: "date",
                                                help: "Establecida en la constancia de situación fiscal",
                                            },
                                            {
                                                label: "Constancia inscripción IIBB",
                                                name: "constanciaIIBB",
                                                type: "file",
                                                help: "Constancia IIBB de la persona jurídica",
                                            },
                                            {
                                                label: "Constancia inscripción ARCA *",
                                                name: "constanciaARCA",
                                                type: "file",
                                                help: "Constancia ARCA de la persona jurídica",
                                            },
                                        ].map((field) => (
                                            <div key={field.name}>
                                                <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1">
                                                    {field.label}
                                                </label>
                                                <input
                                                    type={field.type}
                                                    name={field.name}
                                                    value={
                                                        field.type !== "file"
                                                            ? formData[
                                                                  field.name
                                                              ]
                                                            : undefined
                                                    }
                                                    onChange={handleChange}
                                                    className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                                    required={field.label.includes(
                                                        "*"
                                                    )}
                                                />
                                                <p className="text-xs text-gray-500 mt-1">
                                                    {field.help}
                                                </p>
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* Sección: Dirección */}
                                <div className="space-y-4">
                                    <h3 className="flex items-center gap-2 text-lg font-semibold text-[#ffffff] bg-gradient-to-r from-[#2ba29f] to-[#1f8275] px-4 py-2 rounded-lg shadow-md">
                                        Dirección de la empresa
                                    </h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {[
                                            "calle",
                                            "numero",
                                            "depto",
                                            "cp",
                                            "provincia",
                                            "localidad",
                                            "referencia",
                                        ].map((campo) => (
                                            <div key={campo}>
                                                <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1 capitalize">
                                                    {campo}
                                                </label>
                                                <input
                                                    type="text"
                                                    name={campo}
                                                    data-section="direccion"
                                                    value={
                                                        formData.direccion[
                                                            campo
                                                        ]
                                                    }
                                                    onChange={handleChange}
                                                    className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                {/* Sección: Acta constitutiva */}
                                <div className="space-y-4">
                                    <h3 className="flex items-center gap-2 text-lg font-semibold text-[#ffffff] bg-gradient-to-r from-[#2ba29f] to-[#1f8275] px-4 py-2 rounded-lg shadow-md">
                                        Acta constitutiva
                                    </h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1">
                                                Fecha de constitución
                                            </label>
                                            <input
                                                type="date"
                                                name="fecha"
                                                data-section="actaConstitutiva"
                                                value={
                                                    formData.actaConstitutiva
                                                        .fecha
                                                }
                                                onChange={handleChange}
                                                className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1">
                                                Número de acta
                                            </label>
                                            <input
                                                type="text"
                                                name="numero"
                                                data-section="actaConstitutiva"
                                                value={
                                                    formData.actaConstitutiva
                                                        .numero
                                                }
                                                onChange={handleChange}
                                                className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                            />
                                        </div>
                                        <div>
                                            <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1">
                                                Acta constitutiva (PDF)
                                            </label>
                                            <input
                                                type="file"
                                                name="archivo"
                                                data-section="actaConstitutiva"
                                                onChange={handleChange}
                                                className="w-full text-sm"
                                            />
                                        </div>
                                    </div>
                                </div>

                                {/* Sección: Representante legal */}
                                <div className="space-y-4">
                                    <h3 className="flex items-center gap-2 text-lg font-semibold text-[#ffffff] bg-gradient-to-r from-[#2ba29f] to-[#1f8275] px-4 py-2 rounded-lg shadow-md">
                                        Representante legal
                                    </h3>
                                    <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {[
                                            { name: "cuit", type: "text" },
                                            { name: "nombres", type: "text" },
                                            { name: "apellido", type: "text" },
                                            { name: "telefono", type: "tel" },
                                            { name: "correo", type: "email" },
                                            { name: "poder", type: "file" },
                                        ].map((field) => (
                                            <div key={field.name}>
                                                <label className="block text-sm font-medium text-[#176261] bg-gray-100 px-2 py-1 rounded-md mb-1 capitalize">
                                                    {field.name}
                                                </label>
                                                <input
                                                    type={field.type}
                                                    name={field.name}
                                                    data-section="representante"
                                                    value={
                                                        field.type !== "file"
                                                            ? formData
                                                                  .representante[
                                                                  field.name
                                                              ]
                                                            : undefined
                                                    }
                                                    onChange={handleChange}
                                                    className="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm"
                                                    required={
                                                        field.type !== "file"
                                                    }
                                                />
                                            </div>
                                        ))}
                                    </div>
                                </div>

                                <button
                                    type="submit"
                                    className="bg-[#2ba29f] hover:bg-[#298e8c] text-white px-6 py-2 rounded-lg font-medium transition"
                                >
                                    Registrar empresa
                                </button>
                            </form>
                        </>
                    ) : (
                        <>
                            <button
                                type="button"
                                onClick={() => setShowForm(true)}
                                className="bg-[#2ba29f] hover:bg-[#298e8c] text-white font-medium px-4 py-2 rounded-lg shadow-md transition-all duration-200 flex items-center gap-2 mb-4"
                            >
                                <span>➕</span> Registrar nueva empresa
                            </button>
                            {/* TABS */}
                            <div className="flex gap-3 mb-6 border-b border-gray-200">
                                <button
                                    onClick={() => setActiveTab("misEmpresas")}
                                    className={`px-4 py-2 text-sm font-semibold transition-all rounded-t-lg ${
                                        activeTab === "misEmpresas"
                                            ? "bg-[#2ba29f]/10 text-[#176261] border-b-2 border-[#2ba29f]"
                                            : "text-[#298e8c]/70 hover:text-[#176261]"
                                    }`}
                                >
                                    Mis Empresas
                                </button>
                                <button
                                    onClick={() => setActiveTab("gestionadas")}
                                    className={`px-4 py-2 text-sm font-semibold transition-all rounded-t-lg ${
                                        activeTab === "gestionadas"
                                            ? "bg-[#2ba29f]/10 text-[#176261] border-b-2 border-[#2ba29f]"
                                            : "text-[#298e8c]/70 hover:text-[#176261]"
                                    }`}
                                >
                                    Empresas que Gestiono
                                </button>
                            </div>

                            {/* TAB 1: Mis Empresas */}
                            {activeTab === "misEmpresas" && (
                                <div className="overflow-x-auto">
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
                                            {misEmpresas.length ? (
                                                misEmpresas.map((e) => (
                                                    <tr
                                                        key={e.id}
                                                        className="hover:bg-gray-50 transition"
                                                    >
                                                        <td className="px-4 py-2 text-sm text-[#176261] font-medium">
                                                            {e.nombre}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm text-[#298e8c]/80">
                                                            {e.cuit}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm text-[#298e8c]/80">
                                                            {e.vencimiento ||
                                                                "-"}
                                                        </td>
                                                        <td className="px-4 py-2 text-sm">
                                                            <span
                                                                className={`px-2 py-1 rounded-full text-xs font-semibold ${
                                                                    e.estatus ===
                                                                    "activo"
                                                                        ? "bg-green-100 text-green-800"
                                                                        : e.estatus ===
                                                                          "pendiente"
                                                                        ? "bg-yellow-100 text-yellow-600"
                                                                        : "bg-red-100 text-red-800"
                                                                }`}
                                                            >
                                                                {e.estatus ||
                                                                    "pendiente"}
                                                            </span>
                                                        </td>
                                                        <td className="px-4 py-2 text-center">
                                                            <button
                                                                onClick={() =>
                                                                    openModal(e)
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
                                                        No tenés empresas
                                                        registradas.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            )}

                            {/* TAB 2: Empresas que gestiono */}
                            {activeTab === "gestionadas" && (
                                <div className="overflow-x-auto">
                                    <table className="min-w-full border border-gray-200 rounded-lg">
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
                                            {gestionadas.length ? (
                                                gestionadas.map((e) => (
                                                    <tr
                                                        key={e.id}
                                                        className="border-t border-gray-200 hover:bg-gray-50 transition"
                                                    >
                                                        <td className="px-4 py-2">
                                                            {e.nombre}
                                                        </td>
                                                        <td className="px-4 py-2">
                                                            {e.cuit}
                                                        </td>
                                                        <td className="px-4 py-2">
                                                            {e.vencimiento ||
                                                                "-"}
                                                        </td>
                                                        <td
                                                            className={`px-4 py-2 font-semibold ${
                                                                e.estatus ===
                                                                "activo"
                                                                    ? "text-green-600"
                                                                    : "text-yellow-600"
                                                            }`}
                                                        >
                                                            {e.estatus ||
                                                                "pendiente"}
                                                        </td>
                                                        <td className="px-4 py-2">
                                                            <button
                                                                onClick={() =>
                                                                    openModal(e)
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
                                                        No gestionás ninguna
                                                        empresa.
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
                        </>
                    )}
                </div>
            </div>
        </CiudadanoLayout>
    );
}

const Modal = ({ modalData, setModalOpen }) => (
    <div className="fixed inset-0 bg-black/40 flex items-center justify-center z-50 p-4">
        <div className="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md relative animate-fadeIn">
            <button
                className="absolute top-3 right-3 text-gray-400 hover:text-gray-700 transition"
                onClick={() => setModalOpen(false)}
            >
                <X className="w-6 h-6" />
            </button>

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
