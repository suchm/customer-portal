<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import MyAccountLinks from "@/Components/MyAccountLinks.vue";
</script>

<template>
    <Head title="My Account" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                My Account
            </h2>
        </template>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
