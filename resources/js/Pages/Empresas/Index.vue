<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import Swal from 'sweetalert2';
import { Head, useForm, Link } from '@inertiajs/vue3';
import {onMounted} from 'vue'

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
        confirmButtonText: '<i class="fa-solid fa-check ml-2"></i> Sí, eliminar',
        cancelButtonText: '<i class="fa-solid fa-ban mr-2"></i> Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger mx-2', // Clase de estilo para el botón de confirmar
            cancelButton: 'btn btn-secondary',    // Clase de estilo para el botón de cancelar
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('empresas.destroy', { empresa: k_empresa}));
        }
    });
};

</script>

<template>
    <Head title="Empresas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Empresas</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('empresas.create')"
                    :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'">
                    <i class="fa-solid fa-plus-circle"></i> Agregar
                    </Link>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4">Empresas</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="empresa, i in empresas" :key="empresa.k_empresa">
                        <td class="border border-gray-400 px-4 py-4">{{ empresa.k_empresa }}</td>
                        <td class="border border-gray-400 px-4 py-4">{{ empresa.mis_datos_nombre }}</td>
                        <td class="border border-gray-400 px-4 py-4">{{ empresa.mis_datos_municipio}}</td>
                        <td class="border border-gray-400 px-4 py-4">
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
