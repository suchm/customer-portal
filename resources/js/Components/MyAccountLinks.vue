<script setup>
import { computed } from "vue";
import Panel from "@/Components/Panel.vue";
import { useMyAccountStore } from '@/stores/myAccountStore.js'
import { usePage } from '@inertiajs/vue3'; // Import usePage from Inertia

let accountItems = useMyAccountStore();
let page = usePage();
let pageName = page.props.route.name;

const myAccountName = 'my-account';

let classObject = computed(() => ({
    'text-xl': pageName !== myAccountName,
    'text-4xl': pageName === myAccountName,
}));

let panelPadding = pageName !== myAccountName ? 'p-2' : 'p-4';
</script>

<template>
    <Panel v-for="item in accountItems.$state" :url="item.href" :padding="panelPadding" :flex="true">
        <template #icon>
            <i :class="[
            `${item.icon} text-yellow-500`, classObject
        ]"></i>
        </template>
        <template #heading>{{ item.name }}</template>
        <p v-if="pageName === myAccountName">{{ item.description }}</p>
    </Panel>
</template>

<style scoped>

</style>
