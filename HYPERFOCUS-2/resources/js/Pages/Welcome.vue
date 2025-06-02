<script setup>
import PlantillaIndex from "@/Layouts/PlantillaIndex.vue";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay } from "swiper/modules"; // Importar el módulo de autoplay
import "swiper/css";
import "swiper/css/autoplay";
</script>

<template>
  <PlantillaIndex>
    <!-- Carousel -->
    <div class="carousel-container">
      <swiper
        :modules="[Autoplay]"
        :slides-per-view="1"
        :loop="true"
        :autoplay="{ delay: 3000, disableOnInteraction: false }"
        class="w-full h-[500px]"
      >
        <swiper-slide>
          <img
            src="/img/slide1.png"
            alt="Slide 1"
            class="w-full h-full object-cover"
          />
        </swiper-slide>
        <swiper-slide>
          <img
            src="/img/slide2.png"
            alt="Slide 2"
            class="w-full h-full object-cover"
          />
        </swiper-slide>
        <swiper-slide>
          <img
            src="/img/slide3.png"
            alt="Slide 3"
            class="w-full h-full object-cover"
          />
        </swiper-slide>
      </swiper>
    </div>

    <!-- Hero Section -->
    <section class="bg-purple-500 text-white py-20">
      <div class="container mx-auto text-center">
        <h1 class="text-8xl font-bold mb-4">¡Un día a la vez!</h1>
        <p class="text-lg">
          Organiza, enfócate y trabaja de manera eficiente en tus metas.
        </p>
      </div>
    </section>

    <!-- Hyperfocus Section -->
    <section class="container mx-auto my-10 px-8 md:px-5">
      <div class="flex flex-col md:flex-row items-center gap-5">
        <!-- Texto a la izquierda -->
        <div class="md:w-1/3 text-center md:text-left">
          <h2 class="text-4xl font-bold text-gray-800">¿Qué es Hyperfocus?</h2>
          <p class="mt-4 text-lg text-gray-600">
            Hyperfocus es una herramienta diseñada para mejorar tu productividad
            utilizando técnicas científicamente probadas, como el método Pomodoro y la
            gestión eficiente de tareas. ¡Organiza tu tiempo y maximiza tu rendimiento!
          </p>
        </div>
        <!-- Imagen a la derecha -->
        <div class="md:w-2/3 flex justify-center">
          <img
            src="/img/hyperfocus.png"
            alt="Hyperfocus en acción"
            class="w-[800px] h-[500px] object-cover rounded-lg shadow-lg"
          />
        </div>
      </div>
    </section>
    <br>
    <br>

    <!-- Content and Reviews Section -->
    <section class="container mx-auto my-1 grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Content Section -->
      <div class="lg:col-span-2 space-y-1">
        <!-- Section 1 -->
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/4 px-10">
            <img
              src="/img/calendario.svg"
              alt="Organiza tus actividades"
              class="h-80 w-80"
            />
          </div>
          <div class="md:w-1/4 px-1 text-center md:text-left">
            <h2 class="text-3xl font-bold text-gray-800">¡Organiza tus actividades!</h2>
            <p class="mt-4 text-gray-600">
              Estructuramos tus tareas diarias de una manera eficiente para que no
              tengas que preocuparte por organizarlas.
            </p>
          </div>
        </div>

        <!-- Section 2 -->
        <div class="flex flex-col md:flex-row-reverse items-center">
          <div class="md:w-1/2 px-1">
            <img
              src="/img/concentracion.svg"
              alt="Mejora tu concentración"
              class="h-80 w-80"
            />
          </div>
          <div class="md:w-1/4 px-6 text-center md:text-left">
            <h2 class="text-3xl font-bold text-gray-800">¡Mejora tu concentración!</h2>
            <p class="mt-4 text-gray-600">
              Personalizamos tu periodo de concentración utilizando el método
              Pomodoro.
            </p>
          </div>
        </div>

        <!-- Section 3 -->
        <div class="flex flex-col md:flex-row items-center">
          <div class="md:w-1/4 px-10">
            <img
              src="/img/memoria.svg"
              alt="Trabaja tu memoria"
              class="h-80 w-80"
            />
          </div>
          <div class="md:w-1/4 px-6 text-center md:text-left">
            <h2 class="text-3xl font-bold text-gray-800">¡Trabaja tu memoria!</h2>
            <p class="mt-4 text-gray-600">
              Trabaja tu memoria utilizando flashcards de repaso.
            </p>
          </div>
        </div>
      </div>

      <!-- Reviews Section -->
      <div class="space-y-1">
        <h3 class="text-2xl font-bold text-gray-800">Reseñas de nuestros usuarios</h3>
        <div class="space-y-6">
          <!-- Review Card -->
          <div class="bg-white shadow-md rounded-lg p-4">
            <p class="text-gray-600 italic">"Esta herramienta cambió mi forma de trabajar, ahora soy mucho más productivo."</p>
            <div class="mt-2 flex items-center justify-between">
              <span class="text-sm text-gray-500">- Juan Pérez</span>
              <span class="text-yellow-500">★★★★★</span>
            </div>
          </div>
          <!-- Review Card -->
          <div class="bg-white shadow-md rounded-lg p-4">
            <p class="text-gray-600 italic">"El método pomodoro me ha ayudado a mantener mi enfoque en mis tareas diarias."</p>
            <div class="mt-2 flex items-center justify-between">
              <span class="text-sm text-gray-500">- María Gómez</span>
              <span class="text-yellow-500">★★★★☆</span>
            </div>
          </div>
          <!-- Review Card -->
          <div class="bg-white shadow-md rounded-lg p-4">
            <p class="text-gray-600 italic">"Me encantan las funciones de organización y las herramientas de memoria."</p>
            <div class="mt-2 flex items-center justify-between">
              <span class="text-sm text-gray-500">- Carlos López</span>
              <span class="text-yellow-500">★★★★★</span>
            </div>
          </div>
        </div>
      </div>
    </section>
  </PlantillaIndex>
</template>

<style scoped>
.carousel-container {
  height: 500px;
  position: relative;
  overflow: hidden;
}
</style>


<script>
import { Swiper, SwiperSlide } from "swiper/vue";
import "swiper/css";
import "swiper/css/autoplay";

export default {
  name: "Welcome",
  components: {
    Swiper,
    SwiperSlide,
  },
};
</script>


<style scoped>
.carousel-container {
  height: 500px;
  position: relative;
  overflow: hidden;
}

.swiper-pagination-progressbar {
  background-color: #e0e0e0; /* Fondo de la barra */
  height: 4px; /* Altura de la barra */
}

.swiper-pagination-progressbar-fill {
  background-color: #6b46c1; /* Color de la barra de progreso */
  height: 4px;
}
</style>
