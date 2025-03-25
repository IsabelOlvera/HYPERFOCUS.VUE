<template>
    <div class="border border-gray-300 rounded-lg overflow-hidden">
      <button
        class="w-full text-left p-4 bg-gray-200 hover:bg-gray-300 transition-all duration-300"
        @click="toggle"
      >
        <span class="font-semibold text-lg">{{ pregunta }}</span>
        <span class="float-right">{{ isOpen ? "▲" : "▼" }}</span>
      </button>
      <div v-if="isOpen" class="p-4 bg-white border-t border-gray-300">
        {{ respuesta }}
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from "vue";
  
  defineProps({
    pregunta: String,
    respuesta: String,
  });
  
  const isOpen = ref(false);
  const toggle = () => {
    isOpen.value = !isOpen.value;
  };
  </script>
  