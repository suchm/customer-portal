<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
import SubscriptionItem from "@/Components/Subscriptions/SubscriptionItem.vue";
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Orders
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead>
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0" scope="col">
                                    Type
                                </th>
                                <th class="px-3 py-3.5 text-left font-semibold text-gray-900 min-w-64" scope="col">
                                    Product
                                </th>
                                <th class="px-3 py-3.5 text-left font-semibold text-gray-900" scope="col">Price
                                </th>
                                <th class="px-3 py-3.5 text-left font-semibold text-gray-900" scope="col">Order Date
                                </th>
                                <th class="px-3 py-3.5 text-left font-semibold text-gray-900" scope="col">Status</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-0" scope="col">
                                    <span class="sr-only">Help</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                            <SubscriptionItem v-for="sub in subscriptions"
                                              :end-date="sub.end_date"
                                              :name="sub.name"
                                              :products="sub.products"
                                              :start-date="sub.start_date"
                                              :status="sub.status"
                            />
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
