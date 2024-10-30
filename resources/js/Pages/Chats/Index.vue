<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Panel from "@/Components/Panel.vue";
import { Head } from '@inertiajs/vue3';
import ChatUsers from "@/Components/Chats/ChatUsers.vue";
import ChatTextArea from "@/Components/Chats/ChatTextArea.vue";
import ChatThread from "@/Components/Chats/ChatThread.vue";
import {useMessageStore} from "@/stores/messageStore.js";
import {useUsersStore} from "@/stores/usersStore.js";
import {useChatsStore} from "@/stores/chatsStore.js";
import {onUnmounted} from "vue";
import ChatItems from "@/Components/Chats/ChatItems.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

let props = defineProps({
    chats: Array,
    currentChat: Object,
})

let messageStore = useMessageStore()
let usersStore = useUsersStore()
let chatsStore = useChatsStore()

chatsStore.setChats(props.chats)
chatsStore.setActiveChat(props.currentChat)

//console.log(props.chats);

let currentSlug = props.currentChat ? props.currentChat.slug : null;
let currentId = props.currentChat ? props.currentChat.id : null;

messageStore.fetchState(currentSlug);

let storeMessage = (payload) => {
    messageStore.storeMessage(currentSlug, payload)
}

let channel = Echo.join(`chat.${currentId}`)

// An alternative way to set the Socket Id
// window.axios.defaults.headers.common['X-Socket-Id'] = Echo.socketId()

channel
    .listen('MessageCreated', (e) => {
        // Add message to the chat
        messageStore.pushMessage(e)
    })

    .here(users => usersStore.setUsers(users))

    .joining(user => usersStore.addUser(user))

    .leaving(user => usersStore.removeUser(user))

    .listenForWhisper('typing', e => usersStore.setTyping(e));

onUnmounted(() => {
    // When a user leaves the page it will remove them from the channel
    Echo.leave(`chat.${currentId}`)
})

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Messages
            </h2>
        </template>

        <div v-if="currentChat" class="grid md:grid-cols-3 gap-4">

            <div class="md:col-span-1">
                <Panel :no-border="true" padding="pb-2">
                    <ChatItems />
                </Panel>
            </div>

            <div class="md:col-span-2">
                <Panel>
                    <div class="p-4 mb-4 bg-gray-100 text-center rounded-2xl">Talking about <strong>{{ currentChat.title }}</strong></div>
                    <ChatThread :chat="currentChat" />
                    <ChatTextArea
                        class="w-full"
                        @send="storeMessage({ body: $event })"
                        @typing="channel.whisper('typing', { id: $page.props.auth.user.id, typing: $event })"
                        placeholder="Enter message..."
                    />
                </Panel>
            </div>

        </div>

        <div v-else class="flex justify-center">
            <Panel class="text-center py-10 sm:px-4 md:px-8">
                <p class="mb-4">You currently have no chats. Click the button below to start a new chat.</p>
                <PrimaryButton>New Chat</PrimaryButton>
            </Panel>
        </div>

    </AuthenticatedLayout>
</template>
