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

const { data, meta } = defineProps({
    empresas: Array,
    meta: Object,
    data: Object,
});

onMounted(() => {
})

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
                        <tr v-for="empresa, i in empresas.data" :key="empresa.k_empresa">
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
                <br>
                <nav aria-label="Page navigation example">
                    <ul class="flex items-center -space-x-px h-10 text-base">
                        <li>
                        <a @click.prevent="$inertia.visit(empresas.prev_page_url)" class="flex items-center justify-center px-4 h-10 ms-0 leading-tight text-gray-500 bg-white border border-e-0 border-gray-300 rounded-s-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Previous</span>
                            <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                            </svg>
                        </a>
                        </li>
                        <li>
                        <a href="#" aria-current="page" class="z-10 flex items-center justify-center px-4 h-10 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white">{{ empresas.current_page }}</a>
                        </li>
                        <li>
                        <a @click.prevent="$inertia.visit(empresas.next_page_url)" class="flex items-center justify-center px-4 h-10 leading-tight text-gray-500 bg-white border border-gray-300 rounded-e-lg hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                            <span class="sr-only">Next</span>
                            <svg class="w-3 h-3 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
