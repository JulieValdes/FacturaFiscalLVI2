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
    articulo_poriva: '16.00',
    articulo_retiva: '0.0000',
    articulo_retisr: '0.0000',
    borrado: '0',
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
        confirmButtonText: '<i class="ml-2 fa-solid fa-check"></i> Sí, eliminar',
        cancelButtonText: '<i class="mr-2 fa-solid fa-ban"></i> Cancelar',
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
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Productos</h2>
        </template>

        <div class="py-12">
            <div class="grid bg-white v-screen place-items-center">
                <div class="flex mt-3 mb-3">
                    <PrimaryButton @click="openModal(1)">
                        <i class="fa-solid fa-plus-circle"></i> Agregar
                    </PrimaryButton>
                </div>
            </div>
            <div class="grid py-3 overflow-x-auto bg-white v-screen place-items-center">
                <table class="border border-gray-400 table-auto">
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
                            <td class="px-2 py-2 border border-gray-400">{{ articulo.k_articulo }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ articulo.articulo_clave }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ articulo.articulo_nombre }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ articulo.articulo_codigo }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ articulo.articulo_precio }}</td>
                            <td class="px-2 py-2 border border-gray-400">
                                <WarningButton 
                                    @click="openModal(2, articulo.k_articulo, articulo.k_empresa, articulo.articulo_clave, articulo.articulo_nombre, articulo.articulo_codigo, articulo.articulo_unidad, articulo.articulo_almacenable, articulo.articulo_precio, articulo.articulo_costo, articulo.articulo_poriva, articulo.articulo_retiva, articulo.articulo_retisr, articulo.borrado, articulo.articulo_codigo_sat, articulo.articulo_unidad_sat)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="px-2 py-2 border border-gray-400">
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
            <div class="flex flex-col items-center justify-center mt-5">
                <h2 class="justify-start p-3 text-lg text-gray-900 font-semibolds ">{{ title }}</h2>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulos_nombre" value="Nombre :"></InputLabel>
                        <TextInput id="articulos_nombre" ref="nameInput"
                        v-model="form.articulo_nombre" type="text" class="w-full mt-1 mr-6 block-sm" placeholder="Nombre"></TextInput>
                        <InputError :message="form.errors.articulo_nombre" class="mt-2"></InputError>
                    </div>
                    <div class="p-3"  >
                        <InputLabel for="articulo_clave" value="Clave Ext:" class="ml-4"></InputLabel>
                        <TextInput id="articulo_clave"
                        v-model="form.articulo_clave" type="text" class="w-full mt-1 ml-4 block-sm"
                        placeholder="Clave"></TextInput>
                        <InputError :message="form.errors.articulo_clave" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_codigo" value="Código:"></InputLabel>
                        <TextInput id="articulo_codigo"
                        v-model="form.articulo_codigo" type="text" class="w-full mt-1 mr-6 block-sm"
                        placeholder="Código"></TextInput>
                        <InputError :message="form.errors.articulo_codigo" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_codigo_sat"  class="ml-4" >
                            Código SAT:
                        </InputLabel>
                        <div class="relative">
                            <TextInput id="articulo_codigo_sat"
                                v-model="form.articulo_codigo_sat" type="text" class="w-full mt-1 ml-4 block-sm"
                                placeholder="Código SAT">
                            </TextInput>
                            <a href="http://200.57.3.89/PyS/catPyS.aspx" target="_blank" rel="noopener noreferrer" class="absolute transform -translate-y-1/2 right-1 top-1/2" title="Guia SAT">
                                <i class="fa-solid fa-search"></i>
                            </a>
                        </div>
                        <InputError :message="form.errors.articulo_codigo_sat" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_unidad" value="Unidad:"></InputLabel>
                        <TextInput id="articulo_unidad"
                        v-model="form.articulo_unidad" type="text" class="w-full mt-1 mr-6 block-sm"
                        placeholder="Unidad"></TextInput>
                        <InputError :message="form.errors.articulo_unidad" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_unidad_sat" value="Unidad SAT:" class="ml-4"></InputLabel>
                        <div class="relative">
                            <TextInput id="articulo_unidad_sat"
                            v-model="form.articulo_unidad_sat" type="text" class="w-full mt-1 ml-4 block-sm"
                            placeholder="Unidad SAT"></TextInput>
                            <a href="http://200.57.3.89/PyS/catUnidades.aspx" target="_blank" rel="noopener noreferrer" class="absolute transform -translate-y-1/2 right-1 top-1/2" title="Guia SAT">
                                <i class="fa-solid fa-search"></i>
                            </a>
                        </div>
                        <InputError :message="form.errors.articulo_unidad_sat" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="articulo_precio" value="Precio:"></InputLabel>
                        <TextInput id="articulo_precio"
                        v-model="form.articulo_precio" type="text" class="w-full mt-1 mr-6 block-sm"
                        placeholder="Precio"></TextInput>
                        <InputError :message="form.errors.articulo_precio" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="articulo_costo" value="Costo:"  class="ml-4"></InputLabel>
                        <TextInput id="articulo_costo"
                        v-model="form.articulo_costo" type="text" class="w-full mt-1 ml-4 block-sm"
                        placeholder="Costo"></TextInput>
                        <InputError :message="form.errors.articulo_costo" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3 ">
                        <InputLabel for="articulo_poriva" value="Porcentaje IVA %:"></InputLabel>
                        <TextInput id="articulo_poriva"
                        v-model="form.articulo_poriva" type="text" class="block w-full mt-1 mr-6"
                        placeholder="Porcentaje IVA"></TextInput>
                        <InputError :message="form.errors.articulo_poriva" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <div class="w-full ml-6 mt-7 block-sm">
                            <input type="radio"  id="articulo_almacenable" name="cl_o_pr" default_value="0" value="1" v-model="form.articulo_almacenable">
                            <label for="articulo_almacenable">Almacenable</label><br>
                        </div>
                    </div>
                </div>
                
                <div class="p-3 mt-3">
                    <PrimaryButton :disabled="form.processing" @click="save">
                        <i class="fa-solid fa-save"></i> Guardar
                    </PrimaryButton>
                </div>
            </div>
            <div class="flex justify-end p-3 mt-6">
                <SecondaryButton class="ml-3" :disabled="form.processing" @click="closeModal">
                    Cancelar
                </SecondaryButton>
            </div>
            
        </Modal>
    </AuthenticatedLayout>
</template>