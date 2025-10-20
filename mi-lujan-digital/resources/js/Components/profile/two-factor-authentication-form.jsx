import { useState, useEffect } from "react";
import { useForm, router, usePage } from "@inertiajs/react";
import axios from "axios";

import ActionSection from "@/components/ui/action-section";
import ConfirmsPassword from "@/components/ui/confirms-password";
import DangerButton from "@/components/ui/danger-button";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import SecondaryButton from "@/components/ui/secondary-button";
import TextInput from "@/components/ui/text-input";

export default function TwoFactorAuthenticationForm({ requiresConfirmation }) {
    const { props } = usePage();
    const user = props.auth.user;

    const [enabling, setEnabling] = useState(false);
    const [confirming, setConfirming] = useState(false);
    const [disabling, setDisabling] = useState(false);
    const [qrCode, setQrCode] = useState(null);
    const [setupKey, setSetupKey] = useState(null);
    const [recoveryCodes, setRecoveryCodes] = useState([]);

    const confirmationForm = useForm({ code: "" });

    const twoFactorEnabled = !enabling && user?.two_factor_enabled;

    // 🔄 Limpia errores si se desactiva
    useEffect(() => {
        if (!twoFactorEnabled) {
            confirmationForm.reset();
            confirmationForm.clearErrors();
        }
    }, [twoFactorEnabled]);

    // ✅ Habilitar 2FA
    const enableTwoFactorAuthentication = () => {
        setEnabling(true);
        router.post(
            route("two-factor.enable"),
            {},
            {
                preserveScroll: true,
                onSuccess: async () => {
                    await Promise.all([
                        showQrCode(),
                        showSetupKey(),
                        showRecoveryCodes(),
                    ]);
                },
                onFinish: () => {
                    setEnabling(false);
                    setConfirming(requiresConfirmation);
                },
            }
        );
    };

    // 📸 Mostrar QR
    const showQrCode = async () => {
        const response = await axios.get(route("two-factor.qr-code"));
        setQrCode(response.data.svg);
    };

    // 🔑 Mostrar clave de configuración
    const showSetupKey = async () => {
        const response = await axios.get(route("two-factor.secret-key"));
        setSetupKey(response.data.secretKey);
    };

    // 🧩 Mostrar códigos de recuperación
    const showRecoveryCodes = async () => {
        const response = await axios.get(route("two-factor.recovery-codes"));
        setRecoveryCodes(response.data);
    };

    // ✅ Confirmar 2FA
    const confirmTwoFactorAuthentication = () => {
        confirmationForm.post(route("two-factor.confirm"), {
            errorBag: "confirmTwoFactorAuthentication",
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                setConfirming(false);
                setQrCode(null);
                setSetupKey(null);
            },
        });
    };

    // 🔁 Regenerar códigos
    const regenerateRecoveryCodes = async () => {
        await axios.post(route("two-factor.recovery-codes"));
        await showRecoveryCodes();
    };

    // 🚫 Desactivar 2FA
    const disableTwoFactorAuthentication = () => {
        setDisabling(true);
        router.delete(route("two-factor.disable"), {
            preserveScroll: true,
            onSuccess: () => {
                setDisabling(false);
                setConfirming(false);
            },
        });
    };

    return (
        <ActionSection
            title="Two Factor Authentication"
            description="Add additional security to your account using two factor authentication."
        >
            {/* === Estado === */}
            <h3 className="text-lg font-medium text-gray-900 mt-2">
                {twoFactorEnabled && !confirming
                    ? "You have enabled two factor authentication."
                    : twoFactorEnabled && confirming
                    ? "Finish enabling two factor authentication."
                    : "You have not enabled two factor authentication."}
            </h3>

            <div className="mt-3 max-w-xl text-sm text-gray-600">
                <p>
                    When two factor authentication is enabled, you will be
                    prompted for a secure token during login. Retrieve this
                    token from your Google Authenticator app.
                </p>
            </div>

            {/* === Código QR === */}
            {twoFactorEnabled && (
                <>
                    {qrCode && (
                        <div>
                            <div className="mt-4 max-w-xl text-sm text-gray-600">
                                <p className="font-semibold">
                                    {confirming
                                        ? "To finish enabling two factor authentication, scan the QR code or enter the setup key and provide the generated OTP code."
                                        : "Two factor authentication is enabled. Scan the following QR code or enter the setup key."}
                                </p>
                            </div>

                            <div
                                className="mt-4 p-2 inline-block bg-white"
                                dangerouslySetInnerHTML={{ __html: qrCode }}
                            />

                            {setupKey && (
                                <div className="mt-4 max-w-xl text-sm text-gray-600">
                                    <p className="font-semibold">
                                        Setup Key:{" "}
                                        <span
                                            dangerouslySetInnerHTML={{
                                                __html: setupKey,
                                            }}
                                        />
                                    </p>
                                </div>
                            )}

                            {confirming && (
                                <div className="mt-4">
                                    <InputLabel htmlFor="code" value="Code" />
                                    <TextInput
                                        id="code"
                                        type="text"
                                        name="code"
                                        value={confirmationForm.data.code}
                                        onChange={(e) =>
                                            confirmationForm.setData(
                                                "code",
                                                e.target.value
                                            )
                                        }
                                        className="block mt-1 w-1/2"
                                        inputMode="numeric"
                                        autoFocus
                                        autoComplete="one-time-code"
                                        onKeyUp={(e) => {
                                            if (e.key === "Enter")
                                                confirmTwoFactorAuthentication();
                                        }}
                                    />
                                    <InputError
                                        message={confirmationForm.errors.code}
                                        className="mt-2"
                                    />
                                </div>
                            )}
                        </div>
                    )}

                    {/* === Códigos de recuperación === */}
                    {recoveryCodes.length > 0 && !confirming && (
                        <div>
                            <div className="mt-4 max-w-xl text-sm text-gray-600">
                                <p className="font-semibold">
                                    Store these recovery codes securely. They
                                    allow account recovery if your authenticator
                                    device is lost.
                                </p>
                            </div>

                            <div className="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                                {recoveryCodes.map((code) => (
                                    <div key={code}>{code}</div>
                                ))}
                            </div>
                        </div>
                    )}
                </>
            )}

            {/* === Acciones === */}
            <div className="mt-5">
                {!twoFactorEnabled ? (
                    <ConfirmsPassword
                        onConfirmed={enableTwoFactorAuthentication}
                    >
                        <PrimaryButton
                            type="button"
                            className={enabling ? "opacity-25" : ""}
                            disabled={enabling}
                        >
                            Enable
                        </PrimaryButton>
                    </ConfirmsPassword>
                ) : (
                    <div>
                        {confirming && (
                            <ConfirmsPassword
                                onConfirmed={confirmTwoFactorAuthentication}
                            >
                                <PrimaryButton
                                    type="button"
                                    className={`me-3 ${
                                        enabling ? "opacity-25" : ""
                                    }`}
                                    disabled={enabling}
                                >
                                    Confirm
                                </PrimaryButton>
                            </ConfirmsPassword>
                        )}

                        {recoveryCodes.length > 0 && !confirming && (
                            <ConfirmsPassword
                                onConfirmed={regenerateRecoveryCodes}
                            >
                                <SecondaryButton className="me-3">
                                    Regenerate Recovery Codes
                                </SecondaryButton>
                            </ConfirmsPassword>
                        )}

                        {recoveryCodes.length === 0 && !confirming && (
                            <ConfirmsPassword onConfirmed={showRecoveryCodes}>
                                <SecondaryButton className="me-3">
                                    Show Recovery Codes
                                </SecondaryButton>
                            </ConfirmsPassword>
                        )}

                        {confirming ? (
                            <ConfirmsPassword
                                onConfirmed={disableTwoFactorAuthentication}
                            >
                                <SecondaryButton
                                    className={`${
                                        disabling ? "opacity-25" : ""
                                    }`}
                                    disabled={disabling}
                                >
                                    Cancel
                                </SecondaryButton>
                            </ConfirmsPassword>
                        ) : (
                            <ConfirmsPassword
                                onConfirmed={disableTwoFactorAuthentication}
                            >
                                <DangerButton
                                    className={`${
                                        disabling ? "opacity-25" : ""
                                    }`}
                                    disabled={disabling}
                                >
                                    Disable
                                </DangerButton>
                            </ConfirmsPassword>
                        )}
                    </div>
                )}
            </div>
        </ActionSection>
    );
}
