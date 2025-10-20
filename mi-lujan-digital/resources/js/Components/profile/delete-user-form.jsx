import { useRef, useState } from "react";
import { useForm } from "@inertiajs/react";

import ActionSection from "@/components/ui/action-section";
import DangerButton from "@/components/ui/danger-button";
import DialogModal from "@/components/ui/dialog-modal";
import InputError from "@/components/ui/input-error";
import SecondaryButton from "@/components/ui/secondary-button";
import TextInput from "@/components/ui/text-input";

export default function DeleteUserForm() {
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
    const passwordInput = useRef(null);

    const {
        data,
        setData,
        delete: destroy,
        processing,
        errors,
        reset,
    } = useForm({
        password: "",
    });

    const confirmUserDeletion = () => {
        setConfirmingUserDeletion(true);
        setTimeout(() => passwordInput.current?.focus(), 250);
    };

    const deleteUser = (e) => {
        e?.preventDefault();

        destroy(route("current-user.destroy"), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    const closeModal = () => {
        setConfirmingUserDeletion(false);
        reset();
    };

    return (
        <ActionSection
            title="Delete Account"
            description="Permanently delete your account."
        >
            {/* === Descripción === */}
            <div className="max-w-xl text-sm text-gray-600">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </div>

            {/* === Botón principal === */}
            <div className="mt-5">
                <DangerButton onClick={confirmUserDeletion}>
                    Delete Account
                </DangerButton>
            </div>

            {/* === Modal de confirmación === */}
            <DialogModal show={confirmingUserDeletion} onClose={closeModal}>
                <h2 className="text-lg font-medium text-gray-900 mb-2">
                    Delete Account
                </h2>

                <p className="text-sm text-gray-600">
                    Are you sure you want to delete your account? Once your
                    account is deleted, all of its resources and data will be
                    permanently deleted. Please enter your password to confirm
                    you would like to permanently delete your account.
                </p>

                {/* === Campo de contraseña === */}
                <div className="mt-4">
                    <TextInput
                        ref={passwordInput}
                        type="password"
                        value={data.password}
                        onChange={(e) => setData("password", e.target.value)}
                        className="mt-1 block w-3/4"
                        placeholder="Password"
                        autoComplete="current-password"
                        onKeyUp={(e) => e.key === "Enter" && deleteUser()}
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                {/* === Botones del modal === */}
                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={closeModal}>
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        className={`ms-3 ${processing ? "opacity-25" : ""}`}
                        disabled={processing}
                        onClick={deleteUser}
                    >
                        Delete Account
                    </DangerButton>
                </div>
            </DialogModal>
        </ActionSection>
    );
}
