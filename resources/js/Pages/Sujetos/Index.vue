<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import SelectInput from '@/Components/SelectInput.vue';
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
const id = ref('');

const props = defineProps({
    sujetos: {type:Object}
});

onMounted(() => {
    console.log(props.sujetos);
})
const form = useForm({
    sujetos_nombre:'', sujetos_calle:'',sujetos_numero_ext:'',sujetos_numero_int:'',sujetos_colonia:'', sujetos_ciudad:'', sujetos_estado:'',sujetos_cp:'',sujetos_pais:'',sujetos_email:'', sujetos_referencia:'',sujetos_alias:'',sujetos_cliente:'',sujetos_proovedor:'',sujetos_telefono:'',borrado:'',sujetos_regimen:''
});
const formPage = useForm({});

const onPageClick = (event)=>{
    formPage.get(route('sujetos.index',{page:event}));
}

const openModal = (op,sujetos_nombre,sujetos_calle,sujetos_numero_ext,sujetos_numero_int,sujetos_colonia,sujetos_ciudad,sujetos_estado,sujetos_cp,sujetos_pais,sujetos_email,sujetos_rfc,sujetos_referencia,sujetos_alias,sujetos_cliente,sujetos_proovedor,sujetos_telefono,borrado,sujetos_regimen) =>{

    modal.value = true;

    nextTick( () => nameInput.value.focus());

    operation.value = op;
    id.value = sujetos;
    if(op == 1){
        title.value = 'Crear cliente';
    }
    else{
        title.value = 'Editar cliente';
        form.sujetos_nombre = sujetos_nombre;
        form.sujetos_calle = sujetos_calle;
        form.sujetos_numero_ext = sujetos_numero_ext;
        form.sujetos_numero_int = sujetos_numero_int;
        form.sujetos_colonia = sujetos_colonia;
        form.sujetos_ciudad = sujetos_ciudad;
        form.sujetos_estado = sujetos_estado;
        form.sujetos_cp = sujetos_cp;
        form.sujetos_pais = sujetos_pais;
        form.sujetos_email = sujetos_email;
        form.sujetos_rfc = sujetos_rfc;
        form.sujetos_referencia = sujetos_referencia;
        form.sujetos_alias = sujetos_alias;
        form.sujetos_cliente = sujetos_cliente;
        form.sujetos_proovedor = sujetos_proovedor;
        form.sujetos_telefono = sujetos_telefono;
        form.borrado = borrado;
        form.sujetos_regimen = sujetos_regimen;
    }
}
const closeModal = () =>{
    modal.value = false;
    form.reset();
}
const save = () =>{
    if(operation.value == 1){
        form.post(route('sujetos.store'),{
            onSuccess: () => {ok('Cliente created')}
        });
    }
    else{
        form.put(route('sujetos.update',id.value),{
            onSuccess: () => {ok('Employee updated')}
        });
    }
}
const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'});
}
const deleteSujetos = (id,sujetos_nombre) =>{
    const alerta = Swal.mixin({
        buttonsStyling:true
    });
    alerta.fire({
        title:'¿Estás seguro que quieres eliminar  '+sujetos_nombre+' ?',
        icon:'question', showCancelButton:true,
        confirmButtonText:'<i class="fa-solid fa-check"></i> Yes,delete',
        cancelButtonText:'<i class="fa-solid fa-ban"></i> Cancel'
    }).then((result) => {
        if(result.isConfirmed) {
            form.delete(route('sujetos.destroy',id),{
                onSuccess: () =>{ok('Cliente eliminado')}
            });
        }
    });
}
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
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">RFC</th>
                            <th class="px-2 py-2">Alias</th>
                            <th class="px-2 py-2">Cl</th>
                            <th class="px-2 py-2">Pr</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="sujeto, i in sujetos.data" :key="sujeto.k_sujetos">
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.k_sujetos }}</td>
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_nombre }}</td>
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_rfc }}</td>
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_alias}}</td>
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_cliente }}</td>
                        <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_proovedor }}</td>
                        <td class="border border-gray-400 px-2 py-2">
                            <WarningButton 
                            @click="openModal(2,sujeto.sujetos_nombre,sujeto.sujetos_calle,sujeto.sujetos_numero_ext,sujeto.sujetos_numero_int,sujeto.sujetos_colonia,sujeto.sujetos_ciudad,sujeto.sujetos_estado,sujeto.sujetos_cp,sujeto.sujetos_pais,sujeto.sujetos_email,sujeto.sujetos_rfc,sujeto.sujetos_referencia,sujeto.sujetos_alias,sujeto.sujetos_cliente,sujeto.sujetos_proovedor,sujeto.sujetos_telefono,sujeto.sujetos_regimen)">
                                <i class="fa-solid fa-edit"></i>
                            </WarningButton>
                        </td>
                        <td class="border border-gray-400 px-2 py-2">
                            <DangerButton @click="deleteSujetos(sujeto.k_sujetos,sujeto.sujetos_nombre)">
                                <i class="fa-solid fa-trash"></i>
                            </DangerButton>
                        </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="bg-white grid v-screen place-items-center">
                <VueTailwindPagination
                    :current="sujetos.currentPage" :total="sujetos.total"
                    :per-page="sujetos.perPage"
                    @page-changed="onPageClick($event)"
                ></VueTailwindPagination>
            </div>
        </div>
        <Modal :show="modal" @close="closeModal">
            <h2 class="p-3 text-lg font.medium text-hray-900">{{ title }}</h2>
            <div class="p-3 mt-6">
                <InputLabel for="name" value="Name:"></InputLabel>
                <TextInput id="name" ref="nameInput"
                v-model="form.name" type="text" class="mt-1 block w-3/4"
                placeholder="Name"></TextInput>
                <InputError :message="form.errors.name" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="email" value="Email:"></InputLabel>
                <TextInput id="email"
                v-model="form.email" type="text" class="mt-1 block w-3/4"
                placeholder="Email"></TextInput>
                <InputError :message="form.errors.email" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="phone" value="Phone:"></InputLabel>
                <TextInput id="phone"
                v-model="form.phone" type="text" class="mt-1 block w-3/4"
                placeholder="Phone"></TextInput>
                <InputError :message="form.errors.phone" class="mt-2"></InputError>
            </div>
            <div class="p-3">
                <InputLabel for="department_id" value="Department:"></InputLabel>
                <SelectInput id="department_id" :options="departments"
                v-model="form.department_id" type="text" class="mt-1 block w-3/4"
                ></SelectInput>
                <InputError :message="form.errors.department_id" class="mt-2"></InputError>
            </div>
            <div class="p-3 mt-6">
                <PrimaryButton :disabled="form.processing" @click="save">
                    <i class="fa-solid fa-save"></i> Save
                </PrimaryButton>
            </div>
            <div class="p-3 mt-6 flex justify-end">
                <SecondaryButton class="ml-3" :disabled="form.processing"
                @click="closeModal">
                    Cancel
                </SecondaryButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>