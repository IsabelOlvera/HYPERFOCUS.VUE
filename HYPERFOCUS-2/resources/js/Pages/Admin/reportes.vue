<script setup>
// --- Importaciones ---
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { Head } from '@inertiajs/vue3'
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'


// --- Props ---
const props = defineProps({
    reportes: { type: Array, required: true, default: () => [] },
    estadisticas: { type: Object, required: true },
    estatus_reportes: { type: Array, required: true },
    filtros: { type: Object, required: false, default: () => ({}) },
    visible: Boolean,
})

// --- Estados ---
const modalAbierto = ref(false)
const archivoActual = ref('')
const solucionModal = ref(false)
const reporteActual = ref(null)
const solucionTexto = ref('')
const emit = defineEmits(['cancelar', 'confirmar'])
const mostrarModalEliminar = ref(false)
const idReporteAEliminar = ref(null)

// --- Computed ---
const estatusDisponibles = computed(() => props.estatus_reportes)

const reportesSeguros = computed(() => 
    props.reportes.map(reporte => ({
        ...reporte,
        solucion: reporte.solucion || null,
        estatus_id: reporte.estatus_id || (reporte.estatus ? reporte.estatus.id : null)
    }))
)

// --- Métodos CRUD ---
const abrirModalSolucion = (reporte) => {
    reporteActual.value = reporte
    solucionTexto.value = reporte.solucion || ''
    solucionModal.value = true
}

const guardarSolucion = () => {
    if (!reporteActual.value) return
    
    router.put(route('reportes.agregar-solucion', reporteActual.value.id), {
        solucion: solucionTexto.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            const index = props.reportes.findIndex(r => r.id === reporteActual.value.id)
            if (index !== -1) {
                props.reportes[index].solucion = solucionTexto.value
                props.reportes[index].estatus_reportes_id = 3 // Finalizado
            }
            solucionModal.value = false
        }
    })
}

// --- Manejo de archivos ---
const verArchivo = (archivoPath) => {
    if (!archivoPath) return
    
    const extension = archivoPath.split('.').pop().toLowerCase()
    const url = `/storage/${archivoPath}`
    
    if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
        archivoActual.value = url
        modalAbierto.value = true
    } else {
        window.open(url, '_blank')
    }
}

// --- Gestión de estatus ---
const actualizarEstatus = (reporte) => {
    router.put(route('reportes.actualizar-estatus', reporte.id), {
        estatus_reportes_id: reporte.estatus_reportes_id
    }, {
        preserveScroll: true,
        onSuccess: () => {
            const index = props.reportes.findIndex(r => r.id === reporte.id)
            if (index !== -1) {
                props.reportes[index].estatus_reportes_id = reporte.estatus_reportes_id
            }
        }
    })
}

// --- Helpers de UI ---
const getStatusColor = (nombre) => {
    switch (nombre?.toLowerCase()) {
        case 'pendiente': return 'bg-yellow-100 text-yellow-800 border-yellow-300 dark:bg-yellow-900 dark:text-yellow-200'
        case 'en proceso': return 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900 dark:text-blue-200'
        case 'finalizado': return 'bg-green-100 text-green-800 border-green-300 dark:bg-green-900 dark:text-green-200'
        default: return 'bg-gray-100 text-gray-800 border-gray-300 dark:bg-gray-700 dark:text-gray-200'
    }
}

const getStatusText = (status) => ({
    'pendiente': 'Pendiente',
    'en_proceso': 'En proceso',
    'finalizado': 'Finalizado'
})[status] || status

const estatus_reportes_id = ref(props.filtros.estatus_reportes_id || '')
const usuario_nombre = ref(props.filtros?.usuario_nombre || '')


function aplicarFiltros() {
  router.get('/admin/reportes', {
    estatus_reportes_id: estatus_reportes_id.value,
    usuario_nombre: usuario_nombre.value,
  }, {
    preserveState: true,
    preserveScroll: true,
  })
}

const eliminarReporte = (reporte) => {
  if (confirm(`¿Estás seguro de eliminar el reporte #${reporte.id}? Esta acción no se puede deshacer.`)) {
    router.delete(`/reportes/${reporte.id}`, {
      preserveScroll: true,
      onSuccess: () => {
        alert('Reporte eliminado correctamente.')
      },
      onError: (error) => {
        alert('Hubo un error al eliminar el reporte.')
        console.error(error)
      }
    })
  }
}

function cancelar() {
  mostrarModalEliminar.value = false
  idReporteAEliminar.value = null
}

function confirmar() {
  if (!idReporteAEliminar.value) return

  router.delete(route('reportes.destroy', idReporteAEliminar.value), {
    preserveScroll: true,
    onSuccess: () => {
      mostrarModalEliminar.value = false
      idReporteAEliminar.value = null
    },
    onError: () => {
      alert('Hubo un error al eliminar el reporte.')
    }
  })
}


function pedirConfirmacionEliminar(id) {
  idReporteAEliminar.value = id
  mostrarModalEliminar.value = true
}

function confirmarEliminacion() {
  router.delete(route('reportes.destroy', idReporteAEliminar.value), {
    onSuccess: () => {
      mostrarModalEliminar.value = false
      idReporteAEliminar.value = null
    },
  })
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
    
    <!-- Layout principal -->
    <div class="flex bg-gray-50 dark:bg-gray-900 h-screen overflow-hidden">
      <!-- Sidebar -->
      <div class="w-64 flex-shrink-0 hidden lg:block">
        <Sidebar />
      </div>

      <!-- Contenido principal -->
      <div class="flex-1 min-w-0 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-y-auto p-4 lg:p-6">
          
          <!-- Estadísticas -->
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
                  <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">{{ estadisticas.pendientes }}</p>
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
                  <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">{{ estadisticas.en_proceso }}</p>
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
                  <p class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">{{ estadisticas.finalizados }}</p>
                </div>
                <div class="p-2 lg:p-3 bg-green-100 dark:bg-green-900 rounded-full flex-shrink-0 ml-2">
                  <svg class="w-4 h-4 lg:w-6 lg:h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                  </svg>
                </div>
              </div>
            </div>
          </div>
          
          <!-- Filtros -->
        
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-4 lg:p-6 mb-6">
            <div class="flex flex-col lg:flex-row gap-4 items-stretch lg:items-center">
              
              <div class="flex flex-col sm:flex-row gap-3 flex-1 lg:flex-none">

                <!-- Input de usuario_id -->
                <input 
                  type="text" 
                  v-model="usuario_nombre"
                  placeholder="Buscar por ID de usuario..." 
                  class="px-3 py-2 lg:px-4 lg:py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm lg:text-base flex-1 lg:w-64"
                />

                <!-- Select de estatus -->
                <select 
                  v-model="estatus_reportes_id" 
                  class="px-3 py-2 lg:px-4 lg:py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-sm lg:text-base flex-1 lg:w-48"
                >
                  <option value="">Todos los estados</option>
                  <option 
                    v-for="estatus in estatus_reportes" 
                    :key="estatus.id" 
                    :value="estatus.id"
                  >
                    {{ estatus.nombre }}
                  </option>
                </select>

              </div>


              <!--<button class="bg-gradient-to-r from-purple-500 to-blue-600 text-white px-4 py-2 lg:px-6 lg:py-2 rounded-lg hover:from-purple-600 hover:to-blue-700 transition-all duration-200 shadow-lg hover:shadow-xl text-sm lg:text-base whitespace-nowrap flex-shrink-0">
                <svg class="w-4 h-4 lg:w-5 lg:h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                </svg>
                Nuevo Usuario
              </button>-->

              <button 
                @click="aplicarFiltros" 
                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700"
              >
                Aplicar filtros
              </button>

            </div>
          </div>
          
          <!-- Tabla de reportes -->
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden flex flex-col">
            <div class="flex-1 overflow-hidden">
              <div class="overflow-x-auto overflow-y-auto max-h-96 lg:max-h-[500px]">
                <div class="min-w-full inline-block align-middle">
                  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <!-- Encabezados de columna -->
                    <thead class="bg-gradient-to-r from-purple-600 to-blue-600 text-white sticky top-0 z-10">
                      <tr>
                        <!-- Columna: Nombre del usuario -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Usuario</th>
                        <!-- Columna: Título del reporte -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Información</th>
                        <!-- Columna: Descripción del reporte -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Detalles</th>
                        <!-- Columna: Persona asignada para atender -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Atendido por</th>
                        <!-- Columna: Solución del reporte -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Solución</th>
                        <!-- Columna: Fecha de creación -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Fecha</th>
                        <!-- Columna: Correo del usuario -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Correo</th>
                        <!-- Columna: ID del reporte -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">ID Reporte</th>
                        <!-- Columna: Fecha límite -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Fecha Límite</th>
                        <!-- Columna: Estatus -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Status</th>
                        <!-- Columna: Votos totales-->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider">Votos</th>
                        <!-- Columna: Acciones -->
                        <th class="px-3 py-3 lg:px-6 lg:py-4 text-left text-xs font-semibold uppercase tracking-wider whitespace-nowrap">Acciones</th>
                      </tr>
                    </thead>

                    <!-- Cuerpo de la tabla -->
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                      <tr
                        v-for="(reporte, index) in reportes"
                        :key="reporte.id"
                        class="hover:bg-purple-50 dark:hover:bg-gray-700 transition-colors duration-200"
                        :class="index % 2 === 0 ? 'bg-gray-50 dark:bg-gray-900' : 'bg-white dark:bg-gray-800'"
                      >
                        <!-- Celda: Usuario -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
                          <div class="flex items-center min-w-0">
                            <!-- Inicial del nombre del usuario -->
                            <div class="w-8 h-8 lg:w-10 lg:h-10 bg-gradient-to-br from-purple-400 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold text-xs lg:text-sm flex-shrink-0">
                              {{ reporte.usuario?.name.charAt(0).toUpperCase() }}
                            </div>
                            <!-- Nombre del usuario -->
                            <div class="ml-2 lg:ml-4 min-w-0">
                              <div class="text-xs lg:text-sm font-medium text-gray-900 dark:text-white">
                                {{ reporte.usuario?.name }}
                              </div>
                            </div>
                          </div>
                        </td>

                        <!-- Celda: Información (título del reporte) -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400">
                          <div class="max-w-xs truncate">{{ reporte.titulo }}</div>
                        </td>

                        <!-- Celda: Detalles (descripción) -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                          <div class="whitespace-normal break-words" :title="reporte.descripcion" v-if="reporte.descripcion">
                            {{ reporte.descripcion }}
                          </div>
                          <span v-else class="text-gray-400 italic">Sin detalles</span>
                        </td>

                        <!-- Celda: Atendido por (nombre del asignado) -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
                          <span class="inline-flex items-center px-2 py-1 lg:px-3 lg:py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            {{ reporte.asignado_a?.name || 'Sin asignar' }}
                          </span>
                        </td>

                        <!-- Celda: Solución -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 max-w-xs">
                          <div v-if="reporte.solucion" class="whitespace-normal break-words">
                            {{ reporte.solucion }}
                          </div>
                          <button
                            v-else
                            @click="abrirModalSolucion(reporte)"
                            class="text-xs text-blue-500 hover:text-blue-700 dark:text-blue-400"
                          >
                            Agregar solución
                          </button>
                        </td>

                        <!-- Celda: Fecha de creación -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                          {{ new Date(reporte.fecha_generacion).toLocaleString('es-MX') }}
                        </td>

                        <!-- Celda: Correo -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
                          <a :href="`mailto:${reporte.usuario?.email}`" class="text-purple-600 hover:text-purple-900 dark:text-purple-400 dark:hover:text-purple-300 text-xs lg:text-sm">
                            {{ reporte.usuario?.email }}
                          </a>
                        </td>

                        <!-- Celda: ID del reporte -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
                          <span class="inline-flex items-center px-2 py-1 rounded text-xs font-mono bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            {{ reporte.id }}
                          </span>
                        </td>

                        <!-- Celda: Fecha límite -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 text-xs lg:text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                          {{ reporte.fecha_solucion ? new Date(reporte.fecha_solucion).toLocaleString('es-MX') : 'No definida' }}
                        </td>

                        <!-- Celda: Estatus del reporte -->
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

                        <!-- Celda: Total de votos -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap text-xs lg:text-sm text-center text-gray-700 dark:text-gray-200">
                          <span class="inline-flex items-center px-2 py-1 bg-indigo-100 dark:bg-indigo-800 text-indigo-800 dark:text-indigo-200 rounded-full">
                            {{ reporte.total_votos > 0 ? reporte.total_votos : '0' }}
                          </span>
                        </td>


                        <!-- Celda: Acciones -->
                        <td class="px-3 py-3 lg:px-6 lg:py-4 whitespace-nowrap">
                          <div class="flex gap-1 lg:gap-2">

                            <!-- 
                            ==========================================================================================
                            Botón: Editar 
                            ==========================================================================================
                            <button class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 lg:p-2 rounded-lg transition">
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                              </svg>
                            </button>-->

                            <!-- Botón: Agregar solución -->
                            <button 
                              @click="abrirModalSolucion(reporte)"
                              class="bg-blue-500 hover:bg-blue-600 text-white p-1.5 lg:p-2 rounded-lg transition"
                              title="Agregar solución"
                            >
                              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                              </svg>
                            </button>


                            <!-- Botón: Ver archivo adjunto -->
                            <button 
                              @click="verArchivo(reporte.archivo_adjunto)"
                              class="p-1.5 lg:p-2 rounded-lg transition"
                              :class="{
                                'bg-green-500 hover:bg-green-600 text-white': reporte.archivo_adjunto,
                                'bg-gray-300 text-gray-500 cursor-not-allowed': !reporte.archivo_adjunto
                              }"
                              :disabled="!reporte.archivo_adjunto"
                              :title="reporte.archivo_adjunto ? 'Ver archivo adjunto' : 'No hay archivo disponible'"
                            >
                              <svg 
                                class="w-4 h-4" 
                                fill="currentColor" 
                                viewBox="0 0 20 20"
                                :class="{ 'opacity-50': !reporte.archivo_adjunto }"
                              >
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                              </svg>
                            </button>

                            <!-- Botón: Eliminar -->
                            <button
                              v-if="reporte.estatus?.nombre === 'Finalizado'"
                              @click="pedirConfirmacionEliminar(reporte.id)"
                              class="bg-red-500 hover:bg-red-600 text-white p-1.5 lg:p-2 rounded-lg transition"
                              title="Eliminar reporte"
                            >
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

            
            <!-- Paginación -->
            <div class="bg-gray-50 dark:bg-gray-900 px-4 py-3 lg:px-6 lg:py-4 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
              <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
                <div class="text-xs lg:text-sm text-gray-700 dark:text-gray-300 order-2 sm:order-1">
                  Mostrando <span class="font-medium">1</span> a <span class="font-medium">10</span> de 
                  <span class="font-medium">{{ users?.length || 0 }}</span> resultados
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

    <!-- Modal de vista previa -->
    <div v-if="modalAbierto" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg max-w-4xl max-h-screen overflow-auto">
        <div class="flex justify-between items-center p-4 border-b">
          <h3 class="text-lg font-medium">Vista previa</h3>
          <button @click="modalAbierto = false" class="text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <img :src="archivoActual" class="max-w-full max-h-[80vh] mx-auto" alt="Archivo adjunto">
      </div>
    </div>

    <!-- Modal de solución -->
    <div v-if="solucionModal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-lg max-w-2xl w-full">
        <div class="flex justify-between items-center p-4 border-b">
          <h3 class="text-black font-medium">Agregar Solución</h3>
          <button @click="solucionModal = false" class="text-gy-500 hover:text-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <textarea 
            v-model="solucionTexto"
            class="w-full h-40 p-2 border rounded-lg dark:bg-gray-700 dark:border-gray-600"
            placeholder="Escribe aquí la solución al reporte..."
          ></textarea>
        </div>
        <div class="flex justify-end gap-2 p-4 border-t">
          <button 
            @click="solucionModal = false" 
            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 dark:bg-gray-600 dark:text-gray-200"
          >
            Cancelar
          </button>
          <button 
            @click="guardarSolucion" 
            class="px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600"
            :disabled="!solucionTexto.trim()"
          >
            Guardar Solución
          </button>
        </div>
      </div>
    </div>

    <!-- Modal de eliminar solución-->
     <div
        v-if="mostrarModalEliminar"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
      >
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 w-full max-w-md">
          <h2 class="text-lg lg:text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Confirmar eliminación
          </h2>

          <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">
            ¿Estás seguro de que deseas eliminar este reporte? Esta acción no se puede deshacer.
          </p>

          <div class="flex justify-end space-x-3">
            <button
              @click="cancelar"
              class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition"
            >
              Cancelar
            </button>
            <button
              @click="confirmar"
              class="px-4 py-2 text-sm rounded-lg bg-red-600 text-white hover:bg-red-700 transition"
            >
              Eliminar
            </button>
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