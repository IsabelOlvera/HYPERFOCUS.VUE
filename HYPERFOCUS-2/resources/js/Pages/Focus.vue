<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount, watch } from 'vue';

// Variables para el tiempo total de estudio
const hours = ref(0);
const minutes = ref(20);
const seconds = ref(0);

// Variables para los intervalos
const concentrationIntervals = ref(2);
const concentrationTime = ref(9); // en minutos
const breakIntervals = ref(1);
const breakTime = ref(2); // en minutos

// Variables para la funcionalidad del timer
const isRunning = ref(false);
const isPaused = ref(false);
const timerInterval = ref(null);
const currentPhase = ref('setup'); // 'setup', 'concentration', 'break', 'completed'
const currentIntervalNumber = ref(0);
const currentCountdown = ref(0); // en segundos
const waitingForUserAction = ref(false);

// Estado actual del ciclo (mensaje informativo)
const statusMessage = ref('Listo para comenzar');

// Sonido para notificaciones
let notificationSound = null;

// Inicializar el sonido cuando el componente se monta
if (typeof Audio !== 'undefined') {
  notificationSound = new Audio('/sounds/notification.mp3'); // Asegúrate de tener este archivo
}

// Calcular el tiempo total de estudio en segundos
const totalStudyTime = computed(() => {
  return hours.value * 3600 + minutes.value * 60 + seconds.value;
});

// Calcular el tiempo aproximado que tomará completar todos los ciclos
const totalCycleTime = computed(() => {
  return (concentrationTime.value * concentrationIntervals.value + 
          breakTime.value * breakIntervals.value) * 60;
});

// Formatear el tiempo para mostrar
const formattedCountdown = computed(() => {
  const h = Math.floor(currentCountdown.value / 3600);
  const m = Math.floor((currentCountdown.value % 3600) / 60);
  const s = currentCountdown.value % 60;
  
  return {
    hours: h.toString().padStart(2, '0'),
    minutes: m.toString().padStart(2, '0'),
    seconds: s.toString().padStart(2, '0')
  };
});

// Formatear el tiempo total para mostrar
const formattedTotalTime = computed(() => {
  const h = hours.value;
  const m = minutes.value;
  const s = seconds.value;
  
  return {
    hours: h.toString().padStart(2, '0'),
    minutes: m.toString().padStart(2, '0'),
    seconds: s.toString().padStart(2, '0')
  };
});

// Progreso actual del intervalo
const intervalProgress = computed(() => {
  if (currentPhase.value === 'concentration') {
    const totalDuration = concentrationTime.value * 60;
    return ((totalDuration - currentCountdown.value) / totalDuration) * 100;
  } else if (currentPhase.value === 'break') {
    const totalDuration = breakTime.value * 60;
    return ((totalDuration - currentCountdown.value) / totalDuration) * 100;
  }
  return 0;
});

// Progreso general de la sesión de estudio
const sessionProgress = computed(() => {
  const totalConcentrationTime = concentrationTime.value * 60 * concentrationIntervals.value;
  const totalBreakTime = breakTime.value * 60 * breakIntervals.value;
  const totalTime = totalConcentrationTime + totalBreakTime;
  
  let completedTime = 0;
  
  // Sumar el tiempo de los intervalos completados
  if (currentIntervalNumber.value > 0) {
    completedTime += (currentIntervalNumber.value - 1) * (concentrationTime.value * 60);
    completedTime += Math.min(currentIntervalNumber.value - 1, breakIntervals.value) * (breakTime.value * 60);
  }
  
  // Sumar el tiempo transcurrido del intervalo actual
  if (currentPhase.value === 'concentration') {
    completedTime += concentrationTime.value * 60 - currentCountdown.value;
  } else if (currentPhase.value === 'break') {
    completedTime += concentrationTime.value * 60; // El intervalo de concentración completo
    completedTime += breakTime.value * 60 - currentCountdown.value;
  }
  
  return (completedTime / totalTime) * 100;
});

// Función para editar el tiempo
const updateTime = (type, operation) => {
  if (isRunning.value) return; // No permitir cambios mientras está en ejecución
  
  if (type === 'hours') {
    if (operation === 'increase' && hours.value < 23) {
      hours.value++;
    } else if (operation === 'decrease' && hours.value > 0) {
      hours.value--;
    }
  } else if (type === 'minutes') {
    if (operation === 'increase' && minutes.value < 59) {
      minutes.value++;
    } else if (operation === 'decrease' && minutes.value > 0) {
      minutes.value--;
    }
  } else if (type === 'seconds') {
    if (operation === 'increase' && seconds.value < 59) {
      seconds.value++;
    } else if (operation === 'decrease' && seconds.value > 0) {
      seconds.value--;
    }
  } else if (type === 'concentrationIntervals') {
    if (operation === 'increase' && concentrationIntervals.value < 10) {
      concentrationIntervals.value++;
    } else if (operation === 'decrease' && concentrationIntervals.value > 1) {
      concentrationIntervals.value--;
    }
  } else if (type === 'concentrationTime') {
    if (operation === 'increase' && concentrationTime.value < 60) {
      concentrationTime.value++;
    } else if (operation === 'decrease' && concentrationTime.value > 1) {
      concentrationTime.value--;
    }
  } else if (type === 'breakIntervals') {
    if (operation === 'increase' && breakIntervals.value < 10) {
      breakIntervals.value++;
    } else if (operation === 'decrease' && breakIntervals.value > 0) {
      breakIntervals.value--;
    }
  } else if (type === 'breakTime') {
    if (operation === 'increase' && breakTime.value < 30) {
      breakTime.value++;
    } else if (operation === 'decrease' && breakTime.value > 1) {
      breakTime.value--;
    }
  }
  
  // Actualizar el mensaje al cambiar la configuración
  updateStatusMessage();
};

// Iniciar el temporizador
const startTimer = () => {
  if (isRunning.value && !isPaused.value) return;
  
  if (currentPhase.value === 'setup' || currentPhase.value === 'completed') {
    // Iniciar desde el principio
    currentPhase.value = 'concentration';
    currentIntervalNumber.value = 1;
    currentCountdown.value = concentrationTime.value * 60;
    updateStatusMessage();
  }
  
  isRunning.value = true;
  isPaused.value = false;
  waitingForUserAction.value = false;
  
  timerInterval.value = setInterval(() => {
    if (currentCountdown.value > 0) {
      currentCountdown.value--;
    } else {
      handleIntervalComplete();
    }
  }, 1000);
};

// Pausar el temporizador
const pauseTimer = () => {
  if (!isRunning.value) return;
  clearInterval(timerInterval.value);
  isPaused.value = true;
  statusMessage.value += ' (Pausado)';
};

// Detener y resetear el temporizador
const stopTimer = () => {
  clearInterval(timerInterval.value);
  isRunning.value = false;
  isPaused.value = false;
  waitingForUserAction.value = false;
  currentPhase.value = 'setup';
  currentIntervalNumber.value = 0;
  updateStatusMessage();
};

// Continuar al siguiente intervalo
const continueToNextInterval = () => {
  if (!waitingForUserAction.value) return;
  
  waitingForUserAction.value = false;
  
  if (currentPhase.value === 'concentration') {
    // Pasar al descanso si no es el último intervalo
    if (currentIntervalNumber.value <= breakIntervals.value) {
      currentPhase.value = 'break';
      currentCountdown.value = breakTime.value * 60;
      updateStatusMessage();
      startTimer();
    } else {
      // Finalizar si no hay más descansos
      completeSession();
    }
  } else if (currentPhase.value === 'break') {
    // Pasar al siguiente intervalo de concentración
    if (currentIntervalNumber.value < concentrationIntervals.value) {
      currentIntervalNumber.value++;
      currentPhase.value = 'concentration';
      currentCountdown.value = concentrationTime.value * 60;
      updateStatusMessage();
      startTimer();
    } else {
      // Finalizar la sesión
      completeSession();
    }
  }
};

// Manejar cuando un intervalo se completa
const handleIntervalComplete = () => {
  clearInterval(timerInterval.value);
  isPaused.value = true;
  waitingForUserAction.value = true;
  
  // Reproducir sonido y enviar notificación dependiendo de la fase actual
  if (currentPhase.value === 'concentration') {
    sendNotification('¡Intervalo de concentración completado!', 'Toma un descanso antes de continuar.');
    statusMessage.value = '¡Intervalo de concentración completado! Haz clic en "Continuar" para tu descanso.';
  } else if (currentPhase.value === 'break') {
    sendNotification('¡Descanso completado!', 'Es hora de volver a concentrarte.');
    statusMessage.value = '¡Descanso completado! Haz clic en "Continuar" para el siguiente intervalo de concentración.';
  }
};

// Finalizar la sesión completa
const completeSession = () => {
  clearInterval(timerInterval.value);
  isRunning.value = false;
  isPaused.value = false;
  waitingForUserAction.value = false;
  currentPhase.value = 'completed';
  sendNotification('¡Sesión completada!', 'Has terminado todos los intervalos. ¡Buen trabajo!');
  statusMessage.value = '¡Sesión completada! Has terminado todos los intervalos programados.';
};

// Actualizar el mensaje de estado
const updateStatusMessage = () => {
  if (currentPhase.value === 'setup') {
    statusMessage.value = 'Listo para comenzar';
  } else if (currentPhase.value === 'concentration') {
    statusMessage.value = `Concentración: Intervalo ${currentIntervalNumber.value}/${concentrationIntervals.value}`;
  } else if (currentPhase.value === 'break') {
    statusMessage.value = `Descanso: ${breakTime.value} minutos`;
  } else if (currentPhase.value === 'completed') {
    statusMessage.value = '¡Sesión completada!';
  }
};

// --------------------------------
// IMPLEMENTACIÓN DE WEB NOTIFICATIONS API
// --------------------------------

/**
 * Reproducir sonido de notificación
 * Esta función usa el objeto Audio para reproducir un sonido
 * Se usa como respaldo en caso de que la notificación no pueda reproducir sonido
 */
const playNotificationSound = () => {
  if (notificationSound) {
    notificationSound.currentTime = 0;
    notificationSound.play().catch(error => console.log('Error al reproducir sonido:', error));
  }
};

/**
 * Enviar notificación con Web Notifications API
 * Esta función muestra notificaciones del sistema operativo y reproduce sonido
 * 
 * @param {string} title - Título de la notificación
 * @param {string} body - Cuerpo/mensaje de la notificación
 */
const sendNotification = (title, body) => {
  // Verificar si las notificaciones están disponibles en el navegador
  if ('Notification' in window) {
    if (Notification.permission === 'granted') {
      // Si ya tenemos permiso, mostramos la notificación inmediatamente
      const notification = new Notification(title, {
        body: body,
        icon: '/icon.png', // Puedes añadir un icono personalizado
        sound: '/sounds/notification.mp3' // Propiedad para sonido (no es compatible con todos los navegadores)
      });
      
      // Reproducimos el sonido manualmente como respaldo
      playNotificationSound();
      
      // Opcionalmente podemos añadir un evento para cuando el usuario hace clic en la notificación
      notification.onclick = function() {
        // Enfoca la ventana de la aplicación cuando se hace clic en la notificación
        window.focus();
        notification.close();
      };
    } else if (Notification.permission !== 'denied') {
      // Si no hemos pedido permiso aún, lo solicitamos
      Notification.requestPermission().then(permission => {
        if (permission === 'granted') {
          // Si se concede el permiso, mostramos la notificación
          const notification = new Notification(title, {
            body: body,
            icon: '/icon.png',
            sound: '/sounds/notification.mp3'
          });
          
          // Reproducimos el sonido manualmente como respaldo
          playNotificationSound();
          
          notification.onclick = function() {
            window.focus();
            notification.close();
          };
        } else {
          // Si se rechaza el permiso, solo reproducimos el sonido
          playNotificationSound();
        }
      });
    } else {
      // Si las notificaciones están denegadas, solo reproducimos el sonido
      playNotificationSound();
    }
  } else {
    // Si el navegador no admite notificaciones, solo reproducimos el sonido
    playNotificationSound();
  }
};

/**
 * Solicitar permiso para notificaciones al iniciar la aplicación
 * Es buena práctica solicitar el permiso al iniciar para mejorar la UX
 */
const requestNotificationPermission = () => {
  // Verificar si las notificaciones están disponibles y aún no se ha pedido permiso
  if ('Notification' in window && Notification.permission !== 'granted' && Notification.permission !== 'denied') {
    // Mostramos un mensaje informativo antes de solicitar permiso (opcional)
    statusMessage.value = 'Para recibir alertas, necesitamos tu permiso para mostrar notificaciones';
    
    // Solicitamos permiso
    Notification.requestPermission().then(permission => {
      if (permission === 'granted') {
        statusMessage.value = 'Notificaciones habilitadas. ¡Listo para comenzar!';
      } else {
        statusMessage.value = 'Las notificaciones están deshabilitadas. Serás notificado solo con sonido.';
      }
      
      // Restauramos el mensaje normal después de 3 segundos
      setTimeout(() => {
        updateStatusMessage();
      }, 3000);
    });
  }
};

// Llamar a la función para solicitar permiso cuando el componente se monta
requestNotificationPermission();

// Limpiar el intervalo antes de desmontar el componente
onBeforeUnmount(() => {
  if (timerInterval.value) {
    clearInterval(timerInterval.value);
  }
});

// Vigilar cambios en el tiempo total para mostrar información
watch([hours, minutes, seconds, concentrationIntervals, concentrationTime, breakIntervals, breakTime], () => {
  if (!isRunning.value) {
    updateStatusMessage();
  }
});
</script>

<template>
    <Head title="Concentración" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                CONCENTRACIÓN
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <p class="text-gray-700 dark:text-gray-300 text-center">
                    Ingresa la cantidad de tiempo que quieres concentrarte y nosotros generamos tu ciclo de concentración.
                </p>

                <!-- Estado actual -->
                <div class="mt-4 text-center text-purple-700 dark:text-purple-300 font-semibold text-lg">
                    {{ statusMessage }}
                </div>

                <!-- Barra de progreso del intervalo actual -->
                <div v-if="isRunning || waitingForUserAction" class="mt-2">
                    <div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                        <span>Intervalo actual:</span>
                        <span>{{ Math.round(intervalProgress) }}%</span>
                    </div>
                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-1000"
                             :class="{'bg-purple-600': currentPhase === 'concentration', 'bg-green-500': currentPhase === 'break'}"
                             :style="{ width: intervalProgress + '%' }">
                        </div>
                    </div>
                </div>

                <!-- Barra de progreso de la sesión completa -->
                <div v-if="isRunning || waitingForUserAction" class="mt-2">
                    <div class="text-sm text-gray-600 dark:text-gray-400 flex justify-between">
                        <span>Progreso total:</span>
                        <span>{{ Math.round(sessionProgress) }}%</span>
                    </div>
                    <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 transition-all duration-1000"
                             :style="{ width: sessionProgress + '%' }">
                        </div>
                    </div>
                </div>

                <!-- Configuración de tiempo -->
                <div class="flex justify-center items-center mt-6 bg-purple-100 dark:bg-purple-900 p-4 rounded-lg">
                    <div class="flex space-x-4">
                        <!-- Horas -->
                        <div class="relative">
                            <button @click="updateTime('hours', 'increase')" 
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↑
                            </button>
                            <div class="bg-purple-300 dark:bg-purple-700 px-6 py-4 text-center rounded-lg">
                                <p class="text-2xl font-bold">
                                    {{ (isRunning || waitingForUserAction) ? formattedCountdown.hours : formattedTotalTime.hours }}
                                </p>
                                <p class="text-sm">H</p>
                            </div>
                            <button @click="updateTime('hours', 'decrease')" 
                                    class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↓
                            </button>
                        </div>
                        <p class="text-2xl font-bold self-center">•</p>
                        
                        <!-- Minutos -->
                        <div class="relative">
                            <button @click="updateTime('minutes', 'increase')" 
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↑
                            </button>
                            <div class="bg-purple-300 dark:bg-purple-700 px-6 py-4 text-center rounded-lg">
                                <p class="text-2xl font-bold">
                                    {{ (isRunning || waitingForUserAction) ? formattedCountdown.minutes : formattedTotalTime.minutes }}
                                </p>
                                <p class="text-sm">MIN</p>
                            </div>
                            <button @click="updateTime('minutes', 'decrease')" 
                                    class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↓
                            </button>
                        </div>
                        <p class="text-2xl font-bold self-center">•</p>
                        
                        <!-- Segundos -->
                        <div class="relative">
                            <button @click="updateTime('seconds', 'increase')" 
                                    class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↑
                            </button>
                            <div class="bg-purple-300 dark:bg-purple-700 px-6 py-4 text-center rounded-lg">
                                <p class="text-2xl font-bold">
                                    {{ (isRunning || waitingForUserAction) ? formattedCountdown.seconds : formattedTotalTime.seconds }}
                                </p>
                                <p class="text-sm">SEG</p>
                            </div>
                            <button @click="updateTime('seconds', 'decrease')" 
                                    class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 w-8 h-8 bg-purple-500 text-white rounded-full"
                                    :disabled="isRunning">
                                ↓
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Controles del temporizador -->
                <div class="flex justify-center mt-8 space-x-4">
                    <button 
                        v-if="!waitingForUserAction"
                        @click="isRunning && !isPaused ? pauseTimer() : startTimer()" 
                        class="px-6 py-2 rounded-lg transition"
                        :class="[isRunning && !isPaused ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600']">
                        {{ isRunning && !isPaused ? 'Pausar' : (isPaused ? 'Reanudar' : 'Iniciar') }}
                    </button>
                    
                    <button 
                        v-if="waitingForUserAction"
                        @click="continueToNextInterval" 
                        class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">
                        Continuar
                    </button>
                    
                    <button 
                        @click="stopTimer" 
                        class="bg-red-500 text-white px-6 py-2 rounded-lg hover:bg-red-600 transition"
                        :disabled="!isRunning && !isPaused && !waitingForUserAction">
                        Detener
                    </button>
                </div>

                <!-- Intervalos -->
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Intervalos de concentración -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <p class="font-bold text-center mb-2">Intervalos de concentración</p>
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center justify-between">
                                <span>Cantidad:</span>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateTime('concentrationIntervals', 'decrease')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-l-lg"
                                            :disabled="isRunning">-</button>
                                    <span class="bg-purple-300 dark:bg-purple-700 px-4 py-1 font-bold">{{ concentrationIntervals }}</span>
                                    <button @click="updateTime('concentrationIntervals', 'increase')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-r-lg"
                                            :disabled="isRunning">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Duración (min):</span>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateTime('concentrationTime', 'decrease')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-l-lg"
                                            :disabled="isRunning">-</button>
                                    <span class="bg-purple-300 dark:bg-purple-700 px-4 py-1 font-bold">{{ concentrationTime }}</span>
                                    <button @click="updateTime('concentrationTime', 'increase')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-r-lg"
                                            :disabled="isRunning">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Intervalos de descanso -->
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <p class="font-bold text-center mb-2">Intervalos de descanso</p>
                        <div class="flex flex-col space-y-4">
                            <div class="flex items-center justify-between">
                                <span>Cantidad:</span>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateTime('breakIntervals', 'decrease')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-l-lg"
                                            :disabled="isRunning">-</button>
                                    <span class="bg-purple-300 dark:bg-purple-700 px-4 py-1 font-bold">{{ breakIntervals }}</span>
                                    <button @click="updateTime('breakIntervals', 'increase')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-r-lg"
                                            :disabled="isRunning">+</button>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Duración (min):</span>
                                <div class="flex items-center space-x-2">
                                    <button @click="updateTime('breakTime', 'decrease')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-l-lg"
                                            :disabled="isRunning">-</button>
                                    <span class="bg-purple-300 dark:bg-purple-700 px-4 py-1 font-bold">{{ breakTime }}</span>
                                    <button @click="updateTime('breakTime', 'increase')" 
                                            class="w-8 h-8 bg-purple-500 text-white rounded-r-lg"
                                            :disabled="isRunning">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tiempo total estimado -->
                <div v-if="!isRunning && !waitingForUserAction" class="mt-6 text-center text-gray-600 dark:text-gray-400">
                    <p>Tiempo estimado para completar todos los intervalos: 
                        <span class="font-medium">{{ Math.floor(totalCycleTime / 60) }} minutos y {{ totalCycleTime % 60 }} segundos</span>
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>