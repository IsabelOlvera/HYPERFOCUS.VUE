<script setup>
import PlantillaIndex from "@/Layouts/PlantillaIndex.vue";
import PreguntaCard from "@/Components/PreguntaCard.vue";

const preguntas = [
  { pregunta: "¿Qué es HyperFocus y cómo puede ayudarme?", respuesta: "HyperFocus es una aplicación web diseñada para ayudar a personas con TDAH a mejorar su gestión del tiempo, concentración y memoria. A través de tres módulos clave (Gestión del Tiempo, Foco y Concentración, y Memoria y Recuerdo), ofrecemos herramientas prácticas que permiten a los usuarios organizar mejor sus actividades diarias, mantenerse concentrados en tareas importantes y mejorar su capacidad de recordar información relevante." },
  { pregunta: "¿Cómo puedo usar HyperFocus en mi día a día?", respuesta: "Una vez que te registres en HyperFocus, podrás acceder a los tres módulos que hemos creado especialmente para abordar los desafíos del TDAH. Puedes empezar por planificar tus actividades con el módulo de Gestión del Tiempo, usar herramientas para mejorar tu concentración y realizar ejercicios que te ayuden a retener información importante. Todo está diseñado para ser intuitivo, accesible y adaptado a las necesidades de cada usuario." },
  { pregunta: "¿Es HyperFocus solo para personas con TDAH?", respuesta: "No, aunque HyperFocus está específicamente diseñado para abordar los desafíos que enfrentan las personas con TDAH, sus herramientas son útiles para cualquier persona que quiera mejorar su productividad, organización y concentración. Si te resulta difícil gestionar tu tiempo o mantenerte enfocado en tareas diarias, HyperFocus puede ser una excelente herramienta para ti." },
  { pregunta: "¿Necesito conocimientos técnicos para usar la aplicación?", respuesta: "No. Hemos diseñado HyperFocus para que sea fácil de usar para cualquier persona, independientemente de su nivel de habilidad técnica. La interfaz es intuitiva y se adapta a las necesidades de cada usuario, por lo que no necesitarás experiencia previa en tecnología para beneficiarte de las funcionalidades que ofrecemos." },
  { pregunta: "¿Es HyperFocus gratuita o tiene algún costo?", respuesta: "HyperFocus ofrece una versión gratuita con acceso a funciones básicas. Sin embargo, si deseas acceder a funcionalidades premium, como personalización avanzada y más herramientas de seguimiento, tenemos planes de suscripción que se ajustan a diferentes necesidades. Visita nuestra página de precios para más detalles." },
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
