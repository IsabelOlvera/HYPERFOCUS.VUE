<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// Estado para manejar el menú lateral
const isOffcanvasOpen = ref(false);

const toggleOffcanvas = () => {
    isOffcanvasOpen.value = !isOffcanvasOpen;
};
</script>

<template>
  <div>
    <!-- Navbar -->
    <nav class="navbar border-4 border-bottom border-dark">
      <div class="container-fluid">
        
        <!-- Botón menú hamburguesa -->
        <button class="navbar-toggler" @click="toggleOffcanvas">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Perfil -->
        <div class="dropdown me-4">
          <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown">
            <i class="fa-solid fa-circle-user fa-2xl"></i>
          </a>
          <ul class="dropdown-menu text-small shadow">
            <li><Link class="dropdown-item" href="#">Perfil</Link></li>
            <li><hr class="dropdown-divider"></li>
            <li><Link class="dropdown-item" href="/">Cerrar sesión</Link></li>
          </ul>
        </div>

        <!-- Menú Offcanvas -->
        <div class="offcanvas offcanvas-start" :class="{ 'show': isOffcanvasOpen }">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title">Menú</h5>
            <button type="button" class="btn-close" @click="toggleOffcanvas"></button>
          </div>
          <div class="offcanvas-body">
            <ul class="nav nav-pills flex-column">
              <li><Link class="nav-link" href="/home"><i class="fa-solid fa-house me-2"></i>Inicio</Link></li>
              <li><Link class="nav-link" href="/mi-semana"><i class="fa-solid fa-calendar-days me-2"></i>Mi semana</Link></li>
              <li><Link class="nav-link" href="/concentracion"><i class="fa-solid fa-hourglass-half me-2"></i>Concentración</Link></li>
              <li><Link class="nav-link" href="/memoria"><i class="fa-solid fa-puzzle-piece me-2"></i>Memoria</Link></li>
              <li><Link class="nav-link" href="/mis-logros"><i class="fa-solid fa-medal me-2"></i>Mis logros</Link></li>
            </ul>
          </div>
        </div>

      </div>
    </nav>

    <!-- Contenedor de la página -->
    <div class="container-fluid mb-2">
      <slot></slot> <!-- Aquí se renderizan las páginas -->
    </div>
  </div>
</template>

<style>
/* Agrega aquí tus estilos si es necesario */
</style>
