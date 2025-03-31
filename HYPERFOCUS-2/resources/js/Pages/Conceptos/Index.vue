<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Sidebar from '@/Components/Sidebar.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

// Recibir los datos desde el controlador
const props = defineProps({
    conjunto: Object,
    conceptos: Array,
    flash: Object
});

const showModal = ref(false);
const showEditModal = ref(false);
const editingConcepto = ref(null);
const showDeleteModal = ref(false);
const deletingConceptoId = ref(null);

// Variables del modal
const showSuccessModal = ref(false);
const successMessage = ref('');

const form = useForm({
    nombre: '',
    definicion: ''
});

const editForm = useForm({
    nombre: '',
    definicion: ''
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const openEditModal = (concepto) => {
    editingConcepto.value = concepto;
    editForm.nombre = concepto.nombre;
    editForm.definicion = concepto.definicion;
    showEditModal.value = true;
};

const closeEditModal = () => {
    showEditModal.value = false;
    editingConcepto.value = null;
    editForm.reset();
};

const submitForm = () => {
    form.post(route('conceptos.store', props.conjunto.id), {
        onSuccess: () => {
            form.reset();
            closeModal();
            
            // Muestra el modal de éxito
            if (props.flash && props.flash.success) {
                showSuccess(props.flash.success);
            } else {
                showSuccess('Concepto creado con éxito');
            }
        }
    });
};

const submitEditForm = () => {
    editForm.put(route('conceptos.update', [props.conjunto.id, editingConcepto.value.id]), {
        onSuccess: () => {
            closeEditModal();
            showSuccess('Concepto actualizado con éxito');
        }
    });
};

const deleteConcepto = (id) => {
    deletingConceptoId.value = id;
    showDeleteModal.value = true;
};

const confirmDelete = () => {
    router.delete(route('conceptos.destroy', [props.conjunto.id, deletingConceptoId.value]), {
        onSuccess: () => {
            showDeleteModal.value = false;
            showSuccess('Concepto eliminado con éxito');
        }
    });
};

const cancelDelete = () => {
    showDeleteModal.value = false;
    deletingConceptoId.value = null;
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
    <Head :title="`Conceptos - ${conjunto.nombre}`" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-3xl font-bold text-center text-gray-900 dark:text-gray-200">
                CONCEPTOS: {{ conjunto.nombre }}
            </h2>
        </template>

        <div class="flex">
            <!-- Sidebar -->
            <Sidebar />

            <!-- Contenido Principal -->
            <div class="flex-1 py-6 px-8">
                <div class="mb-6">
                    <Link :href="route('memory')" class="bg-gray-300 text-gray-800 px-4 py-2 rounded">
                        Volver a Conjuntos
                    </Link>
                </div>
                
                <div class="flex flex-wrap gap-6">
                    <!-- Mostrar los conceptos existentes -->
                    <div v-for="concepto in conceptos" :key="concepto.id" 
                         class="bg-blue-100 p-6 rounded-lg shadow-lg w-1/3 max-w-sm">
                        <div class="bg-blue-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                            <span>✔ {{ concepto.nombre }}</span>
                        </div>
                        <p class="mt-4 text-gray-700">{{ concepto.definicion }}</p>
                        <hr class="my-2 border-gray-400" />
                        <div class="space-y-3 mt-4">
                            <button @click="openEditModal(concepto)" 
                                    class="w-full bg-blue-400 text-white px-4 py-2 rounded-lg">Editar</button>
                            <button @click="deleteConcepto(concepto.id)" 
                                    class="w-full bg-red-400 text-white px-4 py-2 rounded-lg">Eliminar</button>
                        </div>
                    </div>

                    <!-- Crear nuevo concepto -->
                    <div class="bg-blue-100 p-6 rounded-lg shadow-lg w-1/3 max-w-sm">
                        <div class="bg-blue-400 text-white font-bold px-4 py-2 rounded-t-lg flex items-center justify-between">
                            <span>➕ CREAR</span>
                        </div>
                        <hr class="my-2 border-gray-400" />
                        <div class="flex flex-col items-center mt-6">
                            <div @click="openModal" class="bg-blue-400 text-white rounded-full p-4 text-3xl cursor-pointer">+</div>
                            <button @click="openModal" class="mt-4 bg-blue-500 text-white px-6 py-2 rounded-lg">Crear</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para crear nuevo concepto -->
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" @click="closeModal"></div>
            <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Crear Nuevo Concepto</h2>
                <form @submit.prevent="submitForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="nombre">Nombre:</label>
                        <input v-model="form.nombre" type="text" id="nombre" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm mt-1">{{ form.errors.nombre }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="definicion">Definición:</label>
                        <textarea v-model="form.definicion" id="definicion" rows="4"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        <div v-if="form.errors.definicion" class="text-red-500 text-sm mt-1">{{ form.errors.definicion }}</div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal para editar concepto -->
        <div v-if="showEditModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" @click="closeEditModal"></div>
            <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Editar Concepto</h2>
                <form @submit.prevent="submitEditForm">
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="editNombre">Nombre:</label>
                        <input v-model="editForm.nombre" type="text" id="editNombre" 
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        <div v-if="editForm.errors.nombre" class="text-red-500 text-sm mt-1">{{ editForm.errors.nombre }}</div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="editDefinicion">Definición:</label>
                        <textarea v-model="editForm.definicion" id="editDefinicion" rows="4"
                                 class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
                        <div v-if="editForm.errors.definicion" class="text-red-500 text-sm mt-1">{{ editForm.errors.definicion }}</div>
                    </div>
                    <div class="flex justify-end">
                        <button type="button" @click="closeEditModal" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="absolute inset-0 bg-black opacity-50" @click="cancelDelete"></div>
            <div class="bg-white p-6 rounded-lg shadow-lg z-10 max-w-md w-full">
                <h2 class="text-2xl font-bold mb-4">Confirmar eliminación</h2>
                <p class="mb-6 text-gray-700">¿Estás seguro de que deseas eliminar este concepto? Esta acción no se puede deshacer.</p>
                <div class="flex justify-end">
                    <button type="button" @click="cancelDelete" class="bg-gray-300 text-gray-800 px-4 py-2 rounded mr-2">Cancelar</button>
                    <button type="button" @click="confirmDelete" class="bg-red-500 text-white px-4 py-2 rounded">Eliminar</button>
                </div>
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
    </AuthenticatedLayout>
</template>