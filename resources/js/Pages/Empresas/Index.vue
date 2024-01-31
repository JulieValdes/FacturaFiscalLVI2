<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Swal from 'sweetalert2';
import { Head, useForm, Link } from '@inertiajs/vue3';
import {onMounted} from 'vue'
import { defineProps } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
import WarningButton from '@/Components/WarningButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { nextTick,ref } from 'vue';
import VueTailwindPagination from '@ocrv/vue-tailwind-pagination';

onMounted(() => {
  console.log(props)
})

const props = defineProps({
    empresas: {type: Object},
});

const form= useForm({
    k_empresa: '',
});

const deleteEmpresa = (k_empresa, mis_datos_nombre) => {
    const alerta = Swal.mixin({buttonsStyling: false,});

    alerta.fire({
        title: '¿Estás seguro que quieres eliminar ' + mis_datos_nombre + '?',
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
            form.delete(route('empresas.destroy',  k_empresa), {
                onSuccess: () => {
                    ok('Empresa eliminada');
                },
            });
        }
    });
};

</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Empresas</h2>
        </template>

        <div class="py-12">
            <div class="grid bg-white v-screen place-items-center">
                <div class="flex mt-3 mb-3">
                    <Link :href="route('empresas.create')"
                    :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'">
                    <i class="fa-solid fa-plus-circle"></i> Agregar
                    </Link>
                </div>
            </div>
            <div class="grid py-3 overflow-x-auto bg-white v-screen place-items-center">
                <table class="border border-gray-400 table-auto">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">Empresas</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="empresa, i in empresas" :key="empresa.k_empresa">
                        <td class="px-4 py-4 border border-gray-400">{{ empresa.k_empresa }}</td>
                        <td class="px-4 py-4 border border-gray-400">{{ empresa.mis_datos_nombre }}</td>
                        <td class="px-4 py-4 border border-gray-400">{{ empresa.mis_datos_municipio}}</td>
                        <td class="px-2 py-2 border border-gray-400">
                                <WarningButton 
                                    @click="openModal(2,empresa.k_empresa, empresa.mis_datos_nombre, empresa.mis_datos_municipio, empresa.mis_datos_direccion, empresa.mis_datos_telefono, empresa.mis_datos_email, empresa.mis_datos_rfc, empresa.mis_datos_curp, empresa.mis_datos_ine, empresa.mis_datos_tipo, empresa.mis_datos_status)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="px-2 py-2 border border-gray-400">
                                <DangerButton @click="deleteEmpresa(empresa.k_empresa, empresa.mis_datos_nombre)">
                                    <i class="fa-solid fa-trash"></i>
                                </DangerButton>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
