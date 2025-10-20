"use client";

import { useState, useRef } from "react";
import { Camera } from "lucide-react";
import FormSection from "@/components/ui/form-section";
import InputLabel from "@/components/ui/input-label";
import TextInput from "@/components/ui/text-input";
import InputError from "@/components/ui/input-error";
import ActionMessage from "@/components/ui/action-message";

export default function UpdateProfileInformationForm({ user }) {
    const photoInputRef = useRef(null);
    const [form, setForm] = useState({
        photo: null,
        photoPreview: null,
        cuil: user?.cuil || "27291146967",
        nombre: user?.nombre || "Alicia Adela Raquel",
        apellidoPaterno: user?.apellidoPaterno || "ARANDA",
        fechaNacimiento: user?.fechaNacimiento || "1982-05-03",
        telefonoCelular: user?.telefonoCelular || "261 232 3480",
        telefonoFijo: user?.telefonoFijo || "",
        calle: user?.calle || "Pinzón",
        numero: user?.numero || "444",
        depto: user?.depto || "",
        barrio: user?.barrio || "21 de Julio",
        codigoPostal: user?.codigoPostal || "5505",
        referencias: user?.referencias || "",
        email: user?.email || "admin@sistema.com",
        processing: false,
        recentlySuccessful: false,
        errors: {},
    });

    const updateProfileInformation = (e) => {
        e.preventDefault();

        setForm((prev) => ({ ...prev, processing: true }));

        // Simulate API call
        setTimeout(() => {
            console.log("Updating profile:", form);
            setForm((prev) => ({
                ...prev,
                processing: false,
                recentlySuccessful: true,
            }));

            setTimeout(() => {
                setForm((prev) => ({ ...prev, recentlySuccessful: false }));
            }, 2000);
        }, 500);
    };

    const selectNewPhoto = () => {
        photoInputRef.current?.click();
    };

    const updatePhotoPreview = (e) => {
        const file = e.target.files?.[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (e) => {
            setForm((prev) => ({
                ...prev,
                photo: file,
                photoPreview: e.target.result,
            }));
        };
        reader.readAsDataURL(file);
    };

    const deletePhoto = () => {
        setForm((prev) => ({
            ...prev,
            photo: null,
            photoPreview: null,
        }));
        if (photoInputRef.current) {
            photoInputRef.current.value = "";
        }
    };

    const handleChange = (field, value) => {
        setForm((prev) => ({ ...prev, [field]: value }));
    };

    return (
        <FormSection
            title="Información del Perfil"
            description="Actualiza la información de perfil y dirección de correo electrónico de tu cuenta."
            onSubmit={updateProfileInformation}
        >
            {/* Profile Photo */}
            <div>
                <InputLabel value="Foto" />
                <input
                    ref={photoInputRef}
                    type="file"
                    accept="image/*"
                    onChange={updatePhotoPreview}
                    className="hidden"
                />

                <div className="flex items-center gap-6 mt-2">
                    <div className="relative">
                        {form.photoPreview ? (
                            <img
                                src={form.photoPreview || "/placeholder.svg"}
                                alt="Profile"
                                className="w-20 h-20 rounded-full object-cover border-4 border-slate-200"
                            />
                        ) : (
                            <div className="w-20 h-20 rounded-full bg-gradient-to-br from-sky-400 to-sky-600 border-4 border-slate-200 flex items-center justify-center">
                                <span className="text-2xl font-bold text-white">
                                    {form.nombre.charAt(0)}
                                    {form.apellidoPaterno.charAt(0)}
                                </span>
                            </div>
                        )}
                        <button
                            type="button"
                            onClick={selectNewPhoto}
                            className="absolute bottom-0 right-0 p-2 bg-sky-500 text-white rounded-full hover:bg-sky-600 transition-colors shadow-lg"
                        >
                            <Camera className="w-4 h-4" />
                        </button>
                    </div>

                    <div className="flex gap-3">
                        <button
                            type="button"
                            onClick={selectNewPhoto}
                            className="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                        >
                            Seleccionar Nueva Foto
                        </button>
                        {form.photoPreview && (
                            <button
                                type="button"
                                onClick={deletePhoto}
                                className="px-4 py-2 bg-slate-200 text-slate-700 rounded-lg hover:bg-slate-300 transition-colors"
                            >
                                Eliminar Foto
                            </button>
                        )}
                    </div>
                </div>
                <InputError message={form.errors.photo} />
            </div>

            {/* Mi perfil section */}
            <div className="space-y-4">
                <h4 className="text-lg font-bold text-slate-900">Mi perfil</h4>
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    {/* Email */}
                    <div>
                        <InputLabel htmlFor="email" value="Email" required />
                        <TextInput
                            id="email"
                            type="email"
                            value={form.email}
                            onChange={(e) =>
                                handleChange("email", e.target.value)
                            }
                            placeholder="tu@email.com"
                        />
                        <InputError message={form.errors.email} />
                    </div>

                    {/* CUIL */}
                    <div>
                        <InputLabel htmlFor="cuil" value="CUIL" required />
                        <TextInput
                            id="cuil"
                            value={form.cuil}
                            onChange={(e) =>
                                handleChange("cuil", e.target.value)
                            }
                            placeholder="CUIL"
                        />
                        <InputError message={form.errors.cuil} />
                    </div>
                </div>

                {/* Nombre and Apellido paterno */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <InputLabel htmlFor="nombre" value="Nombre" required />
                        <TextInput
                            id="nombre"
                            value={form.nombre}
                            onChange={(e) =>
                                handleChange("nombre", e.target.value)
                            }
                            placeholder="Nombre"
                        />
                        <InputError message={form.errors.nombre} />
                    </div>
                    <div>
                        <InputLabel
                            htmlFor="apellidoPaterno"
                            value="Apellido paterno"
                            required
                        />
                        <TextInput
                            id="apellidoPaterno"
                            value={form.apellidoPaterno}
                            onChange={(e) =>
                                handleChange("apellidoPaterno", e.target.value)
                            }
                            placeholder="Apellido paterno"
                        />
                        <InputError message={form.errors.apellidoPaterno} />
                    </div>
                </div>

                {/* Fecha de nacimiento and Teléfono celular */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <InputLabel
                            htmlFor="fechaNacimiento"
                            value="Fecha de nacimiento"
                        />
                        <TextInput
                            id="fechaNacimiento"
                            type="date"
                            value={form.fechaNacimiento}
                            onChange={(e) =>
                                handleChange("fechaNacimiento", e.target.value)
                            }
                        />
                        <InputError message={form.errors.fechaNacimiento} />
                    </div>
                    <div>
                        <InputLabel
                            htmlFor="telefonoCelular"
                            value="Teléfono celular"
                        />
                        <TextInput
                            id="telefonoCelular"
                            type="tel"
                            value={form.telefonoCelular}
                            onChange={(e) =>
                                handleChange("telefonoCelular", e.target.value)
                            }
                            placeholder="Teléfono celular"
                        />
                        <InputError message={form.errors.telefonoCelular} />
                    </div>
                </div>
            </div>

            {/* Mi dirección section */}
            <div className="space-y-4">
                <h4 className="text-lg font-bold text-slate-900">
                    Mi dirección
                </h4>

                {/* Teléfono fijo and Calle */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <InputLabel
                            htmlFor="telefonoFijo"
                            value="Teléfono fijo"
                        />
                        <TextInput
                            id="telefonoFijo"
                            type="tel"
                            value={form.telefonoFijo}
                            onChange={(e) =>
                                handleChange("telefonoFijo", e.target.value)
                            }
                            placeholder="Teléfono fijo"
                        />
                        <InputError message={form.errors.telefonoFijo} />
                    </div>
                    <div>
                        <InputLabel htmlFor="calle" value="Calle" />
                        <TextInput
                            id="calle"
                            value={form.calle}
                            onChange={(e) =>
                                handleChange("calle", e.target.value)
                            }
                            placeholder="Calle"
                        />
                        <InputError message={form.errors.calle} />
                    </div>
                </div>

                {/* Número and Depto */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <InputLabel htmlFor="numero" value="Número" />
                        <TextInput
                            id="numero"
                            value={form.numero}
                            onChange={(e) =>
                                handleChange("numero", e.target.value)
                            }
                            placeholder="Número"
                        />
                        <InputError message={form.errors.numero} />
                    </div>
                    <div>
                        <InputLabel htmlFor="depto" value="Depto." />
                        <TextInput
                            id="depto"
                            value={form.depto}
                            onChange={(e) =>
                                handleChange("depto", e.target.value)
                            }
                            placeholder="Depto."
                        />
                        <InputError message={form.errors.depto} />
                    </div>
                </div>

                {/* Barrio and Código postal */}
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <div>
                        <InputLabel htmlFor="barrio" value="Barrio" />
                        <TextInput
                            id="barrio"
                            value={form.barrio}
                            onChange={(e) =>
                                handleChange("barrio", e.target.value)
                            }
                            placeholder="Barrio"
                        />
                        <InputError message={form.errors.barrio} />
                    </div>
                    <div>
                        <InputLabel
                            htmlFor="codigoPostal"
                            value="Código postal"
                        />
                        <TextInput
                            id="codigoPostal"
                            value={form.codigoPostal}
                            onChange={(e) =>
                                handleChange("codigoPostal", e.target.value)
                            }
                            placeholder="Código postal"
                        />
                        <InputError message={form.errors.codigoPostal} />
                    </div>
                </div>

                {/* Referencias */}
                <div>
                    <InputLabel htmlFor="referencias" value="Referencias" />
                    <textarea
                        id="referencias"
                        value={form.referencias}
                        onChange={(e) =>
                            handleChange("referencias", e.target.value)
                        }
                        rows={3}
                        className="w-full px-4 py-3 bg-white border-2 border-slate-200 rounded-lg focus:outline-none focus:border-sky-500 hover:border-sky-300 transition-colors resize-none"
                        placeholder="Referencias de ubicación"
                    />
                    <InputError message={form.errors.referencias} />
                </div>
            </div>

            {/* Actions */}
            <div className="flex items-center justify-end gap-4">
                <ActionMessage on={form.recentlySuccessful}>
                    Guardado.
                </ActionMessage>
                <button
                    type="submit"
                    disabled={form.processing}
                    className="px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors shadow-sm disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Guardar
                </button>
            </div>
        </FormSection>
    );
}
