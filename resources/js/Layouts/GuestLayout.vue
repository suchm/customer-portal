<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import {Link} from '@inertiajs/vue3';

defineProps({
    background: {
        type: Boolean,
        default: true,
    },
});
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500"/>
            </Link>
        </div>

        <div
            :class="{ 'bg-white shadow-md sm:max-w-md mt-6': background }"
            class="w-full overflow-hidden px-6 py-4 sm:max-w-md"
        >
            <slot/>
        </div>
    </div>
</template>
