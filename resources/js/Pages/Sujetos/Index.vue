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
    sujetos: {type: Object},
    empresas: {type: Object},
});

const form = useForm({
    k_sujetos: '',
    currentEmpresa: 1,  
});

const deleteSujeto = (k_sujetos, sujetos_nombre) => {
    const alerta = Swal.mixin({ buttonsStyling: false });

    alerta.fire({
        title: '¿Estás seguro que quieres eliminar ' + sujetos_nombre + '?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check ml-2"></i> Sí, eliminar',
        cancelButtonText: '<i class="fa-solid fa-ban mr-2"></i> Cancelar',
        customClass: {
            confirmButton: 'btn btn-danger mx-2',
            cancelButton: 'btn btn-secondary',
        },
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('sujetos.destroy', { sujetos: k_sujetos, k_empresa: form.data.currentEmpresa }));
        }
    });
};

</script>

<template>
    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clientes</h2>
        </template>

        <div class="py-12">
            <div class="bg-white grid v-screen place-items-center">
                <div class="mt-3 mb-3 flex">
                    <Link :href="route('sujetos.create')"
                    :class="'px-4 py-2 bg-gray-800 text-white border rounded-md font-semibold text-xs'">
                    <i class="fa-solid fa-plus-circle"></i> Agregar Cliente
                    </Link>
                </div>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <table class="table-auto border border-gray-400">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-4">#</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4">Clientes</th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                            <th class="px-4 py-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="sujeto, i in sujetos" :key="sujeto.k_sujetos" v-if="sujeto.k_empresa === form.data.currentEmpresa">
                        <td class="border border-gray-400 px-4 py-4">{{ sujetos.k_empresa }}</td>
                        <td class="border border-gray-400 px-4 py-4">{{ sujetos.sujetos_rfc}}</td>
                        <td class="border border-gray-400 px-4 py-4">{{ sujetos.sujetos_alias}}</td>
                        <td class="border border-gray-400 px-4 py-4">{{ sujetos.sujetos_nombre}}</td>
                        <td class="border border-gray-400 px-4 py-4">
                            <DangerButton @click="deleteSujeto(sujeto.k_sujetos, sujeto.sujetos_nombre)">
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
