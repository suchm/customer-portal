<script setup>
import {useChatsStore} from "@/stores/chatsStore.js";
import {formatDistanceFromDate} from '@/utils/formatDate.js';
import {router} from '@inertiajs/vue3';

let chatsStore = useChatsStore()
let activeChat = chatsStore.getActiveChat;
let activeChatMessage = chatsStore.getActiveChatMessage;

if (new Date(activeChat.last_read) >= new Date(activeChatMessage.created_at)) {
    // chatsStore.updateLastRead(props.currentChat);
}

let goToRoute = (chat) => {
    // Alternatively, you can pass additional options
    router.visit(`/my-account/chats/${chat.slug}`, {
        preserveScroll: true, // Preserve the scroll position
    });
}
</script>

<template>
    <h2 class="p-4 border-b border-gray-200">My Chats</h2>
    <ul class="overflow-hidden bg-white" role="list">
        <li v-for="chat in chatsStore.allChats"
            :class="{
                'bg-blue-50 border-l-green-700': chat.slug === chatsStore.getActiveChat.slug,
                'border-l-white hover:bg-gray-100 hover:border-l-gray-100 cursor-pointer': chat.slug !== chatsStore.getActiveChat.slug,
            }"
            class="gap-x-6 py-4 px-4 border-l-4 border-b border-b-gray-200 last:border-b-white border-w"
            @click="goToRoute(chat)"
        >
            <div class="flex min-w-0 gap-x-4 items-center">
                <img :src="chat.message[0].user.avatar" alt="" class="h-12 w-12 flex-none rounded-full bg-gray-50">
                <div class="min-w-0 flex-auto">
                    <div class="flex justify-between">
                        <p class="text-sm font-semibold leading-6 text-gray-800">{{ chat.message[0].user.name }}</p>
                        <p class="text-xs leading-5 text-gray-500">{{
                                formatDistanceFromDate(chat.message[0].created_at)
                            }}</p>
                    </div>
                    <h3>{{ chat.title }}</h3>
                    <p class="mt-1 truncate text-sm leading-5 text-gray-500">{{ chat.message[0].body }}</p>
                </div>
            </div>
        </li>
    </ul>
</template>
