<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    href: String
})
</script>

<template>
    <Link
        :href="route(href)"
        class="text-black rounded bg-yellow-400 hover:bg-yellow-500 px-4 py-3"
    >
        <slot />
    </Link>
</template>

<style scoped>

</style>
