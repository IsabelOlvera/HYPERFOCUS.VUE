<script setup>
import PlantillaIndex from "@/Layouts/PlantillaIndex.vue";
import PreguntaCard from "@/Components/PreguntaCard.vue";

const preguntas = [
  { pregunta: "¿Pregunta 1?", respuesta: "Aquí debe ir toda la respuesta detallada de la pregunta 1." },
  { pregunta: "¿Pregunta 2?", respuesta: "Aquí debe ir toda la respuesta detallada de la pregunta 2." },
  { pregunta: "¿Pregunta 3?", respuesta: "Aquí debe ir toda la respuesta detallada de la pregunta 3." },
  { pregunta: "¿Pregunta 4?", respuesta: "Aquí debe ir toda la respuesta detallada de la pregunta 4." },
  { pregunta: "¿Pregunta 5?", respuesta: "Aquí debe ir toda la respuesta detallada de la pregunta 5." },
];
</script>

<template>
  <PlantillaIndex>
    <div class="bg-purple-200 py-10 rounded-b-lg">
      <h1 class="text-center text-5xl font-bold text-gray-800">Preguntas Frecuentes</h1>
    </div>

    <div class="container mx-auto my-10 max-w-3xl space-y-4">
      <PreguntaCard
        v-for="(item, index) in preguntas"
        :key="index"
        :pregunta="item.pregunta"
        :respuesta="item.respuesta"
      />
    </div>
  </PlantillaIndex>
</template>
