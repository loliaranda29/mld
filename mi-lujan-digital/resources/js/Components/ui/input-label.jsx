export default function InputLabel({ htmlFor, value, required, children }) {
    return (
        <label
            htmlFor={htmlFor}
            className="block text-sm font-medium text-slate-700 mb-2"
        >
            {value || children}
            {required && <span className="text-red-500 ml-1">*</span>}
        </label>
    );
}
