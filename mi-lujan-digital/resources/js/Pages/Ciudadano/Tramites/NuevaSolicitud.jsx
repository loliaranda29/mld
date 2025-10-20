"use client";

import { useState, useEffect, useRef } from "react";
import { router, usePage } from "@inertiajs/react";
import CiudadanoLayout from "../Ciudadano";
import { ChevronLeft, ChevronRight, CheckCircle2 } from "lucide-react";

export default function TramiteWizard() {
    const { props } = usePage();
    const { tramite, sections } = props;

    const [stepIndex, setStepIndex] = useState(0);
    const [model, setModel] = useState({});
    const answersRef = useRef();

    useEffect(() => {
        const used = new Set();
        const initModel = {};
        sections.forEach((sec, si) => {
            (sec.fields || []).forEach((f, fi) => {
                let name = (f.name || "").trim();
                if (!name || used.has(name)) name = `s${si}_f${fi}`;
                f._name = name;
                used.add(name);
                if (f.type === "checkbox" && f.multiple) initModel[name] = [];
                else if (f.type === "checkbox") initModel[name] = false;
                else initModel[name] = "";
            });
        });
        setModel(initModel);
    }, [sections]);

    const currentSection = () => sections[stepIndex] || null;
    const isLastStep = () => stepIndex >= sections.length - 1;
    const handleChange = (name, value) =>
        setModel((prev) => ({ ...prev, [name]: value }));
    const handleFileChange = (e, name) => {
        const files = e.target.files;
        handleChange(name, files.length ? "[archivo seleccionado]" : "");
    };
    const isFieldVisible = (field) => {
        if (!field || !field.conditions) return true;
        const dep = field.dependsOn;
        if (!dep) return true;
        const val = model[dep];
        return Object.prototype.hasOwnProperty.call(field.conditions, val);
    };
    const canGoNext = () => {
        const sec = currentSection();
        if (!sec) return false;
        for (const f of sec.fields || []) {
            if (!f.required || !isFieldVisible(f)) continue;
            const v = model[f._name];
            if (f.type === "file") continue;
            if (f.type === "checkbox" && f.multiple) {
                if (!Array.isArray(v) || v.length === 0) return false;
            } else if (v === "" || v === null || v === false) return false;
        }
        return true;
    };
    const nextStep = () =>
        stepIndex < sections.length - 1 && setStepIndex(stepIndex + 1);
    const prevStep = () => stepIndex > 0 && setStepIndex(stepIndex - 1);

    const handleSubmit = (e) => {
        e.preventDefault();
        if (!isLastStep()) return nextStep();
        const formData = new FormData(e.target);
        formData.append("answers_json", JSON.stringify(model));
        router.post(route("profile.solicitudes.store"), formData);
    };

    return (
        <CiudadanoLayout>
            <div className="w-full">
                <div className="max-w-5xl mx-auto bg-white rounded-xl border border-gray-200 shadow-sm p-5 sm:p-8">
                    {/* HEADER */}
                    <div className="text-center mb-6 sm:mb-8">
                        <h2 className="text-2xl sm:text-3xl font-bold text-[#176261] mb-2">
                            {tramite?.nombre || "Nuevo trámite"}
                        </h2>
                        <p className="text-[#298e8c]/80 text-sm sm:text-base">
                            Complete los siguientes pasos para enviar su
                            solicitud
                        </p>
                    </div>

                    {/* STEP PROGRESS */}
                    <div className="mb-8">
                        <div className="relative flex justify-between items-center">
                            {sections.map((section, idx) => (
                                <div
                                    key={idx}
                                    className="flex flex-col items-center flex-1 relative"
                                >
                                    {idx < sections.length - 1 && (
                                        <div
                                            className={`absolute top-1/2 left-1/2 w-full h-1 ${
                                                idx < stepIndex
                                                    ? "bg-[#2ba29f]"
                                                    : "bg-[#cbd5e1]"
                                            }`}
                                        ></div>
                                    )}

                                    <div
                                        className={`z-10 flex items-center justify-center w-12 h-12 rounded-full text-sm font-semibold transition-all duration-300 ${
                                            idx === stepIndex
                                                ? "bg-white border-2 border-[#2ba29f] text-[#2ba29f] shadow-md"
                                                : idx < stepIndex
                                                ? "bg-[#2ba29f] text-white"
                                                : "bg-white border border-gray-300 text-gray-400"
                                        }`}
                                    >
                                        {idx + 1}
                                    </div>

                                    <small
                                        className={`mt-2 text-center text-xs font-medium ${
                                            idx === stepIndex
                                                ? "text-[#2ba29f]"
                                                : "text-gray-500"
                                        }`}
                                    >
                                        {section.name || `Paso ${idx + 1}`}
                                    </small>
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* FORMULARIO */}
                    <form
                        method="POST"
                        encType="multipart/form-data"
                        onSubmit={handleSubmit}
                    >
                        <input
                            type="hidden"
                            name="tramite_id"
                            value={tramite?.id}
                        />
                        <input
                            ref={answersRef}
                            type="hidden"
                            name="answers_json"
                            value={JSON.stringify(model)}
                        />

                        {currentSection() && (
                            <div className="border border-gray-200 rounded-lg sm:rounded-xl p-5 sm:p-6 bg-white shadow-sm mb-8">
                                <div className="flex justify-between items-center mb-5">
                                    <h5 className="font-semibold text-[#176261] text-base sm:text-lg">
                                        {currentSection().name ||
                                            `Sección ${stepIndex + 1}`}
                                    </h5>
                                    <span className="text-[#2ba29f] bg-[#2ba29f]/20 px-3 py-1 rounded-full text-xs sm:text-sm font-semibold">
                                        {stepIndex + 1} de {sections.length}
                                    </span>
                                </div>

                                <div className="space-y-5">
                                    {(currentSection().fields || []).map(
                                        (field, idx) =>
                                            isFieldVisible(field) && (
                                                <div key={idx}>
                                                    <label className="block text-[#176261] font-medium mb-1.5 sm:mb-2">
                                                        {field.label ||
                                                            field.name}
                                                        {field.required && (
                                                            <span className="text-red-500 ml-1">
                                                                *
                                                            </span>
                                                        )}
                                                    </label>

                                                    {/* Text / input fields */}
                                                    {[
                                                        "text",
                                                        "search",
                                                        "code",
                                                    ].includes(
                                                        (
                                                            field.type || "text"
                                                        ).toLowerCase()
                                                    ) && (
                                                        <input
                                                            type="text"
                                                            name={`form[${field._name}]`}
                                                            placeholder={
                                                                field.placeholder ||
                                                                ""
                                                            }
                                                            value={
                                                                model[
                                                                    field._name
                                                                ] || ""
                                                            }
                                                            onChange={(e) =>
                                                                handleChange(
                                                                    field._name,
                                                                    e.target
                                                                        .value
                                                                )
                                                            }
                                                            className="w-full border border-gray-300 rounded-md px-3 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-[#2ba29f] focus:border-[#2ba29f] outline-none"
                                                        />
                                                    )}

                                                    {field.type ===
                                                        "textarea" && (
                                                        <textarea
                                                            name={`form[${field._name}]`}
                                                            rows="4"
                                                            value={
                                                                model[
                                                                    field._name
                                                                ] || ""
                                                            }
                                                            onChange={(e) =>
                                                                handleChange(
                                                                    field._name,
                                                                    e.target
                                                                        .value
                                                                )
                                                            }
                                                            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#2ba29f] focus:border-[#2ba29f] outline-none"
                                                        />
                                                    )}

                                                    {field.type ===
                                                        "number" && (
                                                        <input
                                                            type="number"
                                                            name={`form[${field._name}]`}
                                                            value={
                                                                model[
                                                                    field._name
                                                                ] || ""
                                                            }
                                                            onChange={(e) =>
                                                                handleChange(
                                                                    field._name,
                                                                    e.target
                                                                        .value
                                                                )
                                                            }
                                                            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#2ba29f] focus:border-[#2ba29f] outline-none"
                                                        />
                                                    )}

                                                    {field.type === "date" && (
                                                        <input
                                                            type="date"
                                                            name={`form[${field._name}]`}
                                                            value={
                                                                model[
                                                                    field._name
                                                                ] || ""
                                                            }
                                                            onChange={(e) =>
                                                                handleChange(
                                                                    field._name,
                                                                    e.target
                                                                        .value
                                                                )
                                                            }
                                                            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#2ba29f] focus:border-[#2ba29f] outline-none"
                                                        />
                                                    )}

                                                    {field.type ===
                                                        "select" && (
                                                        <select
                                                            name={`form[${field._name}]`}
                                                            value={
                                                                model[
                                                                    field._name
                                                                ] || ""
                                                            }
                                                            onChange={(e) =>
                                                                handleChange(
                                                                    field._name,
                                                                    e.target
                                                                        .value
                                                                )
                                                            }
                                                            className="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-[#2ba29f] focus:border-[#2ba29f] outline-none"
                                                        >
                                                            <option value="">
                                                                -- Seleccionar
                                                                --
                                                            </option>
                                                            {(
                                                                field.options ||
                                                                []
                                                            ).map((opt, i2) => {
                                                                const value =
                                                                    typeof opt ===
                                                                    "object"
                                                                        ? opt.value ??
                                                                          opt.label
                                                                        : opt;
                                                                const label =
                                                                    typeof opt ===
                                                                    "object"
                                                                        ? opt.label ??
                                                                          opt.value
                                                                        : opt;
                                                                return (
                                                                    <option
                                                                        key={i2}
                                                                        value={
                                                                            value
                                                                        }
                                                                    >
                                                                        {label}
                                                                    </option>
                                                                );
                                                            })}
                                                        </select>
                                                    )}

                                                    {field.type === "file" && (
                                                        <input
                                                            type="file"
                                                            multiple={
                                                                field.multiple
                                                            }
                                                            name={
                                                                field.multiple
                                                                    ? `files[${field._name}][]`
                                                                    : `files[${field._name}]`
                                                            }
                                                            onChange={(e) =>
                                                                handleFileChange(
                                                                    e,
                                                                    field._name
                                                                )
                                                            }
                                                            className="w-full border-2 border-dashed border-gray-300 rounded-md px-3 py-6 text-sm text-[#176261] cursor-pointer hover:border-[#2ba29f] transition-all duration-200"
                                                        />
                                                    )}

                                                    {field.help && (
                                                        <small className="text-[#298e8c]/80 mt-1 block">
                                                            {field.help}
                                                        </small>
                                                    )}
                                                </div>
                                            )
                                    )}
                                </div>
                            </div>
                        )}

                        {/* NAVEGACIÓN */}
                        <div className="flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-3 mt-6">
                            <button
                                type="button"
                                onClick={prevStep}
                                disabled={stepIndex === 0}
                                className="flex items-center justify-center gap-2 px-4 py-2 sm:px-6 sm:py-2.5 border border-gray-300 rounded-md text-[#176261] hover:bg-[#e6f6f6] disabled:opacity-50 transition-all duration-200 w-full sm:w-auto"
                            >
                                <ChevronLeft className="w-4 h-4" />
                                Anterior
                            </button>

                            {!isLastStep() ? (
                                <button
                                    type="button"
                                    onClick={nextStep}
                                    disabled={!canGoNext()}
                                    className="flex items-center justify-center gap-2 bg-[#2ba29f] text-white px-5 py-2 sm:px-7 sm:py-2.5 rounded-md hover:bg-[#298e8c] transition-all duration-200 disabled:opacity-50 w-full sm:w-auto"
                                >
                                    Siguiente
                                    <ChevronRight className="w-4 h-4" />
                                </button>
                            ) : (
                                <button
                                    type="submit"
                                    disabled={!canGoNext()}
                                    className="flex items-center justify-center gap-2 bg-[#176261] text-white px-5 py-2 sm:px-7 sm:py-2.5 rounded-md hover:bg-[#2ba29f] transition-all duration-200 disabled:opacity-50 w-full sm:w-auto"
                                >
                                    <CheckCircle2 className="w-5 h-5" />
                                    Enviar solicitud
                                </button>
                            )}
                        </div>
                    </form>
                </div>
            </div>
        </CiudadanoLayout>
    );
}
