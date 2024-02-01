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
import { onMounted } from 'vue';



const nameInput = ref(null);
const modal = ref(false);
const title = ref('');
const operation = ref(1);
const k_venta = ref('');
const k_empresa = ref('');
const k_sujeto = ref('');

const props = defineProps({
    ventas: {type:Object}
});

onMounted(() => {
    console.log(props.sujetos);
})

const form = useForm({
    k_empresa : '',
    k_venta:'',
    k_sujeto: '',
    venta_fecha:'', 
    venta_subtotal:'0.00',
    venta_iva:'0.000',
    venta_ieps:'0.0000',
    venta_retencion:'0.00',
    venta_total:'0.0000',
    venta_costo:'',
    venta_orden_compra:'',
    venta_comentario1:'',
    venta_comentario2:'',
    venta_fin:'',
    venta_factura:'',
    venta_UUID:'',
    venta_foliofact:'',
    venta_formapago:'',
    venta_condiciones:'',
    venta_serie:'F',
    venta_folio:'',
    venta_tipo:'ingreso',
    venta_metodo:'01 Efectivo',
    venta_cadena:'',
    ventas_cancelada:'',
    venta_moneda:'',
    venta_tcambio:'',
    venta_descuento:'',
    venta_motivo_desc:'',
    venta_local:'',
    venta_local_ret:'',
    venta_comenta_metodo:'',
    venta_lugar:'', 
    venta_pagado:'',
    venta_uso_cfdi:'',
    tipo_relacion:'', 
    uuid_relacionado:'',
});

const formPage = useForm({});

const onPageClick = (event)=>{
    formPage.get(route('sujetos.index',{page:event}));
}


const openModal = (op,empresa, venta, sujeto, venta_fecha, venta_subtotal, venta_iva, venta_ieps, venta_retencion, venta_total, venta_costo, venta_orden_compra, venta_comentario1, venta_comentario2, venta_fin, venta_factura, venta_UUID, venta_foliofact, venta_formapago, venta_condiciones, venta_serie, venta_folio, venta_tipo, venta_metodo, venta_cadena, ventas_cancelada, venta_moneda, venta_tcambio, venta_descuento, venta_motivo_desc, venta_local, venta_local_ret, venta_comenta_metodo, venta_lugar, venta_pagado, venta_uso_cfdi, tipo_relacion, uuid_relacionado  ) =>{
    console.log("Valor de la venta en openModal:", venta);
    console.log("Valor de sujetos_regimen antes de abrir el modal:", sujetos_regimen);
    modal.value = true;

    nextTick(() => nameInput.value.focus());

    operation.value = op;
    k_venta.value =  venta; 
    k_empresa.value = empresa;
    k_sujeto.value =sujeto;

    if(op == 1){
        title.value = 'Crear cliente';
    }
    else{
        title.value = 'Editar cliente';
        form.venta_fecha = venta_fecha;
        f

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
    }
    else{
        form.k_sujetos = k_sujetos.value;
        form.k_empresa = k_empresa.value;
        form.put(route('sujetos.update', k_sujetos.value), {
        k_empresa: form.k_empresa,
        sujetos_nombre: form.sujetos_nombre,
        onSuccess: () => {
            ok('Cliente actualizado');
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


const er = (msj) =>{
    form.reset();
    closeModal();
    Swal.fire({title:msj,icon:'error'});
}


const deleteSujetos = (k_sujetos, sujetos_nombre,k_empresa) => {
    const alerta = Swal.mixin({buttonsStyling: false,});

    alerta.fire({
        title: '¿Estás seguro que quieres eliminar ' + sujetos_nombre + '?',
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
            form.delete(route('sujetos.destroy',  [k_sujetos, k_empresa]), {
                onSuccess: () => {
                    ok('Empresa eliminada');
                },

            });
        }
    });
};

</script>

<template>
    <Head title="Clientes" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Clientes</h2>
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
                            <th class="px-2 py-2">Serie</th>
                            <th class="px-2 py-2">Folio</th>
                            <th class="px-2 py-2">Cliente</th>
                            <th class="px-2 py-2">Fecha</th>
                            <th class="px-2 py-2">Subtotal</th>
                            <th class="px-2 py-2">IVA</th>
                            <th class="px-2 py-2">Total</th>
                            <th class="px-2 py-2">Moneda</th>
                            <th class="px-2 py-2">Factura</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(venta, index) in ventas" :key="index">
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_serie }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_folio }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.sujetos_nombre }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_fecha}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_subtotal }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_iva}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_total }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_moneda}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_factura }} </td>
                            <td class="px-2 py-2 border border-gray-400">
                                <WarningButton 
                                    @click="openModal(2, venta.k_empresa, venta.k_venta, venta.k_sujeto, venta.venta_fecha, venta.venta_subtotal, venta.venta_iva, venta.venta_ieps, venta.venta_retencion, venta.venta_total, venta.venta_costo, venta.venta_orden_compra, venta.venta_comentario1, venta.venta_comentario2, venta.venta_fin, venta.venta_factura, venta.venta_UUID, venta.venta_foliofact, venta.venta_formapago, venta.venta_condiciones, venta.venta_serie, venta.venta_folio, venta.venta_tipo, venta.venta_metodo, venta.venta_cadena, venta.ventas_cancelada, venta.venta_moneda, venta.venta_tcambio, venta.venta_descuento, venta.venta_motivo_desc, venta.venta_local, venta.venta_local_ret, venta.venta_comenta_metodo, venta.venta_lugar, venta.venta_pagado, venta.venta_uso_cfdi, venta.tipo_relacion, venta.uuid_relacionado, venta.sujetos_nombre)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="px-2 py-2 border border-gray-400">
                                <DangerButton @click="deleteSujetos(sujeto.k_sujetos, sujeto.sujetos_nombre, sujeto.k_empresa)">
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
                <h2 class="p-3 text-lg text-gray-900 font-semibolds">{{ title }}</h2>
                <div class="w-3/4 p-3 mt-3">
                    <InputLabel for="sujetos_nombre" value="Nombre:"></InputLabel>
                    <TextInput id="sujetos_nombre" ref="nameInput"
                    v-model="form.sujetos_nombre" type="text" class="block w-full mt-1" placeholder="Nombre"></TextInput>
                    <InputError :message="form.errors.sujetos_nombre" class="mt-2"></InputError>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_alias" value="Alias:"></InputLabel>
                        <TextInput id="sujetos_alias"
                        v-model="form.sujetos_alias" type="text" class="w-full mt-1 mr-6 block-sm"
                        placeholder="Alias"></TextInput>
                        <InputError :message="form.errors.sujetos_alias" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_telefono" value="Teléfono:" class="ml-4"></InputLabel>
                        <TextInput id="sujetos_telefono"
                        v-model="form.sujetos_telefono" type="text" class="block w-full mt-1 ml-4"
                        placeholder="Teléfono"></TextInput>
                        <InputError :message="form.errors.sujetos_telefono" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_calle" value="Calle:" ></InputLabel>
                        <TextInput id="sujetos_calle"
                        v-model="form.sujetos_calle" type="text" class="block w-full mt-1 mr-6"
                        placeholder="Calle"></TextInput>
                        <InputError :message="form.errors.sujetos_calle" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_numero_ext" value="Número exterior:" class="ml-4"></InputLabel>
                        <TextInput id="sujetos_numero_ext"
                        v-model="form.sujetos_numero_ext" type="text" class="block w-full mt-1 ml-4 "
                        placeholder="Número exterior"></TextInput>
                        <InputError :message="form.errors.sujetos_numero_ext" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_colonia" value="Colonia:"></InputLabel>
                        <TextInput id="sujetos_colonia"
                        v-model="form.sujetos_colonia" type="text" class="block w-full mt-1 mr-6"
                        placeholder="Colonia"></TextInput>
                        <InputError :message="form.errors.sujetos_colonia" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_numero_int" value="Número interior:" class="ml-4"></InputLabel>
                        <TextInput id="sujetos_numero_int"
                        v-model="form.sujetos_numero_int" type="text" class="block w-full mt-1 ml-4"
                        placeholder="Número interior"></TextInput>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_ciudad" value="Ciudad:"></InputLabel>
                        <TextInput id="sujetos_ciudad"
                        v-model="form.sujetos_ciudad" type="text" class="block w-full mt-1 mr-6"
                        placeholder="Ciudad"></TextInput>
                        <InputError :message="form.errors.sujetos_ciudad" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_estado" value="Estado:"  class="ml-4"></InputLabel>
                        <TextInput id="sujetos_estado"
                        v-model="form.sujetos_estado" type="text" class="block w-full mt-1 ml-4"
                        placeholder="Estado"></TextInput>
                        <InputError :message="form.errors.sujetos_estado" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_cp" value="Código postal:"></InputLabel>
                        <TextInput id="sujetos_cp"
                        v-model="form.sujetos_cp" type="text" class="block w-full mt-1 mr-6"
                        placeholder="Código postal"></TextInput>
                        <InputError :message="form.errors.sujetos_cp" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_pais" value="País:"  class="ml-4"></InputLabel>
                        <TextInput id="sujetos_pais"
                        v-model="form.sujetos_pais" type="text" class="block w-full mt-1 ml-4"
                        placeholder="País"></TextInput>
                        <InputError :message="form.errors.sujetos_pais" class="mt-2"></InputError>
                    </div>
                </div>  
                <div class="w-3/4 p-3">
                        <InputLabel for="sujetos_email" value="Email:"></InputLabel>
                        <TextInput id="sujetos_email"
                        v-model="form.sujetos_email" type="text" class="w-full mt-1 "
                        placeholder="Email"></TextInput>
                        <InputError :message="form.errors.sujetos_email" class="mt-2"></InputError>
                </div>
                <div class="flex flex-row w-3/4">
                    <div class="p-3">
                        <InputLabel for="sujetos_rfc" value="RFC:"></InputLabel>
                        <TextInput id="sujetos_rfc"
                        v-model="form.sujetos_rfc" type="text" class="block w-full mt-1 mr-6"
                        placeholder="RFC"></TextInput>
                        <InputError :message="form.errors.sujetos_rfc" class="mt-2"></InputError>
                    </div>
                    <div class="p-3">
                        <InputLabel for="sujetos_referencia" value="Referencia:"  class="ml-4"></InputLabel>
                        <TextInput id="sujetos_referencia"
                        v-model="form.sujetos_referencia" type="text" class="block w-full mt-1 ml-4"
                        placeholder="Referencia"></TextInput>
                        <InputError :message="form.errors.sujetos_referencia" class="mt-2"></InputError>
                    </div>
                </div>
                <div class="flex flex-row w-3/4 my-4">
                    <div class="block w-1/3 mt-1 ml-4">
                        <input type="radio" id="sujetos_cliente" name="cl_o_pr" value="1" v-model="form.sujetos_cliente">
                        <label for="sujetos_cliente">Cliente</label><br>
                    </div>
                    <div class="block w-1/3 mt-1 ">
                        <input type="radio" id="sujetos_proovedor" name="cl_o_pr" default_value="0" value="1" v-model="form.sujetos_proveedor">
                        <label for="sujetos_proovedor">Proovedor</label><br>
                    </div>
                </div>
                <div class="w-3/4 p-3">
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
            <div class="flex justify-end p-3 mt-6">
                <SecondaryButton class="ml-3" :disabled="form.processing" @click="closeModal">
                    Cancelar
                </SecondaryButton>
            </div>
            
        </Modal>
    </AuthenticatedLayout>
</template>