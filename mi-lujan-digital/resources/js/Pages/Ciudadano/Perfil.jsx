"use client";

import { useState } from "react";
import { Edit2, Lock, Monitor, AlertTriangle } from "lucide-react";

import UpdatePasswordForm from "@/components/profile/update-password-form";
import TwoFactorAuthenticationForm from "@/components/profile/two-factor-authentication-form";
import LogoutOtherBrowserSessionsForm from "@/components/profile/logout-other-browser-sessions-form";
import DeleteUserForm from "@/components/profile/delete-user-form";
import CiudadanoLayout from "./Ciudadano";
import { usePage } from "@inertiajs/react";
import UpdateProfileInformationForm from "@/Components/profile/update-profile-information-form";

export default function ProfilePage() {
    const [activeTab, setActiveTab] = useState("profile");
    const { props } = usePage();
    const user = props.auth.user;

    const sessions = [
        {
            agent: { platform: "Windows", browser: "Chrome", is_desktop: true },
            ip_address: "192.168.1.1",
            is_current_device: true,
            last_active: "ahora",
        },
        {
            agent: { platform: "iOS", browser: "Safari", is_desktop: false },
            ip_address: "192.168.1.2",
            is_current_device: false,
            last_active: "hace 2 horas",
        },
    ];

    const tabs = [
        { id: "profile", label: "Perfil", icon: Edit2 },
        { id: "security", label: "Seguridad", icon: Lock },
        { id: "sessions", label: "Sesiones", icon: Monitor },
        { id: "danger", label: "Zona de Peligro", icon: AlertTriangle },
    ];

    return (
        <CiudadanoLayout>
            <div className="min-h-screen bg-gradient-to-br from-slate-50 via-sky-50/30 to-blue-50/20 p-4 lg:p-8">
                <div className="max-w-6xl mx-auto space-y-6">
                    {/* Header */}
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                        <h1 className="text-3xl font-bold text-slate-900">
                            Configuración de Perfil
                        </h1>
                        <p className="text-slate-600 mt-2">
                            Administra tu información personal y configuración
                            de seguridad
                        </p>
                    </div>

                    {/* Tabs */}
                    <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                        <div className="flex overflow-x-auto border-b border-slate-200">
                            {tabs.map((tab) => {
                                const Icon = tab.icon;
                                return (
                                    <button
                                        key={tab.id}
                                        onClick={() => setActiveTab(tab.id)}
                                        className={`flex items-center gap-2 px-6 py-4 font-medium transition-colors whitespace-nowrap ${
                                            activeTab === tab.id
                                                ? "text-sky-600 border-b-2 border-sky-600 bg-sky-50/50"
                                                : "text-slate-600 hover:text-sky-600 hover:bg-slate-50"
                                        }`}
                                    >
                                        <Icon className="w-5 h-5" />
                                        {tab.label}
                                    </button>
                                );
                            })}
                        </div>

                        <div className="p-6 lg:p-8">
                            {activeTab === "profile" && (
                                <UpdateProfileInformationForm user={user} />
                            )}

                            {activeTab === "security" && (
                                <div className="space-y-8">
                                    <UpdatePasswordForm />
                                    <TwoFactorAuthenticationForm
                                        requiresConfirmation={true}
                                    />
                                </div>
                            )}

                            {activeTab === "sessions" && (
                                <LogoutOtherBrowserSessionsForm
                                    sessions={sessions}
                                />
                            )}

                            {activeTab === "danger" && <DeleteUserForm />}
                        </div>
                    </div>
                </div>
            </div>
        </CiudadanoLayout>
    );
}
