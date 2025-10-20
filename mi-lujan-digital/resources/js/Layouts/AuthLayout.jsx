"use client";

import React from "react";
import { Link } from "@inertiajs/react";
import { Info, ShieldCheck, Users } from "lucide-react";
import Header from "@/Components/Header";

export default function AuthLayout({
    children,
    canResetPassword = true,
    status = "",
}) {
    return (
        <>
            <Header />

            <div className="min-h-screen flex flex-col md:flex-row bg-slate-50">
                {/* 🟩 COLUMNA IZQUIERDA — Información / Guía */}
                <div
                    className="hidden md:flex flex-col justify-center items-start w-full md:w-1/2 
                    bg-gradient-to-br from-[#298e8c] via-[#2ba29f] to-[#34b7b4] text-white 
                    px-8 lg:px-14 xl:px-20 py-12 relative overflow-hidden"
                >
                    <div className="absolute inset-0 opacity-20 bg-[url('https://www.toptal.com/designers/subtlepatterns/patterns/pw_maze_white.png')]"></div>

                    <div className="relative z-10 max-w-lg">
                        <div className="flex items-center gap-3 mb-6">
                            <img
                                src="https://lujandecuyo.gob.ar/wp-content/uploads/2024/10/2024_dengueRecurso-10-500x111.png"
                                alt="Somos digitales"
                                className="h-10 md:h-12 object-contain"
                            />
                        </div>

                        <h2 className="text-3xl lg:text-4xl font-bold mb-6 leading-tight drop-shadow-sm">
                            Bienvenido al Portal de Mi Luján Digital
                        </h2>

                        <p className="text-emerald-50 mb-8 leading-relaxed text-base">
                            Accedé a tus trámites, servicios y notificaciones
                            desde un solo lugar. Nuestra plataforma te permite
                            realizar gestiones de forma simple, rápida y segura.
                        </p>

                        {/* Mensajes tipo guía */}
                        <div className="space-y-5">
                            <div className="flex items-start gap-3">
                                <ShieldCheck className="w-6 h-6 text-emerald-100 mt-0.5 flex-shrink-0" />
                                <p className="text-emerald-50 text-sm">
                                    Tus datos personales están protegidos bajo
                                    las normas de seguridad y privacidad
                                    municipal.
                                </p>
                            </div>

                            <div className="flex items-start gap-3">
                                <Users className="w-6 h-6 text-emerald-100 mt-0.5 flex-shrink-0" />
                                <p className="text-emerald-50 text-sm">
                                    Podés vincular tus cuentas y realizar
                                    gestiones para vos o tu familia.
                                </p>
                            </div>

                            <div className="flex items-start gap-3">
                                <Info className="w-6 h-6 text-emerald-100 mt-0.5 flex-shrink-0" />
                                <p className="text-emerald-50 text-sm">
                                    Si necesitás ayuda, contactá al soporte o
                                    consultá la guía de usuario.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {/* 🩶 COLUMNA DERECHA — Formulario */}
                <div
                    className="flex flex-col justify-center items-center w-full md:w-1/2 bg-white 
                    px-6 sm:px-10 lg:px-16 py-10 sm:py-16"
                >
                    <div className="w-full max-w-md space-y-8">
                        {/* Logo visible solo en móvil */}
                        <div className="flex justify-center mb-6 md:hidden">
                            <img
                                src="https://lujandecuyo.gob.ar/wp-content/uploads/2024/10/2024_dengueRecurso-10-500x111.png"
                                alt="Logo móvil"
                                className="h-10 object-contain"
                            />
                        </div>

                        {status && (
                            <div className="mb-4 font-medium text-sm text-green-600 text-center">
                                {status}
                            </div>
                        )}

                        {/* 🔹 Aquí se renderiza el formulario */}
                        {children}
                    </div>
                </div>
            </div>
        </>
    );
}
