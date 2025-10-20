"use client";

import React from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";
import AuthLayout from "@/Layouts/AuthLayout";

export default function ResetPassword() {
    const { props } = usePage();
    const { token, email } = props;

    const { data, setData, post, processing, errors, reset } = useForm({
        token: token || "",
        email: email || "",
        password: "",
        password_confirmation: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("password.update"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <AuthLayout>
            <Head title="Restablecer contraseña" />
            <div className="text-center mb-6">
                <h2 className="text-2xl sm:text-3xl font-bold text-slate-800">
                    Reseteo de contraseña
                </h2>
            </div>
            <div className="text-sm text-gray-600 mb-6 text-center leading-relaxed">
                Ingresá tu nueva contraseña para recuperar el acceso a tu cuenta
                de <strong>Mi Luján Digital</strong>. Asegurate de elegir una
                clave segura y fácil de recordar.
            </div>

            <form onSubmit={handleSubmit} className="space-y-5 sm:space-y-6">
                {/* TOKEN (oculto) */}
                <input type="hidden" name="token" value={data.token} />

                {/* EMAIL */}
                <div>
                    <InputLabel htmlFor="email" value="Correo electrónico" />
                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        required
                        autoFocus
                        onChange={(e) => setData("email", e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                {/* NUEVA CONTRASEÑA */}
                <div>
                    <InputLabel htmlFor="password" value="Nueva contraseña" />
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        required
                        onChange={(e) => setData("password", e.target.value)}
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                {/* CONFIRMAR CONTRASEÑA */}
                <div>
                    <InputLabel
                        htmlFor="password_confirmation"
                        value="Confirmar contraseña"
                    />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        required
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                    />
                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                {/* BOTÓN ENVIAR */}
                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton
                        className="w-full py-2.5 bg-gradient-to-r from-[#298e8c] to-[#1f7775] hover:from-[#2ba29f] hover:to-[#176261] transition-all duration-300 font-semibold text-white text-sm sm:text-base rounded-md"
                        disabled={processing}
                    >
                        {processing
                            ? "Guardando nueva contraseña..."
                            : "Restablecer contraseña"}
                    </PrimaryButton>
                </div>
            </form>
        </AuthLayout>
    );
}
