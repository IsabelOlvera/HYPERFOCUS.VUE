<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/PlantillaAuth.vue";
import Swal from "sweetalert2";

defineProps({
  canResetPassword: {
    type: Boolean,
  },
  status: {
    type: String,
  },
});

const form = useForm({
  email: "",
  password: "",
  remember: false,
});

const submit = () => {
  form.post(route("login"), {
    onFinish: () => form.reset("password"),
  });
};

// Función para mostrar alerta de recuperación de contraseña
const showPasswordResetAlert = () => {
  Swal.fire({
    title: "Correo enviado",
    text: "Se ha enviado un enlace de restablecimiento de contraseña a tu correo.",
    icon: "success",
    confirmButtonText: "Aceptar",
    confirmButtonColor: "#3085d6",
    background: "#f8f9fa",
  });
};
</script>

<template>
    <GuestLayout>
      <Head title="Iniciar Sesión" />
  
      <div class="bg-white shadow-md rounded-lg p-6 w-full max-w-md text-center mx-auto my-8">
        <!-- Logo arriba del título -->
        <img src="/img/logo.png" alt="Hyperfocus Logo" class="mx-auto w-24 mb-4" />
  
        <h2 class="text-2xl font-bold text-gray-800">Iniciar Sesión</h2>
  
        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
          {{ status }}
        </div>
  
        <form @submit.prevent="submit" class="mt-4">
          <!-- Correo -->
          <div class="mb-4 text-left">
            <label class="block text-gray-700">Correo Electrónico</label>
            <input
              type="email"
              v-model="form.email"
              required
              autofocus
              autocomplete="username"
              class="w-full p-2 border rounded"
              placeholder="Ingresa tu correo"
            />
            <small class="text-red-500">{{ form.errors.email }}</small>
          </div>
  
          <!-- Contraseña -->
          <div class="mb-4 text-left">
            <label class="block text-gray-700">Contraseña</label>
            <input
              type="password"
              v-model="form.password"
              required
              autocomplete="current-password"
              class="w-full p-2 border rounded"
              placeholder="********"
            />
            <small class="text-red-500">{{ form.errors.password }}</small>
          </div>
  
          <!-- Botón de Iniciar Sesión -->
          <button
            type="submit"
            class="w-full bg-purple-500 text-white p-2 rounded hover:bg-purple-600"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Iniciar Sesión
          </button>
        </form>
  
        <!-- Olvidaste tu contraseña -->
        <div class="text-center mt-4">
          <button
            v-if="canResetPassword"
            @click="showPasswordResetAlert"
            class="text-purple-500 hover:underline"
          >
            ¿Olvidaste tu contraseña?
          </button>
        </div>
  
        <!-- Enlace para registrarse -->
        <div class="text-center mt-4">
          <small>
            ¿No tienes cuenta?
            <Link href="/register" class="text-purple-500 hover:underline">Regístrate aquí</Link>
          </small>
        </div>
      </div>
    </GuestLayout>
  </template>  