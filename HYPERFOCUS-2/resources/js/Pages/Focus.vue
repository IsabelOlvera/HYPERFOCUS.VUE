<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const hours = ref(0);
const minutes = ref(20);
const seconds = ref(0);

const concentrationIntervals = ref(2);
const concentrationTime = ref(9);
const breakIntervals = ref(1);
const breakTime = ref(2);
</script>

<template>
    <Head title="Concentración" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                CONCENTRACIÓN
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <p class="text-gray-700 dark:text-gray-300 text-center">
                    Ingresa la cantidad de tiempo que quieres concentrarte y nosotros generamos tu ciclo de concentración.
                </p>

                <!-- Configuración de tiempo -->
                <div class="flex justify-center items-center mt-6 bg-purple-100 p-4 rounded-lg">
                    <div class="flex space-x-4">
                        <div class="bg-purple-300 px-6 py-4 text-center rounded-lg">
                            <p class="text-2xl font-bold">{{ hours.toString().padStart(2, '0') }}</p>
                            <p class="text-sm">H</p>
                        </div>
                        <p class="text-2xl font-bold">•</p>
                        <div class="bg-purple-300 px-6 py-4 text-center rounded-lg">
                            <p class="text-2xl font-bold">{{ minutes.toString().padStart(2, '0') }}</p>
                            <p class="text-sm">MIN</p>
                        </div>
                        <p class="text-2xl font-bold">•</p>
                        <div class="bg-purple-300 px-6 py-4 text-center rounded-lg">
                            <p class="text-2xl font-bold">{{ seconds.toString().padStart(2, '0') }}</p>
                            <p class="text-sm">SEG</p>
                        </div>
                    </div>
                    <div class="ml-4">
                        <span class="text-2xl">✏</span>
                    </div>
                </div>

                <!-- Intervalos -->
                <div class="mt-8 flex justify-around">
                    <!-- Intervalos de concentración -->
                    <div class="text-center">
                        <p class="font-bold">Intervalos de concentración</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="bg-purple-300 px-3 py-1 rounded-lg text-lg font-bold">{{ concentrationIntervals }}</span>
                            <p>De</p>
                            <span class="bg-purple-500 text-white px-4 py-1 rounded-lg text-lg font-bold">{{ concentrationTime }} min</span>
                        </div>
                    </div>

                    <!-- Intervalos de descanso -->
                    <div class="text-center">
                        <p class="font-bold">Intervalos de descanso</p>
                        <div class="flex items-center space-x-2 mt-2">
                            <span class="bg-purple-300 px-3 py-1 rounded-lg text-lg font-bold">{{ breakIntervals }}</span>
                            <p>De</p>
                            <span class="bg-purple-500 text-white px-4 py-1 rounded-lg text-lg font-bold">{{ breakTime }} min</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
