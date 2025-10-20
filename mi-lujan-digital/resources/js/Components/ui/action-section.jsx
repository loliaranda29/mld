export default function ActionSection({ title, description, children }) {
    return (
        <div className="border-b border-slate-200 pb-8">
            <div className="mb-6">
                <h3 className="text-lg font-bold text-slate-900">{title}</h3>
                <p className="text-sm text-slate-600 mt-2">{description}</p>
            </div>
            <div>{children}</div>
        </div>
    );
}
