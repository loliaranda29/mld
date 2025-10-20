import { Head, useForm, Link, usePage } from "@inertiajs/react";
import AuthenticationCard from "@/components/ui/authentication-card";
import AuthenticationCardLogo from "@/components/ui/authentication-card-logo";
import PrimaryButton from "@/components/ui/primary-button";

export default function VerifyEmail() {
    const { props } = usePage();
    const { status } = props;

    const { post, processing } = useForm({});

    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("verification.send"));
    };

    const verificationLinkSent = status === "verification-link-sent";

    return (
        <>
            <Head title="Email Verification" />

            <AuthenticationCard logo={<AuthenticationCardLogo />}>
                <div className="mb-4 text-sm text-gray-600">
                    Before continuing, could you verify your email address by
                    clicking on the link we just emailed to you? If you didn’t
                    receive the email, we will gladly send you another.
                </div>

                {verificationLinkSent && (
                    <div className="mb-4 font-medium text-sm text-green-600">
                        A new verification link has been sent to the email
                        address you provided in your profile settings.
                    </div>
                )}

                <form onSubmit={handleSubmit}>
                    <div className="mt-4 flex items-center justify-between">
                        <PrimaryButton
                            className={`${processing ? "opacity-25" : ""}`}
                            disabled={processing}
                        >
                            Resend Verification Email
                        </PrimaryButton>

                        <div>
                            <Link
                                href={route("profile.show")}
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Edit Profile
                            </Link>

                            <Link
                                href={route("logout")}
                                method="post"
                                as="button"
                                className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 ms-2"
                            >
                                Log Out
                            </Link>
                        </div>
                    </div>
                </form>
            </AuthenticationCard>
        </>
    );
}
