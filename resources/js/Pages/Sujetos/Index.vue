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
const k_sujetos = ref('');

const props = defineProps({
    sujetos: {type:Object}
});

onMounted(() => {
    console.log(props.sujetos);
})
const form = useForm({
    sujetos_nombre: '',
    sujetos_calle: '',
    sujetos_numero_ext: '',
    sujetos_numero_int: '',
    sujetos_colonia: '',
    sujetos_ciudad: '',
    sujetos_estado: '',
    sujetos_cp: '',
    sujetos_pais: '',
    sujetos_email: '',
    sujetos_rfc: '',
    sujetos_referencia: '',
    sujetos_alias: '',
    sujetos_cliente: '',
    sujetos_proveedor: '',
    sujetos_telefono: '',
    sujetos_regimen: ''
});
const formPage = useForm({});

const onPageClick = (event)=>{
    formPage.get(route('sujetos.index',{page:event}));
}


const openModal = (op,sujetos_nombre,sujetos_calle,sujetos_numero_ext,sujetos_numero_int,sujetos_colonia,sujetos_ciudad,sujetos_estado,sujetos_cp,sujetos_pais,sujetos_email,sujetos_rfc,sujetos_referencia,sujetos_alias,sujetos_cliente,sujetos_proveedor,sujetos_telefono,borrado,sujetos_regimen, sujeto) =>{

    modal.value = true;

    nextTick(() => nameInput.value.focus());

    operation.value = op;
    k_sujetos.value =  sujeto; 

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
        form.sujetos_proveedor = sujetos_proveedor;
        form.sujetos_telefono = sujetos_telefono;
        form.borrado = borrado;
        form.sujetos_regimen = sujetos_regimen;
    }
}

const closeModal = () =>{
    modal.value = false;
    form.reset();
}

const ok = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'success'});
}

const save = () =>{
    if(operation.value == 1){
        form.post(route('sujetos.store'),{
            onSuccess: () => {ok('Cliente creado')},
            onError : (error) => {
                er('Cliente no actualizado '+ error.message);}
        });
    }
    else{

        form.put(route('sujetos.update', k_sujetos.value),{
            onSuccess: () => {ok('Cliente actualizado')},
            onError : (error) => {
                er('Error al actualizar '+ error.message);}
        });
    }
}


const er = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'error'});
}


const deleteSujetos = (k_sujetos,sujetos_nombre) =>{
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
            form.delete(route('sujetos.destroy',k_sujetos),{
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(sujeto, index) in sujetos" :key="index">
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.k_sujetos }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_nombre }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_rfc }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_alias}}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_cliente }}</td>
                            <td class="border border-gray-400 px-2 py-2">{{ sujeto.sujetos_proveedor }}</td>
                            <td class="border border-gray-400 px-2 py-2">
                                <WarningButton 
                                    @click="openModal(2, sujeto.sujetos_nombre, sujeto.sujetos_calle, sujeto.sujetos_numero_ext, sujeto.sujetos_numero_int, sujeto.sujetos_colonia, sujeto.sujetos_ciudad, sujeto.sujetos_estado, sujeto.sujetos_cp, sujeto.sujetos_pais, sujeto.sujetos_email, sujeto.sujetos_rfc, sujeto.sujetos_referencia, sujeto.sujetos_alias, sujeto.sujetos_cliente, sujeto.sujetos_proveedor, sujeto.sujetos_telefono, sujeto.borrado, sujeto.sujetos_regimen, sujeto.k_sujetos)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="border border-gray-400 px-2 py-2">
                                <DangerButton @click="deleteSujetos(sujeto.k_sujetos, sujeto.sujetos_nombre)">
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
            <div class="flex items-center justify-center flex-col mt-5">
                <h2 class="p-3 text-lg font-semibolds text-gray-900">{{ title }}</h2>
                <div class="p-3 mt-3 w-3/4">
                    <InputLabel for="sujetos_nombre" value="Nombre:"></InputLabel>
                    <TextInput id="sujetos_nombre" ref="nameInput"
                    v-model="form.sujetos_nombre" type="text" class="mt-1 block w-full" placeholder="Nombre"></TextInput>
                    <InputError :message="form.errors.sujetos_nombre" class="mt-2"></InputError>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_alias" value="Alias:"></InputLabel>
                        <TextInput id="sujetos_alias"
                        v-model="form.sujetos_alias" type="text" class="mt-1 mr-6 block-sm w-full"
                        placeholder="Alias"></TextInput>
                        <InputError :message="form.errors.sujetos_alias" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_telefono" value="Teléfono:"></InputLabel>
                        <TextInput id="sujetos_telefono"
                        v-model="form.sujetos_telefono" type="text" class="mt-1 ml-4 block w-full"
                        placeholder="Teléfono"></TextInput>
                        <InputError :message="form.errors.sujetos_telefono" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_calle" value="Calle:"></InputLabel>
                        <TextInput id="sujetos_calle"
                        v-model="form.sujetos_calle" type="text" class="mt-1 mr-6 block w-full"
                        placeholder="Calle"></TextInput>
                        <InputError :message="form.errors.sujetos_calle" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_numero_ext" value="Número exterior:"></InputLabel>
                        <TextInput id="sujetos_numero_ext"
                        v-model="form.sujetos_numero_ext" type="text" class="mt-1 ml-4 block w-full "
                        placeholder="Número exterior"></TextInput>
                        <InputError :message="form.errors.sujetos_numero_ext" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_colonia" value="Colonia:"></InputLabel>
                        <TextInput id="sujetos_colonia"
                        v-model="form.sujetos_colonia" type="text" class="mt-1 mr-6 block w-full"
                        placeholder="Colonia"></TextInput>
                        <InputError :message="form.errors.sujetos_colonia" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_numero_int" value="Número interior:"></InputLabel>
                        <TextInput id="sujetos_numero_int"
                        v-model="form.sujetos_numero_int" type="text" class="mt-1 ml-4 block w-full"
                        placeholder="Número interior"></TextInput>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_ciudad" value="Ciudad:"></InputLabel>
                        <TextInput id="sujetos_ciudad"
                        v-model="form.sujetos_ciudad" type="text" class="mt-1 mr-6 block w-full"
                        placeholder="Ciudad"></TextInput>
                        <InputError :message="form.errors.sujetos_ciudad" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_estado" value="Estado:"></InputLabel>
                        <TextInput id="sujetos_estado"
                        v-model="form.sujetos_estado" type="text" class="mt-1 ml-4 block w-full"
                        placeholder="Estado"></TextInput>
                        <InputError :message="form.errors.sujetos_estado" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_cp" value="Código postal:"></InputLabel>
                        <TextInput id="sujetos_cp"
                        v-model="form.sujetos_cp" type="text" class="mt-1 mr-6 block w-full"
                        placeholder="Código postal"></TextInput>
                        <InputError :message="form.errors.sujetos_cp" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_pais" value="País:"></InputLabel>
                        <TextInput id="sujetos_pais"
                        v-model="form.sujetos_pais" type="text" class="mt-1 ml-4 block w-full"
                        placeholder="País"></TextInput>
                        <InputError :message="form.errors.sujetos_pais" class="mt-2"></InputError>
                    </div>
                </div>  
                <div class="p-3 w-3/4">
                        <InputLabel for="sujetos_email" value="Email:"></InputLabel>
                        <TextInput id="sujetos_email"
                        v-model="form.sujetos_email" type="text" class="mt-1 w-full "
                        placeholder="Email"></TextInput>
                        <InputError :message="form.errors.sujetos_email" class="mt-2"></InputError>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_rfc" value="RFC:"></InputLabel>
                        <TextInput id="sujetos_rfc"
                        v-model="form.sujetos_rfc" type="text" class="mt-1 mr-6 block w-full"
                        placeholder="RFC"></TextInput>
                        <InputError :message="form.errors.sujetos_rfc" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_referencia" value="Referencia:"></InputLabel>
                        <TextInput id="sujetos_referencia"
                        v-model="form.sujetos_referencia" type="text" class="mt-1 ml-4 block w-full"
                        placeholder="Referencia"></TextInput>
                        <InputError :message="form.errors.sujetos_referencia" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row my-4 w-3/4">
                    <div class="mt-1 block w-1/3 ml-4">
                        <input type="radio" id="sujetos_cliente" name="cl_o_pr" value="1" v-model="form.sujetos_cliente">
                        <label for="sujetos_cliente">Cliente</label><br>
                    </div>
                    <div class="mt-1 block w-1/3 ">
                        <input type="radio" id="sujetos_proovedor" name="cl_o_pr" value="1" v-model="form.sujetos_proveedor">
                        <label for="sujetos_proovedor">Proovedor</label><br>
                    </div>
                </div>
                    
                <!--<div class="p-3">
                    <InputLabel for="sujetos_cliente" value="Cliente:"></InputLabel>
                    <TextInput id="sujetos_cliente"
                    v-model="form.sujetos_cliente" type="text" class="mt-1 block w-3/4"
                    placeholder="Cliente"></TextInput>
                    <InputError :message="form.errors.sujetos_cliente" class="mt-2"></InputError>
                </div>
                <div class="p-3">
                    <InputLabel for="sujetos_proovedor" value="Proovedor:"></InputLabel>
                    <TextInput id="sujetos_proovedor"
                    v-model="form.sujetos_proovedor" type="text" class="mt-1 block w-3/4"
                    placeholder="Proovedor"></TextInput>
                    <InputError :message="form.errors.sujetos_proovedor" class="mt-2"></InputError>
                </div>
                -->
                <div class="p-3 w-3/4">
                    <InputLabel for="sujetos_regimen" value="Regimen Fiscal:"></InputLabel>
                    <select id="sujetos_regimen" v-model="form.sujetos_regimen" name="sujetos_regimen" autocomplete="sujetos_regimen" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                        <option selected disabled value="">Seleccione un régimen fiscal</option>
                        <option value="601 - General de Ley Personas Morales" selected>601 - General de Ley Personas Morales</option>  
                        <option value="603 - Personas Morales con Fines no Lucrativos">603 - Personas Morales con Fines no Lucrativos</option>
                        <option value="605 - Sueldos y Salarios e Ingresos Asimilados a Salarios">605 - Sueldos y Salarios e Ingresos Asimilados a Salarios</option>
                        <option value="606 - Arrendamiento">606 - Arrendamiento</option>
                        <option value="607 - Enajenacion o Adquisicion de Bienes">607 - Enajenacion o Adquisicion de Bienes</option>
                        <option value="608 - Demas ingresos">608 - Demas ingresos</option>
                        <option value="609 - Consolidacion">609 - Consolidacion</option>
                        <option value="610 - Residentes en el Extranjero sin Establecimiento Permanente en Mexico">610 - Residentes en el Extranjero sin Establecimiento Permanente en Mexico</option>
                        <option value="Efectivo">611 - Ingresos por Dividendos (socios y accionistas)</option>611 - Ingresos por Dividendos (socios y accionistas)
                        <option value="612 - Personas Fisicas con Actividades Empresariales y Profesionales">612 - Personas Fisicas con Actividades Empresariales y Profesionales</option>
                        <option value="614 - Ingresos por intereses">614 - Ingresos por intereses</option>
                        <option value="615 - Ingresos por obtencion de premios">615 - Ingresos por obtencion de premios</option>
                        <option value="616 - Sin obligaciones fiscales">616 - Sin obligaciones fiscales</option>
                        <option value="620 - Sociedades Cooperativas de Produccion que optan por diferir sus ingresos">620 - Sociedades Cooperativas de Produccion que optan por diferir sus ingresos</option>
                        <option value="621 - Incorporacion Fiscal">621 - Incorporacion Fiscal</option>
                        <option value="622 - Actividades Agricolas, Ganaderas, Silvicolas y Pesqueras">622 - Actividades Agricolas, Ganaderas, Silvicolas y Pesqueras</option>
                        <option value="623 - Opcional para Grupos de Sociedades">623 - Opcional para Grupos de Sociedades</option>
                        <option value="624 - Coordinados">624 - Coordinados</option>
                        <option value="624 - Coordinados">625 - Actividades Empresariales con ingresos a traves de Plataformas Tecnologicas</option>
                        <option value="626 - Simplificado de Confianza">626 - Simplificado de Confianza</option>
                        <option value="628 - Hidrocarburos">628 - Hidrocarburos</option>
                        <option value="629 - Fiscales Preferentes y de las Empresas Multinacionales">629 - Fiscales Preferentes y de las Empresas Multinacionales</option>
                        <option value="630 - Enajenacion de acciones en bolsa de valores">630 - Enajenacion de acciones en bolsa de valores</option>
                    </select>
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