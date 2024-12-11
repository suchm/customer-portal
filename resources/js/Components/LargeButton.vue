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
        class="inline-flex items-center bg-yellow-400 hover:bg-yellow-500 rounded-md border px-4 py-2 text-md font-semibold tracking-widest text-gray-700 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25"
    >
        <slot/>
    </button>
</template>
