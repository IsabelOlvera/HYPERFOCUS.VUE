<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const currentRoute = usePage().url;
const isSidebarOpen = ref(true);

const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value;
};

const menuItems = [
    { name: 'Inicio', icon: '🏠', route: '/home' },
    { name: 'Mi semana', icon: '📅', route: '/week' },
    { name: 'Concentración', icon: '⏳', route: '/focus' },
    { name: 'Memoria', icon: '🧩', route: '/memory' },
    { name: 'Mis logros', icon: '🏅', route: '/achievements' },
    { name: 'Centro de ayuda', icon: '❓', route: '/suport' },
];
</script>

<template>
    <div class="relative">
        <!-- Botón para ocultar/mostrar sidebar -->
        <button 
            @click="toggleSidebar" 
            class="absolute top-5 -right-6 bg-purple-500 text-white p-2 rounded-full shadow-lg z-50"
        >
            <span v-if="isSidebarOpen">◀</span>
            <span v-else>▶</span>
        </button>

        <!-- Sidebar -->
        <div 
            :class="isSidebarOpen ? 'w-48' : 'w-0 overflow-hidden'" 
            class="h-screen bg-purple-200 p-4 flex flex-col transition-all duration-300"
        >
            <nav v-if="isSidebarOpen" class="space-y-4">
                <div 
                    v-for="item in menuItems" 
                    :key="item.route" 
                    :class="currentRoute === item.route 
                        ? 'bg-purple-500 text-white font-bold' 
                        : 'text-purple-800 hover:bg-purple-300 hover:text-purple-900'"
                    class="flex items-center space-x-2 px-4 py-2 rounded-lg cursor-pointer transition-all duration-200"
                    @click="$inertia.visit(item.route)"
                >
                    <span class="text-lg">{{ item.icon }}</span>
                    <span>{{ item.name }}</span>
                </div>
            </nav>
        </div>
    </div>
</template>

