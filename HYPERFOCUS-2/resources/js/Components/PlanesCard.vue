<template>
    <div class="bg-white shadow-md rounded-lg p-6 text-center w-full max-w-sm">
      <h2 class="text-2xl font-bold text-gray-800 mb-4">{{ encabezado }}</h2>
      <ul class="text-gray-600 space-y-2 mb-4">
        <li>✔️ {{ punto1 }}</li>
        <li>✔️ {{ punto2 }}</li>
        <li>✔️ {{ punto3 }}</li>
      </ul>
      <p class="text-xl font-semibold text-purple-600 mb-4">{{ precio }}</p>
      <button class="bg-purple-500 text-white py-2 px-4 rounded hover:bg-purple-600">
        Elegir Plan
      </button>
    </div>
  </template>
  
  <script setup>
  defineProps({
    encabezado: String,
    punto1: String,
    punto2: String,
    punto3: String,
    precio: String,
  });
  </script>
  