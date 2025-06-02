<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const titulo = ref('')
const descripcion = ref('')
const archivo = ref(null)
const mostrarModal = ref(false)
const mensajeModal = ref('')
const modalAbierto = ref(false);
const reporteSeleccionado = ref(null);


function handleFileUpload(event) {
  archivo.value = event.target.files[0]
}

const props = defineProps({
  titulos: Array,
})


function enviarSugerencia() {
  const formData = new FormData()
  formData.append('titulo', titulo.value)
  formData.append('descripcion', descripcion.value)
  if (archivo.value) formData.append('archivo', archivo.value)

  router.post('/reportes', formData)
}

function votar(id) {
  router.post(`/reportes/${id}/votar`, {}, {
    onSuccess: (page) => {
      // Puedes inspeccionar `page.props` si devuelves algo desde el backend
      mensajeModal.value = '¡Tu voto fue registrado con éxito!'
      mostrarModal.value = true
    },
    onError: (errors) => {
      // Si usas validaciones puedes mostrar mensajes personalizados
      mensajeModal.value = 'Ya has votado por esta propuesta'
      mostrarModal.value = true
    }
  })
}

function abrirModal(reporte) {
  reporteSeleccionado.value = reporte;
  modalAbierto.value = true;
}

function claseContornoPorEstatus(estatusId) {
  switch (estatusId) {
    case 1:
      return 'border-2 border-red-400'; // Pendiente
    case 2:
      return 'border-2 border-blue-400';   // En proceso
    case 3:
      return 'border-2 border-green-500';  // Finalizado
    default:
      return 'border border-gray-300';
  }
}


</script>

<template>
  <Head title="Centro de ayuda" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
        Centro de ayuda
      </h2>
    </template>

    <div class="h-screen flex overflow-hidden">
      <!-- Sidebar -->
      <Sidebar class="hidden md:block md:w-1/4" />

      <!-- Contenido principal con scroll interno -->
      <div class="flex-1 overflow-y-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
          <!-- Formulario de sugerencia -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <h3 class="text-2xl font-bold mb-6 text-center text-gray-900 dark:text-white">
              ¿Cuál es tu sugerencia?
            </h3>

            <div class="space-y-4">
              <input
                v-model="titulo"
                type="text"
                placeholder="Título de la sugerencia"
                class="w-full p-3 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white"
              />

              <textarea
                v-model="descripcion"
                rows="4"
                placeholder="Descripción detallada"
                class="w-full p-3 rounded border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white resize-none"
              ></textarea>

              <div class="text-left">
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Adjuntar archivo:</label>
                <input
                  type="file"
                  @change="handleFileUpload"
                  class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-purple-500 file:text-white hover:file:bg-purple-600"
                />
              </div>
            </div>

            <div class="flex justify-center gap-4 mt-6">
              <button
                class="bg-purple-400 text-white font-semibold px-6 py-2 rounded hover:bg-purple-500"
                @click="titulo = ''; descripcion = ''; archivo = null"
              >
                Cancelar
              </button>
              <button
                class="bg-purple-500 text-white font-semibold px-6 py-2 rounded hover:bg-purple-600"
                @click="enviarSugerencia"
              >
                Enviar
              </button>
            </div>
          </div>

          <!-- Línea divisoria -->
          <div class="h-2 bg-purple-500 rounded mb-8"></div>

          <!-- Sección de sugerencias frecuentes -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
  <div
  v-for="reporte in titulos"
  :key="reporte.id"
  :class="[
    'bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-col justify-between transition hover:shadow-lg',
    claseContornoPorEstatus(reporte.estatus_reportes_id)
  ]"
>

    <div>
      <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2 flex items-center gap-2">
        <span class="text-2xl">👤</span> {{ reporte.titulo }}
      </h4>
      <!--<p class="text-gray-700 dark:text-gray-300 text-sm">
        {{ reporte.descripcion }}
      </p>-->
    </div>

    <div class="mt-4 flex justify-between items-center">
  <button
    class="bg-purple-600 text-white font-semibold px-4 py-2 rounded hover:bg-purple-700 transition"
    @click="votar(reporte.id)"
  >
    ⭐ Votar
  </button>
  <button @click="abrirModal(reporte)" class="ml-4 text-sm text-gray-600 hover:underline dark:text-gray-300">
    Ver detalle
  </button>
</div>

  </div>
</div>


        </div>
      </div>
    </div>
  </AuthenticatedLayout>

<!-- Modal -->
<div v-if="mostrarModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
  <div class="bg-white dark:bg-gray-900 rounded-lg shadow-lg p-6 max-w-md w-full text-center">
    <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Notificación</h2>
    <p class="text-gray-700 dark:text-gray-300 mb-6">{{ mensajeModal }}</p>
    <button
      class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition"
      @click="mostrarModal = false"
    >
      Cerrar
    </button>
  </div>
</div>

<teleport to="body">
  <div v-if="modalAbierto" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white dark:bg-gray-900 p-6 rounded-xl shadow-xl max-w-md w-full">
      
      <!-- Título -->
      <h2 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
        {{ reporteSeleccionado?.titulo }}
      </h2>

      

      <!-- Descripción -->
      <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">
        {{ reporteSeleccionado?.descripcion }}
      </p>

      <!-- Solución (si existe) -->
      <p v-if="reporteSeleccionado?.solucion" class="text-sm text-green-600 dark:text-green-300 mt-2">
        <strong>Solución:</strong> {{ reporteSeleccionado.solucion }}
      </p>

      <!-- Estatus con badge de color -->
      <p v-if="reporteSeleccionado?.estatus" class="mb-2">
        <span
          class="inline-block px-3 py-1 rounded-full text-sm font-semibold"
          :class="{
            'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200': reporteSeleccionado.estatus.id === 1,
            'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200': reporteSeleccionado.estatus.id === 2,
            'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200': reporteSeleccionado.estatus.id === 3
          }"
        >
          {{ reporteSeleccionado.estatus.nombre }}
        </span>
      </p>

      <!-- Botón de cerrar -->
      <div class="mt-4 text-right">
        <button
          @click="modalAbierto = false"
          class="bg-gray-300 dark:bg-gray-700 px-4 py-2 rounded hover:bg-gray-400 dark:hover:bg-gray-600"
        >
          Cerrar
        </button>
      </div>
    </div>
  </div>
</teleport>


</template>
