"use client";

import { useState } from "react";

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
    MoreVertical,
} from "lucide-react";
import DashboardLayout from "../../Funcionario/Funcionario";

export default function AppointmentsPage() {
    const [currentPage, setCurrentPage] = useState(1);
    const [searchTerm, setSearchTerm] = useState("");
    const [statusFilter, setStatusFilter] = useState("all");
    const itemsPerPage = 10;

    // Sample data
    const appointments = [
        {
            id: 1,
            patient: "Juan Pérez",
            procedure: "Consulta General",
            date: "2024-01-15",
            time: "09:00",
            status: "confirmed",
            email: "juan@email.com",
        },
        {
            id: 2,
            patient: "María García",
            procedure: "Examen de Sangre",
            date: "2024-01-15",
            time: "10:00",
            status: "pending",
            email: "maria@email.com",
        },
        {
            id: 3,
            patient: "Carlos López",
            procedure: "Radiografía",
            date: "2024-01-16",
            time: "11:00",
            status: "confirmed",
            email: "carlos@email.com",
        },
        {
            id: 4,
            patient: "Ana Martínez",
            procedure: "Consulta General",
            date: "2024-01-16",
            time: "14:00",
            status: "cancelled",
            email: "ana@email.com",
        },
        {
            id: 5,
            patient: "Pedro Sánchez",
            procedure: "Vacunación",
            date: "2024-01-17",
            time: "09:30",
            status: "confirmed",
            email: "pedro@email.com",
        },
        {
            id: 6,
            patient: "Laura Torres",
            procedure: "Consulta Especializada",
            date: "2024-01-17",
            time: "15:00",
            status: "pending",
            email: "laura@email.com",
        },
        {
            id: 7,
            patient: "Diego Ramírez",
            procedure: "Examen de Sangre",
            date: "2024-01-18",
            time: "10:30",
            status: "confirmed",
            email: "diego@email.com",
        },
        {
            id: 8,
            patient: "Sofia Hernández",
            procedure: "Consulta General",
            date: "2024-01-18",
            time: "16:00",
            status: "confirmed",
            email: "sofia@email.com",
        },
        {
            id: 9,
            patient: "Miguel Flores",
            procedure: "Radiografía",
            date: "2024-01-19",
            time: "11:30",
            status: "pending",
            email: "miguel@email.com",
        },
        {
            id: 10,
            patient: "Carmen Ruiz",
            procedure: "Vacunación",
            date: "2024-01-19",
            time: "13:00",
            status: "confirmed",
            email: "carmen@email.com",
        },
        {
            id: 11,
            patient: "Roberto Castro",
            procedure: "Consulta Especializada",
            date: "2024-01-20",
            time: "09:00",
            status: "pending",
            email: "roberto@email.com",
        },
        {
            id: 12,
            patient: "Isabel Morales",
            procedure: "Examen de Sangre",
            date: "2024-01-20",
            time: "14:30",
            status: "confirmed",
            email: "isabel@email.com",
        },
    ];

    // Filter appointments
    const filteredAppointments = appointments.filter((apt) => {
        const matchesSearch =
            apt.patient.toLowerCase().includes(searchTerm.toLowerCase()) ||
            apt.procedure.toLowerCase().includes(searchTerm.toLowerCase()) ||
            apt.email.toLowerCase().includes(searchTerm.toLowerCase());
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
            confirmed: "bg-emerald-100 text-emerald-700 border-emerald-200",
            pending: "bg-amber-100 text-amber-700 border-amber-200",
            cancelled: "bg-red-100 text-red-700 border-red-200",
        };
        const labels = {
            confirmed: "Confirmada",
            pending: "Pendiente",
            cancelled: "Cancelada",
        };
        const icons = {
            confirmed: <CheckCircle className="w-3.5 h-3.5" />,
            pending: <Clock className="w-3.5 h-3.5" />,
            cancelled: <XCircle className="w-3.5 h-3.5" />,
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
                                        Paciente
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Procedimiento
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Fecha
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Hora
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Estado
                                    </th>
                                    <th className="text-left px-6 py-4 text-xs font-semibold text-muted-foreground uppercase tracking-wider">
                                        Acciones
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
                                                    <User className="w-5 h-5 text-white" />
                                                </div>
                                                <div>
                                                    <p className="font-medium text-foreground">
                                                        {apt.patient}
                                                    </p>
                                                    <p className="text-sm text-muted-foreground">
                                                        {apt.email}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="text-sm text-foreground">
                                                {apt.procedure}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <Calendar className="w-4 h-4 text-sky-500" />
                                                {apt.date}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-2 text-sm text-foreground">
                                                <Clock className="w-4 h-4 text-sky-500" />
                                                {apt.time}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            {getStatusBadge(apt.status)}
                                        </td>
                                        <td className="px-6 py-4">
                                            <button className="p-2 hover:bg-sky-100 rounded-lg transition-colors opacity-0 group-hover:opacity-100">
                                                <MoreVertical className="w-5 h-5 text-muted-foreground" />
                                            </button>
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
                                                {apt.patient}
                                            </p>
                                            <p className="text-xs text-muted-foreground">
                                                {apt.email}
                                            </p>
                                        </div>
                                    </div>
                                    <button className="p-2 hover:bg-sky-100 rounded-lg transition-colors">
                                        <MoreVertical className="w-5 h-5 text-muted-foreground" />
                                    </button>
                                </div>
                                <div className="space-y-2 mb-3">
                                    <p className="text-sm text-foreground">
                                        <span className="font-medium">
                                            Procedimiento:
                                        </span>{" "}
                                        {apt.procedure}
                                    </p>
                                    <div className="flex items-center gap-4 text-sm text-muted-foreground">
                                        <div className="flex items-center gap-1.5">
                                            <Calendar className="w-4 h-4 text-sky-500" />
                                            {apt.date}
                                        </div>
                                        <div className="flex items-center gap-1.5">
                                            <Clock className="w-4 h-4 text-sky-500" />
                                            {apt.time}
                                        </div>
                                    </div>
                                </div>
                                {getStatusBadge(apt.status)}
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
