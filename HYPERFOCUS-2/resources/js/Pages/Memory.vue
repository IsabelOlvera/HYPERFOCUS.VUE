<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Memoria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MEMORIA
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8 flex justify-center space-x-8">
                <!-- Práctica 1 -->
                <div class="bg-purple-100 p-6 rounded-lg shadow-lg w-1/3">
                    <div class="bg-purple-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                        <span>✔ PRÁCTICA 1</span>
                    </div>
                    <p class="mt-4 text-gray-700 font-bold">## Conceptos</p>
                    <hr class="my-2 border-gray-400" />
                    <div class="space-y-3 mt-4">
                        <button class="w-full bg-purple-400 text-white px-4 py-2 rounded-lg">Practicar</button>
                        <button class="w-full bg-blue-400 text-white px-4 py-2 rounded-lg">Editar</button>
                        <button class="w-full bg-red-400 text-white px-4 py-2 rounded-lg">Eliminar</button>
                    </div>
                </div>

                <!-- Crear nueva práctica -->
                <div class="bg-purple-100 p-6 rounded-lg shadow-lg w-1/3">
                    <div class="bg-purple-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                        <span>➕ CREAR</span>
                    </div>
                    <hr class="my-2 border-gray-400" />
                    <div class="flex flex-col items-center mt-6">
                        <div class="bg-purple-400 text-white rounded-full p-4 text-3xl">+</div>
                        <button class="mt-4 bg-purple-500 text-white px-6 py-2 rounded-lg">Crear</button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
