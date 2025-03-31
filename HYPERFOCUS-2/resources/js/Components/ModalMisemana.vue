<template>
    <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
      <div class="bg-white p-6 rounded-lg shadow-lg w-96 text-black ">
        <div class="">
          <h3 class="text-xl font-semibold text-red text-red-500 text-center">{{ title }}</h3>
          
          <!-- <button @click="$emit('close')" class="text-red-500">✖</button> -->
        </div>
        <div class="mt-4">
          <slot>
            
          </slot>
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    isOpen: Boolean,
    title: String
  });
  </script>