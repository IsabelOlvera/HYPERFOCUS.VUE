<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
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
                            <svg class="w-16 h-16 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10M5 11h14m-7 6v-3m-6 0a6 6 0 1112 0 6 6 0 01-12 0z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800">Gestión del tiempo</p>
                    </Link>

                    <!-- Memoria y recuerdo -->
                    <Link href="/memory" class="relative transition-transform transform hover:scale-110">
                        <div class="w-40 h-40 bg-purple-700 shadow-xl rounded-full flex items-center justify-center">
                            <svg class="w-20 h-20 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a10 10 0 100 20 10 10 0 000-20z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6l4 2"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800 text-center">Memoria y recuerdo</p>
                    </Link>

                    <!-- Foco y concentración -->
                    <Link href="/focus" class="flex flex-col items-center transition-transform transform hover:scale-110">
                        <div class="w-32 h-32 bg-purple-500 shadow-lg rounded-full flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold mt-4 text-gray-800">Foco y concentración</p>
                    </Link>
                </div>
            </div>

            <!-- Sección de reseñas -->
            <div class="mt-16 bg-purple-100 p-8 rounded-lg w-full max-w-3xl shadow-lg">
                <h2 class="text-2xl font-bold text-center mb-6">¡CALIFICA TU EXPERIENCIA!</h2>
                
                <!-- Reseñas existentes -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center shadow">
                                <svg class="w-6 h-6 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A12.008 12.008 0 0112 14a12.008 12.008 0 016.879 3.804m-9.758 0A16.978 16.978 0 0112 21a16.978 16.978 0 016.879-3.196"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="font-bold">MARÍA</p>
                                <p class="text-yellow-500">★★★★★</p>
                            </div>
                        </div>
                        <p class="mt-2 text-gray-600">Estoy encantada con el servicio y la atención. Recomendable 100%.</p>
                    </div>
                </div>

                <!-- Formulario para calificación -->
                <div class="mt-6 text-center">
                    <p class="text-lg font-semibold">DÉJANOS TU RESEÑA ESTAREMOS ENCANTADOS DE LEEROS</p>
                    <form class="mt-4">
                        <div class="flex justify-center space-x-1">
                            <span class="text-yellow-500 text-2xl cursor-pointer">★</span>
                            <span class="text-yellow-500 text-2xl cursor-pointer">★</span>
                            <span class="text-yellow-500 text-2xl cursor-pointer">★</span>
                            <span class="text-yellow-500 text-2xl cursor-pointer">★</span>
                            <span class="text-gray-400 text-2xl cursor-pointer">☆</span>
                        </div>
                        <textarea 
                            class="w-full mt-3 p-2 border rounded resize-none"
                            rows="3"
                            placeholder="Escribe tu experiencia"
                        ></textarea>
                        <button 
                            class="bg-purple-600 text-white px-6 py-2 mt-3 rounded hover:bg-purple-700"
                        >
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
