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
          encabezado="Plan Focus Lite"
          punto1="Hasta 3 listas de tareas y 2 mazos de tarjetas."
          punto2="Temporizador Pomodoro básico (25/5).                  "
          punto3=" Medidor de rachas para seguir tu constancia."
          precio="$0.00"
        />
        <PlanesCard
          encabezado="Plan Focus Plus"
          punto1="Actividades, tarjetas y cronómetro sin límites."
          punto2="Modo de práctica para ver qué conceptos necesitas reforzar."
          punto3="Medidor de rachas con estadísticas semanales."
          precio="$49.99.00"
        />
        <PlanesCard
          encabezado="Plan Focus Pro"
          punto1="Uso completo en todos tus dispositivos (web y móvil)"
          punto2="Panel de progreso avanzado para ver tu evolución."
          punto3="Medidor de rachas con análisis detallado de hábitos."
          precio="$99.99"
        />
      </div>
    </div>
  </PlantillaIndex>
</template>