<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    href: String,
    rounded: Boolean,
})
</script>

<template>
    <Link
        :class="{
            'rounded px-4 py-3': rounded === false,
            'rounded-3xl px-4 py-3': rounded === true,
        }"
        :href="route(href)"
        class="text-black bg-yellow-400 hover:bg-yellow-500"
    >
        <slot/>
    </Link>
</template>

<style scoped>

</style>
