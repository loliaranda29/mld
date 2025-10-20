"use client";

import { useState } from "react";
import {
    LayoutDashboard,
    Calendar,
    Users,
    Settings,
    FileText,
    Menu,
    X,
    Bell,
    Search,
} from "lucide-react";

export default function DashboardLayout({ children }) {
    const [sidebarOpen, setSidebarOpen] = useState(false);
    const [open, setOpen] = useState(false);

    const menuItems = [
        { icon: LayoutDashboard, label: "Dashboard", href: "#", active: false },
        {
            icon: Calendar,
            label: "Citas",
            href: "#",
            active: true,
        },
        { icon: Users, label: "Usuarios", href: "#", active: false },
        { icon: FileText, label: "Reportes", href: "#", active: false },
        { icon: Settings, label: "Configuración", href: "#", active: false },
    ];

    return (
        <div className="min-h-screen bg-background flex">
            {sidebarOpen && (
                <div
                    className="fixed inset-0 bg-black/20 backdrop-blur-sm z-40 lg:hidden animate-in fade-in duration-200"
                    onClick={() => setSidebarOpen(false)}
                />
            )}

            <aside
                className={`
                            fixed lg:static inset-y-0 left-0 z-50
                            w-72 h-screen bg-sidebar border-r border-sidebar-border shadow-lg lg:shadow-none
                            transform transition-transform duration-300 ease-in-out
                            ${
                                sidebarOpen
                                    ? "translate-x-0"
                                    : "-translate-x-full lg:translate-x-0"
                            }
                          `}
            >
                <div className="flex flex-col h-full">
                    <div className="h-20 flex items-center justify-between px-6 bg-gradient-to-r from-sky-500 to-sky-600 border-b border-sky-600">
                        <div className="flex items-center gap-3">
                            <div>
                                <span className="font-bold text-white text-lg block">
                                    Mi Lujan Digital
                                </span>
                                <span className="text-sky-100 text-xs">
                                    Funcionario
                                </span>
                            </div>
                        </div>
                        <button
                            onClick={() => setSidebarOpen(false)}
                            className="lg:hidden text-white hover:bg-white/20 rounded-lg p-1.5 transition-colors"
                        >
                            <X className="w-5 h-5" />
                        </button>
                    </div>

                    <nav className="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                        {menuItems.map((item) => {
                            const Icon = item.icon;

                            // Si es el menú "Configuración de Citas", mostramos un acordeón
                            if (item.label === "Citas") {
                                const [open, setOpen] = useState(true); // abierto por defecto
                                const subItems = [
                                    {
                                        label: "Bandeja de entrada",
                                        href: "/citas",
                                    },
                                    {
                                        label: "Nueva Configuración",
                                        href: "/citas/create",
                                    },
                                    {
                                        label: "Editar Configuración",
                                        href: "/citas/edit",
                                    },
                                ];

                                return (
                                    <div key={item.label} className="space-y-1">
                                        <button
                                            onClick={() => setOpen(!open)}
                                            className={`
                                                        flex items-center justify-between w-full px-4 py-3.5 rounded-xl
                                                        transition-all duration-200 group relative
                                                        ${
                                                            open
                                                                ? "bg-gradient-to-r from-sky-500 to-sky-600 text-white shadow-md shadow-sky-500/30 font-medium"
                                                                : "text-sidebar-foreground hover:bg-sky-50 hover:text-sky-700"
                                                        }
                                                      `}
                                        >
                                            <div className="flex items-center gap-3">
                                                <Icon className="w-5 h-5" />
                                                <span>{item.label}</span>
                                            </div>
                                            <svg
                                                className={`w-4 h-4 transform transition-transform ${
                                                    open
                                                        ? "rotate-180"
                                                        : "rotate-0"
                                                }`}
                                                fill="none"
                                                stroke="currentColor"
                                                strokeWidth={2}
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    d="M19 9l-7 7-7-7"
                                                />
                                            </svg>
                                        </button>

                                        {open && (
                                            <div className="ml-6 mt-2 space-y-1 animate-in slide-in-from-top-2 fade-in duration-150">
                                                {subItems.map((sub) => (
                                                    <a
                                                        key={sub.label}
                                                        href={sub.href}
                                                        className="
                                                                    flex items-center gap-2 px-3 py-2 rounded-lg 
                                                                    text-sm font-medium text-gray-700 
                                                                    hover:bg-sky-50 hover:text-sky-600 transition-colors
                                                                  "
                                                    >
                                                        <div className="w-1.5 h-1.5 rounded-full bg-sky-400"></div>
                                                        {sub.label}
                                                    </a>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                );
                            }

                            // Resto de los ítems normales
                            return (
                                <a
                                    key={item.label}
                                    href={item.href}
                                    className={`
                                                flex items-center gap-3 px-4 py-3.5 rounded-xl
                                                transition-all duration-200 group relative
                                                ${
                                                    item.active
                                                        ? "bg-gradient-to-r from-sky-500 to-sky-600 text-white shadow-md shadow-sky-500/30 font-medium"
                                                        : "text-sidebar-foreground hover:bg-sky-50 hover:text-sky-700"
                                                }
                                              `}
                                >
                                    <Icon
                                        className={`w-5 h-5 flex-shrink-0 ${
                                            item.active
                                                ? ""
                                                : "group-hover:scale-110 transition-transform"
                                        }`}
                                    />
                                    <span className="flex-1">{item.label}</span>
                                    {item.active && (
                                        <div className="w-2 h-2 bg-white rounded-full animate-pulse" />
                                    )}
                                </a>
                            );
                        })}
                    </nav>

                    <div className="p-4 border-t border-sidebar-border bg-gradient-to-b from-transparent to-sky-50/50">
                        <div className="flex items-center gap-3 px-3 py-3 rounded-xl bg-white border border-sky-100 shadow-sm hover:shadow-md transition-shadow">
                            <div className="w-10 h-10 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 flex items-center justify-center shadow-md">
                                <span className="text-sm font-bold text-white">
                                    AD
                                </span>
                            </div>
                            <div className="flex-1 min-w-0">
                                <p className="text-sm font-semibold text-sidebar-foreground truncate">
                                    Admin User
                                </p>
                                <p className="text-xs text-muted-foreground truncate">
                                    admin@sistema.com
                                </p>
                            </div>
                            <Settings className="w-4 h-4 text-muted-foreground hover:text-sky-600 cursor-pointer transition-colors" />
                        </div>
                    </div>
                </div>
            </aside>

            {/* Main Content */}
            <div className="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
                {/* Header fijo */}
                <header className="h-20 bg-white border-b border-border flex items-center justify-between px-4 lg:px-8 fixed top-0 right-0 left-0 lg:left-72 z-30 shadow-sm">
                    <div className="flex items-center gap-4">
                        <button
                            onClick={() => setSidebarOpen(true)}
                            className="lg:hidden text-foreground hover:bg-sky-50 hover:text-sky-600 rounded-lg p-2 transition-colors"
                        >
                            <Menu className="w-6 h-6" />
                        </button>
                        <div>
                            <h1 className="text-xl font-bold text-foreground">
                                Configuración de Citas
                            </h1>
                            <p className="text-sm text-muted-foreground hidden sm:block">
                                Gestiona tus citas y horarios
                            </p>
                        </div>
                    </div>
                    <div className="flex items-center gap-3">
                        <button className="text-muted-foreground hover:text-sky-600 hover:bg-sky-50 rounded-lg p-2 transition-colors relative">
                            <Bell className="w-5 h-5" />
                            <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-sky-500 rounded-full"></span>
                        </button>
                        <button className="text-muted-foreground hover:text-sky-600 hover:bg-sky-50 rounded-lg p-2 transition-colors">
                            <Search className="w-5 h-5" />
                        </button>
                    </div>
                </header>

                {/* Contenido desplazable */}
                <main
                    className="
                                flex-1 overflow-y-auto overflow-x-hidden 
                                bg-gradient-to-br from-sky-50/30 to-blue-50/20
                                pt-20
                                h-[calc(100vh-5rem)]  /* 100vh - altura del header */
                                  "
                >
                    <div className="p-6">{children}</div>
                </main>
            </div>
        </div>
    );
}
