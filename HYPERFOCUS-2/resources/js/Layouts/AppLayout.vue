<script setup>
import UserNavbar from '@/Components/UserNavbar.vue';
</script>

<template>
  <div class="min-h-screen">
    <UserNavbar />
    <main class="container mt-4">
      <slot />
    </main>
  </div>
</template>
