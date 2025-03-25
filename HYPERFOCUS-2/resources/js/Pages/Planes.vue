<script setup>
import PlantillaIndex from "@/Layouts/PlantillaIndex.vue";
import PlanesCard from "@/Components/PlanesCard.vue";
</script>

<template>
  <PlantillaIndex>
    <div class="bg-purple-200 py-10 rounded-b-lg">
      <h1 class="text-center text-5xl font-bold text-gray-800">Planes</h1>
    </div>

    <div class="container mx-auto my-10">
      <div class="flex flex-wrap justify-center gap-6">
        <PlanesCard
          encabezado="Gratis"
          punto1="Característica 1"
          punto2="Característica 2"
          punto3="Característica 3"
          precio="$10.00"
        />
        <PlanesCard
          encabezado="Básico"
          punto1="Característica Básica 1"
          punto2="Característica Básica 2"
          punto3="Característica Básica 3"
          precio="$15.00"
        />
        <PlanesCard
          encabezado="Premium"
          punto1="Característica Premium 1"
          punto2="Característica Premium 2"
          punto3="Característica Premium 3"
          precio="$20.00"
        />
      </div>
    </div>
  </PlantillaIndex>
</template>
