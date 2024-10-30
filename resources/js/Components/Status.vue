<script setup>
defineProps({
    type: String
})
</script>

<template>
  <span
      :class="{
    'inline-flex items-center rounded-md px-2 py-1 text-sm font-medium ring-1 ring-inset': true,
    'bg-green-50 text-green-700 ring-green-600/20': type === 'A' || type === 'R',
    'bg-red-50 text-red-700 ring-red-600/20': type === 'C',
    'bg-blue-50 text-blue-700 ring-blue-600/20': type === 'P',
    'bg-orange-50 text-orange-700 ring-orange-600/20': type === 'S' || type === 'H',
  }">
    <slot />
  </span>
</template>
