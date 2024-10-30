<script setup>
import ButtonLink from "@/Components/ButtonLink.vue";
import Status from "@/Components/Status.vue";
import { computed, ref } from 'vue'
import { formatDate } from '@/utils/formatDate.js';

let props = defineProps({
    name: String,
    products: Array,
    startDate: String,
    endDate: String,
    status: Object
})

let startDate = ref(props.startDate);
let endDate = ref(props.endDate);

let formattedDate = computed(() => {
    return {
        start: formatDate(startDate.value),
        end: formatDate(endDate.value)
    };
});

</script>

<template>
    <tr>
        <td class="whitespace-nowrap px-3 py-5 text-xl text-gray-500 font-bold min-w-24">
            {{ name }}
        </td>
        <td class="whitespace-nowrap px-3 py-5 text-gray-500">
            <ul>
                <li v-for="product in products" class="flex gap-x-3">
                    <svg class="h-6 w-5 flex-none text-yellow-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                    {{ product.name }}
                </li>
            </ul>
        </td>
        <td class="whitespace-nowrap px-3 py-5 text-gray-500">{{ formattedDate.start }}</td>
        <td class="whitespace-nowrap px-3 py-5 text-gray-500">{{ formattedDate.end }}</td>
        <td class="whitespace-nowrap px-3 py-5  text-gray-500">
            <Status :type="status.code">{{ status.name }}</Status>
        </td>
        <td class="relative whitespace-nowrap py-5 pl-3 pr-4 text-right font-medium sm:pr-0">
            <ButtonLink href="pricing">Upgrade</ButtonLink>
        </td>
    </tr>
</template>

<style scoped>

</style>
