import { forwardRef } from "react";

const TextInput = forwardRef(
    ({ type = "text", className = "", ...props }, ref) => {
        return (
            <input
                type={type}
                ref={ref}
                className={`w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-lg focus:outline-none focus:border-sky-500 hover:border-sky-300 transition-colors ${className}`}
                {...props}
            />
        );
    }
);

TextInput.displayName = "TextInput";

export default TextInput;
