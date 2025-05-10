<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    usuario: Object,
    consultaA: Array, // Aquí llegan las actividades desde el backend
    totalActD: Number,
    totalActS: Number,
    totalActRS: Number,
    fechaHoy: String,
    nombreDia: String
});

// Manejo del progreso de actividades
const progreso = ref({});
props.consultaA.forEach(actividad => {
    progreso.value[actividad.id] = actividad.completada;
});

const guardarProgreso = () => {
    router.post('/guardar-progreso', progreso.value);
};

// Calcular el color de cada tarjeta según el estado de la actividad
const getCardColor = (completada) => {
    return completada ? "bg-green-100 text-green-700" : "bg-red-100 text-red-700";
};

const porcentajeDiario = computed(() => {
    return props.totalActD > 0
        ? Math.round((Object.values(progreso.value).filter(val => val === 1).length / props.totalActD) * 100)
        : 100;
});

const porcentajeSemanal = computed(() => {
    return Math.round((props.totalActRS / props.totalActS) * 100);
});

const radio = 45;
const perimetro = 2 * Math.PI * radio;

const dashOffsetDiario = computed(() => {
    return perimetro - (perimetro * porcentajeDiario.value) / 100;
});

const dashOffsetSemanal = computed(() => {
    return perimetro - (perimetro * porcentajeSemanal.value) / 100;
});

const tareasEstatus = computed(() => {
    return props.consultaA.map(actividad => actividad.completada ? 'bg-purple-600' : 'bg-red-600');
});


</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                ¡Hola, {{ usuario.name }}!
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="grid grid-cols-12 gap-4">
                     <!-- Actividades del día -->
                     <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-2">Actividades de Hoy: {{ nombreDia }} {{ fechaHoy }}</h3>

                        <div v-if="consultaA.length === 0">
                            <p class="text-green-600">¡Yuju! No tienes actividades para hoy.</p>
                        </div>

                        <div v-else class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                            <form @submit.prevent="guardarProgreso" class="space-y-2">
                                <div v-for="actividad in consultaA" :key="actividad.id" 
                                    class="p-2 rounded flex items-center justify-between"
                                    :class="getCardColor(progreso[actividad.id])">
                                    <span>{{ actividad.nombre }}</span>
                                    <input 
                                        type="checkbox" 
                                        v-model="progreso[actividad.id]" 
                                        :true-value="1" 
                                        :false-value="0"
                                    />
                                </div>
                                <button type="submit" class="mt-3 bg-green-500 text-white px-4 py-2 rounded w-full">
                                    Guardar Progreso
                                </button>
                            </form>
                        </div>
                    </div>

                    

                    <!-- Progreso -->
                    <div class="col-span-8 grid grid-cols-2 gap-4">
                            <!-- Progreso del Día -->
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center">
                                <h3 class="text-md font-semibold">Progreso del Día</h3>
                                <div class="relative w-32 h-32 flex items-center justify-center">
                                    <svg class="w-32 h-32" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" stroke="black" stroke-width="6" fill="none"></circle>
                                        <circle 
                                            cx="50" cy="50" r="45" 
                                            stroke="purple" stroke-width="6" 
                                            fill="none" stroke-dasharray="283"
                                            :stroke-dashoffset="dashOffsetDiario"
                                            class="transition-all duration-500"
                                        ></circle>
                                    </svg>
                                    <span class="absolute text-xl font-bold text-purple-600">{{ porcentajeDiario }}%</span>
                                </div>
                            </div>

                            <!-- Progreso Semanal -->
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow flex flex-col items-center">
                                <h3 class="text-md font-semibold">Progreso Semanal</h3>
                                <div class="relative w-32 h-32 flex items-center justify-center">
                                    <svg class="w-32 h-32" viewBox="0 0 100 100">
                                        <circle cx="50" cy="50" r="45" stroke="black" stroke-width="6" fill="none"></circle>
                                        <circle 
                                            cx="50" cy="50" r="45" 
                                            stroke="purple" stroke-width="6" 
                                            fill="none" stroke-dasharray="283"
                                            :stroke-dashoffset="dashOffsetSemanal"
                                            class="transition-all duration-500"
                                        ></circle>
                                    </svg>
                                    <span class="absolute text-xl font-bold text-purple-600">{{ porcentajeSemanal }}%</span>
                                </div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow col-span-2">
                                <h3 class="text-md font-semibold text-center">Estatus de Tareas</h3>
                                <div class="flex justify-center space-x-1 mt-2">
                                    <span v-for="(estatus, index) in tareasEstatus" 
                                        :key="index" 
                                        class="w-4 h-4 rounded-full" 
                                        :class="estatus">
                                    </span>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
