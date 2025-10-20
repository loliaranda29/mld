"use client";

import { useState } from "react";
import { Link } from "@inertiajs/react";

import {
    Search,
    Filter,
    ChevronLeft,
    ChevronRight,
    Calendar,
    Clock,
    User,
    CheckCircle,
    XCircle,
    FileText,
    PencilLine,
} from "lucide-react";
import DashboardLayout from "../../Funcionario/Funcionario";
import { usePage } from "@inertiajs/react";

export default function AppointmentsPage() {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchTerm, setSearchTerm] = useState("");
    const [statusFilter, setStatusFilter] = useState("all");
    const itemsPerPage = 10;
    const { props } = usePage();
    const { citasConfiguraciones } = props;
    console.log(citasConfiguraciones);

    // Filter appointments
    const filteredAppointments = citasConfiguraciones.filter((apt) => {
        const matchesSearch =
            apt.tramite.nombre
                .toLowerCase()
                .includes(searchTerm.toLowerCase()) ||
            apt.estado.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesStatus =
            statusFilter === "all" || apt.status === statusFilter;
        return matchesSearch && matchesStatus;
    });

    // Pagination
    const totalPages = Math.ceil(filteredAppointments.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    const currentAppointments = filteredAppointments.slice(
        startIndex,
        endIndex
    );

    const getStatusBadge = (status) => {
        const styles = {
            activo: "bg-emerald-100 text-emerald-700 border-emerald-200",
            pending: "bg-amber-100 text-amber-700 border-amber-200",
            inactivo: "bg-red-100 text-red-700 border-red-200",
        };
        const labels = {
            activo: status,
            pending: "Pendiente",
            inactivo: status,
        };
        const icons = {
            activo: <CheckCircle className="w-3.5 h-3.5" />,
            pending: <Clock className="w-3.5 h-3.5" />,
            inactivo: <XCircle className="w-3.5 h-3.5" />,
        };
        return (
            <span
                className={`inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium border ${styles[status]}`}
            >
                {icons[status]}
                {labels[status]}
            </span>
        );
    };

    return (
        <DashboardLayout>
            <div className="p-4 lg:p-8 space-y-6">
                {/* Header */}
                <div className="bg-white rounded-2xl shadow-sm border border-border p-6">
                    <div className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                        <div>
                            <h2 className="text-2xl font-bold text-foreground">
                                Citas Programadas
                            </h2>
                            <p className="text-sm text-muted-foreground mt-1">
                                Gestiona y visualiza todas las citas del sistema
                            </p>
                        </div>
                        <div className="flex items-center gap-3">
                            <span className="text-sm text-muted-foreground">
                                Total:{" "}
                                <span className="font-semibold text-foreground">
                                    {filteredAppointments.length}
                                </span>{" "}
                                citas
                            </span>
                        </div>
                    </div>
                </div>

                {/* Filters */}
                <div className="bg-white rounded-2xl shadow-sm border border-border p-6">
                    <div className="flex flex-col lg:flex-row gap-4">
                        {/* Search */}
                        <div className="flex-1 relative">
                            <Search className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground" />
                            <input
                                type="text"
                                placeholder="Buscar por paciente, procedimiento o email..."
                                value={searchTerm}
                                onChange={(e) => {
                                    setSearchTerm(e.target.value);
                                    setCurrentPage(1);
                                }}
                                className="w-full pl-12 pr-4 py-3 bg-background border border-input rounded-xl text-sm
                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500
                  hover:border-sky-300 hover:bg-sky-50/50 transition-all"
                            />
                        </div>

                        {/* Status Filter */}
                        <div className="relative lg:w-64">
                            <Filter className="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted-foreground pointer-events-none" />
                            <select
                                value={statusFilter}
                                onChange={(e) => {
                                    setStatusFilter(e.target.value);
                                    setCurrentPage(1);
                                }}
                                className="w-full pl-12 pr-4 py-3 bg-background border border-input rounded-xl text-sm appearance-none cursor-pointer
                  focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500
                  hover:border-sky-300 hover:bg-sky-50/50 transition-all"
                            >
                                <option value="all">Todos los estados</option>
                                <option value="confirmed">Confirmadas</option>
                                <option value="pending">Pendientes</option>
                                <option value="cancelled">Canceladas</option>
                            </select>
                        </div>
                    </div>
                </div>

                {/* Table */}
                <div className="bg-white rounded-2xl shadow-sm border border-border overflow-hidden">
                    {/* Desktop Table */}
                    <div className="hidden lg:block overflow-x-auto">
                        <table className="w-full">
                            <thead>
                                <tr className="bg-gradient-to-r from-sky-50 to-blue-50 border-b border-border">
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Tramite
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Fecha de inicio
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Fecha de fin
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Cupos por bloque
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Duracion del bloque
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Editar
                                    </th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-border">
                                {currentAppointments.map((apt) => (
                                    <tr
                                        key={apt.id}
                                        className="hover:bg-sky-50/50 transition-colors group"
                                    >
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-sm">
                                                    <FileText className="w-5 h-5 text-white" />
                                                </div>
                                                <div>
                                                    <p className="font-medium text-foreground">
                                                        {apt.tramite.nombre}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <Calendar className="w-4 h-4 text-sky-500" />
                                                {apt.fecha_inicio}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <Calendar className="w-4 h-4 text-sky-500" />
                                                {apt.fecha_fin}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <User className="w-4 h-4 text-sky-500" />
                                                {apt.cupo_por_bloque}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <Clock className="w-4 h-4 text-sky-500" />
                                                {apt.duracion_bloque} minutos
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            {getStatusBadge(apt.estado)}
                                        </td>
                                        <td className="px-6 py-4">
                                            <Link
                                                href={route(
                                                    "citas.show",
                                                    apt.id
                                                )}
                                                className="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-sm hover:from-sky-500 hover:to-sky-700 transition-all"
                                            >
                                                <PencilLine className="w-5 h-5 text-white" />
                                            </Link>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>

                    {/* Mobile Cards */}
                    <div className="lg:hidden divide-y divide-border">
                        {currentAppointments.map((apt) => (
                            <div
                                key={apt.id}
                                className="p-4 hover:bg-sky-50/50 transition-colors"
                            >
                                <div className="flex items-start justify-between mb-3">
                                    <div className="flex items-center gap-3">
                                        <div className="w-12 h-12 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-sm">
                                            <User className="w-6 h-6 text-white" />
                                        </div>
                                        <div>
                                            <p className="font-semibold text-foreground">
                                                {apt.tramite.nombre}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {apt.fecha_inicio}
                                            </p>
                                        </div>
                                    </div>
                                    <Link
                                        href={route("citas.show", apt.id)}
                                        className="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-sm hover:from-sky-500 hover:to-sky-700 transition-all"
                                    >
                                        <PencilLine className="w-5 h-5 text-white" />
                                    </Link>
                                </div>
                                <div className="space-y-2 mb-3">
                                    <p className="text-sm text-foreground">
                                        <span className="font-medium">
                                            Fecha de inicio:
                                        </span>{" "}
                                        {apt.fecha_fin}
                                    </p>
                                    <div className="flex items-center gap-4 text-sm text-muted-foreground">
                                        <div className="flex items-center gap-1.5">
                                            <Calendar className="w-4 h-4 text-sky-500" />
                                            {apt.cupo_por_bloque}
                                        </div>
                                        <div className="flex items-center gap-1.5">
                                            <Clock className="w-4 h-4 text-sky-500" />
                                            {apt.duracion_bloque} minutos
                                        </div>
                                    </div>
                                </div>
                                {getStatusBadge(apt.estado)}
                            </div>
                        ))}
                    </div>

                    {/* Empty State */}
                    {currentAppointments.length === 0 && (
                        <div className="py-16 text-center">
                            <Calendar className="w-16 h-16 text-muted-foreground mx-auto mb-4 opacity-50" />
                            <p className="text-lg font-medium text-foreground mb-1">
                                No se encontraron citas
                            </p>
                            <p className="text-sm text-muted-foreground">
                                Intenta ajustar los filtros de búsqueda
                            </p>
                        </div>
                    )}
                </div>

                {/* Pagination */}
                {totalPages > 1 && (
                    <div className="bg-white rounded-2xl shadow-sm border border-border p-4">
                        <div className="flex flex-col sm:flex-row items-center justify-between gap-4">
                            <p className="text-sm text-muted-foreground">
                                Mostrando{" "}
                                <span className="font-medium text-foreground">
                                    {startIndex + 1}
                                </span>{" "}
                                a{" "}
                                <span className="font-medium text-foreground">
                                    {Math.min(
                                        endIndex,
                                        filteredAppointments.length
                                    )}
                                </span>{" "}
                                de{" "}
                                <span className="font-medium text-foreground">
                                    {filteredAppointments.length}
                                </span>{" "}
                                resultados
                            </p>
                            <div className="flex items-center gap-2">
                                <button
                                    onClick={() =>
                                        setCurrentPage((prev) =>
                                            Math.max(1, prev - 1)
                                        )
                                    }
                                    disabled={currentPage === 1}
                                    className="p-2 rounded-lg border border-input hover:bg-sky-50 hover:border-sky-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-all"
                                >
                                    <ChevronLeft className="w-5 h-5" />
                                </button>
                                <div className="flex items-center gap-1">
                                    {Array.from(
                                        { length: totalPages },
                                        (_, i) => i + 1
                                    ).map((page) => (
                                        <button
                                            key={page}
                                            onClick={() => setCurrentPage(page)}
                                            className={`min-w-[40px] h-10 px-3 rounded-lg text-sm font-medium transition-all ${
                                                currentPage === page
                                                    ? "bg-gradient-to-r from-sky-500 to-sky-600 text-white shadow-md shadow-sky-500/30"
                                                    : "hover:bg-sky-50 text-foreground border border-transparent hover:border-sky-300"
                                            }`}
                                        >
                                            {page}
                                        </button>
                                    ))}
                                </div>
                                <button
                                    onClick={() =>
                                        setCurrentPage((prev) =>
                                            Math.min(totalPages, prev + 1)
                                        )
                                    }
                                    disabled={currentPage === totalPages}
                                    className="p-2 rounded-lg border border-input hover:bg-sky-50 hover:border-sky-300 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-transparent transition-all"
                                >
                                    <ChevronRight className="w-5 h-5" />
                                </button>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </DashboardLayout>
    );
}
