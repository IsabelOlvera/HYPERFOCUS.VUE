<template>
    <div class="flex flex-col min-h-screen bg-gray-50">
      <!-- Navbar -->
      <header class="bg-white shadow-md">
        <nav class="container mx-auto flex items-center justify-between py-4 px-6">
            <Link href="/" class="flex items-center space-x-2 hover:opacity-80 transition">
                <i class="fa-solid fa-brain text-purple-500 text-3xl"></i>
                <span class="text-2xl font-bold text-gray-800">Hyperfocus</span>
            </Link>
          <ul class="flex items-center space-x-6">
            <li>
              <a href="/planes" class="text-gray-800">Planes</a>
            </li>
            <li>
              <a href="/preguntas" class="text-gray-800">Preguntas frecuentes</a>
            </li>
          </ul>
          <div class="space-x-4">
            <a
              href="/login"
              class="py-2 px-4 border border-gray-400 rounded text-gray-700 hover:bg-gray-100"
            >
              Iniciar sesión
            </a>
            <a
              href="/register"
              class="py-2 px-4 bg-purple-500 text-white rounded hover:bg-purple-600"
            >
              Registrarse
            </a>
          </div>
        </nav>
      </header>
  
      <!-- Contenido de la Página -->
      <main class="flex-1">
        <slot />
      </main>
  
      <!-- Footer -->
      <footer class="bg-gray-900 text-gray-300 py-8">
        <div class="container mx-auto flex flex-wrap justify-between items-center">
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
  </template>
  

<script setup>
import { Link } from "@inertiajs/vue3";
</script>
