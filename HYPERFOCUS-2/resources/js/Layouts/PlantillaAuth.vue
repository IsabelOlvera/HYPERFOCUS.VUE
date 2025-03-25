<script setup>
import { Link } from "@inertiajs/vue3"; // Asegúrate de que Link esté correctamente importado
</script>

<template>
  <div class="flex flex-col min-h-screen bg-gray-50">
    <!-- Navbar -->
    <header class="bg-white shadow-md z-10 fixed w-full">
      <nav class="container mx-auto flex items-center justify-between py-4 px-6">
        <!-- Enlace a la página de inicio -->
        <Link href="/" class="flex items-center space-x-2 hover:opacity-80 transition">
          <!-- Ícono del cerebro -->
          <i class="fa-solid fa-brain text-purple-500 text-3xl"></i>
          <!-- Texto "Hyperfocus" -->
          <span class="text-2xl font-bold text-gray-800">Hyperfocus</span>
        </Link>
      </nav>
    </header>

    <!-- Contenido de la Página -->
    <main class="flex-1 relative pt-16"> <!-- Añadimos pt-16 para compensar la altura de la navbar -->
      <!-- Imagen de fondo -->
      <div
        class="absolute inset-0 bg-cover bg-center bg-no-repeat"
        style="background-image: url('/img/fondo.png');"
      ></div>

      <!-- Contenido centrado sobre la imagen de fondo -->
      <div class="relative z-10 flex items-center justify-center min-h-screen">
        <slot />
      </div>
    </main>
  </div>
</template>