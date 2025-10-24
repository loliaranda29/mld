"use client";

import { useState } from "react";
import {
    LayoutDashboard,
    Calendar,
    Users,
    User,
    Briefcase,
    Settings,
    FileText,
} from "lucide-react";
import { usePage, Link } from "@inertiajs/react";
import Header from "@/Components/Header";

export default function CiudadanoLayout({ children }) {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const { props, url } = usePage();
    const user = props.auth.user;

    const menuItems = [
        {
            icon: LayoutDashboard,
            label: "Dashboard",
            href: "/ciudadano/dashboard",
        },
        {
            icon: FileText,
            label: "Mis trámites",
            href: "/ciudadano/tramites/solicitudes",
        },
        { icon: Calendar, label: "Mis citas", href: "/ciudadano/citas" },
        { icon: User, label: "Perfil", href: "/ciudadano/perfil" },
        { icon: FileText, label: "Reportes", href: "/ciudadano/reportes" },
        {
            icon: Settings,
            label: "Configuración",
            href: "/ciudadano/configuracion",
        },
        {
            icon: Users,
            label: "Representantes",
            href: "/ciudadano/representantes",
        },
        {
            icon: Briefcase,
            label: "Empresas",
            href: "/ciudadano/empresas",
        },
    ];

    return (
        <div className="h-screen flex flex-col bg-[#f9fafb]">
            {/* Header fijo */}
            <div className="fixed top-0 left-0 right-0 z-50">
                <Header user={user} setSidebarOpen={setSidebarOpen} />
            </div>

            {/* Contenedor general */}
            <div className="flex flex-1 pt-[72px]">
                {/* Overlay móvil */}
                {sidebarOpen && (
                    <div
                        className="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 lg:hidden"
                        onClick={() => setSidebarOpen(false)}
                    />
                )}

                {/* Sidebar fija */}
                <aside
                    className={`fixed lg:static top-[72px] left-0 z-40 w-64 lg:w-72 h-[calc(100vh-72px)] bg-white border-r border-gray-200 shadow-lg lg:shadow-none
                    transform transition-transform duration-300 ease-in-out
                    ${
                        sidebarOpen
                            ? "translate-x-0"
                            : "-translate-x-full lg:translate-x-0"
                    }`}
                >
                    <div className="flex flex-col h-full">
                        <nav className="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                            {menuItems.map((item) => {
                                const Icon = item.icon;
                                const isActive = url.startsWith(item.href);
                                return (
                                    <Link
                                        key={item.label}
                                        href={item.href}
                                        className={`flex items-center gap-3 px-4 py-3 rounded-lg transition-all duration-200 group
                                            ${
                                                isActive
                                                    ? "bg-[#e6f7f6] text-[#176261] font-semibold border border-[#2ba29f]/30"
                                                    : "text-gray-600 hover:bg-[#2ba29f]/10 hover:text-[#176261]"
                                            }`}
                                    >
                                        <Icon
                                            className={`w-5 h-5 flex-shrink-0 ${
                                                isActive
                                                    ? "text-[#298e8c]"
                                                    : "text-gray-500 group-hover:text-[#298e8c]"
                                            }`}
                                        />
                                        <span className="flex-1">
                                            {item.label}
                                        </span>
                                    </Link>
                                );
                            })}
                        </nav>

                        {/* Perfil inferior */}
                        <div className="p-3 border-t border-gray-200 bg-gray-50">
                            <div className="flex items-center gap-3 px-3 py-3 rounded-lg bg-white border border-gray-100 shadow-sm hover:shadow transition-shadow">
                                <div className="w-9 h-9 rounded-full bg-gradient-to-br from-[#298e8c] to-[#176261] flex items-center justify-center text-white font-bold text-sm">
                                    {user?.name?.charAt(0) || "?"}
                                </div>
                                <div className="flex-1 min-w-0">
                                    <p className="text-sm font-semibold text-gray-800 truncate">
                                        {user?.name}
                                    </p>
                                    <p className="text-xs text-gray-500 truncate">
                                        {user?.email}
                                    </p>
                                </div>
                                <Settings className="w-4 h-4 text-gray-400 hover:text-[#298e8c] cursor-pointer" />
                            </div>
                        </div>
                    </div>
                </aside>

                {/* Contenido principal con scroll interno */}
                <main
                    className="flex-1 ml-0 lg:ml-62 overflow-y-auto px-4 lg:px-8 py-6"
                    style={{ height: "calc(100vh - 72px)" }}
                >
                    <div className="max-w-7xl mx-auto w-full">{children}</div>
                </main>
            </div>
        </div>
    );
}
