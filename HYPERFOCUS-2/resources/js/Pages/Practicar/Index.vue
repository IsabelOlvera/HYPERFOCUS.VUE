<script setup>
import { ref, onMounted, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    conjunto: Object,
    conceptos: Array,
    success: String // Para recibir mensajes flash de éxito
});

// Variables para el ejercicio
const currentQuestionIndex = ref(0);
const totalQuestions = ref(10); // Puedes ajustar este número
const ejercicioCompletado = ref(false);
const fechaInicio = ref(new Date());
const opciones = ref([]);
const conceptoActual = ref(null);
const aciertos = ref(0);
const fallos = ref(0);
const intentos = ref(1);
const preguntasGeneradas = ref([]);
const mensajeExito = ref('');
const mostrarMensaje = ref(false);

// Formulario
const form = useForm({
    fecha_hora_inicio: '',
    fecha_hora_fin: '',
    aciertos: 0,
    fallos: 0,
    intentos: 1
});

// Inicializar el ejercicio
onMounted(() => {
    iniciarEjercicio();
    
    // Mostrar mensaje de éxito si existe
    if (props.success) {
        mostrarMensajeExito(props.success);
    }
});

// Mostrar mensaje de éxito durante unos segundos
const mostrarMensajeExito = (mensaje) => {
    mensajeExito.value = mensaje;
    mostrarMensaje.value = true;
    
    setTimeout(() => {
        mostrarMensaje.value = false;
    }, 5000); // 5 segundos
};

// Preparar el ejercicio
const iniciarEjercicio = () => {
    fechaInicio.value = new Date();
    
    // Asegurarnos de tener suficientes preguntas para el ejercicio
    const numPreguntas = Math.min(totalQuestions.value, props.conceptos.length);
    totalQuestions.value = numPreguntas;
    
    // Generar la lista de preguntas (conceptos a preguntar)
    const conceptosDisponibles = [...props.conceptos];
    preguntasGeneradas.value = [];
    
    for (let i = 0; i < numPreguntas && conceptosDisponibles.length > 0; i++) {
        const randomIndex = Math.floor(Math.random() * conceptosDisponibles.length);
        preguntasGeneradas.value.push(conceptosDisponibles.splice(randomIndex, 1)[0]);
    }
    
    cargarPreguntaActual();
};

// Cargar la siguiente pregunta
const cargarPreguntaActual = () => {
    if (currentQuestionIndex.value >= preguntasGeneradas.value.length) {
        finalizarEjercicio();
        return;
    }
    
    conceptoActual.value = preguntasGeneradas.value[currentQuestionIndex.value];
    
    // Generar opciones: 1 correcta + 3 distractores (o menos si no hay suficientes)
    opciones.value = [];
    
    // Añadir la definición correcta
    opciones.value.push({
        definicion: conceptoActual.value.definicion,
        esCorrecta: true
    });
    
    // Añadir distractores (definiciones incorrectas)
    const conceptosRestantes = props.conceptos.filter(
        c => c.id !== conceptoActual.value.id
    );
    
    // Seleccionar aleatoriamente hasta 3 distractores
    const numDistractores = Math.min(3, conceptosRestantes.length);
    for (let i = 0; i < numDistractores; i++) {
        const randomIndex = Math.floor(Math.random() * conceptosRestantes.length);
        const distractor = conceptosRestantes.splice(randomIndex, 1)[0];
        
        opciones.value.push({
            definicion: distractor.definicion,
            esCorrecta: false
        });
    }
    
    // Mezclar las opciones
    opciones.value = opciones.value.sort(() => Math.random() - 0.5);
};

// Verificar respuesta y avanzar a la siguiente pregunta
const verificarRespuesta = (opcion) => {
    if (opcion.esCorrecta) {
        aciertos.value++;
    } else {
        fallos.value++;
    }
    
    // Avanzar a la siguiente pregunta
    currentQuestionIndex.value++;
    
    if (currentQuestionIndex.value < preguntasGeneradas.value.length) {
        cargarPreguntaActual();
    } else {
        finalizarEjercicio();
    }
};

// Finalizar el ejercicio
const finalizarEjercicio = () => {
    ejercicioCompletado.value = true;
    const fechaFin = new Date();
    
    // Formatear fechas para el envío
    form.fecha_hora_inicio = fechaInicio.value.toISOString();
    form.fecha_hora_fin = fechaFin.toISOString();
    form.aciertos = aciertos.value;
    form.fallos = fallos.value;
    form.intentos = intentos.value;
};

// Enviar la práctica
const submitPractica = () => {
    form.post(route('practicar.store', props.conjunto.id), {
        onSuccess: () => {
            mostrarMensajeExito('Práctica guardada correctamente');
        },
        onError: (errors) => {
            console.error('Errores al guardar:', errors);
        }
    });
};

// Calcular progreso
const progreso = computed(() => {
    return Math.round((currentQuestionIndex.value / totalQuestions.value) * 100);
});

// Ver historial de prácticas
const verHistorial = () => {
    window.location.href = route('practicar.historial');
};
</script>

<template>
<AuthenticatedLayout>
    <Head title="Practicar" />
    
    <!-- Mensaje de éxito -->
    <div v-if="mostrarMensaje" class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50 shadow-md">
        <span class="font-bold">¡Éxito!</span>
        <span class="block sm:inline"> {{ mensajeExito }}</span>
    </div>
    
    <div class="p-6 max-w-4xl mx-auto bg-white rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Practicando: {{ conjunto.nombre }}</h1>
        <p class="text-gray-700 mb-6">{{ conjunto.descripcion }}</p>
        
        <div v-if="!ejercicioCompletado">
            <!-- Barra de progreso -->
            <div class="w-full bg-gray-200 rounded-full h-4 mb-6">
                <div class="bg-blue-500 h-4 rounded-full" :style="{ width: progreso + '%' }"></div>
            </div>
            
            <!-- Pregunta actual -->
            <div class="bg-gray-50 p-6 rounded-lg mb-6">
                <h2 class="text-xl font-bold mb-4">¿Cuál es la definición de "{{ conceptoActual?.nombre }}"?</h2>
                
                <!-- Opciones -->
                <div class="space-y-3">
                    <button 
                        v-for="(opcion, index) in opciones" 
                        :key="index"
                        @click="verificarRespuesta(opcion)"
                        class="w-full text-left p-4 border rounded-lg hover:bg-blue-50 transition duration-200"
                    >
                        {{ opcion.definicion }}
                    </button>
                </div>
            </div>
            
            <!-- Contadores -->
            <div class="flex justify-between text-sm text-gray-500">
                <div>Pregunta {{ currentQuestionIndex + 1 }} de {{ totalQuestions }}</div>
                <div>Aciertos: {{ aciertos }} | Fallos: {{ fallos }}</div>
            </div>
        </div>
        
        <!-- Resultados y formulario de envío -->
        <div v-if="ejercicioCompletado" class="mt-6">
            <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                <h2 class="text-xl font-bold text-green-800 mb-2">¡Práctica completada!</h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="font-semibold">Aciertos:</p>
                        <p class="text-2xl text-green-600">{{ aciertos }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Fallos:</p>
                        <p class="text-2xl text-red-600">{{ fallos }}</p>
                    </div>
                    <div>
                        <p class="font-semibold">Porcentaje de acierto:</p>
                        <p class="text-2xl" :class="aciertos/(aciertos+fallos) >= 0.7 ? 'text-green-600' : 'text-yellow-600'">
                            {{ Math.round((aciertos/(aciertos+fallos))*100) || 0 }}%
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex space-x-4">
                <button 
                    @click="submitPractica"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition"
                >
                    Guardar Resultados
                </button>
                
                <button 
                    @click="verHistorial"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg transition"
                >
                    Ver Historial de Prácticas
                </button>
            </div>
        </div>
    </div>
</AuthenticatedLayout>
</template>