import { useEffect, useRef, useState } from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticationCard from "@/components/ui/authentication-card";
import AuthenticationCardLogo from "@/components/ui/authentication-card-logo";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";

export default function TwoFactorChallenge() {
    const [recovery, setRecovery] = useState(false);

    const { data, setData, post, processing, errors, reset } = useForm({
        code: "",
        recovery_code: "",
    });

    const recoveryCodeInput = useRef(null);
    const codeInput = useRef(null);

    const toggleRecovery = () => {
        setRecovery((prev) => !prev);

        // Limpiar y enfocar el campo adecuado después del cambio
        setTimeout(() => {
            if (recovery) {
                codeInput.current?.focus();
                setData("recovery_code", "");
            } else {
                recoveryCodeInput.current?.focus();
                setData("code", "");
            }
        }, 100);
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("two-factor.login"));
    };

    return (
        <>
            <Head title="Two-Factor Confirmation" />

            <AuthenticationCard logo={<AuthenticationCardLogo />}>
                <div className="mb-4 text-sm text-gray-600">
                    {!recovery ? (
                        <>
                            Please confirm access to your account by entering
                            the authentication code provided by your
                            authenticator application.
                        </>
                    ) : (
                        <>
                            Please confirm access to your account by entering
                            one of your emergency recovery codes.
                        </>
                    )}
                </div>

                <form onSubmit={handleSubmit}>
                    {!recovery ? (
                        <div>
                            <InputLabel htmlFor="code" value="Code" />
                            <TextInput
                                id="code"
                                ref={codeInput}
                                type="text"
                                inputMode="numeric"
                                className="mt-1 block w-full"
                                autoComplete="one-time-code"
                                autoFocus
                                value={data.code}
                                onChange={(e) =>
                                    setData("code", e.target.value)
                                }
                            />
                            <InputError
                                message={errors.code}
                                className="mt-2"
                            />
                        </div>
                    ) : (
                        <div>
                            <InputLabel
                                htmlFor="recovery_code"
                                value="Recovery Code"
                            />
                            <TextInput
                                id="recovery_code"
                                ref={recoveryCodeInput}
                                type="text"
                                className="mt-1 block w-full"
                                autoComplete="one-time-code"
                                value={data.recovery_code}
                                onChange={(e) =>
                                    setData("recovery_code", e.target.value)
                                }
                            />
                            <InputError
                                message={errors.recovery_code}
                                className="mt-2"
                            />
                        </div>
                    )}

                    <div className="flex items-center justify-end mt-4">
                        <button
                            type="button"
                            onClick={toggleRecovery}
                            className="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer"
                        >
                            {!recovery
                                ? "Use a recovery code"
                                : "Use an authentication code"}
                        </button>

                        <PrimaryButton
                            className={`ms-4 ${processing ? "opacity-25" : ""}`}
                            disabled={processing}
                        >
                            Log in
                        </PrimaryButton>
                    </div>
                </form>
            </AuthenticationCard>
        </>
    );
}
