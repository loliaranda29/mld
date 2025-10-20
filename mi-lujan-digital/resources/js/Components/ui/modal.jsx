import { useEffect, useRef, useState } from "react";

export default function Modal({
    show = false,
    maxWidth = "2xl",
    closeable = true,
    onClose = () => {},
    children,
}) {
    const dialogRef = useRef(null);
    const [showSlot, setShowSlot] = useState(show);

    // Mapeo de tamaños máximos (Tailwind)
    const maxWidthClass = {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
    }[maxWidth];

    // Cerrar con tecla Escape
    useEffect(() => {
        const closeOnEscape = (e) => {
            if (e.key === "Escape" && show && closeable) {
                onClose();
            }
        };
        document.addEventListener("keydown", closeOnEscape);
        return () => {
            document.removeEventListener("keydown", closeOnEscape);
            document.body.style.overflow = null;
        };
    }, [show, closeable, onClose]);

    // Mostrar / ocultar modal
    useEffect(() => {
        if (show) {
            document.body.style.overflow = "hidden";
            setShowSlot(true);
            dialogRef.current?.showModal?.();
        } else {
            document.body.style.overflow = null;
            setTimeout(() => {
                dialogRef.current?.close?.();
                setShowSlot(false);
            }, 200);
        }
    }, [show]);

    return (
        <dialog
            ref={dialogRef}
            className="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        >
            <div
                className="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50"
                scroll-region="true"
            >
                {/* === Fondo gris con transición === */}
                <div
                    onClick={() => closeable && onClose()}
                    className={`fixed inset-0 transform transition-all duration-300 ${
                        show ? "opacity-100" : "opacity-0"
                    }`}
                >
                    <div className="absolute inset-0 bg-gray-500 opacity-75" />
                </div>

                {/* === Contenedor del modal === */}
                {showSlot && (
                    <div
                        className={`
                            mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all duration-300 
                            sm:w-full sm:mx-auto ${maxWidthClass}
                            ${
                                show
                                    ? "opacity-100 scale-100 translate-y-0"
                                    : "opacity-0 scale-95 translate-y-4"
                            }
                        `}
                    >
                        {children}
                    </div>
                )}
            </div>
        </dialog>
    );
}
