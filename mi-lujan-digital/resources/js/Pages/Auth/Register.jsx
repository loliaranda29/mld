"use client";

import React from "react";
import { Head, useForm, usePage, Link } from "@inertiajs/react";
import Checkbox from "@/components/ui/checkbox";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";
import AuthLayout from "@/Layouts/AuthLayout";

export default function Register() {
    const { props } = usePage();
    const { jetstream } = props;

    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        terms: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("register"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <AuthLayout>
            <Head title="Crear cuenta" />
            <div className="text-center mb-6">
                <h2 className="text-2xl sm:text-3xl font-bold text-slate-800">
                    Registro
                </h2>
            </div>
            <form onSubmit={handleSubmit} className="space-y-5 sm:space-y-6">
                {/* NOMBRE */}
                <div>
                    <InputLabel htmlFor="name" value="Nombre completo" />
                    <TextInput
                        id="name"
                        type="text"
                        name="name"
                        value={data.name}
                        className="mt-1 block w-full"
                        autoComplete="name"
                        required
                        autoFocus
                        onChange={(e) => setData("name", e.target.value)}
                    />
                    <InputError message={errors.name} className="mt-2" />
                </div>

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
                        onChange={(e) => setData("email", e.target.value)}
                    />
                    <InputError message={errors.email} className="mt-2" />
                </div>

                {/* CONTRASEÑA */}
                <div>
                    <InputLabel htmlFor="password" value="Contraseña" />
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

                {/* ACEPTAR TÉRMINOS Y POLÍTICAS */}
                {jetstream?.hasTermsAndPrivacyPolicyFeature && (
                    <div className="mt-4">
                        <label
                            htmlFor="terms"
                            className="flex items-start gap-2"
                        >
                            <Checkbox
                                id="terms"
                                checked={data.terms}
                                onCheckedChange={(checked) =>
                                    setData("terms", checked)
                                }
                                name="terms"
                                required
                            />

                            <span className="text-sm text-gray-600">
                                Acepto los{" "}
                                <a
                                    target="_blank"
                                    href={route("terms.show")}
                                    className="underline text-[#298e8c] hover:text-[#1d6b69] font-medium"
                                >
                                    Términos del servicio
                                </a>{" "}
                                y la{" "}
                                <a
                                    target="_blank"
                                    href={route("policy.show")}
                                    className="underline text-[#298e8c] hover:text-[#1d6b69] font-medium"
                                >
                                    Política de privacidad
                                </a>
                                .
                            </span>
                        </label>
                        <InputError message={errors.terms} className="mt-2" />
                    </div>
                )}

                {/* BOTONES */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-6">
                    <Link
                        href={route("login")}
                        className="text-sm text-[#298e8c] hover:text-[#1d6b69] underline font-medium transition"
                    >
                        ¿Ya tenés una cuenta? Iniciá sesión
                    </Link>

                    <PrimaryButton
                        className="w-full sm:w-auto py-2.5 bg-gradient-to-r from-[#298e8c] to-[#1f7775] hover:from-[#2ba29f] hover:to-[#176261] transition-all duration-300 font-semibold text-white text-sm sm:text-base rounded-md"
                        disabled={processing}
                    >
                        {processing ? "Creando cuenta..." : "Registrarme"}
                    </PrimaryButton>
                </div>
            </form>
        </AuthLayout>
    );
}
