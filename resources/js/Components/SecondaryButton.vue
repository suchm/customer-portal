<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
});
</script>

<template>
    <button
        :type="type"
        class="rounded px-4 py-3 text-black bg-yellow-400 hover:bg-yellow-500 transition duration-150 ease-in-out"
    >
        <slot/>
    </button>
</template>
