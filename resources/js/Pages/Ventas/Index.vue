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



const modal = ref(false);
const title = ref('');
const operation = ref(1);
const k_venta = ref('');
const k_empresa = ref('');

const props = defineProps({
    ventas: {type:Object},
    sujetos: {type:Object},
    series: {type:Object},
});


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
    venta_fin:'0',
    venta_factura:'',
    venta_UUID:'',
    venta_foliofact:'',
    venta_formapago:'PUE - Pago en una sola exhibición',
    venta_condiciones:'',
    venta_serie:'F',
    venta_folio:'',
    venta_tipo:'ingreso',
    venta_metodo:'',
    venta_cadena:'',
    ventas_cancelada:'',
    venta_moneda:'MXN',
    venta_tcambio:'1.0000',
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


const openModal = (op, venta) =>{
    modal.value = true;
    operation.value = op;
    

    if(op == 1){
        title.value = 'Crear venta';
    }
    else{
        title.value = 'Editar venta';

        Object.keys(venta).forEach((key) => {
                form[key] = venta[key] !== null ? venta[key] : form[key];   
            });
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
        console.log(form.venta_metodo);
        form.post(route('ventas.store'),{
            onSuccess: () => {ok('Venta creado')},
            onError: (error) => {

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

        k_venta.value = form.k_venta ;
        k_empresa.value = form.k_empresa;
        console.log(form); 
        form.put(route('ventas.update', k_venta.value), {
        k_empresa: form.k_empresa,
        sujetos_nombre: form.sujetos_nombre,
        k_venta: form.k_venta,
        onSuccess: () => {
            ok('Venta actualizada');
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

const deleteVentas = (sujetos_nombre,k_venta, k_empresa) => {
    const alerta = Swal.mixin({buttonsStyling: false,});

    alerta.fire({
        title: '¿Estás seguro que quieres eliminar la venta de ' + sujetos_nombre + '?',
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
            form.delete(route('sujetos.destroy',  [k_venta, k_empresa]), {
                onSuccess: () => {
                    ok('Venta eliminada');
                },

            });
        }
    });
};

</script>

<template>
    <Head title="Ventas" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Ventas</h2>
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
                <table class="border border-gray-400 table-auto w-2/3">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2 w-1/12">Serie</th>
                            <th class="px-2 py-2 w-1/12">Folio</th>
                            <th class="px-2 py-2 w-4/12">Cliente</th>
                            <th class="px-2 py-2 w-2/12">Fecha</th>
                            <th class="px-2 py-2 w-1/12">Subtotal</th>
                            <th class="px-2 py-2 w-2/12">IVA</th>
                            <th class="px-2 py-2 w-2/12">Total</th>
                            <th class="px-2 py-2 w-2/12">Moneda</th>
                            <th class="px-2 py-2 w-2/12">Factura</th>
                            <th class="px-2 py-2"></th>
                            <th class="px-2 py-2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(venta, index) in ventas" :key="index">
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_serie }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_folio }}</td>
                            <td class="px-2 py-2 border border-gray-400" >{{ venta.sujetos_nombre }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_fecha}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_subtotal }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_iva}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_total }}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_moneda}}</td>
                            <td class="px-2 py-2 border border-gray-400">{{ venta.venta_factura }} </td>
                            <td class="px-2 py-2 border border-gray-400">
                                <WarningButton
                                    @click="openModal(2, venta)"
                                >
                                <i class="fa-solid fa-edit"></i>
                                </WarningButton>
                            </td>
                            <td class="px-2 py-2 border border-gray-400">
                                <DangerButton @click="deleteVentas(venta.sujetos_nombre, venta.k_venta, venta.k_empresa)">
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

                <div class="flex flex-row w-3/4 justify-start">
                    <div class="p-3 w-1/4 ">
                        <InputLabel  value="Serie:"></InputLabel>
                        <select name="venta_serie" id="venta_serie" v-model="form.venta_serie"  class="block w-full mt-1 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                            <option value="" disabled selected>Serie</option>
                            <option v-for="serie in series" :key="serie.id" :value="serie.id">{{serie.serie }}</option>
                        </select>
                    </div>
                    <div class="p-3 ml-20   ">
                        <InputLabel  value="Tipo:"></InputLabel>
                        <select name="venta_tipo" id="venta_tipo" v-model="form.venta_tipo"  class="block w-full mt-1 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                            <option value="" selected disabled>Tipo</option>
                            <option value="ingreso">Ingreso</option>
                            <option value="traslado">Traslado</option>
                        </select>
                    </div>
                </div>

                <div class="w-3/4 p-3">
                    <InputLabel for="sujetos_nombre" value="Cliente:"></InputLabel>
                    <select  v-model="form.k_sujeto" name="sujetos_nombre" id="sujetos_nombre"  class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                    <option value="" disabled selected>Selecciona un cliente</option>
                    <option  v-for="sujeto in sujetos" :key="sujeto.id" :value="sujeto.id">{{ sujeto.sujetos_nombre }}</option>
                    </select>
                </div>

                <div class="w-3/4 p-3">
                    <InputLabel  value="Forma de pago:"></InputLabel>
                    <select v-model="form.venta_formapago" id="venta_formapago" name="venta_formapago" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                        <option value=""  disabled selected>Selecciona una forma de pago</option>
                        <option value="PUE - Pago en una sola exhibición">PUE - Pago en una sola exhibición</option>
                        <option value="PPD - Pago en parcialidades o diferido">PPD - Pago en parcialidades o diferido</option>
                    </select>
                </div>
                
                <div class="w-3/4 p-3">
                    <InputLabel for="venta_condiciones" value="Condiciones de pago:"></InputLabel>
                    <textarea v-model="form.venta_condiciones" id="venta_condiciones" rows="4" class="block h-12 p-2.5 w-full text-sm rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Condiciones"></textarea>
                    
                </div>
                
                
                <div class="flex flex-row w-3/4 justify-start items-center">
                    <div class="p-3">
                        <InputLabel for="venta_moneda" value="Moneda:"></InputLabel>
                        <select id="venta_moneda" v-model="form.venta_moneda" name="venta_moneda" autocomplete="venta_moneda" class="w-52 mt-1 mr-6 block-sm rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                            <option disabled selected value="">Moneda</option>
                            <option selected value="MXN">MXN</option>
                        </select>
                        
                    </div>
                    <div class="p-3">
                        <InputLabel for="venta_tcambio" value="Tipo de cambio:"></InputLabel>
                            <input type="number" id="venta_tcambio" v-model="form.venta_tcambio" name="venta_tcambio" autocomplete="venta_tcambio" class="w-48 mt-1 ml-1 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600" step="1.0000" min="0" />
                        
                    </div>
                </div>

                <div class="w-3/4 p-3">
                    <InputLabel for="venta_metodo" value="Metodo de pago:"></InputLabel>
                    <select id="venta_metodo" v-model="form.venta_metodo" name="venta_metodo"  class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                            <option value="" disabled selected>Selecciona un metodo de pago</option>
                            <option value="01 Efectivo" >01 Efectivo</option>
                            <option value="02 Cheque nominativo">02 Cheque nominativo</option>
                            <option value="03 Transferencia  electrónica de fondos">03 Transferencia  electrónica de fondos</option>
                            <option value="04 Tarjeta de crédito">04 Tarjeta de Crédito</option>
                            <option value="05 Monedero Electrónico">05 Monedero Electrónico</option>
                            <option value="06 Dinero Electrónico">06 Dinero Electrónico</option>
                            <option value="07 Tarjetas digitales">07 Tarjetas digitales</option>
                            <option value="08 Vales de despensa">08 Vales de despensa</option>
                            <option value="12 Dación en pago">12 Dación en pago</option>
                            <option value="13 Pago por subrogación">13 Pago por subrogación</option>
                            <option value="14 Pago por consignación">14 Pago por consignación</option>
                            <option value="15 Condonación">15 Condonación</option>
                            <option value="17 Compensación">17 Compensación</option>
                            <option value="23 Novación">23 Novación</option>
                            <option value="24 Confusión">24 Confusión</option>
                            <option value="25 Remisión de Deuda">25 Remisión de Deuda</option>
                            <option value="26 Prescripción o caducidad">26 Prescripción o caducidad</option>
                            <option value="27 A satisfacción del acreedor">27 A satisfacción del acreedor</option>
                            <option value="28 Tarjeta de Debito">28 Tarjeta de Debito</option>
                            <option value="29 Tarjeta de Servicio">29 Tarjeta de Servicio</option>
                            <option value="30 Aplicación de anticipos">30 Aplicación de anticipos</option>
                            <option value="31 Intermediario pagos">31 Intermediario pagos</option>
                            <option value="99 Por definir">99 Por definir</option>
                    </select>
                    
                </div>
                
                <div class="w-3/4 p-3">
                    <InputLabel for="venta_comenta_metodo" value="Comentario :"></InputLabel>
                    <textarea id="venta_comenta_metodo" v-model="form.venta_comenta_metodo" rows="4" class="block p-2.5 w-full h-12 text-sm rounded-md border-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Comentarios metodo de pago"></textarea>
                    
                </div>
                
                <div class="w-3/4 p-3">
                    <InputLabel for="venta_uso_cfdi" value="Uso del CFDI:"></InputLabel>
                        <select id="venta_uso_cfdi" v-model="form.venta_uso_cfdi" name="venta_uso_cfdi" autocomplete="venta_uso_cfdi" class="mt-1 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 ">
                            <option value="" disabled selected>Selecciona el uso de cdfi</option>
                            <option value="G01 - Adquisición de mercancias">G01 - Adquisición de mercancias</option>
                            <option value="G02 - Devoluciones, descuentos o bonificaciones">G02 - Devoluciones, descuentos o bonificaciones</option>
                            <option selected="" value="G03 - Gastos en general">G03 - Gastos en general</option>
                            <option value="I01 - Construcciones">I01 - Construcciones</option>
                            <option value="I02 - Mobilario y equipo de oficina por inversiones">I02 - Mobilario y equipo de oficina por inversiones</option>
                            <option value="I03 - Equipo de transporte">I03 - Equipo de transporte</option>
                            <option value="I04 - Equipo de computo y accesorios">I04 - Equipo de computo y accesorios</option>
                            <option value="I05 - Dados, troqueles, moldes, matrices y herramental">I05 - Dados, troqueles, moldes, matrices y herramental</option>
                            <option value="I06 - Comunicaciones telefónicas">I06 - Comunicaciones telefónicas</option>
                            <option value="I07 - Comunicaciones satelitales">I07 - Comunicaciones satelitales</option>
                            <option value="I08 - Otra maquinaria y equipo">I08 - Otra maquinaria y equipo</option>
                            <option value="D01 - Honorarios médicos, dentales y gastos hospitalarios">D01 - Honorarios médicos, dentales y gastos hospitalarios</option>
                            <option value="D02 - Gastos médicos por incapacidad o discapacidad">D02 - Gastos médicos por incapacidad o discapacidad</option>
                            <option value="D03 - Gastos funerales">D03 - Gastos funerales</option>
                            <option value="D04 - Donativos">D04 - Donativos</option>
                            <option value="D05 - Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)">D05 - Intereses reales efectivamente pagados por créditos hipotecarios (casa habitación)</option>
                            <option value="D06 - Aportaciones voluntarias al SAR">D06 - Aportaciones voluntarias al SAR</option>
                            <option value="D07 - Primas por seguros de gastos médicos">D07 - Primas por seguros de gastos médicos</option>
                            <option value="D08 - Gastos de transportación escolar obligatoria">D08 - Gastos de transportación escolar obligatoria</option>
                            <option value="D09 - Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones">D09 - Depósitos en cuentas para el ahorro, primas que tengan como base planes de pensiones</option>
                            <option value="D10 - Pagos por servicios educativos (colegiaturas)">D10 - Pagos por servicios educativos (colegiaturas)</option>
                            <option value="S01 - Sin efectos fiscales">S01 - Sin efectos fiscales</option>
                            <option value="CP01 - Pagos">CP01 - Pagos</option>
                            <option value="CN01 - Nomina">CN01 - Nomina</option>
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
