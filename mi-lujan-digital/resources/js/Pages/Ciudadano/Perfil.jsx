"use client";

import { useState } from "react";
import { Edit2, Lock, Monitor, AlertTriangle } from "lucide-react";
import { usePage } from "@inertiajs/react";

import CiudadanoLayout from "./Ciudadano";
import UpdateProfileInformationForm from "@/Components/profile/update-profile-information-form";
import UpdatePasswordForm from "@/components/profile/update-password-form";
import TwoFactorAuthenticationForm from "@/components/profile/two-factor-authentication-form";
import LogoutOtherBrowserSessionsForm from "@/components/profile/logout-other-browser-sessions-form";
import DeleteUserForm from "@/components/profile/delete-user-form";

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
            <div
                className="relative flex flex-col min-h-[calc(100vh-72px)] px-3 sm:px-6 lg:px-10 py-6 lg:py-10 overflow-y-auto"
                style={{
                    background:
                        "linear-gradient(180deg, #f9fafb 0%, rgba(41,162,159,0.04) 45%, rgba(41,162,159,0.02) 100%)",
                }}
            >
                <div className="w-full max-w-6xl mx-auto space-y-6 lg:space-y-8">
                    {/* Tabs */}
                    <div className="bg-white/95 backdrop-blur-sm border border-[#e6f7f6] rounded-2xl shadow-sm overflow-hidden">
                        <div className="flex overflow-x-auto border-b border-[#e6f7f6] scrollbar-thin scrollbar-thumb-[#cfeee9]/60 scrollbar-track-transparent">
                            {tabs.map((tab) => {
                                const Icon = tab.icon;
                                const active = activeTab === tab.id;
                                return (
                                    <button
                                        key={tab.id}
                                        onClick={() => setActiveTab(tab.id)}
                                        className={`flex items-center gap-2 px-4 sm:px-6 py-3 text-sm sm:text-base font-medium transition-all duration-200 whitespace-nowrap ${
                                            active
                                                ? "text-[#176261] bg-[#2ba29f]/10 border-b-2 border-[#2ba29f]"
                                                : "text-[#176261]/80 hover:text-[#176261] hover:bg-[#f0fbfa]"
                                        }`}
                                    >
                                        <Icon
                                            className={`w-4 h-4 sm:w-5 sm:h-5 ${
                                                active
                                                    ? "text-[#298e8c]"
                                                    : "text-[#176261]/60"
                                            }`}
                                        />
                                        {tab.label}
                                    </button>
                                );
                            })}
                        </div>

                        {/* Content */}
                        <div className="p-4 sm:p-6 lg:p-8">
                            {activeTab === "profile" && (
                                <div className="space-y-6">
                                    <UpdateProfileInformationForm user={user} />
                                </div>
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
                                <div className="space-y-6">
                                    <LogoutOtherBrowserSessionsForm
                                        sessions={sessions}
                                    />
                                </div>
                            )}
                            {activeTab === "danger" && (
                                <div className="space-y-6">
                                    <DeleteUserForm />
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </CiudadanoLayout>
    );
}
