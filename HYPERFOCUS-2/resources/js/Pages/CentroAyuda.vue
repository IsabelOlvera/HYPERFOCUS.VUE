<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const titulo = ref('')
const descripcion = ref('')
const archivo = ref(null)

function handleFileUpload(event) {
  archivo.value = event.target.files[0]
}

function enviarSugerencia() {
  const formData = new FormData()
  formData.append('titulo', titulo.value)
  formData.append('descripcion', descripcion.value)
  if (archivo.value) formData.append('archivo', archivo.value)

  router.post('/reportes', formData)
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
          <div>
            <h3 class="text-2xl font-bold mb-4 text-gray-900 dark:text-white text-center">
              Sugerencias Frecuentes
            </h3>
            <div class="text-center font-semibold text-gray-600 dark:text-gray-400 mb-4">
              Puedes votar
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
              <div
                v-for="i in 4"
                :key="i"
                class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 flex items-center justify-between"
              >
                <div class="flex items-center gap-2">
                  <span class="text-2xl">👤</span>
                  <p class="text-lg font-semibold text-gray-800 dark:text-white">
                    “Lorem ipsum”
                  </p>
                </div>
                <button
                  class="flex items-center bg-black text-white px-4 py-2 rounded shadow hover:bg-gray-800 transition"
                >
                  <span class="mr-2">⭐</span> Votar
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
