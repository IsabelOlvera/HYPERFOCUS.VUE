<script setup>
import GuestLayout from '@/Layouts/PlantillaAuth.vue'; // Asegúrate de que sea la plantilla correcta
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue'; // Importamos ref para manejar el estado de visibilidad
import '@fortawesome/fontawesome-free/css/all.css';

const form = useForm({
    name: '',
    apellido: '',
    email: '', // 🔴 Cambio: antes estaba "email"
    password: '', // 🔴 Cambio: antes estaba "password"
    password_confirmation: '', // 🔴 Cambio: antes estaba "password_confirmation"
    edad: '',
    foto_perfil: '',
    rol_id: 2, // 🔴 Asegurar que se envía el rol por defecto
});

const submit = () => {
    console.log('Datos a enviar:', form); // Agrega esto para ver los datos antes de enviar
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
        onError: (errors) => console.log('Errores:', errors), // Agrega para ver errores
        onSuccess: () => console.log('Registro exitoso') // Confirma si llega al éxito
    });
};

// Estado para controlar la visibilidad de la contraseña
const showPassword = ref(false);
const showConfirmPassword = ref(false);
</script>

<template>
    <GuestLayout>
        <Head title="Registro" />

        <div class="flex items-center justify-center min-h-screen">
            <div class="flex bg-white shadow-md rounded-lg overflow-hidden max-w-8xl w-full">
                <!-- Logo -->
                <div class="flex items-center justify-center bg-purple-white p-10 mb-0">
                    <img src="/img/logo.png" alt="Hyperfocus Logo" class="w-80 h-80 object-cover" />
                </div>

                <!-- Formulario -->
                <div class="flex-1 p-10 bg-purple-100">
                    <h2 class="text-2xl font-bold text-gray-800 mb-0">Registrarse</h2>
                    <br>
                    <form @submit.prevent="submit">
                        <input type="hidden" name="_token" :value="$page.props.csrf_token">
                        
                        <!-- Nombre -->
                        <div class="mb-4 text-left">
                            <InputLabel for="nombre" value="Nombre" />
                            <TextInput id="name" v-model="form.name" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <!-- Apellido -->
                        <div class="mb-4 text-left">
                            <InputLabel for="apellido" value="Apellido" />
                            <TextInput id="apellido" v-model="form.apellido" required />
                            <InputError class="mt-2" :message="form.errors.apellido" />
                        </div>

                        <!-- Correo Electrónico -->
                        <div class="mb-4 text-left">
                            <InputLabel for="correo_electronico" value="Correo Electrónico" />
                            <TextInput id="email" type="email" v-model="form.email" required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4 text-left">
                            <InputLabel for="password" value="Contraseña" />
                            <div class="relative">
                                <TextInput
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    required
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm"
                                    @click="showPassword = !showPassword"
                                >
                                    <i :class="showPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'" class="text-gray-500 hover:text-gray-700"></i>
                                </button>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4 text-left">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña" />
                            <div class="relative">
                                <TextInput
                                    id="password_confirmation"
                                    :type="showConfirmPassword ? 'text' : 'password'"
                                    v-model="form.password_confirmation"
                                    required
                                />
                                <button
                                    type="button"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm"
                                    @click="showConfirmPassword = !showConfirmPassword"
                                >
                                    <i :class="showConfirmPassword ? 'fa-solid fa-eye' : 'fa-solid fa-eye-slash'" class="text-gray-500 hover:text-gray-700"></i>
                                </button>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                        <!-- Botón de Registrarse -->
                        <PrimaryButton class="w-full justify-center" :disabled="form.processing">
                            Registrarse
                        </PrimaryButton>
                    </form>

                    <!-- Enlace para iniciar sesión -->
                    <div class="text-center mt-4">
                        <small>
                            ¿Ya tienes cuenta?
                            <Link :href="route('login')" class="text-purple-500 hover:underline">Inicia sesión</Link>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
