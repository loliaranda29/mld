<template>
    <app-layout title="Nuevo Usuario">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <strong>NUEVO USUARIO</strong>
            </div>
            <form @submit.prevent="submit">
            <div class="card-body">
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" v-model="form.name" />
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" v-model="form.email" />
                     <label for="password" class="form-label">Contraseña</label>
                    <input :type="input_password" class="form-control" id="password"  name="password"  v-model="form.password" />
                     <label for="password_confirm" class="form-label">Confirmar Contraseña</label>
                    <input :type="input_password" class="form-control" id="password_confirm" name="password_confirm"  v-model="form.password_confirm" />
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" role="switch" id="verPassword" v-model="verPassword" />
                        <label class="form-check-label" for="verPassword">Ver Contraseña</label>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary text-white"><strong>GUARDAR</strong></button>
                </div>
            </div>
            </form>
        </div>
    </app-layout>
</template>

<script>
import { reactive } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import AppLayout from '@/Layouts/AppLayout.vue';
export default {
    components: {
        AppLayout
    },
    setup() {
        const form = reactive({
            name: null,
            email: null,
            password: null,
            password_confirm: null
        })
        function submit() {
            Inertia.post(route('users.post'), form)
        }
        return {form, submit}
    },
    data() {
        return {
            verPassword: false,
        }
    },
    computed: {
        input_password() {
           return this.verPassword ? 'text':'password';
        }
    },
    methods: {

    },
}
</script>
