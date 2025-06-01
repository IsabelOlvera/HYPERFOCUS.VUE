
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { Head } from '@inertiajs/vue3'
import { ref, onMounted, computed } from 'vue'
import { usePage, router } from '@inertiajs/vue3'

const reportes = ref(usePage().props.reportes)

onMounted(() => {
  reportes.value.forEach(r => {
    if (!r.estatus_id && r.estatus) r.estatus_id = r.estatus.id;
  })
})


const estadisticas = usePage().props.estadisticas
const estatusDisponibles = usePage().props.estatus_reportes


// Datos de ejemplo
const users = ref([
  {
    id: 1,
    usuario: 'usuario 1',
    informacion: '-',
    atendido_por: 'Aon 1',
    solucion: '----',
    fecha: 'Feb 26, 2022',
    correo: 'a@gmail.com',
    id_reporte: '03K00340',
    fecha_limite: 'Feb 26, 2024',
    status: 'pendiente',
    comentarios: ''
  },
  {
    id: 10,
    usuario: 'usuario 10',
    informacion: '-',
    atendido_por: 'Aon 3',
    solucion: '----',
    fecha: 'Jan 22, 2022',
    correo: 'a@gmail.com',
    id_reporte: '03K00340',
    fecha_limite: 'Feb 26, 2024',
    status: 'completado',
    comentarios: ''
  }
])

const currentPage = ref(1)
const itemsPerPage = 10

const getStatusColor = (nombre) => {
  switch (nombre?.toLowerCase()) {
    case 'pendiente':
      return 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-200';
    case 'en proceso':
      return 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900 dark:text-blue-200';
    case 'finalizado':
      return 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900 dark:text-green-200';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-700 dark:text-gray-200';
  }
}

const getStatusText = (status) => {
  const texts = {
    'pendiente': 'Pendiente',
    'en_proceso': 'En proceso',  // Clave como viene del backend
    'finalizado': 'Finalizado'   // Clave consistente
  }
  return texts[status] || status;
}

const totalPages = computed(() => Math.ceil(users.value.length / itemsPerPage))

// Función corregida en Vue
const actualizarEstatus = (reporte) => {
  // Asegúrate que estás enviando solo el ID correcto
  router.put(route('reportes.actualizar-estatus', reporte.id), {
    estatus_reportes_id: reporte.estatus_reportes_id // Nombre consistente
  }, {
    preserveScroll: true,
    onSuccess: () => {
      // Actualiza el objeto localmente
      const updatedReporte = reportes.value.find(r => r.id === reporte.id);
      if (updatedReporte) {
        updatedReporte.estatus_reportes_id = reporte.estatus_reportes_id;
        // Actualiza el objeto estatus completo si es necesario
        updatedReporte.estatus = estatusDisponibles.value.find(
          e => e.id === reporte.estatus_reportes_id
        );
      }
      alert('Estatus actualizado correctamente');
    },
    onError: (errors) => {
      console.error('Error completo:', errors.response?.data);
      alert(`Error: ${errors.response?.data?.message || 'Error al actualizar'}`);
    }
  });
}



</script>

<template>
  <Head title="Centro de ayuda" />
  
  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-3xl font-bold text-center bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">
        Centro de ayuda
      </h2>
    </template>
    
    <!-- Contenedor principal con altura fija y scroll vertical -->
    <div class="flex bg-gray-50 dark:bg-gray-900 h-screen overflow-hidden">
      <!-- Sidebar fijo -->
      <div class="w-64 flex-shrink-0 hidden lg:block">
        <Sidebar />
      </div>

      <!-- Contenedor del contenido principal con scroll vertical -->
      <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
        <!-- Contenido scrolleable -->
        <div class="flex-1 overflow-y-auto p-4 lg:p-6">
          
    <!-- Header con estadísticas - Grid responsivo -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-6 mb-6">
  <!-- Total Reportes -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 lg:p-6 border-l-4 border-purple-500">
    <div class="flex items-center justify-between">
      <div class="min-w-0 flex-1">
        <p class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Total Reportes</p>
        <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">{{ estadisticas.total }}</p>
      </div>
      <div class="p-2 lg:p-3 bg-purple-100 dark:bg-purple-900 rounded-full flex-shrink-0 ml-2">
        <svg class="w-4 h-4 lg:w-6 lg:h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
    </div>
  </div>
  
  <!-- Pendientes -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 lg:p-6 border-l-4 border-yellow-500">
    <div class="flex items-center justify-between">
      <div class="min-w-0 flex-1">
        <p class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Pendientes</p>
        <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
          {{ estadisticas.pendientes }}
        </p>
      </div>
      <div class="p-2 lg:p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full flex-shrink-0 ml-2">
        <svg class="w-4 h-4 lg:w-6 lg:h-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
        </svg>
      </div>
    </div>
  </div>
  
  <!-- En Proceso -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 lg:p-6 border-l-4 border-blue-500">
    <div class="flex items-center justify-between">
      <div class="min-w-0 flex-1">
        <p class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">En Proceso</p>
        <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
          {{ estadisticas.en_proceso }}
        </p>
      </div>
      <div class="p-2 lg:p-3 bg-blue-100 dark:bg-blue-900 rounded-full flex-shrink-0 ml-2">
        <svg class="w-4 h-4 lg:w-6 lg:h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
        </svg>
      </div>
    </div>
  </div>
  
  <!-- Finalizados -->
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-3 lg:p-6 border-l-4 border-green-500">
    <div class="flex items-center justify-between">
      <div class="min-w-0 flex-1">
        <p class="text-xs lg:text-sm font-medium text-gray-600 dark:text-gray-400 truncate">Finalizados</p>
        <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">
          {{ estadisticas.finalizados }}
        </p>
      </div>
      <div class="p-2 lg:p-3 bg-green-100 dark:bg-green-900 rounded-full flex-shrink-0 ml-2">
        <svg class="w-4 h-4 lg:w-6 lg:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
        </svg>
      </div>
    </div>
  </div>
</div>
          
          <!-- Filtros y búsqueda -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 lg:p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
              <div class="flex flex-col sm:flex-row gap-3 flex-1 lg:flex-none">
                <input 
                  type="text" 
                  placeholder="Buscar usuario..." 
                  class="px-3 py-2 lg:px-4 lg:py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm lg:text-base flex-1 lg:w-64"
                />
                <select class="px-3 py-2 lg:px-4 lg:py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm lg:text-base flex-1 lg:w-48">
                  <option value="">Todos los estados</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="en_progreso">En Progreso</option>
                  <option value="completado">Completado</option>
                </select>
              </div>
              <button class="bg-gradient-to-r from-purple-500 to-blue-600 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:from-purple-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl text-sm lg:text-base whitespace-nowrap flex-shrink-0">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Nuevo Usuario
              </button>
            </div>
          </div>
          
          <!-- Contenedor de tabla con control de altura y scroll -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden flex flex-col">
            <!-- Contenedor con altura máxima y scroll vertical para la tabla -->
            <div class="flex-1 overflow-hidden">
              <div class="overflow-x-auto overflow-y-auto max-h-96 lg:max-h-[500px]">
                <div class="min-w-full inline-block align-middle">
                  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white sticky top-0 z-10">
                      <tr>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Usuario
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Información
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Atendido por
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Solución
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Fecha
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Correo
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          ID Reporte
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Fecha Límite
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Status
                        </th>
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">
                          Acciones
                        </th>
                      </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
  <tr
    v-for="(reporte, index) in reportes"
    :key="reporte.id"
    class="hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors duration-200"
    :class="index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800'"
  >
    <!-- Usuario -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
      <div class="flex items-center min-w-0">
        <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs lg:text-sm flex-shrink-0">
          {{ reporte.usuario?.name.charAt(0).toUpperCase() }}
        </div>
        <div class="ml-2 lg:ml-4 min-w-0">
          <div class="text-xs lg:text-sm font-medium text-gray-900 dark:text-white">
            {{ reporte.usuario?.name }}
          </div>
        </div>
      </div>
    </td>

    <!-- Información (Descripción del reporte) -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400">
      <div class="max-w-xs truncate">{{ reporte.descripcion }}</div>
    </td>

    <!-- Atendido por -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
      <span class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
        {{ reporte.asignado_a?.name || 'Sin asignar' }}
      </span>
    </td>

    <!-- Solución (Podrías usar estatus si no tienes campo específico) -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400">
      <div class="max-w-xs truncate">
        {{ reporte.estatus?.nombre || 'Pendiente' }}
      </div>
    </td>

    <!-- Fecha -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
      {{ new Date(reporte.fecha_generacion).toLocaleDateString() }}
    </td>

    <!-- Correo del usuario -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
      <a href="mailto:{{ reporte.usuario?.email }}" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 text-xs lg:text-sm">
        {{ reporte.usuario?.email }}
      </a>
    </td>

    <!-- ID Reporte -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
      <span class="inline-flex items-center px-2 py-1 rounded text-xs font-mono bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
        {{ reporte.id }}
      </span>
    </td>

    <!-- Fecha Límite (si no existe, puedes usar otra lógica o dejarlo vacío) -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
      {{ reporte.fecha_limite ? new Date(reporte.fecha_limite).toLocaleDateString() : 'No definida' }}
    </td>

    <!-- Status -->
    <td class="py-3 px-4 whitespace-nowrap">
  <select
    v-model="reporte.estatus_reportes_id"
    @change="actualizarEstatus(reporte)"
    class="text-xs px-2 py-1 rounded border dark:bg-gray-700 dark:text-white"
  >
    <option v-for="estado in estatusDisponibles" :key="estado.id" :value="estado.id">
      {{ estado.nombre }}
    </option>
  </select>
</td>



    <!-- Acciones (puedes conectar estos botones a rutas o métodos) -->
    <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
      <div class="flex gap-1 lg:gap-2">
        <button class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 lg:p-2 rounded-lg transition">
          <!-- Edit icon -->
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
          </svg>
        </button>
        <button class="bg-green-500 hover:bg-green-600 text-white p-1.5 lg:p-2 rounded-lg transition">
          <!-- View icon -->
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
          </svg>
        </button>
        <button class="bg-red-500 hover:bg-red-600 text-white p-1.5 lg:p-2 rounded-lg transition">
          <!-- Delete icon -->
          <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
    </td>
  </tr>
</tbody>

                  </table>
                </div>
              </div>
            </div>
            
            <!-- Paginación fija en la parte inferior -->
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 lg:px-6 lg:py-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
              <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs lg:text-sm text-gray-700 dark:text-gray-300 order-2 sm:order-1">
                  Mostrando <span class="font-medium">1</span> a <span class="font-medium">10</span> de <span class="font-medium">{{ users.length }}</span> resultados
                </div>
                <div class="flex gap-1 lg:gap-2 order-1 sm:order-2">
                  <button 
                    class="px-3 py-1.5 lg:px-4 lg:py-2 text-xs lg:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === 1"
                  >
                    Anterior
                  </button>
                  <button 
                    v-for="page in totalPages" 
                    :key="page"
                    class="px-3 py-1.5 lg:px-4 lg:py-2 text-xs lg:text-sm font-medium rounded-lg transition-colors duration-200"
                    :class="page === currentPage 
                      ? 'text-white bg-gradient-to-r from-purple-500 to-blue-600 shadow-lg' 
                      : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700'"
                    @click="currentPage = page"
                  >
                    {{ page }}
                  </button>
                  <button 
                    class="px-3 py-1.5 lg:px-4 lg:py-2 text-xs lg:text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed"
                    :disabled="currentPage === totalPages"
                  >
                    Siguiente
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos para evitar desbordamiento */
* {
  box-sizing: border-box;
}

/* Scrollbar personalizado */
::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Dark mode scrollbar */
.dark ::-webkit-scrollbar-track {
  background: #374151;
}

.dark ::-webkit-scrollbar-thumb {
  background: #6b7280;
}

.dark ::-webkit-scrollbar-thumb:hover {
  background: #9ca3af;
}

/* Efectos hover para las filas de la tabla */
tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Animación para los botones */
button:hover:not(:disabled) {
  transform: translateY(-1px);
}
/* Gradiente animado para el header */
.bg-gradient-to-r {
  background-size: 200% 200%;
  animation: gradient 3s ease infinite;
}

@keyframes gradient {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

/* Optimización para pantallas más pequeñas */
@media (max-width: 1024px) {
  .table-fixed {
    table-layout: auto;
  }
}
</style>