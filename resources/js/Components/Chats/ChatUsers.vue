<script setup>
import { useUsersStore } from "@/stores/usersStore.js";

let usersStore = useUsersStore()
</script>

<template>
    <div class="px-4">
        <h2 class="text-lg font-semibold">Users Online ({{ usersStore.count }})</h2>

        <ul class="space-y-1 mt-3">
            <li
                class="bg-gray-100 rounded-lg px-3 py-2 flex items-center justify-between"
                v-for="user in usersStore.allUsers"
                :key="user.id"
            >
                <span>{{ user.name }}</span>

<!--                <span v-show="user.typing" class="bg-gray-400 w-8 h-4 flex justify-center items-center rounded">-->
                    <svg
                        class="w-6 h-6 size-4 animate-bounce opacity-0 transition-opacity text-gray-500"
                        :class="{ 'opacity-100': user.typing }"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
<!--                </span>-->
            </li>
        </ul>
    </div>
</template>

