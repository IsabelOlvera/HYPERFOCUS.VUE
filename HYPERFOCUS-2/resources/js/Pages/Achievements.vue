<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const chartCanvas = ref(null);

onMounted(() => {
    if (chartCanvas.value) {
        new Chart(chartCanvas.value, {
            type: 'line',
            data: {
                labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                datasets: [{
                    label: 'Consistencia',
                    data: [70, 65, 80, 85, 60, 50, 40],
                    borderColor: '#a855f7',
                    backgroundColor: 'rgba(168, 85, 247, 0.2)',
                    tension: 0.3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
});
</script>

<template>
    <Head title="Mis Logros" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MIS LOGROS
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="grid grid-cols-2 gap-8">
                    <!-- Logros -->
                    <div class="bg-purple-100 p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold text-center flex items-center justify-center gap-2">
                            <span>🏅</span> ¡Mis logros este año! <span>🏆</span>
                        </h3>
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="border-dashed border-2 border-gray-400 p-6 rounded-lg text-center">
                                <p class="text-3xl font-bold text-purple-600">25</p>
                                <p class="text-lg font-bold text-purple-500">Medallas</p>
                            </div>
                            <div class="border-dashed border-2 border-gray-400 p-6 rounded-lg text-center">
                                <p class="text-3xl font-bold text-purple-600">30</p>
                                <p class="text-lg font-bold text-purple-500">Racha de Concentración</p>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfica de consistencia -->
                    <div class="bg-white p-6 rounded-lg shadow-lg">
                        <h3 class="text-lg font-bold text-center">Gráfico de consistencia</h3>
                        <div class="relative h-60">
                            <canvas ref="chartCanvas"></canvas>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <button class="bg-purple-300 px-4 py-2 rounded-lg text-purple-700 font-bold">
                                Expandir gráfico
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
