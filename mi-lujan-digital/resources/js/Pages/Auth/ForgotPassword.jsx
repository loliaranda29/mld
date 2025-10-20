"use client";

import React, { useState } from "react";
import { Head, useForm, usePage } from "@inertiajs/react";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";
import AuthLayout from "@/Layouts/AuthLayout";

export default function ForgotPassword() {
    const { props } = usePage();
    const { status } = props;
    const [maskedEmail, setMaskedEmail] = useState(null);
    const { data, setData, post, processing, errors, reset } = useForm({
        cuit: "",
    });

    const [sent, setSent] = useState(false);

    const handleSubmit = (e) => {
        e.preventDefault();

        post(route("password.email"), {
            data: { cuit: data.cuit },
            preserveScroll: true,
            onSuccess: (page) => {
                // Capturar email censurado desde la respuesta Inertia
                const response = page?.props?.flash || page;
                if (response?.maskedEmail) setMaskedEmail(response.maskedEmail);
                setSent(true);
            },
            onFinish: () => reset("cuit"),
        });
    };

    const handleResend = () => setSent(false);

    return (
        <AuthLayout status={status}>
            <Head title="Restablecer contraseña" />

            <div className="text-center mb-6">
                <h2 className="text-2xl sm:text-3xl font-bold text-slate-800">
                    Recupero de contraseña
                </h2>
            </div>

            <div className="text-sm text-gray-600 mb-6 text-center leading-relaxed">
                {!sent
                    ? "Ingresá tu número de CUIT y te enviaremos un enlace de recuperación al correo asociado."
                    : "Te enviamos un correo con las instrucciones para restablecer tu contraseña. Si no lo recibiste, podés reenviar el enlace."}
            </div>

            {!sent && (
                <form
                    onSubmit={handleSubmit}
                    className="space-y-5 sm:space-y-6"
                >
                    {/* CUIT */}
                    <div>
                        <InputLabel htmlFor="cuit" value="CUIT" />
                        <TextInput
                            id="cuit"
                            type="text"
                            name="cuit"
                            value={data.cuit}
                            className="mt-1 block w-full"
                            required
                            autoFocus
                            onChange={(e) => setData("cuit", e.target.value)}
                        />
                        <InputError message={errors.cuit} className="mt-2" />
                    </div>

                    {/* BOTÓN ENVIAR */}
                    <div className="flex items-center justify-end mt-4">
                        <PrimaryButton
                            className="w-full py-2.5 bg-gradient-to-r from-[#298e8c] to-[#1f7775] hover:from-[#2ba29f] hover:to-[#176261] transition-all duration-300 font-semibold text-white text-sm sm:text-base rounded-md"
                            disabled={processing}
                        >
                            {processing
                                ? "Buscando correo..."
                                : "Enviar enlace de recuperación"}
                        </PrimaryButton>
                    </div>
                </form>
            )}

            {sent && (
                <div className="flex flex-col items-center justify-center mt-6 space-y-5">
                    <div className="text-green-600 font-medium text-center">
                        ✅ Enviamos un correo a <br />
                        <span className="font-semibold text-slate-800">
                            {maskedEmail}
                        </span>
                    </div>
                    <p className="text-sm text-gray-600 text-center">
                        Revisá tu bandeja de entrada o carpeta de spam.
                    </p>
                    <PrimaryButton
                        onClick={handleResend}
                        className="w-full sm:w-auto py-2.5 bg-gradient-to-r from-[#298e8c] to-[#1f7775] hover:from-[#2ba29f] hover:to-[#176261] transition-all duration-300 font-semibold text-white text-sm sm:text-base rounded-md"
                    >
                        Reenviar enlace
                    </PrimaryButton>
                </div>
            )}
        </AuthLayout>
    );
}
