import { useState, useRef } from "react";
import axios from "axios";

import InputError from "./input-error";
import PrimaryButton from "./primary-button";
import SecondaryButton from "./secondary-button";
import TextInput from "./text-input";
import DialogModal from "./dialog-modal";

export default function ConfirmsPassword({
    title = "Confirm Password",
    content = "For your security, please confirm your password to continue.",
    button = "Confirm",
    onConfirmed,
    children,
}) {
    const [confirmingPassword, setConfirmingPassword] = useState(false);
    const [form, setForm] = useState({
        password: "",
        error: "",
        processing: false,
    });
    const passwordInput = useRef(null);

    // Paso 1: Verificar si el usuario ya confirmó recientemente
    const startConfirmingPassword = async () => {
        try {
            const response = await axios.get(route("password.confirmation"));
            if (response.data.confirmed) {
                onConfirmed?.();
            } else {
                setConfirmingPassword(true);
                setTimeout(() => passwordInput.current?.focus(), 250);
            }
        } catch (error) {
            console.error("Error checking confirmation:", error);
        }
    };

    // Paso 2: Confirmar la contraseña manualmente
    const confirmPassword = async () => {
        setForm((f) => ({ ...f, processing: true, error: "" }));
        try {
            await axios.post(route("password.confirm"), {
                password: form.password,
            });

            setForm({ password: "", error: "", processing: false });
            setConfirmingPassword(false);
            onConfirmed?.();
        } catch (error) {
            const message =
                error.response?.data?.errors?.password?.[0] ||
                "Invalid password.";
            setForm((f) => ({
                ...f,
                processing: false,
                error: message,
            }));
            passwordInput.current?.focus();
        }
    };

    // Paso 3: Cerrar modal
    const closeModal = () => {
        setConfirmingPassword(false);
        setForm({ password: "", error: "", processing: false });
    };

    return (
        <span>
            {/* === Botón / elemento disparador === */}
            <span onClick={startConfirmingPassword} className="cursor-pointer">
                {children}
            </span>

            {/* === Modal de confirmación === */}
            <DialogModal
                show={confirmingPassword}
                onClose={closeModal}
                title={<>{title}</>}
                content={
                    <>
                        <p>{content}</p>
                        <div className="mt-4">
                            <TextInput
                                ref={passwordInput}
                                type="password"
                                value={form.password}
                                onChange={(e) =>
                                    setForm((f) => ({
                                        ...f,
                                        password: e.target.value,
                                    }))
                                }
                                className="mt-1 block w-3/4"
                                placeholder="Password"
                                autoComplete="current-password"
                                onKeyUp={(e) =>
                                    e.key === "Enter" && confirmPassword()
                                }
                            />
                            <InputError message={form.error} className="mt-2" />
                        </div>
                    </>
                }
                footer={
                    <>
                        <SecondaryButton onClick={closeModal}>
                            Cancel
                        </SecondaryButton>

                        <PrimaryButton
                            onClick={confirmPassword}
                            disabled={form.processing}
                            className={`ms-3 ${
                                form.processing ? "opacity-25" : ""
                            }`}
                        >
                            {button}
                        </PrimaryButton>
                    </>
                }
            />
        </span>
    );
}
