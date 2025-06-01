<script setup>
import { ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';

const props = defineProps({
    practicas: Array
});

// Formatear fecha
const formatearFecha = (fecha) => {
    const opciones = { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return new Date(fecha).toLocaleDateString('es-ES', opciones);
};

// Obtener clase de color para el porcentaje
const getColorClase = (porcentaje) => {
    if (porcentaje >= 80) return 'text-green-600';
    if (porcentaje >= 60) return 'text-yellow-600';
    return 'text-red-600';
};


const volverAConjuntos = () => {
    router.get(route('memory.index')); // Asegúrate de tener esta ruta definida en web.php
};

</script>

<template>
<AuthenticatedLayout>
    <Head title="Historial de Prácticas" />
    
    <div class="p-6 max-w-5xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Historial de Prácticas</h1>
            
            <div class="mb-6">
                    <Link :href="route('memory')" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">
                        Volver a Conjuntos
                    </Link>
                </div>

        </div>
        
        <div v-if="practicas.length === 0" class="bg-gray-50 p-6 rounded-lg text-center">
            <p class="text-gray-600">No has realizado ninguna práctica todavía.</p>
        </div>
        
        <div v-else class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Conjunto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duración</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aciertos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fallos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intentos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Porcentaje</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="practica in practicas" :key="practica.id">
                            <td class="px-6 py-4 whitespace-nowrap font-medium">{{ practica.conjunto.nombre }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatearFecha(practica.fecha_hora_inicio) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ practica.duracion }} min</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-semibold">{{ practica.aciertos }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-semibold">{{ practica.fallos }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ practica.intentos }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-bold text-lg" :class="getColorClase(practica.porcentaje)">
                                    {{ practica.porcentaje }}%
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Resumen de estadísticas generales -->
            <div class="bg-gray-50 p-4 border-t">
                <h2 class="text-lg font-semibold mb-2">Resumen</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-gray-500 text-sm">Total de prácticas</p>
                        <p class="text-2xl font-bold">{{ practicas.length }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-gray-500 text-sm">Promedio de aciertos</p>
                        <p class="text-2xl font-bold text-green-600">
                            {{ Math.round(practicas.reduce((sum, p) => sum + p.aciertos, 0) / practicas.length) || 0 }}
                        </p>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm">
                        <p class="text-gray-500 text-sm">Promedio de porcentaje</p>
                        <p class="text-2xl font-bold" :class="getColorClase(Math.round(practicas.reduce((sum, p) => sum + p.porcentaje, 0) / practicas.length))">
                            {{ Math.round(practicas.reduce((sum, p) => sum + p.porcentaje, 0) / practicas.length) || 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</AuthenticatedLayout>
</template>