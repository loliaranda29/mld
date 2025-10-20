"use client";

import { useState, useEffect } from "react";
import {
    Calendar,
    Clock,
    Users,
    Settings,
    SplitSquareVertical,
    AlertCircle,
} from "lucide-react";
import { Link } from "@inertiajs/react";

const CitasForm = ({ data, setData, handleSubmit, tramites }) => {
    const [errores, setErrores] = useState({});

    console.log(data);

    const dias = [
        "Lunes",
        "Martes",
        "Miércoles",
        "Jueves",
        "Viernes",
        "Sábado",
        "Domingo",
    ];

    const handleInputChange = (field, value) => {
        if (
            field === "hora_fin" &&
            data?.hora_inicio &&
            value <= data?.hora_inicio
        ) {
            return; // no deja seleccionar hora menor
        }
        if (
            field === "hora_inicio_2" &&
            data?.hora_fin &&
            value <= data?.hora_fin
        ) {
            return;
        }
        if (
            field === "hora_fin_2" &&
            data?.hora_inicio_2 &&
            value <= data?.hora_inicio_2
        ) {
            return;
        }
        if (field === "todo_el_anio") {
            setData((prev) => ({
                ...prev,
                todo_el_anio: value,
                fecha_inicio: value ? "2025-01-01" : "",
                fecha_fin: value ? "2025-12-31" : "",
            }));
            return;
        }
        setData((prev) => ({ ...prev, [field]: value }));
    };

    const handleDayToggle = (day) => {
        setData((prev) => ({
            ...prev,
            dias_atencion: prev.dias_atencion.includes(day)
                ? prev.dias_atencion.filter((d) => d !== day)
                : [...prev.dias_atencion, day],
        }));
    };

    // --- FUNCIONES DE CÁLCULO AUTOMÁTICO ---
    const calcularCupos = (horaInicio, horaFin, duracion) => {
        if (!horaInicio || !horaFin || !duracion) return 0;

        try {
            const [hInicio, mInicio] = horaInicio.split(":").map(Number);
            const [hFin, mFin] = horaFin.split(":").map(Number);
            const minutosInicio = hInicio * 60 + mInicio;
            const minutosFin = hFin * 60 + mFin;
            const totalMinutos = minutosFin - minutosInicio;
            if (totalMinutos <= 0 || duracion <= 0) return 0;
            return Math.floor(totalMinutos / duracion);
        } catch {
            return 0;
        }
    };

    const calcularCuposTotales = () => {
        const duracion = parseInt(data?.duracion_bloque);
        const cupos1 = calcularCupos(
            data?.hora_inicio,
            data?.hora_fin,
            duracion
        );
        const cupos2 = data?.dividir_horario
            ? calcularCupos(data?.hora_inicio_2, data?.hora_fin_2, duracion)
            : 0;
        return cupos1 + cupos2;
    };

    const cuposCalculados = calcularCuposTotales();

    useEffect(() => {
        if (cuposCalculados !== data?.cupo_por_bloque) {
            setData((prev) => ({
                ...prev,
                cupo_por_bloque: cuposCalculados,
            }));
        }
    }, [
        data?.hora_inicio,
        data?.hora_fin,
        data?.hora_inicio_2,
        data?.hora_fin_2,
        data?.dividir_horario,
        data?.duracion_bloque,
    ]);
    // ⚙️ Validación dinámica de horarios
    useEffect(() => {
        const nuevosErrores = {};

        // Primer rango
        if (data?.hora_inicio && data?.hora_fin) {
            const [hi, mi] = data?.hora_inicio.split(":").map(Number);
            const [hf, mf] = data?.hora_fin.split(":").map(Number);
            if (hf * 60 + mf <= hi * 60 + mi) {
                nuevosErrores.hora_fin =
                    "La hora de fin debe ser mayor que la de inicio.";
            }
        }

        // Segundo rango (si está activado)
        if (data?.dividir_horario && data?.hora_inicio_2 && data?.hora_fin_2) {
            const [hf1, mf1] = data?.hora_fin.split(":").map(Number);
            const [hi2, mi2] = data?.hora_inicio_2.split(":").map(Number);
            const [hf2, mf2] = data?.hora_fin_2.split(":").map(Number);

            if (hi2 * 60 + mi2 <= hf1 * 60 + mf1) {
                nuevosErrores.hora_inicio_2 =
                    "El inicio del segundo turno debe ser posterior al fin del primero.";
            }
            if (hf2 * 60 + mf2 <= hi2 * 60 + mi2) {
                nuevosErrores.hora_fin_2 =
                    "La hora de fin del segundo turno debe ser mayor que la de inicio.";
            }
        }

        setErrores(nuevosErrores);
    }, [
        data?.hora_inicio,
        data?.hora_fin,
        data?.hora_inicio_2,
        data?.hora_fin_2,
        data?.dividir_horario,
    ]);
    // 🧹 Limpia los valores del segundo turno si se desactiva el "dividir horario"
    useEffect(() => {
        if (!data?.dividir_horario) {
            setData((prev) => ({
                ...prev,
                hora_inicio_2: "",
                hora_fin_2: "",
            }));
        }
    }, [data?.dividir_horario]);

    const getFieldProps = (field) => {
        switch (field) {
            case "hora_fin":
                return {
                    min: data?.hora_inicio || undefined,
                    disabled: !data?.hora_inicio, // desactiva hasta que haya inicio
                };
            case "hora_inicio_2":
                return {
                    min: data?.hora_fin || undefined,
                    disabled: !data?.hora_fin, // desactiva hasta que se defina la 1° fin
                };
            case "hora_fin_2":
                return {
                    min: data?.hora_inicio_2 || undefined,
                    disabled: !data?.hora_inicio_2, // desactiva hasta que haya inicio 2
                };
            default:
                return {};
        }
    };

    // --- RENDER ---
    return (
        <>
            <Link
                href={route("citas.edit")}
                className="
        inline-flex items-center gap-2
        px-5 py-2.5
        rounded-lg
        bg-gradient-to-r from-sky-500 to-sky-600
        text-white font-medium
        shadow-md hover:shadow-lg
        hover:from-sky-600 hover:to-sky-700
        transition-all duration-200
        focus:outline-none focus:ring-2 focus:ring-sky-300 focus:ring-offset-1
        active:scale-95 mb-3
    "
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    strokeWidth={2}
                    stroke="currentColor"
                    className="w-5 h-5"
                >
                    <path
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        d="M15 19l-7-7 7-7"
                    />
                </svg>
                <span>Volver</span>
            </Link>

            <form
                onSubmit={() => handleSubmit(event)}
                className="
      mx-auto w-full max-w-6xl 
      bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg 
      border border-sky-100 
      p-6 sm:p-10 
      space-y-10 
      transition-all
    "
            >
                {/* === Tipo de Trámite === */}
                <section className="space-y-4">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-md">
                            <Settings className="w-5 h-5 text-white" />
                        </div>
                        <h2 className="text-2xl font-semibold text-gray-800">
                            Tipo de Trámite
                        </h2>
                    </div>

                    <select
                        value={data?.tramite_id}
                        onChange={(e) =>
                            handleInputChange("tramite_id", e.target.value)
                        }
                        className="
          w-full px-4 py-3 rounded-lg border border-sky-200 
          bg-gradient-to-r from-white to-sky-50 
          focus:outline-none focus:ring-2 focus:ring-sky-500
          text-gray-800
        "
                    >
                        <option value="">Seleccionar trámite...</option>
                        {tramites.map((t) => (
                            <option key={t.id} value={Number(t.id)}>
                                {t.nombre}
                            </option>
                        ))}
                    </select>
                </section>

                {/* === Período de Atención === */}
                <section className="space-y-4">
                    <div className="flex items-center justify-between mb-2">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-md">
                                <Calendar className="w-5 h-5 text-white" />
                            </div>
                            <h2 className="text-2xl font-semibold text-gray-800">
                                Período de Atención
                            </h2>
                        </div>

                        {/* Botón Todo el Año */}
                        <button
                            type="button"
                            onClick={() =>
                                handleInputChange(
                                    "todo_el_anio",
                                    !data?.todo_el_anio
                                )
                            }
                            className={`
        flex items-center gap-2 px-4 py-2 text-sm font-medium rounded-lg transition-all
        ${
            data?.todo_el_anio
                ? "bg-gradient-to-r from-sky-500 to-sky-600 text-white shadow-md"
                : "border border-sky-300 text-sky-700 hover:bg-sky-50"
        }
      `}
                        >
                            {data?.todo_el_anio
                                ? "✔ Todo el año activo"
                                : "Todo el año"}
                        </button>
                    </div>

                    {/* Mostrar solo si no está marcado "todo el año" */}
                    {!data?.todo_el_anio && (
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6 animate-in fade-in duration-200">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha inicial
                                </label>
                                <input
                                    type="date"
                                    value={data?.fecha_inicio}
                                    onChange={(e) =>
                                        handleInputChange(
                                            "fecha_inicio",
                                            e.target.value
                                        )
                                    }
                                    className="w-full px-4 py-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500 bg-white"
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Fecha final
                                </label>
                                <input
                                    type="date"
                                    value={data?.fecha_fin}
                                    onChange={(e) =>
                                        handleInputChange(
                                            "fecha_fin",
                                            e.target.value
                                        )
                                    }
                                    className="w-full px-4 py-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500 bg-white"
                                />
                            </div>
                        </div>
                    )}
                </section>

                {/* === Días de atención === */}
                <section>
                    <h3 className="text-lg font-semibold text-gray-800 mb-4">
                        Días de atención
                    </h3>
                    <div className="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
                        {dias.map((dia) => (
                            <label
                                key={dia}
                                className={`flex items-center justify-center px-4 py-3 rounded-xl border-2 cursor-pointer text-sm font-medium transition-all duration-150 ${
                                    data?.dias_atencion.includes(dia)
                                        ? "bg-gradient-to-r from-sky-500 to-sky-600 border-sky-600 text-white shadow-md"
                                        : "border-sky-200 bg-white hover:border-sky-400 hover:bg-sky-50 text-gray-700"
                                }`}
                            >
                                <input
                                    type="checkbox"
                                    checked={data?.dias_atencion.includes(dia)}
                                    onChange={() => handleDayToggle(dia)}
                                    className="sr-only"
                                />
                                {dia}
                            </label>
                        ))}
                    </div>
                </section>

                {/* === Horarios === */}
                <section className="space-y-4">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-md">
                            <Clock className="w-5 h-5 text-white" />
                        </div>
                        <h2 className="text-2xl font-semibold text-gray-800">
                            Horario de Atención
                        </h2>
                    </div>

                    <label className="flex items-center gap-2 cursor-pointer mb-4 w-fit">
                        <input
                            type="checkbox"
                            checked={data?.dividir_horario}
                            onChange={(e) =>
                                handleInputChange(
                                    "dividir_horario",
                                    e.target.checked
                                )
                            }
                            className="accent-sky-600 w-5 h-5"
                        />
                        <SplitSquareVertical className="w-5 h-5 text-sky-600" />
                        <span className="text-gray-700 text-sm font-medium">
                            Dividir en dos turnos
                        </span>
                    </label>

                    {/* Primer rango */}
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Hora de inicio
                            </label>
                            <input
                                type="time"
                                value={data?.hora_inicio}
                                onChange={(e) =>
                                    handleInputChange(
                                        "hora_inicio",
                                        e.target.value
                                    )
                                }
                                className="w-full border border-sky-200 rounded-lg p-2 focus:ring-2 focus:ring-sky-500 bg-white"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">
                                Hora de fin
                            </label>
                            <input
                                type="time"
                                {...getFieldProps("hora_fin")}
                                value={data?.hora_fin}
                                onChange={(e) =>
                                    handleInputChange(
                                        "hora_fin",
                                        e.target.value
                                    )
                                }
                                className={`w-full border rounded-lg p-2 transition-all ${
                                    errores.hora_fin
                                        ? "border-red-500"
                                        : getFieldProps("hora_fin").disabled
                                        ? "bg-gray-100 text-gray-400 cursor-not-allowed opacity-70"
                                        : "focus:ring-2 focus:ring-sky-500 border-sky-200 bg-white"
                                }`}
                            />
                        </div>
                    </div>

                    {/* Segundo rango */}
                    {data?.dividir_horario && (
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 border-t border-sky-100 pt-4">
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Inicio (2° turno)
                                </label>
                                <input
                                    type="time"
                                    {...getFieldProps("hora_inicio_2")}
                                    value={data?.hora_inicio_2}
                                    onChange={(e) =>
                                        handleInputChange(
                                            "hora_inicio_2",
                                            e.target.value
                                        )
                                    }
                                    className={`w-full border rounded-lg p-2 transition-all ${
                                        errores.hora_inicio_2
                                            ? "border-red-500"
                                            : getFieldProps("hora_inicio_2")
                                                  .disabled
                                            ? "bg-gray-100 text-gray-400 cursor-not-allowed opacity-70"
                                            : "focus:ring-2 focus:ring-sky-500 border-sky-200 bg-white"
                                    }`}
                                />
                            </div>
                            <div>
                                <label className="block text-sm font-medium text-gray-700 mb-1">
                                    Fin (2° turno)
                                </label>
                                <input
                                    type="time"
                                    {...getFieldProps("hora_fin_2")}
                                    value={data?.hora_fin_2}
                                    onChange={(e) =>
                                        handleInputChange(
                                            "hora_fin_2",
                                            e.target.value
                                        )
                                    }
                                    className={`w-full border rounded-lg p-2 transition-all ${
                                        errores.hora_fin_2
                                            ? "border-red-500"
                                            : getFieldProps("hora_fin_2")
                                                  .disabled
                                            ? "bg-gray-100 text-gray-400 cursor-not-allowed opacity-70"
                                            : "focus:ring-2 focus:ring-sky-500 border-sky-200 bg-white"
                                    }`}
                                />
                            </div>
                        </div>
                    )}
                </section>

                {/* === Capacidad === */}
                <section className="space-y-4">
                    <div className="flex items-center gap-3 mb-2">
                        <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-md">
                            <Users className="w-5 h-5 text-white" />
                        </div>
                        <h2 className="text-2xl font-semibold text-gray-800">
                            Capacidad y Duración
                        </h2>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Duración por bloque (minutos)
                            </label>
                            <input
                                type="number"
                                min="1"
                                value={data?.duracion_bloque}
                                onChange={(e) =>
                                    handleInputChange(
                                        "duracion_bloque",
                                        e.target.value
                                    )
                                }
                                placeholder="30"
                                className="w-full px-4 py-3 border border-sky-200 rounded-lg focus:ring-2 focus:ring-sky-500 bg-white"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">
                                Cupos totales por día
                            </label>
                            <input
                                type="number"
                                value={data?.cupo_por_bloque}
                                disabled
                                className="w-full px-4 py-3 bg-gray-100 border border-sky-200 rounded-lg text-gray-500 cursor-not-allowed"
                            />
                            <p className="text-sm text-gray-400 mt-1">
                                Calculado automáticamente
                            </p>
                        </div>
                    </div>
                </section>

                {/* === Estado === */}
                <section className="space-y-4">
                    <h3 className="text-lg font-semibold text-gray-800 mb-4">
                        Estado de la configuración
                    </h3>
                    <div className="flex flex-col sm:flex-row gap-3">
                        {["activo", "inactivo"].map((estado) => (
                            <label
                                key={estado}
                                className={`flex-1 flex items-center justify-center px-6 py-4 rounded-lg border-2 cursor-pointer transition-all ${
                                    data?.estado === estado
                                        ? "bg-gradient-to-r from-sky-500 to-sky-600 border-sky-600 text-white font-medium shadow-md"
                                        : "border-sky-200 text-gray-700 hover:border-sky-400 hover:bg-sky-50"
                                }`}
                            >
                                <input
                                    type="radio"
                                    name="estado"
                                    value={estado}
                                    checked={data?.estado === estado}
                                    onChange={(e) =>
                                        handleInputChange(
                                            "estado",
                                            e.target.value
                                        )
                                    }
                                    className="sr-only"
                                />
                                {estado.charAt(0).toUpperCase() +
                                    estado.slice(1)}
                            </label>
                        ))}
                    </div>
                </section>

                {/* === Botón === */}
                <div className="pt-6">
                    <button
                        type="submit"
                        className="
          w-full bg-gradient-to-r from-sky-500 to-sky-600 
          hover:from-sky-600 hover:to-sky-700
          text-white font-semibold px-8 py-4 
          rounded-xl shadow-md hover:shadow-lg
          focus:ring-4 focus:ring-sky-300 transition-all
        "
                    >
                        Guardar Configuración
                    </button>
                </div>
            </form>
        </>
    );
};

export default CitasForm;
