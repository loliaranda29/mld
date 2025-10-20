"use client";

import { Link, usePage } from "@inertiajs/react";
import { Building2, Info, PlayCircle } from "lucide-react";
import CiudadanoLayout from "../Ciudadano";

export default function Catalogo() {
    const { props } = usePage();
    const { plantillas } = props;

    return (
        <CiudadanoLayout>
            <div className="w-full">
                {/* Contenedor principal */}
                <div className="bg-white border border-gray-200 shadow-sm rounded-xl p-4 sm:p-6">
                    {/* Header */}
                    <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-3">
                        <h2 className="text-xl font-bold text-[#176261]">
                            Catálogo de trámites
                        </h2>

                        <Link
                            href={route("ciudadano.tramites.solicitudes")}
                            className="text-sm sm:text-base border border-[#2ba29f]/40 text-[#176261] hover:bg-[#2ba29f]/10 px-4 py-2 rounded-lg transition-all duration-200 w-full sm:w-auto text-center font-medium"
                        >
                            Mis solicitudes
                        </Link>
                    </div>

                    {/* Lista de trámites */}
                    {plantillas?.data?.length > 0 ? (
                        <div className="flex flex-col gap-3 sm:gap-4">
                            {plantillas.data.map((t) => {
                                const puedeIniciar =
                                    Number(t.acepta_solicitudes) === 1 &&
                                    Number(t.disponible) === 1 &&
                                    Number(t.publicado) === 1;

                                return (
                                    <div
                                        key={t.id}
                                        className="border border-gray-200 bg-white rounded-lg sm:rounded-xl shadow-sm hover:shadow-md hover:-translate-y-[1px] transition-all duration-200 p-4 sm:p-5 flex flex-col md:flex-row justify-between items-start md:items-center gap-3"
                                    >
                                        {/* Información */}
                                        <div className="flex-1 w-full md:pr-4">
                                            <h3 className="font-semibold text-[#176261] text-base sm:text-lg break-words">
                                                {t.nombre ||
                                                    "Trámite sin nombre"}
                                            </h3>

                                            {t.descripcion && (
                                                <p className="text-[#298e8c]/80 text-sm mt-1 leading-relaxed">
                                                    {t.descripcion}
                                                </p>
                                            )}

                                            <div className="text-[#176261]/80 text-xs sm:text-sm mt-2 flex flex-wrap gap-2 sm:gap-3">
                                                {t.area && (
                                                    <span className="flex items-center gap-1">
                                                        <Building2 className="w-4 h-4 text-[#2ba29f]/70" />
                                                        {t.area}
                                                    </span>
                                                )}
                                                {t.publicado !== undefined && (
                                                    <span className="flex items-center gap-1">
                                                        <span
                                                            className={`w-2 h-2 rounded-full ${
                                                                t.publicado
                                                                    ? "bg-green-500"
                                                                    : "bg-gray-400"
                                                            }`}
                                                        ></span>
                                                        {t.publicado
                                                            ? "Publicado"
                                                            : "No publicado"}
                                                    </span>
                                                )}
                                            </div>
                                        </div>

                                        {/* Acciones */}
                                        <div className="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                            <Link
                                                href={route(
                                                    "ciudadano.tramites.iniciar",
                                                    t.id
                                                )}
                                                className="border border-[#2ba29f] text-[#2ba29f] hover:bg-[#2ba29f]/10 text-sm px-4 py-2 rounded-lg font-medium flex items-center justify-center gap-1 transition-all duration-200"
                                            >
                                                <Info className="w-4 h-4" />
                                                Ver detalles
                                            </Link>

                                            {puedeIniciar ? (
                                                <Link
                                                    href={route(
                                                        "profile.tramites.iniciar",
                                                        t.id
                                                    )}
                                                    className="bg-[#2ba29f] hover:bg-[#298e8c] text-white text-sm px-4 py-2 rounded-lg font-medium flex items-center justify-center gap-1 transition-all duration-200"
                                                >
                                                    <PlayCircle className="w-4 h-4" />
                                                    Iniciar trámite
                                                </Link>
                                            ) : (
                                                <button
                                                    className="border border-gray-300 text-gray-400 text-sm px-4 py-2 rounded-lg font-medium cursor-not-allowed flex items-center justify-center gap-1"
                                                    title="Este trámite aún no está habilitado para iniciar en línea."
                                                >
                                                    No disponible
                                                </button>
                                            )}
                                        </div>
                                    </div>
                                );
                            })}
                        </div>
                    ) : (
                        <div className="bg-[#2ba29f]/10 border border-[#2ba29f]/30 text-[#176261] px-4 py-6 rounded-xl text-center text-sm sm:text-base">
                            No hay trámites disponibles por el momento.
                        </div>
                    )}

                    {/* Paginación */}
                    {plantillas?.links?.length > 3 && (
                        <div className="mt-6 flex justify-center">
                            <div className="flex gap-1.5 sm:gap-2 flex-wrap justify-center">
                                {plantillas.links.map((link, i) => (
                                    <Link
                                        key={i}
                                        href={link.url || "#"}
                                        dangerouslySetInnerHTML={{
                                            __html: link.label,
                                        }}
                                        className={`px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-md sm:rounded-lg text-xs sm:text-sm font-medium border transition-all duration-150 ${
                                            link.active
                                                ? "bg-[#2ba29f] text-white border-[#2ba29f]"
                                                : "bg-white text-[#176261] hover:bg-[#f0fdfa] border-[#2ba29f]/30"
                                        }`}
                                    />
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </CiudadanoLayout>
    );
}
