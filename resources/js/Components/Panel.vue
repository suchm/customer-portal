<script setup>
import { Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3'; // Import usePage from Inertia

const props = defineProps({
    theme: String,
    url: String,
    padding: String,
    flex: Boolean,
    noBorder: Boolean
})

let page = usePage();
let pageName = page.props.route.name;

let paddingClass = props.padding ? props.padding : 'p-4';

</script>

<template>

  <component
    :is="url ? Link : 'div'"
    v-bind="url ? {
      href: route(url)
    } : {}"
    :class="[`rounded-lg ${paddingClass}`, {
    'border-2': !noBorder,
    'bg-white shadow-lg text-gray-800' : theme !== 'dark',
    'bg-gray-700 border-gray-600' : theme === 'dark',
    'border-yellow-500': pageName === url,
    'border-white hover:border-gray-200': pageName !== url,
  }]">
    <div :class="[flex ? 'flex' : '', 'gap-4 items-center h-full']">
      <div v-if="$slots.icon" class="flex w-14 justify-center">
        <slot name="icon" />
      </div>

      <div class="flex-1">
        <h2 v-if="$slots.heading" class="font-semibold text-xl mb-1">
          <slot name="heading"/>
        </h2>

        <slot/>
      </div>
    </div>
    </component>
</template>

<style scoped>

</style>
