<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                INICIO
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="grid grid-cols-12 gap-4">
                    <!-- Columna Izquierda (Actividades del día) -->
                    <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-2">Actividades De Hoy:</h3>
                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <p class="font-bold text-gray-700 dark:text-gray-300">Monday 18</p>
                            <div class="mt-2 space-y-2">
                                <div class="p-2 bg-purple-100 text-purple-700 rounded">09:00 AM - 10:00 AM <b>Mathematics</b></div>
                                <div class="p-2 bg-green-100 text-green-700 rounded">10:00 AM - 11:00 AM <b>English</b></div>
                                <div class="p-2 bg-pink-100 text-pink-700 rounded">11:00 AM - 12:00 PM <b>Psychology</b></div>
                            </div>
                        </div>
                    </div>

                    <!-- Columna Derecha (Progreso) -->
                    <div class="col-span-8 grid grid-cols-2 gap-4">
                        <!-- Progreso del Día -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center">
                            <h3 class="text-md font-semibold">Proceso del Día</h3>
                            <div class="relative w-32 h-32 flex items-center justify-center">
                                <svg class="w-32 h-32">
                                    <circle cx="50%" cy="50%" r="45" stroke="black" stroke-width="6" fill="none"></circle>
                                    <circle cx="50%" cy="50%" r="45" stroke="purple" stroke-width="6" fill="none" stroke-dasharray="283" stroke-dashoffset="20"></circle>
                                </svg>
                                <span class="absolute text-xl font-bold text-purple-600">92%</span>
                            </div>
                        </div>

                        <!-- Progreso Semanal -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center">
                            <h3 class="text-md font-semibold">Proceso Semanal</h3>
                            <div class="relative w-40 h-40 flex items-center justify-center">
                                <svg class="w-40 h-40">
                                    <circle cx="50%" cy="50%" r="50" stroke="black" stroke-width="6" fill="none"></circle>
                                    <circle cx="50%" cy="50%" r="50" stroke="purple" stroke-width="6" fill="none" stroke-dasharray="314" stroke-dashoffset="80"></circle>
                                </svg>
                                <span class="absolute text-xl font-bold text-purple-600">75%</span>
                            </div>
                        </div>

                        <!-- Tareas Completadas -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow col-span-2 flex justify-between">
                            <div>
                                <h3 class="text-md font-semibold">Tareas Completadas</h3>
                                <div class="flex space-x-1 mt-2">
                                    <span v-for="i in 7" :key="i" class="w-4 h-4 rounded-full" :class="i <= 5 ? 'bg-purple-600' : 'bg-gray-300'"></span>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-md font-semibold">Tareas Faltantes</h3>
                                <div class="flex space-x-1 mt-2">
                                    <span v-for="i in 7" :key="i" class="w-4 h-4 rounded-full" :class="i > 5 ? 'bg-red-600' : 'bg-gray-300'"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
