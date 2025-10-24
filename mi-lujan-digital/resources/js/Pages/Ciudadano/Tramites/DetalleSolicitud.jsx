"use client";

import { useState } from "react";
import CiudadanoLayout from "../Ciudadano";
import { BadgeCheck, FileText, MessageSquare } from "lucide-react";
import { usePage } from "@inertiajs/react";

export default function DetalleSolicitud() {
    const [activeTab, setActiveTab] = useState(0);
    const { props } = usePage();
    const { solicitud, schema } = props;
    const sections = Array.isArray(schema.sections) ? schema.sections : [];

    const fieldValue = (f) => {
        const v = f.value ?? null;
        const type = (f.type || "text").toLowerCase();
        if (type === "checkbox" && f.multiple)
            return Array.isArray(v) ? v : v ? [v] : [];
        return v;
    };

    const dash = (v) => {
        if (Array.isArray(v))
            return v.length ? JSON.stringify(v, null, 0) : "—";
        if (v === null || v === "") return "—";
        return String(v);
    };

    const paneId = (i) => `pane-${i + 1}`;

    // Etapas
    let etapas = [];
    try {
        etapas = JSON.parse(solicitud.tramite?.etapas_json ?? "[]") || [];
    } catch (e) {
        etapas = [];
    }
    const totalEtapas = etapas.length;
    const estado = (solicitud.estado || "").toLowerCase();
    const map = {
        iniciado: 1,
        en_proceso: Math.min(2, Math.max(1, totalEtapas - 1)),
        en_revision: Math.min(2, Math.max(1, totalEtapas - 1)),
        observado: Math.min(2, Math.max(1, totalEtapas - 1)),
        aprobado: totalEtapas ? totalEtapas : 1,
        finalizado: totalEtapas ? totalEtapas : 1,
        rechazado: totalEtapas ? totalEtapas : 1,
    };
    const etapaActual = map[estado] ?? 1;

    return (
        <CiudadanoLayout>
            <div className="min-h-[calc(100vh-80px)] bg-[#f9fafb] px-3 sm:px-6 lg:px-10 py-6 lg:py-10">
                {/* Header */}
                <div className="max-w-6xl mx-auto mb-6">
                    <div className="bg-white shadow-sm rounded-2xl border border-gray-200 p-6 flex flex-col md:flex-row justify-between">
                        <div className="space-y-2">
                            <div className="text-sm text-gray-500">
                                Folio/Prefolio del Expediente
                            </div>
                            <div className="text-lg font-semibold text-[#176261]">
                                {solicitud.expediente}
                            </div>

                            <div className="text-sm text-gray-500 mt-2">
                                Trámite
                            </div>
                            <div className="text-md font-semibold text-[#176261]">
                                {solicitud.tramite?.nombre ?? "Trámite"}
                            </div>

                            <div className="text-sm text-gray-500 mt-2">
                                Fecha de recepción de solicitud
                            </div>
                            <div className="text-md font-medium">
                                {new Date(
                                    solicitud.created_at
                                ).toLocaleString()}
                            </div>
                        </div>

                        <div className="mt-4 md:mt-0 text-right space-y-2">
                            <div className="text-sm text-gray-500">Estatus</div>
                            <span className="inline-block px-3 py-1 text-xs font-semibold uppercase bg-gray-300 text-gray-800 rounded-full">
                                {solicitud.estado}
                            </span>

                            {totalEtapas > 0 && (
                                <div className="text-sm text-gray-500 mt-2">
                                    Etapa ({etapaActual} / {totalEtapas})
                                </div>
                            )}

                            <div className="text-sm text-gray-500 mt-2">
                                Fecha de actualización
                            </div>
                            <div className="text-md font-medium">
                                {new Date(
                                    solicitud.updated_at
                                ).toLocaleString()}
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tabs */}
                <div className="max-w-6xl mx-auto bg-white shadow-sm border border-gray-200 rounded-2xl overflow-hidden">
                    <div className="flex border-b border-gray-200 overflow-x-auto scrollbar-thin scrollbar-thumb-gray-300/50 scrollbar-track-transparent">
                        {sections.map((sec, i) => (
                            <button
                                key={i}
                                className={`flex-1 px-4 sm:px-6 py-3 text-sm sm:text-base font-medium transition-all duration-200 whitespace-nowrap ${
                                    activeTab === i
                                        ? "text-[#176261] border-b-2 border-[#2ba29f] bg-[#2ba29f]/10"
                                        : "text-gray-600 hover:text-[#176261] hover:bg-gray-50"
                                }`}
                                onClick={() => setActiveTab(i)}
                            >
                                {sec.name ?? `Sección ${i + 1}`}
                            </button>
                        ))}
                        <button
                            className={`flex-1 px-4 sm:px-6 py-3 text-sm sm:text-base font-medium transition-all duration-200 whitespace-nowrap ${
                                activeTab === "docs"
                                    ? "text-[#176261] border-b-2 border-[#2ba29f] bg-[#2ba29f]/10"
                                    : "text-gray-600 hover:text-[#176261] hover:bg-gray-50"
                            }`}
                            onClick={() => setActiveTab("docs")}
                        >
                            Documentos
                        </button>
                        <button
                            className={`flex-1 px-4 sm:px-6 py-3 text-sm sm:text-base font-medium transition-all duration-200 whitespace-nowrap ${
                                activeTab === "msgs"
                                    ? "text-[#176261] border-b-2 border-[#2ba29f] bg-[#2ba29f]/10"
                                    : "text-gray-600 hover:text-[#176261] hover:bg-gray-50"
                            }`}
                            onClick={() => setActiveTab("msgs")}
                        >
                            Mensajes
                        </button>
                    </div>

                    <div className="p-4 sm:p-6 lg:p-8 space-y-6">
                        {sections.map((sec, i) => (
                            <div
                                key={i}
                                className={`${
                                    activeTab === i ? "block" : "hidden"
                                }`}
                            >
                                <div className="bg-white shadow-sm rounded-xl border border-gray-200">
                                    <div className="bg-[#176261] text-white px-4 py-2 rounded-t-xl font-semibold">
                                        {sec.name}
                                    </div>
                                    <div className="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                                        {(sec.fields || []).map((f, idx) => {
                                            const type = (
                                                f.type || "text"
                                            ).toLowerCase();
                                            const val = fieldValue(f);
                                            const text = dash(
                                                Array.isArray(val)
                                                    ? JSON.stringify(val)
                                                    : val
                                            );
                                            return (
                                                <div
                                                    key={idx}
                                                    className="flex flex-col"
                                                >
                                                    <label className="text-gray-500 text-sm mb-1">
                                                        {f.label ?? f.name}{" "}
                                                        {f.required && "*"}
                                                    </label>
                                                    {type === "textarea" ? (
                                                        <textarea
                                                            className="border border-gray-300 rounded-md p-2 text-sm"
                                                            rows={2}
                                                            value={text}
                                                            disabled
                                                        />
                                                    ) : type === "file" ? (
                                                        <input
                                                            className="border border-gray-300 rounded-md p-2 text-sm"
                                                            value={text}
                                                            disabled
                                                        />
                                                    ) : (
                                                        <input
                                                            className="border border-gray-300 rounded-md p-2 text-sm"
                                                            value={text}
                                                            disabled
                                                        />
                                                    )}
                                                </div>
                                            );
                                        })}
                                    </div>
                                </div>
                            </div>
                        ))}

                        {activeTab === "docs" && (
                            <div className="bg-white shadow-sm rounded-xl border border-gray-200 p-4 text-gray-500">
                                Sin documentos adicionales.
                            </div>
                        )}

                        {activeTab === "msgs" && (
                            <div className="bg-white shadow-sm rounded-xl border border-gray-200 p-4 text-gray-500">
                                Aún no hay mensajes.
                            </div>
                        )}
                    </div>
                </div>

                <div className="max-w-6xl mx-auto mt-6">
                    <button className="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">
                        Volver a mis trámites
                    </button>
                </div>
            </div>
        </CiudadanoLayout>
    );
}
