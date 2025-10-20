import { useRef } from "react";
import { Head, useForm } from "@inertiajs/react";
import AuthenticationCard from "@/components/ui/authentication-card";
import AuthenticationCardLogo from "@/components/ui/authentication-card-logo";
import InputError from "@/components/ui/input-error";
import InputLabel from "@/components/ui/input-label";
import PrimaryButton from "@/components/ui/primary-button";
import TextInput from "@/components/ui/text-input";

export default function ConfirmPassword() {
    const passwordInput = useRef();
    const { data, setData, post, processing, errors, reset } = useForm({
        password: "",
    });

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("password.confirm"), {
            onFinish: () => {
                reset("password");
                if (passwordInput.current) passwordInput.current.focus();
            },
        });
    };

    return (
        <>
            <Head title="Secure Area" />

            <AuthenticationCard logo={<AuthenticationCardLogo />}>
                <div className="mb-4 text-sm text-gray-600">
                    This is a secure area of the application. Please confirm
                    your password before continuing.
                </div>

                <form onSubmit={handleSubmit}>
                    <div>
                        <InputLabel htmlFor="password" value="Password" />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="current-password"
                            required
                            autoFocus
                            ref={passwordInput}
                            onChange={(e) =>
                                setData("password", e.target.value)
                            }
                        />

                        <InputError
                            message={errors.password}
                            className="mt-2"
                        />
                    </div>

                    <div className="flex justify-end mt-4">
                        <PrimaryButton
                            className={`ms-4 ${processing ? "opacity-25" : ""}`}
                            disabled={processing}
                        >
                            Confirm
                        </PrimaryButton>
                    </div>
                </form>
            </AuthenticationCard>
        </>
    );
}
