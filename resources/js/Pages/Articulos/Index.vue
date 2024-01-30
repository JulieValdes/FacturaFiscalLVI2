<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { Head,useForm } from '@inertiajs/vue3';
import { nextTick,ref } from 'vue';
import Swal from 'sweetalert2';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';
import { onMounted } from 'vue';

const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const k_articulo = ref('');
const k_empresa = ref('');

const props = defineProps({
    articulos: {type:Object}
});

onMounted(() => {
    console.log(props.articulos);
})

const form = useForm({
    k_articulo:'',
    k_empresa: '', 
    articulo_clave: '', 
    articulo_nombre: '', 
    articulo_codigo: '',
    articulo_unidad: '',
    articulo_almacenable: '',
    articulo_precio: '',
    articulo_costo: '',
    articulo_poriva: '',
    articulo_retiva: '',
    articulo_retisr: '',
    borrado: '',
    articulo_codigo_sat: '',
    articulo_unidad_sat: ''
},);

const formPage = useForm({});

const openModal= (op, articulo, empresa, articulo_clave, articulo_nombre, articulo_codigo, articulo_unidad, articulo_almacenable, articulo_precio, articulo_costo, articulo_poriva, articulo_retiva, articulo_retisr, borrado, articulo_codigo_sat, articulo_unidad_sat) => {
    operation.value = op;
    
    modal.value = true;
    nextTick(() => nameInput.value.focus());

    operation.value = op;
    k_articulo.value = articulo; 
    k_empresa.value = empresa;

    if(op == 1){
        title.value = 'Agregar Artículo';
    }else{
        title.value = 'Editar Artículo';
        form.articulo_clave = articulo_clave;
        form.articulo_nombre = articulo_nombre;
        form.articulo_codigo = articulo_codigo;
        form.articulo_unidad = articulo_unidad;
        form.articulo_almacenable = articulo_almacenable;
        form.articulo_precio = articulo_precio;
        form.articulo_costo = articulo_costo;
        form.articulo_poriva = articulo_poriva;
        form.articulo_retiva = articulo_retiva;
        form.articulo_retisr = articulo_retisr;
        form.borrado = borrado;
        form.articulo_codigo_sat = articulo_codigo_sat;
        form.articulo_unidad_sat = articulo_unidad_sat;
    }
}

const closeModal = () => {
    modal.value = false;
    form.reset();
}

const er = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'error'});
}

const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'});
}

const save = () => {
    if(operation.value == 1){
        form.post(route('articulos.store'), {
            onSuccess: () => {
                ok('Artículo agregado');
                closeModal();
            },
            onError: (error) => {
            // Manejar errores aquí
            
            if (error.response) {
                // El servidor ha respondido con un código de estado fuera del rango de éxito
                console.error('Error de respuesta del servidor:', error.response);
                
                if (error.response.data && error.response.data.message) {
                    er('Error al actualizar: ' + error.response.data.message);
                } else {
                    er('Error al actualizar: respuesta del servidor sin mensaje específico');
                }
            } else if (error.request) {
                // La solicitud fue realizada pero no se recibió respuesta
                console.error('No se recibió respuesta del servidor');
                er('Error al actualizar: no se recibió respuesta del servidor');
            } else {
                // Algo ocurrió durante la configuración de la solicitud
                console.error('Error durante la configuración de la solicitud:', error.message);
                er('Error al actualizar: ' + error.message || 'Error desconocido');
            }
            console.error('Error completo:', error);
        },
    });
    }else{
        form.k_articulo = k_articulo.value;
        form.k_empresa = k_empresa.value;
        form.put(route('articulos.update', k_articulo.value), {
            k_empresa: form.k_empresa,
            articulo_nombre: form.articulo_nombre,
            onSuccess: () => {
                ok('Artículo actualizado');
                closeModal();
            },
            onError: (error) => {
            // Manejar errores aquí
            console.error('Error completo:', error);

            if (error.response) {
                // El servidor ha respondido con un código de estado fuera del rango de éxito
                console.error('Error de respuesta del servidor:', error.response);

                if (error.response.data && error.response.data.message) {
                    er('Error al actualizar: ' + error.response.data.message);
                } else {
                    er('Error al actualizar: respuesta del servidor sin mensaje específico');
                }
            } else if (error.request) {
                // La solicitud fue realizada pero no se recibió respuesta
                console.error('No se recibió respuesta del servidor');
                er('Error al actualizar: no se recibió respuesta del servidor');
            } else {
                // Algo ocurrió durante la configuración de la solicitud
                console.error('Error durante la configuración de la solicitud:', error.message);
                er('Error al actualizar: ' + error.message || 'Error desconocido');
            }
        },
        });
    }
}

const deleteArticulo = (k_articulo, articulo_nombre, k_empresa) => {
    const alerta = Swal.mixin({buttonsStyling: false,});

    alerta.fire({
        title: '¿Estás seguro que quieres eliminar ' + articulo_nombre + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check ml-2"></i> Sí, eliminar',
        cancelButtonText: '<i class="fa-solid fa-ban mr-2"></i> Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger mx-2', // Clase de estilo para el botón de confirmar
            cancelButton: 'btn btn-secondary',    // Clase de estilo para el botón de cancelar
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('articulos.destroy',  [k_articulo, k_empresa]), {
                onSuccess: () => {
                    ok('Artículo eliminado');
                },
            });
        }
    });
};
</script>

<template>
    <Head title="Productos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Productos</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <PrimaryButton @click="openModal(1)">
                        <i class="fa-solid fa-plus-circle"></i> Agregar
                    </PrimaryButton>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center overflow-x-auto py-3">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Clave Ext</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Código</th>
                            <th class="px-2 py-2">Precio</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(articulo, index) in articulos" :key="index">
                            <td class="border border-gray-400 px-2 py-2">{{ articulo.k_articulo }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ articulo.articulo_clave }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ articulo.articulo_nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ articulo.articulo_codigo }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ articulo.articulo_precio }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                    @click="openModal(2, articulo.k_articulo, articulo.k_empresa, articulo.articulo_clave, articulo.articulo_nombre, articulo.articulo_codigo, articulo.articulo_unidad, articulo.articulo_almacenable, articulo.articulo_precio, articulo.articulo_costo, articulo.articulo_poriva, articulo.articulo_retiva, articulo.articulo_retisr, articulo.borrado, articulo.articulo_codigo_sat, articulo.articulo_unidad_sat)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="deleteArticulo(articulo.k_articulo, articulo.articulo_nombre,articulo.k_empresa)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <div class="flex items-center justify-center flex-col mt-5">
                <h2 class="p-3 text-lg font-semibolds text-gray-900">{{ title }}</h2>
                <div class="p-3 mt-3 w-3/4">
                    <InputLabel for="articulos_nombre" value="Nombre:"></InputLabel>
                    <TextInput id="articulos_nombre" ref="nameInput"
                    v-model="form.articulo_nombre" type="text" class="mt-1 block w-full" placeholder="Nombre"></TextInput>
                    <InputError :message="form.errors.articulo_nombre" class="mt-2"></InputError>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_clave" value="Clave:"></InputLabel>
                        <TextInput id="articulo_clave"
                        v-model="form.articulo_clave" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Clave"></TextInput>
                        <InputError :message="form.errors.articulo_clave" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_codigo" value="Código:"></InputLabel>
                        <TextInput id="articulo_codigo"
                        v-model="form.articulo_codigo" type="text" class="mt-1 block-sm w-full"
                        placeholder="Código"></TextInput>
                        <InputError :message="form.errors.articulo_codigo" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_precio" value="Precio:"></InputLabel>
                        <TextInput id="articulo_precio"
                        v-model="form.articulo_precio" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Precio"></TextInput>
                        <InputError :message="form.errors.articulo_precio" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_costo" value="Costo:"></InputLabel>
                        <TextInput id="articulo_costo"
                        v-model="form.articulo_costo" type="text" class="mt-1 block-sm w-full"
                        placeholder="Costo"></TextInput>
                        <InputError :message="form.errors.articulo_costo" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_poriva" value="Porcentaje IVA:"></InputLabel>
                        <TextInput id="articulo_poriva"
                        v-model="form.articulo_poriva" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Porcentaje IVA"></TextInput>
                        <InputError :message="form.errors.articulo_poriva" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_retiva" value="Retención IVA:"></InputLabel>
                        <TextInput id="articulo_retiva"
                        v-model="form.articulo_retiva" type="text" class="mt-1 block-sm w-full"
                        placeholder="Retención IVA"></TextInput>
                        <InputError :message="form.errors.articulo_retiva" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_retisr" value="Retención ISR:"></InputLabel>
                        <TextInput id="articulo_retisr"
                        v-model="form.articulo_retisr" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Retención ISR"></TextInput>
                        <InputError :message="form.errors.articulo_retisr" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_codigo_sat" value="Código SAT:"></InputLabel>
                        <TextInput id="articulo_codigo_sat"
                        v-model="form.articulo_codigo_sat" type="text" class="mt-1 block-sm w-full"
                        placeholder="Código SAT"></TextInput>
                        <InputError :message="form.errors.articulo_codigo_sat" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_unidad" value="Unidad:"></InputLabel>
                        <TextInput id="articulo_unidad"
                        v-model="form.articulo_unidad" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Unidad"></TextInput>
                        <InputError :message="form.errors.articulo_unidad" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_unidad_sat" value="Unidad SAT:"></InputLabel>
                        <TextInput id="articulo_unidad_sat"
                        v-model="form.articulo_unidad_sat" type="text" class="mt-1 block-sm w-full"
                        placeholder="Unidad SAT"></TextInput>
                        <InputError :message="form.errors.articulo_unidad_sat" class="mt-2"></InputError>
                    </div>
                </div>
                
                
                <div class="p-3 mt-6">
                    <PrimaryButton :disabled="form.processing" @click="save">
                        <i class="fa-solid fa-save"></i> Guardar
                    </PrimaryButton>
                </div>
            </div>
            <div class="p-3 mt-6 flex justify-end">
                <SecondaryButton class="ml-3" :disabled="form.processing" @click="closeModal">
                    Cancelar
                </SecondaryButton>
            </div>
            
        </Modal>
    </AuthenticatedLayout>
</template>