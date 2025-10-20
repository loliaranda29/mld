"use client";

import { Link, usePage, router } from "@inertiajs/react";
import { useState } from "react";
import {
    Plus,
    FileText,
    Search,
    ClipboardList,
    Clock,
    ChevronRight,
} from "lucide-react";
import CiudadanoLayout from "../Ciudadano";

export default function Solicitudes() {
    const { props } = usePage();
    const { solicitudes, filters } = props;
    const [search, setSearch] = useState(filters?.search || "");

    const handleSearch = (e) => {
        e.preventDefault();
        router.get(
            route("ciudadano.tramites.solicitudes"),
            { search },
            { preserveState: true }
        );
    };

    return (
        <CiudadanoLayout>
            <div className="bg-gradient-to-br from-slate-50 via-sky-50/30 to-blue-50/20 rounded-xl p-4 sm:p-6 space-y-4">
                {/* HEADER */}
                <div className="bg-white border border-gray-200 shadow-sm rounded-xl p-4 sm:p-6">
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4">
                        <div>
                            <h2 className="text-xl sm:text-2xl font-bold text-[#176261]">
                                Mis Solicitudes
                            </h2>
                            <p className="text-[#298e8c]/80 text-sm">
                                Gestiona y consulta el estado de tus trámites
                            </p>
                        </div>
                        <Link
                            href={route("ciudadano.tramites.catalogo")}
                            className="inline-flex items-center gap-2 bg-[#2ba29f] text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-[#298e8c] transition-all text-sm sm:text-base"
                        >
                            <Plus className="w-4 h-4" />
                            Nuevo Trámite
                        </Link>
                    </div>

                    {/* SEARCH BAR */}
                    <form onSubmit={handleSearch} className="relative mb-4">
                        <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-[#298e8c]/60 w-5 h-5" />
                        <input
                            type="text"
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                            placeholder="Buscar por número de expediente..."
                            className="w-full ps-10 pe-28 py-2.5 rounded-lg border border-gray-200 shadow-sm focus:ring-2 focus:ring-[#2ba29f] text-sm text-[#176261]"
                        />
                        <button
                            type="submit"
                            className="absolute right-2 top-1/2 -translate-y-1/2 bg-[#2ba29f] hover:bg-[#298e8c] text-white px-4 py-1.5 rounded-md text-sm transition-all"
                        >
                            Buscar
                        </button>
                    </form>

                    {/* LISTA */}
                    <div className="space-y-3">
                        {solicitudes.data?.length ? (
                            solicitudes.data.map((s) => (
                                <div
                                    key={s.id}
                                    className="bg-white border border-gray-200 shadow-sm rounded-lg p-4 hover:-translate-y-0.5 hover:shadow-md transition"
                                >
                                    <div className="flex flex-col sm:flex-row justify-between items-start gap-3">
                                        <div className="flex items-start gap-3 flex-1">
                                            <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-gradient-to-br from-[#2ba29f] to-[#298e8c]">
                                                <FileText className="w-5 h-5 text-white" />
                                            </div>
                                            <div>
                                                <p className="text-xs uppercase text-[#298e8c]/70 font-semibold tracking-wide">
                                                    Expediente
                                                </p>
                                                <h3 className="text-base font-bold text-[#176261] break-words">
                                                    {s.expediente}
                                                </h3>
                                                <div className="mt-2 space-y-1.5">
                                                    <div className="flex items-center text-sm text-[#176261]/80 flex-wrap">
                                                        <ClipboardList className="w-4 h-4 text-[#298e8c]/60 mr-2" />
                                                        <span className="text-[#298e8c]/70">
                                                            Trámite:
                                                        </span>
                                                        <span className="font-semibold text-[#176261] ml-1">
                                                            {s.tramite
                                                                ?.nombre || "—"}
                                                        </span>
                                                    </div>
                                                    <div className="flex items-center text-sm text-[#176261]/80 flex-wrap">
                                                        <Clock className="w-4 h-4 text-[#298e8c]/60 mr-2" />
                                                        <span className="text-[#298e8c]/70">
                                                            Estado:
                                                        </span>
                                                        <span className="ml-2 px-2 py-0.5 rounded-full bg-[#2ba29f]/20 text-[#176261] text-xs font-semibold uppercase">
                                                            {s.estado}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <Link
                                            href={route(
                                                "profile.solicitudes.show",
                                                s.id
                                            )}
                                            className="inline-flex items-center gap-2 bg-[#2ba29f] text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-[#298e8c] transition text-sm"
                                        >
                                            Ver Detalles
                                            <ChevronRight className="w-4 h-4" />
                                        </Link>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div className="text-center py-10">
                                <FileText className="w-16 h-16 mx-auto text-[#298e8c]/40 mb-3" />
                                <h4 className="text-lg font-semibold text-[#176261] mb-1">
                                    No hay solicitudes aún
                                </h4>
                                <p className="text-[#298e8c]/70 mb-4 text-sm">
                                    Comienza creando tu primer trámite
                                </p>
                                <Link
                                    href={route("profile.catalogo")}
                                    className="inline-flex items-center gap-2 bg-[#2ba29f] text-white px-4 py-2 rounded-lg font-medium shadow-sm hover:bg-[#298e8c] transition"
                                >
                                    <Plus className="w-4 h-4" />
                                    Crear Primer Trámite
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </CiudadanoLayout>
    );
}
