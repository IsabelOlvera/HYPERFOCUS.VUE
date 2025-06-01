<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';

// Recibir los datos desde el controlador
const props = defineProps({
    conjuntos: Array,
    flash: Object
});

const showModal = ref(false);
const showEditModal = ref(false);
const editingConjunto = ref(null);


// Variables del modal
const showSuccessModal = ref(false);
const successMessage = ref('');

// Variables para modal de eliminacion
const showDeleteModal = ref(false);
const deletingConjuntoId = ref(null);

// Variables para modal de confirmación de práctica
const showPracticaModal = ref(false);
const practicaConjuntoId = ref(null);


const form = useForm({
    nombre: '',
    descripcion: ''
});

const editForm = useForm({
    nombre: '',
    descripcion: ''
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const openEditModal = (conjunto) => {
    editingConjunto.value = conjunto;
    editForm.nombre = conjunto.nombre;
    editForm.descripcion = conjunto.descripcion;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingConjunto.value = null;
    editForm.reset();
};

const submitForm = () => {
    form.post(route('memory.store'), {
        onSuccess: () => {
            form.reset();
            closeModal();
            
            // Muestra el modal de éxito
            if (props.flash && props.flash.success) {
                showSuccess(props.flash.success);
            } else {
                showSuccess('Conjunto creado con éxito');
            }
        }
    });
};

const submitEditForm = () => {
    editForm.put(route('memory.update', editingConjunto.value.id), {
        onSuccess: () => {
            closeEditModal();
            showSuccess('Conjunto actualizado con éxito');
        }
    });
};

const deleteConjunto = (id) => {
    deletingConjuntoId.value = id;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    router.delete(route('memory.destroy', deletingConjuntoId.value), {
        onSuccess: () => {
            showDeleteModal.value = false;
            showSuccess('Conjunto eliminado con éxito');
        }
    });
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    deletingConjuntoId.value = null;
};

// Funciones para el modal de confirmación de práctica
const iniciarPractica = (id) => {
    practicaConjuntoId.value = id;
    showPracticaModal.value = true;
};

const confirmPractica = () => {
    router.get(route('practicar.index', practicaConjuntoId.value));
    showPracticaModal.value = false;
};

const cancelPractica = () => {
    showPracticaModal.value = false;
    practicaConjuntoId.value = null;
};

const showSuccess = (message) => {
  successMessage.value = message;
  showSuccessModal.value = true;
  
  // Cierra automáticamente después de 2 segundos
  setTimeout(() => {
    showSuccessModal.value = false;
  }, 2000);
};

</script>

<template>
    <Head title="Memoria" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                MEMORIA
            </h2>
        </template>

        <!--<div v-if="flash && flash.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mx-8 mt-4">
            {{ flash.success }}
        </div>-->

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="flex flex-wrap gap-6">
                    <!-- Mostrar los conjuntos existentes -->
                    <div v-for="conjunto in conjuntos" :key="conjunto.id" 
                         class="bg-purple-100 p-6 rounded-lg shadow-lg w-1/3 max-w-sm">
                        <div class="bg-purple-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                            <span>✔ {{ conjunto.nombre }}</span>
                        </div>
                        <p class="mt-4 text-gray-700">{{ conjunto.descripcion }}</p>
                        <p class="text-sm text-gray-500 mt-2">Creado: {{ new Date(conjunto.fecha_creacion).toLocaleDateString() }}</p>
                        <hr class="my-2 border-gray-400" />
                        <div class="space-y-3 mt-4">
                            
                        <button @click="iniciarPractica(conjunto.id)" 
                            class="w-full bg-purple-400 text-white px-4 py-2 rounded-lg">
                            Practicar
                        </button>

                            <button @click="openEditModal(conjunto)" 
                                    class="w-full bg-blue-400 text-white px-4 py-2 rounded-lg">Editar</button>
                            <button @click="deleteConjunto(conjunto.id)" 
                                    class="w-full bg-red-400 text-white px-4 py-2 rounded-lg">Eliminar</button>
                        </div>
                        <Link :href="route('conceptos.index', conjunto.id)" 
                            class="w-full bg-green-400 text-white px-4 py-2 rounded-lg mb-2">
                            Ver Conceptos ({{ conjunto.conceptos ? conjunto.conceptos.length : 0 }})
                        </Link>
                    </div>

                    <!-- Crear nueva práctica -->
                    <div class="bg-purple-100 p-6 rounded-lg shadow-lg w-1/3 max-w-sm">
                        <div class="bg-purple-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                            <span>➕ CREAR</span>
                        </div>
                        <hr class="my-2 border-gray-400" />
                        <div class="flex flex-col items-center mt-6">
                            <div @click="openModal" class="bg-purple-400 text-white rounded-full p-4 text-3xl cursor-pointer">+</div>
                            <button @click="openModal" class="mt-4 bg-purple-500 text-white px-6 py-2 rounded-lg">Crear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear nuevo conjunto -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Crear Nuevo Conjunto</h2>
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">Nombre:</label>
                        <input v-model="form.nombre" type="text" id="nombre" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm mt-1">{{ form.errors.nombre }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="descripcion">Descripción:</label>
                        <textarea v-model="form.descripcion" id="descripcion" rows="4"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        <div v-if="form.errors.descripcion" class="text-red-500 text-sm mt-1">{{ form.errors.descripcion }}</div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
                        <button type="submit" class="bg-purple-500 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para editar conjunto -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" @click="closeEditModal"></div>
            <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Editar Conjunto</h2>
                <form @submit.prevent="submitEditForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="editNombre">Nombre:</label>
                        <input v-model="editForm.nombre" type="text" id="editNombre" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div v-if="editForm.errors.nombre" class="text-red-500 text-sm mt-1">{{ editForm.errors.nombre }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="editDescripcion">Descripción:</label>
                        <textarea v-model="editForm.descripcion" id="editDescripcion" rows="4"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        <div v-if="editForm.errors.descripcion" class="text-red-500 text-sm mt-1">{{ editForm.errors.descripcion }}</div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeEditModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de éxito -->
<div v-if="showSuccessModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full border-l-4 border-green-500">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <!-- Ícono de éxito -->
                <svg class="h-6 w-6 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ successMessage }}
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Modal de confirmación de eliminación -->
<div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-50" @click="cancelDelete"></div>
    <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Confirmar eliminación</h2>
        <p class="mb-6 text-gray-700">¿Estás seguro de que deseas eliminar este conjunto? Esta acción no se puede deshacer.</p>
        <div class="flex justify-end">
            <button type="button" @click="cancelDelete" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
            <button type="button" @click="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
        </div>
    </div>
</div>

<!-- Modal de confirmación de práctica -->
<div v-if="showPracticaModal" class="fixed inset-0 flex items-center justify-center z-50">
    <div class="absolute inset-0 bg-black opacity-50" @click="cancelPractica"></div>
    <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
        <h2 class="text-2xl font-bold mb-4">Confirmar práctica</h2>
        <p class="mb-6 text-gray-700">¿Estás seguro de que deseas iniciar una práctica con este conjunto?</p>
        <div class="flex justify-end">
            <button type="button" @click="cancelPractica" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
            <button type="button" @click="confirmPractica" class="bg-purple-500 text-white px-4 py-2 rounded">Comenzar</button>
        </div>
    </div>
</div>
    </AuthenticatedLayout>
</template>