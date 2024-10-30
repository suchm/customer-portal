<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SubscriptionList from "@/Components/Subscriptions/SubscriptionList.vue";
import Panel from "@/Components/Panel.vue";
import MyAccountLinks from "@/Components/MyAccountLinks.vue";
import { Head } from '@inertiajs/vue3';

let props = defineProps({
    subscriptions: {
        type: Object,
        required: true
    }
})

console.log(props.subscriptions);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Subscriptions
            </h2>
        </template>

        <Panel>
            <div class="pt-2 px-4 sm:px-2 lg:px-4">
                <div class="flow-root">
                    <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                            <SubscriptionList :subscriptions="subscriptions" />
                        </div>
                    </div>
                </div>
            </div>
        </Panel>

    </AuthenticatedLayout>
</template>
