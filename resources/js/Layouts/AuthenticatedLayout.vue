<script setup>
import { ref, onMounted } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const showingNavigationDropdown = ref(false);

const empresas = ref([]);
const selectedEmpresa = ref(null);
const loading = ref(false);

onMounted(async () => {
    try {
        const response = await axios.get('/empresas-by-user');
        console.log('response.data:', response.data);
        empresas.value = response.data;
        console.log('empresas:', empresas);
        selectedEmpresa.value = localStorage.getItem('k_empresa');
        console.log('selectedEmpresa:', selectedEmpresa.value);
    } catch (error) {
    }
});

function clearLocalStorage() {
    localStorage.removeItem('k_empresa');
}

const OnChangeEmpresa = async (event) => {
    loading.value = true;
    selectedEmpresa.value = event.target.value;
    console.log('selectedEmpresa:', selectedEmpresa.value);
    const response = await axios.post('/setSessionEmpresa', {
        k_empresa: selectedEmpresa.value
    }).finally(() => {
        loading.value = false;
        localStorage.setItem('k_empresa', selectedEmpresa.value);
        window.location.reload();
    });
};

</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="bg-white border-b border-gray-100">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <div class="block h-9 w-auto fill-current text-gray-800">
                                        <img class="h-full w-auto" src="../../../public/images/logo_mff.jpg" alt="">
                                    </div>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                            </div>
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex" v-if="$page.props.auth.roles[0] === 'admin'">
                                <NavLink :href="route('empresas.index')" :active="route().current('empresas.index')">
                                    Empresas
                                </NavLink>
                            </div>
                           
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('sujetos.index')" :active="route().current('sujetos.index')">
                                    Clientes/Proveedores
                                </NavLink>
                            </div>

                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <NavLink :href="route('articulos.index')" :active="route().current('articulos.index')">
                                    Productos
                                </NavLink>
                            </div>
                        </div>
                        <div class="sm:flex sm:items-center sm:ms-6">
                            <form @submit.prevent="handleSubmit">
                                <div class="ms-3 relative" style="width: 300px;">
                                    <select name="empresa" id="empresa" class="border-0" style="width: 100%;" @change="OnChangeEmpresa">
                                        <option v-if="!selectedEmpresa" value="">Selecciona una empresa</option>
                                        <option hidden v-if="selectedEmpresa" :value="selectedEmpresa" selected>
                                            {{ empresas.find(empresa => empresa.k_empresa == selectedEmpresa).mis_datos_nombre }}
                                        </option>
                                        <option v-for="empresa in empresas" :key="empresa.k_empresa" :value="empresa.k_empresa">
                                            {{ empresa.mis_datos_nombre }}
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <!-- Settings Dropdown -->
                            <div class="ms-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ms-2 -me-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button" @click="clearLocalStorage">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('empresas.index')" :active="route().current('empresas.index')">
                            Empresas
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('sujetos.index')" :active="route().current('sujetos.index')">
                            Clientes/Proveedores
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('articulos.index')" :active="route().current('articulos.index')">
                            Articulos
                        </ResponsiveNavLink>
                    </div>
                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
    <div>
        <!-- Contenido de tu componente -->
        <div v-if="loading" class="overlay">
            <div class="spinner"></div>
        </div>
    </div>
</template>

<style>
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.spinner {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
