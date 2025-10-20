import DashboardLayout from "../../Funcionario/Funcionario";
import CitasForm from "./CitasForm";
import { usePage, useForm } from "@inertiajs/react";

export default function Page() {
    const { props } = usePage();
    const { cita, tramites, flash } = props;
    const { data, setData, put, reset } = useForm(cita);
    const handleSubmit = (e) => {
        e.preventDefault();
        put(route("citas.update", cita.id), {
            onSuccess: () => {
                if (flash?.success) {
                    alert(flash.success);
                }
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
