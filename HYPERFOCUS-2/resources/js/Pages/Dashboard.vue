<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from "vue";

// Estado de las reseñas (solo en memoria)
const reviews = ref([
  { name: "MARÍA", rating: 5, comment: "Estoy encantada con el servicio y la atención. Recomendable 100%." }
]);

// Estado para nueva reseña
const nombreUsuario = ref("");
const newRating = ref(0);
const newComment = ref("");

// Función para actualizar la puntuación de estrellas
const setRating = (value) => {
  newRating.value = value;
};

// Función para agregar una reseña
const addReview = () => {
  if (nombreUsuario.value.trim() === "" || newRating.value === 0 || newComment.value.trim() === "") return;

  reviews.value.push({
    name: nombreUsuario.value,
    rating: newRating.value,
    comment: newComment.value
  });

  // Resetear formulario
  nombreUsuario.value = "";
  newRating.value = 0;
  newComment.value = "";
};
</script>

<template>
    <Head title="Inicio" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                INICIO
            </h2>
        </template>

        <div class="py-12 bg-gray-100 min-h-screen flex flex-col items-center">
            <!-- Sección: ¿Con qué quieres empezar hoy? -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-black mb-12">¿Con qué quieres empezar hoy?</h1>
                <div class="flex flex-col md:flex-row items-center justify-center gap-10">
                    
                    <!-- Gestión del tiempo -->
                    <Link href="/week" class="flex flex-col items-center transition-transform transform hover:scale-110">
                        <div class="w-32 h-32 bg-purple-500 shadow-lg rounded-full flex items-center justify-center">
                            <!-- Icono de reloj -->
                            <svg class="w-16 h-16 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800">Gestión del tiempo</p>
                    </Link>

                    <!-- Memoria y recuerdo -->
                    <Link href="/memory" class="relative transition-transform transform hover:scale-110">
                        <div class="w-40 h-40 bg-purple-700 shadow-xl rounded-full flex items-center justify-center">
                            <!-- Icono de cerebro -->
                            <svg class="w-20 h-20 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 12a8 8 0 00-8-8m0 16a8 8 0 01-8-8m8 8V4m0 16c-1.5-2-4-2-4-6s2.5-4 4-6 4 2 4 6-2.5 4-4 6z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800 text-center">Memoria y recuerdo</p>
                    </Link>

                    <!-- Foco y concentración -->
                    <Link href="/focus" class="flex flex-col items-center transition-transform transform hover:scale-110">
                        <div class="w-32 h-32 bg-purple-500 shadow-lg rounded-full flex items-center justify-center">
                            <!-- Dejar el icono tal como estaba -->
                            <svg class="w-16 h-16 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800">Foco y concentración</p>
                    </Link>
                    </div>
            <!-- Sección de Reseñas -->
            <div class="mt-16 bg-purple-100 p-8 rounded-lg w-full max-w-3xl shadow-lg">
                <h2 class="text-2xl font-bold text-center mb-6">¡CALIFICA TU EXPERIENCIA!</h2>

                <!-- Lista de reseñas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div
                    v-for="(review, index) in reviews"
                    :key="index"
                    class="bg-white p-4 rounded shadow"
                >
                    <div class="flex items-center">
                    <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center shadow">
                        <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A12.008 12.008 0 0112 14a12.008 12.008 0 016.879 3.804m-9.758 0A16.978 16.978 0 0112 21a16.978 16.978 0 016.879-3.196"
                        />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-bold">{{ review.name }}</p>
                        <p class="text-yellow-500">{{ "★".repeat(review.rating) + "☆".repeat(5 - review.rating) }}</p>
                    </div>
                    </div>
                    <p class="mt-2 text-gray-600">{{ review.comment }}</p>
                </div>
                </div>

                <!-- Formulario para calificación -->
                <div class="mt-6 text-center">
                <p class="text-lg font-semibold">DÉJANOS TU RESEÑA</p>
                <form class="mt-4" @submit.prevent="addReview">
                    <!-- Campo de Nombre -->
                    <input
                    v-model="nombreUsuario"
                    type="text"
                    class="w-full p-2 border rounded"
                    placeholder="Tu nombre"
                    />

                    <!-- Estrellas clickeables -->
                    <div class="flex justify-center space-x-1 mt-3">
                    <span
                        v-for="star in 5"
                        :key="star"
                        @click="setRating(star)"
                        class="text-2xl cursor-pointer"
                        :class="star <= newRating ? 'text-yellow-500' : 'text-gray-400'"
                    >
                        ★
                    </span>
                    </div>

                    <!-- Campo de Comentario -->
                    <textarea 
                    v-model="newComment"
                    class="w-full mt-3 p-2 border rounded resize-none"
                    rows="3"
                    placeholder="Escribe tu experiencia"
                    ></textarea>

                    <!-- Botón de enviar -->
                    <button
                    type="submit"
                    class="bg-purple-600 text-white px-6 py-2 mt-3 rounded hover:bg-purple-700 disabled:bg-gray-400"
                    :disabled="nombreUsuario.trim() === '' || newRating === 0 || newComment.trim() === ''"
                    >
                    Enviar
                    </button>
                </form>
                </div>
            </div>
            </div>  
        </div>
    </AuthenticatedLayout>
</template>