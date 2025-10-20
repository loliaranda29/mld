import { useRef } from "react";
import { useForm } from "@inertiajs/react";
import FormSection from "@/components/ui/form-section";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";
import ActionMessage from "@/components/ui/action-message";

export default function UpdatePasswordForm() {
    const passwordInput = useRef(null);
    const currentPasswordInput = useRef(null);

    const {
        data,
        setData,
        put,
        errors,
        processing,
        recentlySuccessful,
        reset,
    } = useForm({
        current_password: "",
        password: "",
        password_confirmation: "",
    });

    const updatePassword = (e) => {
        e.preventDefault();

        put(route("user-password.update"), {
            errorBag: "updatePassword",
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: () => {
                if (errors.password) {
                    reset("password", "password_confirmation");
                    passwordInput.current?.focus();
                }

                if (errors.current_password) {
                    reset("current_password");
                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <FormSection
            onSubmit={updatePassword}
            title="Update Password"
            description="Ensure your account is using a long, random password to stay secure."
        >
            {/* === Formulario === */}
            <div className="col-span-6 sm:col-span-4">
                <InputLabel
                    htmlFor="current_password"
                    value="Current Password"
                />
                <TextInput
                    id="current_password"
                    ref={currentPasswordInput}
                    type="password"
                    value={data.current_password}
                    onChange={(e) =>
                        setData("current_password", e.target.value)
                    }
                    className="mt-1 block w-full"
                    autoComplete="current-password"
                />
                <InputError
                    message={errors.current_password}
                    className="mt-2"
                />
            </div>

            <div className="col-span-6 sm:col-span-4">
                <InputLabel htmlFor="password" value="New Password" />
                <TextInput
                    id="password"
                    ref={passwordInput}
                    type="password"
                    value={data.password}
                    onChange={(e) => setData("password", e.target.value)}
                    className="mt-1 block w-full"
                    autoComplete="new-password"
                />
                <InputError message={errors.password} className="mt-2" />
            </div>

            <div className="col-span-6 sm:col-span-4">
                <InputLabel
                    htmlFor="password_confirmation"
                    value="Confirm Password"
                />
                <TextInput
                    id="password_confirmation"
                    type="password"
                    value={data.password_confirmation}
                    onChange={(e) =>
                        setData("password_confirmation", e.target.value)
                    }
                    className="mt-1 block w-full"
                    autoComplete="new-password"
                />
                <InputError
                    message={errors.password_confirmation}
                    className="mt-2"
                />
            </div>

            {/* === Acciones === */}
            <div className="col-span-6 sm:col-span-4 flex items-center justify-end mt-4">
                <ActionMessage on={recentlySuccessful} className="me-3">
                    Saved.
                </ActionMessage>

                <PrimaryButton
                    className={processing ? "opacity-25" : ""}
                    disabled={processing}
                >
                    Save
                </PrimaryButton>
            </div>
        </FormSection>
    );
}
