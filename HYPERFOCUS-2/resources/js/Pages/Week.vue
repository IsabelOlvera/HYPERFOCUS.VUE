<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3'; // Usamos useForm para manejar el formulario
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head, usePage } from '@inertiajs/vue3';

import Swal from 'sweetalert2';
import Modal from '@/Components/ModalMisemana.vue'; // Importa el modal


//Obtencion de datos de consulta
const props = defineProps({
    usuario: Object,
    lunes: Array,
    martes: Array,
    miercoles: Array,
    jueves: Array,
    viernes: Array,
    sabado: Array,
    domingo: Array
});



// Usamos useForm para gestionar los datos del formulario
const form = useForm({
    name: '',
    hora: '',
    duration: '',
    fechaIn: '',
    priority: ''
});


// Agregar actividad SweetAlert y envio de datos
const submitActivity = async () => {
    try {
        // Validación de campos requeridos
        if (!form.name) form.errors.name = "El nombre es obligatorio.";
        if (!form.hora) form.errors.hora = "La hora es obligatoria.";
        if (!form.duration) form.errors.duration = "La duración es obligatoria.";
        if (!form.fechaIn) form.errors.fechaIn = "La fecha es obligatoria.";
        if (!form.priority) form.errors.priority = "Debe seleccionar una prioridad.";

        // Si hay errores, no enviamos el formulario
        if (Object.keys(form.errors).length > 0) {
            return;
        }

        // Enviar datos al servidor
        const response = await fetch('/week/add', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                name: form.name,
                hora: form.hora,
                duration: form.duration,
                fechaIn: form.fechaIn,
                priority: form.priority
            })
        });

        const data = await response.json(); // Esperamos la respuesta en formato JSON

        if (data.success) {
            Swal.fire({
                title: 'Actividad Agregada',
                text: `La actividad "${form.name}" se ha agregado con éxito.`,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });
            obtenerActividadesActualizadas();
            showModal.value = false; // Cerramos el modal
        } else {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al agregar la actividad.',
                icon: 'error',
                showConfirmButton: true
            });
        }
    } catch (error) {
        console.error("Error al agregar actividad:", error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al procesar la solicitud.',
            icon: 'error',
            showConfirmButton: true
        });
    }
};



//Modal para eliminacion 

const showModal = ref(false); // Estado del modal
const selectedItem = ref(null);

const openModal = (item) => {
    // console.log(item);

  selectedItem.value = item;  
//   console.log(selectedItem.value);
  showModal.value = true;  
};


//Actualizacion de actividades

const lunes = ref(props.lunes);
const martes = ref(props.martes);
const miercoles = ref(props.miercoles);
const jueves = ref(props.jueves);
const viernes = ref(props.viernes);
const sabado = ref(props.sabado);
const domingo = ref(props.domingo);

const obtenerActividadesActualizadas = async () => {
    try {
        const response = await fetch('/week/actividades');
        const data = await response.json();

        // Actualiza la lista de actividades
        lunes.value = data.lunes;
        martes.value = data.martes;
        miercoles.value = data.miercoles;
        jueves.value = data.jueves;
        viernes.value = data.viernes;
        sabado.value = data.sabado;
        domingo.value = data.domingo;
    } catch (error) {
        console.error("Error obteniendo actividades actualizadas:", error);
    }
};



// Eliminar actividad SweetAlert y envio de ID
const eliminarActividad = async () => {
    try {
        const response = await fetch(`/week/delete/${selectedItem.value.id}`, {
            method: 'POST',
            headers: { 
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: selectedItem.value.id }) // Enviamos el ID de la actividad
        });

        const data = await response.json(); // Esperamos la respuesta en formato JSON

        if (data.success) {
            Swal.fire({
                title: 'Actividad Eliminada',
                text: `La actividad "${selectedItem.value.act}" ha sido eliminada.`,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });

            obtenerActividadesActualizadas();
            showModal.value = false; // Cerramos el modal

        } else {
            // Si no se logró eliminar correctamente
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al eliminar la actividad.',
                icon: 'error',
                showConfirmButton: false,
                timer: 1500
            });
        }
    } catch (error) {
        console.error("Error eliminando actividad:", error);
        Swal.fire({
            title: 'Error',
            text: 'Hubo un problema al eliminar la actividad.',
            icon: 'error',
            showConfirmButton: true
        });
    }
};




</script>

<template>
    <Head title="Mi Semana" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MI SEMANA
            </h2>
            <!-- <h1>
                Bienvenido, {{ usuario }} 
            </h1> -->
            <!-- <p>{{ lunes }}</p> -->

        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="grid grid-cols-12 gap-4">
                    <!-- Sección Izquierda (Días de la Semana) -->
                    <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow">
                        <div class="space-y-4">
                            <!-- LUNES -->
                            <div>
                                <h3 class="font-bold  text-lg text-purple-600">Lunes</h3>
                                <ul v-if="lunes && lunes.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in lunes" :key="index" class=" ">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>    
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- MARTES -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Martes</h3>
                                <ul v-if="martes && martes.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in martes" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- MIERCOLES -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Miercoles</h3>
                                <ul v-if="miercoles && miercoles.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in miercoles" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- JUEVES -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Jueves</h3>
                                <ul v-if="jueves && jueves.length > 0"  class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in jueves" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- VIERNES -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Viernes</h3>
                                <ul v-if="viernes && viernes.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in viernes" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- SABADO -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Sabado</h3>
                                <ul v-if="sabado && sabado.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300">
                                    <ol v-for="(item, index) in sabado" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                            <!-- DOMINGO -->
                            <div>
                                <h3 class="font-bold text-lg text-purple-600">Domingo</h3>
                                <ul v-if="domingo && domingo.length > 0" class="list-disc pl-4 text-gray-700 dark:text-gray-300" >
                                    <ol v-for="(item, index) in domingo" :key="index" class="">
                                        <div class="flex justify-between items-center">
                                            <div>
                                                <input type="checkbox" class="mr-2">{{ item.act }} 
                                            </div>
                                            <button @click="openModal(item)" class=" text-sm font-semibold text-red text-red-400 text-center">❌</button>
                                        </div>
                                    </ol>
                                </ul>
                                <p v-else class="ml-3">No hay actividades.</p>
                                <hr class="my-2 border-gray-300">
                            </div>
                        </div>

                        <!-- MODAL -->
                        <Modal :isOpen="showModal" title="Eliminar actividad" @close="showModal = false">
                            <p class="mb-5 text-center text-lg">¿Seguro que quieres eliminar la acti vidad {{ selectedItem.act }}?</p>
                            <!-- <p>{{  selectedItem.id  }}</p> -->
                            <!-- <button @click="" class="px-4 py-2 bg-blue-500 text-white rounded mr-10">Cancelar</button> -->
                            <div class="flex justify-between items-center">
                                <button @click="showModal = false" class="px-4 py-2 bg-blue-500 text-white rounded mr-10">Cancelar</button>
                                <button @click="eliminarActividad" class="px-4 py-2 bg-blue-500 text-white rounded">Aceptar</button>
                            </div>
                        </Modal>
                    </div>

                    <!-- Sección de Agregar Actividades -->
                    <div class="col-span-4 bg-white dark:bg-gray-800 p-4 rounded-lg shadow space-y-4">
                        <div>
                            <h3 class="font-bold text-purple-600">Agregar actividad</h3>
                            <div class="space-y-2">
                                <!-- Campos del formulario -->
                                <input v-model="form.name" type="text" placeholder="Nombre" class="w-full p-2 border rounded" >
                                <p v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</p>
                                <div class="flex space-x-2">
                                    <input v-model="form.hora" type="time" placeholder="Duración" class="p-2 border rounded w-1/2">
                                    <input v-model="form.duration" type="number" placeholder="Duración" class="p-2 border rounded w-1/2">
                                </div>
                                <p v-if="form.errors.hora" class="text-red-500 text-sm">{{ form.errors.hora }}</p>
                                <p v-if="form.errors.duration" class="text-red-500 text-sm">{{ form.errors.duration }}</p>
                                <div class="flex space-x-2">
                                    <input v-model="form.fechaIn" type="date" placeholder="fechaIn" class="w-full p-2 border rounded" :min="startOfWeek" 
                                    :max="endOfWeek">
                                    <select v-model="form.priority" class="p-2 border rounded w-1/2" id="Prioridad">
                                        <option value="" disabled >Prioridad</option>
                                        <option value="1">Alta</option>
                                        <option value="2">Media</option>
                                        <option value="3">Baja</option>
                                    </select>
                                </div>
                                <p v-if="form.errors.fechaIn" class="text-red-500 text-sm">{{ form.errors.fechaIn }}</p>
                                <p v-if="form.errors.priority" class="text-red-500 text-sm">{{ form.errors.priority }}</p>


                                <button @click="submitActivity" class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                            </div>
                            <hr class="my-2 border-gray-300">
                        </div>

                        
                    </div>

                    <!-- Sección de Distribución de Tiempo -->
                    <div class="col-span-4 bg-purple-100 p-4 rounded-lg shadow">
                        <h3 class="font-bold text-purple-600 text-lg flex items-center">
                            ✅ DISTRIBUCIÓN DE TIEMPO
                        </h3>
                        <div class="grid grid-cols-1 gap-4 mt-4">
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Tiempo libre:</h4>
                                <div class="flex space-x-2 my-2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                </div>
                                <button class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                                <div class="mt-2 p-2 border rounded">14:00 a 13:00 🗑</div>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow">
                                <h4 class="font-semibold">Tiempo ocupado:</h4>
                                <div class="flex space-x-2 my-2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                    <input type="time" class="p-2 border rounded w-1/2">
                                </div>
                                <button class="bg-purple-500 text-white px-4 py-2 rounded">Agregar</button>
                                <div class="mt-2 p-2 border rounded">14:00 a 13:00 🗑</div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
  data() {
    return {
      form: {
        date: "",
        selected: ""
      }
    };
  },
  computed: {
    startOfWeek() {
      const today = new Date();
      const firstDay = today.getDate() - today.getDay(); // Restamos los días para llegar al domingo
      const start = new Date(today.setDate(firstDay + 1)); // Lunes
      return start.toISOString().split("T")[0];
    },
    endOfWeek() {
      const today = new Date();
      const lastDay = today.getDate() - today.getDay() + 7; // Sumar días hasta domingo
      const end = new Date(today.setDate(lastDay));
      return end.toISOString().split("T")[0];
    }
  }
};
</script>