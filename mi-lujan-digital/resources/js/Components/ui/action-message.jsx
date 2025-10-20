export default function ActionMessage({ on, children }) {
    if (!on) return null;

    return <p className="text-sm text-green-600 font-medium">{children}</p>;
}
