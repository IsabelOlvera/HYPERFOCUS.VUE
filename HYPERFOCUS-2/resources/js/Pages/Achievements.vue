<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const chartCanvas = ref(null);
const flippedCards = ref(new Set());
const currentCardSet = ref(0);
const streakCount = ref(0); // Valor inicial de la racha
let cardRotationInterval = null;
let particles = [];

// Pool completo de cartas motivacionales (18 cartas divididas en 3 sets de 6)
const allMotivationalCards = [
    // Set 1 - Logros y Éxito
    [
        {
            id: 1,
            icon: '🌟',
            title: 'Estrella Brillante',
            message: '¡Eres una estrella que ilumina el camino hacia el éxito! Tu dedicación y esfuerzo te han llevado hasta aquí.'
        },
        {
            id: 2,
            icon: '🚀',
            title: 'Cohete Espacial',
            message: '¡Tu crecimiento no tiene límites! Como un cohete, sigues alcanzando nuevas alturas cada día.'
        },
        {
            id: 3,
            icon: '💎',
            title: 'Diamante Precioso',
            message: '¡Eres un diamante pulido por la perseverancia! Tu valor y brillantez son únicos e incomparables.'
        },
        {
            id: 4,
            icon: '🏔️',
            title: 'Conquistador',
            message: '¡Has conquistado montañas de desafíos! Tu fortaleza y determinación son verdaderamente inspiradoras.'
        },
        {
            id: 5,
            icon: '🌈',
            title: 'Arcoíris de Esperanza',
            message: '¡Traes color y alegría a todo lo que haces! Tu positividad es contagiosa y transformadora.'
        },
        {
            id: 6,
            icon: '⚡',
            title: 'Energía Imparable',
            message: '¡Tu energía y pasión son imparables! Sigues adelante con una fuerza que inspira a todos.'
        }
    ],
    // Set 2 - Crecimiento Personal
    [
        {
            id: 7,
            icon: '🦋',
            title: 'Transformación',
            message: '¡Como una mariposa, has transformado cada desafío en una oportunidad de crecimiento y belleza!'
        },
        {
            id: 8,
            icon: '🌱',
            title: 'Semilla de Grandeza',
            message: '¡Cada pequeño paso que das planta semillas de grandeza que florecerán en el futuro!'
        },
        {
            id: 9,
            icon: '🔥',
            title: 'Llama Interior',
            message: '¡Tu pasión arde como una llama inextinguible que ilumina e inspira a quienes te rodean!'
        },
        {
            id: 10,
            icon: '🏆',
            title: 'Campeón Invencible',
            message: '¡Eres un campeón nato! Cada meta alcanzada es prueba de tu espíritu invencible.'
        },
        {
            id: 11,
            icon: '🌊',
            title: 'Fuerza del Océano',
            message: '¡Como el océano, tu fuerza es profunda y constante, capaz de mover montañas con paciencia!'
        },
        {
            id: 12,
            icon: '🎯',
            title: 'Precisión Perfecta',
            message: '¡Tu enfoque y determinación te llevan directo al blanco de tus sueños más ambiciosos!'
        }
    ],
    // Set 3 - Inspiración y Motivación
    [
        {
            id: 13,
            icon: '☀️',
            title: 'Sol Radiante',
            message: '¡Eres como el sol, traes luz y calor a cada situación, disipando las sombras del camino!'
        },
        {
            id: 14,
            icon: '🎨',
            title: 'Artista de la Vida',
            message: '¡Tu vida es una obra maestra en constante creación, cada día agregas nuevos colores!'
        },
        {
            id: 15,
            icon: '🗝️',
            title: 'Llave del Éxito',
            message: '¡Tienes la llave que abre todas las puertas! Tu actitud positiva es tu superpoder secreto.'
        },
        {
            id: 16,
            icon: '🦅',
            title: 'Águila Majestuosa',
            message: '¡Vuela alto como un águila! Tu visión clara y tu coraje te llevan a alturas extraordinarias.'
        },
        {
            id: 17,
            icon: '💫',
            title: 'Estrella Fugaz',
            message: '¡Eres una estrella fugaz que deja huella! Tu paso por este mundo marca la diferencia.'
        },
        {
            id: 18,
            icon: '🌺',
            title: 'Flor que Florece',
            message: '¡Como una flor hermosa, sigues floreciendo incluso en terrenos difíciles. Tu belleza interior inspira!'
        }
    ]
];

// Función para verificar si ya se volteó una carta hoy
const canIncreaseStreak = () => {
    const today = new Date().toDateString();
    const lastStreakDate = localStorage.getItem('lastStreakDate');
    return lastStreakDate !== today;
};

// Función para aumentar la racha
const increaseStreak = () => {
    if (canIncreaseStreak()) {
        streakCount.value += 1;
        const today = new Date().toDateString();
        localStorage.setItem('lastStreakDate', today);
        localStorage.setItem('streakCount', streakCount.value.toString());
        
        // Crear efecto de partículas especial
        createStreakParticles();
        
        console.log(`🔥 ¡Racha aumentada! Nuevo récord: ${streakCount.value} días`);
    }
};

// Cargar racha desde localStorage
const loadStreak = () => {
    const savedStreak = localStorage.getItem('streakCount');
    if (savedStreak) {
        streakCount.value = parseInt(savedStreak);
    }
};

// Clase para las partículas
class Particle {
    constructor(x, y, type = 'normal') {
        this.x = x;
        this.y = y;
        this.vx = (Math.random() - 0.5) * 2;
        this.vy = (Math.random() - 0.5) * 2;
        this.life = 1;
        this.decay = Math.random() * 0.02 + 0.01;
        this.size = Math.random() * 4 + 2;
        this.type = type;
        
        // Colores según el tipo
        if (type === 'streak') {
            this.color = ['#ff6b6b', '#ffd93d', '#6bcf7f', '#4ecdc4', '#45b7d1'][Math.floor(Math.random() * 5)];
            this.size = Math.random() * 6 + 4;
        } else {
            this.color = ['#a855f7', '#ec4899', '#f59e0b', '#10b981'][Math.floor(Math.random() * 4)];
        }
    }
    
    update() {
        this.x += this.vx;
        this.y += this.vy;
        this.life -= this.decay;
        this.vy -= 0.02; // Gravedad suave hacia arriba
    }
    
    draw(ctx) {
        ctx.save();
        ctx.globalAlpha = this.life;
        ctx.fillStyle = this.color;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.fill();
        
        // Efecto brillante
        ctx.shadowBlur = 10;
        ctx.shadowColor = this.color;
        ctx.fill();
        ctx.restore();
    }
}

// Crear partículas normales
const createParticles = (x, y, count = 8) => {
    for (let i = 0; i < count; i++) {
        particles.push(new Particle(x, y));
    }
};

// Crear partículas especiales para la racha
const createStreakParticles = () => {
    const streakElement = document.querySelector('.streak-counter');
    if (streakElement) {
        const rect = streakElement.getBoundingClientRect();
        const x = rect.left + rect.width / 2;
        const y = rect.top + rect.height / 2;
        
        for (let i = 0; i < 15; i++) {
            particles.push(new Particle(x, y, 'streak'));
        }
    }
};

// Animar partículas
const animateParticles = () => {
    const canvas = document.getElementById('particles-canvas');
    if (!canvas) return;
    
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    // Actualizar y dibujar partículas
    particles = particles.filter(particle => {
        particle.update();
        particle.draw(ctx);
        return particle.life > 0;
    });
    
    // Agregar partículas ambientales ocasionalmente
    if (Math.random() < 0.02) {
        particles.push(new Particle(
            Math.random() * canvas.width,
            canvas.height + 10
        ));
    }
    
    requestAnimationFrame(animateParticles);
};
const getCurrentCards = () => {
    return allMotivationalCards[currentCardSet.value];
};

// Función para rotar al siguiente set de cartas
const rotateCards = () => {
    currentCardSet.value = (currentCardSet.value + 1) % allMotivationalCards.length;
    // Resetear cartas volteadas cuando cambian las cartas
    flippedCards.value.clear();
    
    // Mostrar notificación de cambio (opcional)
    console.log(`🎴 Cartas motivacionales actualizadas! Set ${currentCardSet.value + 1} de ${allMotivationalCards.length}`);
};

// Función para inicializar el set de cartas basado en la hora actual
const initializeCardSet = () => {
    const now = new Date();
    const hoursElapsed = now.getHours() + (now.getMinutes() / 60);
    // Cada 3.5 horas cambian las cartas (0-3.5h = set 0, 3.5-7h = set 1, etc.)
    const setIndex = Math.floor(hoursElapsed / 3.5) % allMotivationalCards.length;
    currentCardSet.value = setIndex;
};

const flipCard = (cardId) => {
    if (flippedCards.value.has(cardId)) {
        flippedCards.value.delete(cardId);
    } else {
        flippedCards.value.add(cardId);
        
        // Aumentar racha si es posible
        increaseStreak();
        
        // Crear partículas en la posición de la carta
        const cardElement = event.target.closest('.motivational-card');
        if (cardElement) {
            const rect = cardElement.getBoundingClientRect();
            const x = rect.left + rect.width / 2;
            const y = rect.top + rect.height / 2;
            createParticles(x, y, 12);
        }
    }
};

const isFlipped = (cardId) => {
    return flippedCards.value.has(cardId);
};

onMounted(() => {
   

    // Cargar racha guardada
    loadStreak();

    // Inicializar el set de cartas basado en la hora actual
    initializeCardSet();
    
    // Configurar rotación automática cada 3.5 horas (12,600,000 ms)
    cardRotationInterval = setInterval(() => {
        rotateCards();
    }, 3.5 * 60 * 60 * 1000); // 3.5 horas en milisegundos

    // Inicializar sistema de partículas
    setTimeout(() => {
        animateParticles();
    }, 100);
});

onUnmounted(() => {
    // Limpiar el intervalo cuando el componente se desmonte
    if (cardRotationInterval) {
        clearInterval(cardRotationInterval);
    }
});
</script>

<template>
    <Head title="Mis Logros" />

    <!-- Canvas para partículas de fondo -->
    <canvas 
        id="particles-canvas" 
        class="fixed inset-0 pointer-events-none z-0"
        style="background: transparent;"
    ></canvas>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MIS LOGROS
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8 relative z-10">
                <div class="space-y-8">
                    <!-- Sección de Logros y Gráfica -->
                    <div class="grid grid-cols-2 gap-8">
                        <!-- Logros -->
                        <div class="bg-purple-100 p-6 rounded-lg shadow-lg">
                            <h3 class="text-lg font-bold text-center flex items-center justify-center gap-2">
                                <span>🏅</span> ¡Mis logros este año! <span>🏆</span>
                            </h3>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div class="border-dashed border-2 border-gray-400 p-6 rounded-lg text-center">
                                <p class="font-bold text-purple-500"><span class="text-[60px]">🥇</span></p>
                                </div>
                                <div class="border-dashed border-2 border-gray-400 p-6 rounded-lg text-center streak-counter">
                                    <p class="text-3xl font-bold text-purple-600">{{ streakCount }}</p>
                                    <p class="text-lg font-bold text-purple-500">Racha de Concentración</p>
                                    <p class="text-xs text-purple-400 mt-1">
                                        {{ canIncreaseStreak() ? '¡Voltea una carta hoy!' : '¡Racha del día completada!' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <!-- Cartas Motivacionales -->
                    <div class="bg-gradient-to-br from-purple-50 to-pink-50 p-8 rounded-xl shadow-lg">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-2xl font-bold text-purple-800 flex items-center gap-3">
                                <span>✨</span> Cartas Motivacionales <span>✨</span>
                            </h3>
                            <div class="text-sm text-purple-600 bg-purple-100 px-3 py-1 rounded-full">
                                Set {{ currentCardSet + 1 }} de {{ allMotivationalCards.length }}
                            </div>
                        </div>
                        <div class="grid grid-cols-3 gap-6">
                            <div 
                                v-for="card in getCurrentCards()" 
                                :key="`${currentCardSet}-${card.id}`"
                                class="motivational-card"
                                :class="{ 'flipped': isFlipped(card.id) }"
                                @click="flipCard(card.id)"
                            >
                                <!-- Frente de la carta -->
                                <div class="card-front">
                                    <div class="text-4xl mb-4">{{ card.icon }}</div>
                                    <h4 class="text-lg font-bold text-white">{{ card.title }}</h4>
                                    <p class="text-sm text-purple-100 mt-2">¡Haz clic para revelar!</p>
                                </div>
                                
                                <!-- Reverso de la carta -->
                                <div class="card-back">
                                    <div class="text-3xl mb-4">{{ card.icon }}</div>
                                    <p class="text-sm text-purple-800 font-medium leading-relaxed">
                                        {{ card.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 text-center">
                            <p class="text-xs text-purple-500">
                                🕐 Regresa por mas motivación en 3 horas con 50 min.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Estilos generales */
body {
    overflow-x: hidden;
}

#particles-canvas {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    pointer-events: none;
    z-index: 0;
}

.streak-counter {
    position: relative;
    transition: all 0.3s ease;
}

.streak-counter:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 25px rgba(168, 85, 247, 0.3);
}

.motivational-card {
    @apply relative w-full h-48 cursor-pointer;
    perspective: 1000px;
    transition: transform 0.3s ease;
    z-index: 1;
}

.motivational-card:hover {
    transform: translateY(-5px) scale(1.02);
}

.card-front,
.card-back {
    @apply absolute inset-0 w-full h-full rounded-xl shadow-lg flex flex-col items-center justify-center text-center p-4;
    backface-visibility: hidden;
    transition: transform 0.6s ease-in-out;
}

.card-front {
    @apply bg-gradient-to-br from-purple-400 to-pink-400 text-white;
    background: linear-gradient(135deg, #460389, #b940fe, #490a95);
    background-size: 200% 200%;
    animation: shimmer 3s ease-in-out infinite;
}

.card-back {
    @apply bg-gradient-to-br from-purple-100 to-pink-100 border-2 border-purple-300;
    transform: rotateY(180deg);
}

.motivational-card.flipped .card-front {
    transform: rotateY(180deg);
}

.motivational-card.flipped .card-back {
    transform: rotateY(0deg);
}

@keyframes shimmer {
    0% {
        background-position: 0% 50%;
        box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
    }
    50% {
        background-position: 100% 50%;
        box-shadow: 0 0 30px rgba(236, 72, 153, 0.4);
    }
    100% {
        background-position: 0% 50%;
        box-shadow: 0 0 20px rgba(168, 85, 247, 0.3);
    }
}

/* Animación de brillo adicional */
.card-front::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    background: linear-gradient(45deg, #3f006c, #5e00a1, #cc00ff, #a200ff, #ff00ee, #9500ff, #7a00ff, #ff00c8, #ff0000);
    border-radius: inherit;
    opacity: 0;
    z-index: -1;
    animation: glowing 2s linear infinite;
}

@keyframes glowing {
    0% { opacity: 0; }
    50% { opacity: 0.8; }
    100% { opacity: 0; }
}

.motivational-card:hover .card-front::before {
    animation: glowing 1s linear infinite;
}
</style>