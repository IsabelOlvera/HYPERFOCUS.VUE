<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Sidebar from '@/Components/Sidebar.vue'
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
  <AuthenticatedLayout>
    <div class="flex">
      <!-- Sidebar -->
      <Sidebar />

      <!-- Contenido Principal -->
      <div class="flex-1 bg-white">
        <!-- Banner -->
        <div class="container mx-auto  max-w-6xl">
            <img
            src="/img/slide1.png"
            alt="Slide 1"
            class="w-full h-full object-cover"
          />
        </div>
        <main class="container mx-auto py-5  max-w-6xl">
            <h1 class="text-5xl font-bold text-center border-b-2 border-b-black">Guía</h1>
        </main>

        <main>
            <!-- CONTENEDOR PRINCIPAL -->
            <div class="w-full h-full bg-white">

              <!-- Apartado #1 -->
               <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Cómo empezar? </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia1.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4 md:mr-5">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">En nuestro panel principal verás tres botones, cada uno te llevará a una sección diferente:</p>
                      </div>
                      <div class="mx-10 text-base">
                          <ol class="list-disc list-outside ">
                              <li class=" font-bold">Gestión de tiempo </li>
                              <p class="mb-2">Aquí podrás añadir recordatorios y llevar un control claro de tu progreso semanal.</p>
                              <li class=" font-bold">Memoria y recuerdo</li>
                              <p class="mb-2">Crea tus propios conjuntos de estudio para practicar y memorizar lo que necesites, a tu ritmo.</p>
                              <li class=" font-bold">Foco y concentración </li>
                              <p class="mb-2">Personaliza una experiencia de enfoque según el tiempo que elijas. Ideal para mantener la concentración en tus tareas.</p>
                          </ol>
                      </div>              
                  </div>
                  </div> 
              </div>





              <!-- Apartado #2 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Tienes dudas sobre nuestro menú lateral? </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia2.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4 md:mr-5">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">A través del menú lateral puedes navegar entre las diferentes secciones de la plataforma.</p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class="list-decimal list-outside ">
                            <p>Si te quedan dudas, sigue explorando nuestra guía. Aquí encontrarás respuestas útiles y 
                              apoyo para que tu experiencia con <a class="font-bold  text-indigo-700">Hyperfocus</a> sea la mejor posible.</p>
                          </ul>
                      </div>              
                    </div>



                  

                  </div> 
              </div>



              <!-- Apartado #3 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Qué estás viendo en Inicio? </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia3.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4 md:mr-5">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">En esta sección podrás consultar tu avance en las tareas, así como tu progreso diario y semanal.</p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class=" ">
                              <li class="">¿Te preguntas cómo añadir tareas? </li>
                              <p class="mb-2 ml-3">Ve a la sección <a class="font-bold  text-indigo-700">Mi semana</a>.</p>
                              <li class="">¿Necesitas ayuda? Revisa nuestra guía bajo la pregunta: </li>
                              <p class="mb-2 ml-3"><a class="font-bold  text-indigo-700">¿Necesitas organizar tus actividades? </a></p>
                          </ul>
                      </div>              
                  </div>
                  </div> 
              </div>



                <!-- Apartado #4 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Necesitas organizar tus actividades?  </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia4.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg mb-5">¡En la sección Mi semana puedes hacerlo de forma sencilla! </p>
                          <p class="text-lg">En la parte superior verás una vista semanal con todas tus actividades registradas. Para agregar una nueva, solo sigue estos pasos:</p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class="list-decimal list-outside ">
                              <li class="mb-2">Escribe el nombre de la actividad. </li>
                              <li class="mb-2">Indica la hora en que deseas realizarla. </li>
                              <li class="mb-2">Define cuánto tiempo le vas a dedicar.</li>
                              <li class="mb-2">Selecciona la fecha correspondiente. </li>
                              <li class="mb-2">Asigna una prioridad según su importancia. </li>
                          </ul>
                      </div>
                       <div class="mb-5 sm:mx-3 mt-5">
                          <p class="text-lg">¡Y listo! Así podrás tener tu semana mucho más organizada. </p>
                      </div>              
                  </div>
                  </div> 
              </div>



                <!-- Apartado #5 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Quieres reforzar tu memoria o prepararte para un examen?  </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia5.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">¡La sección de <a class="font-bold  text-indigo-700">Memoria</a> es perfecta para eso! Aquí puedes crear tus propios conjuntos de 
                            estudio de forma rápida y personalizada. Solo sigue estos pasos: </p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class="list-decimal list-outside ">
                              <li class="mb-2">Haz clic en el botón <a class="font-bold  text-indigo-700">Crear</a> para iniciar un nuevo conjunto. </li>
                              <li class="mb-2">Escribe el nombre y una breve descripción para mantenerlo organizado. </li>
                              <li class="mb-2">Luego, haz clic en el botón <a class="font-bold  text-indigo-700">Conceptos</a> y agrega al menos tres palabras o frases que 
                                quieras estudiar. </li>
                              <li class="mb-2">Cuando termines, regresa a tus conjuntos y haz clic en <a class="font-bold  text-indigo-700">Practicar</a> para comenzar tu 
                                sesión. </li>
                              <li class="mb-2">¡Aprende y diviértete mientras estudias!</li>
                          </ul>
                      </div>              
                  </div>
                  </div> 
              </div>





               <!-- Apartado #6 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Buscas una experiencia de concentración?  </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia6.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">¡Estás en el lugar indicado! Si conoces el método Pomodoro, esto te resultará muy familiar. 
                            Aquí te explicamos cómo empezar: </p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class="list-decimal list-outside ">
                              <li class="mb-2">En la parte inferior izquierda, elige cuántos intervalos de concentración quieres 
                                tener y su duración (en minutos). </li>
                              <li class="mb-2">En la parte inferior derecha, selecciona cuántos descansos deseas y cuánto durará 
                                cada uno. </li>
                              <li class="mb-2">Haz clic en <a class="font-bold  text-indigo-700">Iniciar</a> y nosotros nos encargamos del resto. Te notificaremos cuando 
                                terminen tus sesiones de concentración y descanso. </li>
                              <li >¿Y el reloj morado?</li>
                                <p class="mb-2">Este te mostrará el tiempo total que llevas concentrado o descansando, y verás dos 
                                barras en la parte superior que marcarán tu progreso visualmente. </p>
                              
                          </ul>
                      </div>              
                  </div>
                  </div> 
              </div>



               <!-- Apartado #7 -->
              <!-- SubContenedor -->
              <div class="flex flex-col my-5">
                  <!-- Titulo -->
                  <div class="basis-4/4 ">
                      <div class="my-5 text-center italic font-bold md:text-4xl sm:text-2xl sm:mx-3">
                        <h1>¿Te emociona saber cuál es tu progreso? </h1>
                      </div>
                  </div>

                  <!-- Contenido -->
                  <div class="flex flex-col md:flex-row  items-center">

                    <!-- Imagen -->
                    <div class="md:basis-2/4 ms:basis-2/4 m-5 ">
                        <div class=" flex justify-center  ">
                          <div class=" md:basis-4/5 sm:basis-2/4 ">
                            <img src="/img/guia7.png" alt="Guide 1" class="w-full h-full object-cover rounded-xl"/>
                          </div>  
                        </div>
                    </div>

                    <!-- Texto -->
                    <div class=" flex flex-col md:basis-2/4 ms:basis-4/4">
                      <div class="mb-5 sm:mx-3">
                          <p class="text-lg">¡A nosotros también!</p>
                      </div>
                      <div class="mx-10 text-base">
                          <ul class=" ">
                              <li class="mb-2">Por eso, en la sección <a class="font-bold  text-indigo-700">Mis logros</a> podrás ver las medallas que has ganado por ser constante 
                                  en el uso de <a class="font-bold  text-indigo-700">Hyperfocus</a>, además de visualizar tu consistencia a lo largo del tiempo de 
                                  forma gráfica.</li>
                             
                          </ul>
                      </div>              
                  </div>
                  </div> 
              </div>



            </div>
        </main>

        

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 py-8">
          <div class="container mx-auto flex flex-wrap justify-between items-center px-6">
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
    </div>
  </AuthenticatedLayout>
</template>
