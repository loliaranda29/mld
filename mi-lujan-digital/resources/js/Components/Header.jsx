"use client";

import React, { useState } from "react";
import { Link, router } from "@inertiajs/react";
import { Menu, Bell, Search, LogOut } from "lucide-react";

export default function Header({ user = null, setSidebarOpen }) {
    const [isOpen, setIsOpen] = useState(false);

    const handleLogout = (e) => {
        e.preventDefault();
        router.post(route("logout"));
    };

    return (
        <nav className="fixed top-0 w-full z-50 bg-[#f5d03e] shadow-md h-20 border-b">
            <div className="container mx-auto px-4 h-full flex items-center justify-between">
                {/* Logo */}

                <img
                    src="/assets/img/logo-lujan.png"
                    alt="Logo"
                    style={{ width: "80px" }}
                />

                {/* Botón menú móvil */}
                {setSidebarOpen && (
                    <button
                        className="lg:hidden p-2 text-[#176261] hover:bg-yellow-200/40 rounded-lg transition"
                        onClick={() => setSidebarOpen(true)}
                    >
                        <Menu className="w-6 h-6" />
                    </button>
                )}

                {/* Contenido del header */}
                <div className="hidden lg:flex items-center gap-4">
                    {/* Servicios */}
                    <Link
                        href="#"
                        className="text-[#176261] font-medium hover:text-[#298e8c] px-3 py-2 rounded-md hover:bg-yellow-100 transition-colors"
                    >
                        Servicios digitales
                    </Link>

                    {!user ? (
                        <Link
                            href={route("login")}
                            className="text-[#176261] font-medium px-4 py-2 border border-[#298e8c]/20 rounded-lg hover:bg-[#298e8c]/10 transition-all"
                        >
                            Iniciá sesión / Registrate
                        </Link>
                    ) : (
                        <div className="flex items-center gap-3">
                            {/* Notificaciones */}
                            <button className="relative text-[#176261] hover:text-[#298e8c] hover:bg-yellow-100 p-2 rounded-lg transition">
                                <Bell className="w-5 h-5" />
                                <span className="absolute top-1.5 right-1.5 w-2 h-2 bg-[#2ba29f] rounded-full"></span>
                            </button>

                            {/* Búsqueda */}
                            <button className="text-[#176261] hover:text-[#298e8c] hover:bg-yellow-100 p-2 rounded-lg transition">
                                <Search className="w-5 h-5" />
                            </button>

                            {/* Usuario */}
                            <div className="flex items-center gap-2">
                                <div className="w-9 h-9 rounded-full bg-gradient-to-br from-[#298e8c] to-[#176261] flex items-center justify-center text-white font-semibold">
                                    {user?.name?.charAt(0).toUpperCase() || "U"}
                                </div>
                                <div className="hidden sm:flex flex-col items-start leading-tight">
                                    <span className="text-sm font-semibold text-[#176261]">
                                        {user.name}
                                    </span>
                                    <span className="text-xs text-[#298e8c]/70">
                                        {user.email}
                                    </span>
                                </div>
                                <button
                                    onClick={handleLogout}
                                    className="text-red-600 hover:text-red-700 flex items-center gap-1 text-sm font-medium px-2 py-1.5 rounded-md hover:bg-red-50 transition"
                                >
                                    <LogOut className="w-4 h-4" />
                                    Salir
                                </button>
                            </div>
                        </div>
                    )}
                </div>
            </div>

            {/* Menú móvil */}
            <div
                className={`lg:hidden absolute top-20 left-0 w-full bg-white/95 backdrop-blur-sm shadow-md flex flex-col items-start p-4 space-y-3 border-t border-yellow-100 transition-all duration-300 ${
                    isOpen ? "flex" : "hidden"
                }`}
            >
                <Link
                    href="#"
                    className="text-[#176261] font-medium hover:text-[#298e8c] px-3 py-2 rounded-md hover:bg-yellow-100 transition-colors"
                >
                    Servicios digitales
                </Link>

                {!user ? (
                    <Link
                        href={route("login")}
                        className="text-[#176261] font-medium px-4 py-2 border border-[#298e8c]/20 rounded-lg hover:bg-[#298e8c]/10 transition-all"
                    >
                        Iniciá sesión / Registrate
                    </Link>
                ) : (
                    <div className="flex flex-col gap-3 w-full">
                        <button className="relative text-[#176261] hover:text-[#298e8c] hover:bg-yellow-100 p-2 rounded-lg transition flex items-center justify-between">
                            <span>Notificaciones</span>
                            <Bell className="w-5 h-5" />
                        </button>
                        <button className="text-[#176261] hover:text-[#298e8c] hover:bg-yellow-100 p-2 rounded-lg transition flex items-center justify-between">
                            <span>Búsqueda</span>
                            <Search className="w-5 h-5" />
                        </button>
                        <div className="flex items-center justify-between p-2 border border-gray-200 rounded-md">
                            <div className="flex items-center gap-2">
                                <div className="w-9 h-9 rounded-full bg-gradient-to-br from-[#298e8c] to-[#176261] flex items-center justify-center text-white font-semibold">
                                    {user.name.charAt(0).toUpperCase()}
                                </div>
                                <div className="flex flex-col leading-tight">
                                    <span className="text-sm font-semibold text-[#176261]">
                                        {user.name}
                                    </span>
                                    <span className="text-xs text-[#298e8c]/70">
                                        {user.email}
                                    </span>
                                </div>
                            </div>
                            <button
                                onClick={handleLogout}
                                className="text-red-600 hover:text-red-700 flex items-center gap-1 text-sm font-medium"
                            >
                                <LogOut className="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                )}
            </div>
        </nav>
    );
}
