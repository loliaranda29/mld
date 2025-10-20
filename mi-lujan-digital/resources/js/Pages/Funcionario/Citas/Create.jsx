import DashboardLayout from "../../Funcionario/Funcionario";
import CitasForm from "./CitasForm";
import { usePage, useForm } from "@inertiajs/react";
export default function Page() {
    const { props } = usePage();
    const { tramites, flash } = props;
    const { data, setData, post, reset } = useForm({
        tramite_id: null,
        fecha_inicio: "",
        fecha_fin: "",
        todo_el_anio: false,
        dias_atencion: [],
        hora_inicio: "",
        hora_fin: "",
        dividir_horario: false,
        hora_inicio_2: "",
        hora_fin_2: "",
        duracion_bloque: "",
        cupo_por_bloque: 0,
        estado: "activo",
    });
    const handleSubmit = (e) => {
        e.preventDefault();
        post(route("citas.store"), {
            onSuccess: () => {
                if (flash?.success) {
                    alert(flash?.success);
                }

                reset();
            },
            onError: (errors) => {
                alert(errors.message);
            },
        });
    };
    return (
        <DashboardLayout>
            <CitasForm
                data={data}
                setData={setData}
                reset={reset}
                handleSubmit={handleSubmit}
                tramites={tramites}
            />
        </DashboardLayout>
    );
}
