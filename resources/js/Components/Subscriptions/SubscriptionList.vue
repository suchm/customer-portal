<script setup>
import SubscriptionItem from "@/Components/Subscriptions/SubscriptionItem.vue";

let props = defineProps({
    subscriptions: Array
});

console.log(props.subscriptions);
</script>

<template>
    <table class="min-w-full divide-y divide-gray-300">
        <thead>
        <tr>
            <th scope="col" class="py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 sm:pl-0">Type</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900 min-w-64">Description</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Start Date</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">End Date</th>
            <th scope="col" class="px-3 py-3.5 text-left font-semibold text-gray-900">Status</th>
            <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                <span class="sr-only">Help</span>
            </th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
        <SubscriptionItem v-for="sub in subscriptions"
                           :name="sub.name"
                           :products="sub.products"
                           :start-date="sub.start_date"
                           :end-date="sub.end_date"
                           :status="sub.status"
        />
        </tbody>
    </table>
</template>
