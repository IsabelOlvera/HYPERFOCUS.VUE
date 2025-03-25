<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

// Controla si el menú offcanvas está abierto
const offcanvasOpen = ref(false);
</script>

<template>
  <!-- Navbar -->
  <nav class="navbar border-4 border-bottom border-dark" aria-label="Light offcanvas navbar">
    <div class="container-fluid">
      <!-- Botón Hamburguesa -->
      <button
        class="navbar-toggler"
        type="button"
        data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasNavbarLight"
        aria-controls="offcanvasNavbarLight"
      >
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú de Perfil -->
      <div class="flex-shrink-0 dropdown me-4">
        <a
          href="#"
          class="d-block link-body-emphasis text-decoration-none dropdown-toggle"
          data-bs-toggle="dropdown"
          aria-expanded="false"
        >
          <i class="fa-solid fa-circle-user fa-2xl"></i>
        </a>
        <ul class="dropdown-menu text-small shadow-start">
          <li><a class="dropdown-item" href="#">Perfil</a></li>
          <li><hr class="dropdown-divider"></li>
          <li>
            <Link class="dropdown-item" :href="route('logout')" method="post" as="button">
              Cerrar sesión
            </Link>
          </li>
        </ul>
      </div>

      <!-- Offcanvas -->
      <div
        class="offcanvas offcanvas-start canvaC text-white"
        tabindex="-1"
        id="offcanvasNavbarLight"
        aria-labelledby="offcanvasNavbarLightLabel"
      >
        <div class="offcanvas-header">
          <h5 class="offcanvas-title">Menú</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="nav nav-pills flex-column mb-auto text-white">
            <li class="nav-item">
              <Link class="nav-link text-white" :class="{ 'active': $page.url === route('rutahome') }" :href="route('rutahome')">
                <i class="fa-solid fa-house me-2"></i> Inicio
              </Link>
            </li>
            <li>
              <Link class="nav-link text-white" :class="{ 'active': $page.url === route('rutamisemana') }" :href="route('rutamisemana')">
                <i class="fa-solid fa-calendar-days me-2"></i> Mi Semana
              </Link>
            </li>
            <li>
              <Link class="nav-link text-white" :class="{ 'active': $page.url === route('rutaconcentracion') }" :href="route('rutaconcentracion')">
                <i class="fa-solid fa-hourglass-half me-2"></i> Concentración
              </Link>
            </li>
            <li>
              <Link class="nav-link text-white" :class="{ 'active': $page.url === route('rutamemoria') }" :href="route('rutamemoria')">
                <i class="fa-solid fa-puzzle-piece me-2"></i> Memoria
              </Link>
            </li>
            <li>
              <Link class="nav-link text-white" :class="{ 'active': $page.url === route('rutamislogros') }" :href="route('rutamislogros')">
                <i class="fa-solid fa-medal me-2"></i> Mis Logros
              </Link>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
/* Aquí puedes personalizar estilos */
</style>
