<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
</script>

<template>
  <AuthenticatedLayout>
    <div class="flex bg-white">
      <!-- Sidebar -->
      <Sidebar />

      <!-- Contenido Principal -->
      <div class="flex-1">
        <!-- Banner -->
        <section class="bg-purple-500 text-white text-center py-10 rounded-b-lg">
          <h1 class="text-5xl font-bold mb-2">¡Un día a la vez!</h1>
          <p class="text-lg">Organiza, enfócate y trabaja de manera eficiente en tus metas.</p>
        </section>

        <!-- Sección principal -->
        <main class="container mx-auto py-10 px-6 max-w-6xl bg-white">
          <!-- Ayuda -->
          <h2 class="text-2xl font-bold text-center mb-8">¿En qué necesitas ayuda?</h2>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
            <div class="flex flex-col items-center">
              <img src="/img/sugerencias.png" alt="Sugerencia" class="w-20 h-20 mb-4" />
              <h3 class="text-xl font-semibold mb-2">Realizar sugerencia</h3>
              <a href="/centro-ayuda" class="bg-purple-700 text-white px-6 py-2 rounded-full hover:bg-purple-800 text-center">Aquí</a>
            </div>

            <div class="flex flex-col items-center">
              <img src="/img/guide.png" alt="Guía" class="w-20 h-20 mb-4" />
              <h3 class="text-xl font-semibold mb-2">Guía</h3>
              <button class="bg-purple-700 text-white px-6 py-2 rounded-full hover:bg-purple-800">Aquí</button>
            </div>

            <div class="flex flex-col items-center">
              <img src="/img/comun.png" alt="Problemas comunes" class="w-20 h-20 mb-4" />
              <h3 class="text-xl font-semibold mb-2">Problemas comunes</h3>
              <a href="/preguntas" class="bg-purple-700 text-white px-6 py-2 rounded-full hover:bg-purple-800 text-center">Aquí</a>
            </div>

          </div>

          <!-- Calificación -->
          <section class="mt-16 bg-pink-100 p-6 rounded-lg max-w-2xl mx-auto">
            <h3 class="text-xl font-bold mb-2">¡Califica la app!</h3>
            <div class="flex items-start space-x-4">
              <img src="/images/maria-avatar.png" alt="María" class="w-14 h-14 rounded-full object-cover" />
              <div class="flex-1">
                <p class="font-semibold text-gray-800">MARÍA</p>
                <div class="text-gray-400 text-xl my-1">★★★★★</div>
                <input
                  type="text"
                  placeholder="Escribe tu experiencia"
                  class="w-full p-2 border rounded-lg"
                />
              </div>
              <button class="bg-purple-600 text-white px-4 py-2 rounded-lg self-end">Enviar</button>
            </div>
          </section>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-8">
          <div class="container mx-auto flex flex-wrap justify-between items-center px-6">
            <div class="flex items-center space-x-4">
              <i class="fa-brands fa-twitter text-lg"></i>
              <i class="fa-brands fa-instagram text-lg"></i>
              <i class="fa-brands fa-youtube text-lg"></i>
            </div>
            <div class="flex items-center space-x-2">
              <i class="fa-solid fa-brain text-purple-500 text-lg"></i>
              <span>© 2024 Hyperfocus</span>
            </div>
            <ul class="flex space-x-6">
              <li><a href="/planes" class="hover:text-white">Planes</a></li>
              <li><a href="/preguntas" class="hover:text-white">Preguntas</a></li>
            </ul>
          </div>
        </footer>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
