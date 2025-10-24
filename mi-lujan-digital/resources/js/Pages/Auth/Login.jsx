"use client";

import React from "react";
import { Link, useForm } from "@inertiajs/react";
import Checkbox from "@/components/ui/checkbox";
import InputLabel from "@/components/ui/input-label";
import TextInput from "@/components/ui/text-input";
import InputError from "@/components/ui/input-error";
import PrimaryButton from "@/components/ui/primary-button";

import AuthLayout from "@/Layouts/AuthLayout";

export default function Login({ canResetPassword = true, status = "" }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        cuit: "",
        password: "",
        remember: false,
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("login"), {
            onFinish: () => reset("password"),
        });
    };

    return (
        <AuthLayout canResetPassword={canResetPassword} status={status}>
            <div className="text-center mb-6">
                <h2 className="text-2xl sm:text-3xl font-bold text-slate-800">
                    Iniciar sesión
                </h2>
                <p className="text-slate-500 text-sm mt-1">
                    Accedé con tu cuenta municipal
                </p>
            </div>
            <form onSubmit={handleSubmit} className="space-y-5 sm:space-y-6">
                {/* cuit */}
                <div>
                    <InputLabel htmlFor="cuit" value="C.U.I.T" />
                    <TextInput
                        id="cuit"
                        type="cuit"
                        name="cuit"
                        value={data.cuit}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        required
                        autoFocus
                        onChange={(e) => setData("cuit", e.target.value)}
                    />
                    <InputError message={errors.cuit} className="mt-2" />
                </div>

                {/* PASSWORD */}
                <div>
                    <InputLabel htmlFor="password" value="Contraseña" />
                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        required
                        onChange={(e) => setData("password", e.target.value)}
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                {/* RECORDAR / OLVIDAR */}
                <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <label className="flex items-center gap-2">
                        <Checkbox
                            checked={data.remember}
                            onCheckedChange={(checked) =>
                                setData("remember", checked)
                            }
                            name="remember"
                        />
                        <span className="text-sm text-gray-600">
                            Recordarme
                        </span>
                    </label>

                    {canResetPassword && (
                        <Link
                            href={route("password.request")}
                            className="text-sm text-[#298e8c] hover:text-[#1d6b69] transition font-medium"
                        >
                            ¿Olvidaste tu contraseña?
                        </Link>
                    )}
                </div>

                {/* BOTÓN LOGIN */}
                <div>
                    <PrimaryButton
                        className="w-full py-2.5 bg-gradient-to-r from-[#298e8c] to-[#1f7775] hover:from-[#2ba29f] hover:to-[#176261] transition-all duration-300 font-semibold text-white text-sm sm:text-base rounded-md"
                        disabled={processing}
                    >
                        {processing ? "Ingresando..." : "Iniciar sesión"}
                    </PrimaryButton>
                </div>
            </form>

            <p className="text-center text-slate-500 text-sm mt-6">
                ¿No tenés una cuenta?{" "}
                <Link
                    href={route("register")}
                    className="text-[#298e8c] hover:text-[#1d6b69] font-medium"
                >
                    Registrate aquí
                </Link>
            </p>
        </AuthLayout>
    );
}
