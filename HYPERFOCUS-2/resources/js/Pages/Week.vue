<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Mi Semana" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MI SEMANA
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="grid grid-cols-12 gap-4">
                    <!-- Sección Izquierda (Días de la Semana) -->
                    <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="space-y-4">
                            <div v-for="day in ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']" :key="day">
                                <h3 class="font-bold text-purple-600">{{ day }}</h3>
                                <ul class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <li v-for="i in 3" :key="i" class="text-sm text-purple-500">
                                        Actividad {{ i }} <input type="checkbox" class="ml-2">
                                    </li>
                                </ul>
                                <hr class="my-2 border-gray-300">
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Agregar Actividades -->
                    <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow space-y-4">
                        <div v-for="day in ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes']" :key="day">
                            <h3 class="font-bold text-purple-600">Agregar actividades</h3>
                            <div class="space-y-2">
                                <input type="text" placeholder="Nombre" class="w-full p-2 border rounded">
                                <div class="flex space-x-2">
                                    <input type="number" placeholder="Duración" class="p-2 border rounded w-1/2">
                                    <select class="p-2 border rounded w-1/2">
                                        <option>Alta</option>
                                        <option>Media</option>
                                        <option>Baja</option>
                                    </select>
                                </div>
                                <button class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                            </div>
                            <hr class="my-2 border-gray-300">
                        </div>
                    </div>

                    <!-- Sección de Distribución de Tiempo -->
                    <div class="col-span-4 bg-purple-100 p-4 rounded-lg shadow">
                        <h3 class="font-bold text-purple-600 text-lg flex items-center">
                            ✅ DISTRIBUCIÓN DE TIEMPO
                        </h3>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Tiempo libre:</h4>
                                <div class="flex space-x-2 my-2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                </div>
                                <button class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                                <div class="mt-2 p-2 border rounded">14:00 a 13:00 🗑</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Tiempo ocupado:</h4>
                                <div class="flex space-x-2 my-2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                </div>
                                <button class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                                <div class="mt-2 p-2 border rounded">14:00 a 13:00 🗑</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
