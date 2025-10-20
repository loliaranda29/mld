"use client";

export default function AuthenticationCard({ children, logo }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gradient-to-br from-sky-50 via-white to-blue-50">
            <div className="mb-6">{logo}</div>

            <div className="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-lg rounded-xl border border-sky-100">
                {children}
            </div>
        </div>
    );
}
