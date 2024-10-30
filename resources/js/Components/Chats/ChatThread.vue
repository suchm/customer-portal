<script setup>
import {useMessageStore} from "@/stores/messageStore.js";
import {ref} from "vue";
import {useIntersectionObserver} from '@vueuse/core'
import {formatDistanceFromDate, formatDate} from '@/utils/formatDate.js';

let props = defineProps({
    chat: Object
})

let messageStore = useMessageStore()
let messageContainer = ref(null); // Define the ref for the message container
let target = ref(null)

messageStore.container = messageContainer;

// Determine when at end of messages and load more
let { stop } = useIntersectionObserver(
    target,
    ([{ isIntersecting }], observerElement) => {
        if ( !isIntersecting ) {
            return
        }
        messageStore.fetchPreviousState(props.chat.slug)
    }
)

// Replace \n with <br> tags
let sanitizeInput = (input) => {
    return input.replace(/<\/?[^>]+(>|$)/g, "").replace(/\n/g, '<br>');
}

let notSameDayMessage = (message, prevMessage) => {
    if (!prevMessage) return true; // If there's no previous message, show the date
    return formatDate(message.created_at) !== formatDate(prevMessage.created_at);
};

</script>

<template>
    <div ref="messageContainer" class="h-96 overflow-y-auto mb-6">
        <div v-for="(message, index) in messageStore.allMessages">
            <time
                v-if="notSameDayMessage(message, messageStore.allMessages[index - 1])"
                class="flex items-center text-sm uppercase text-gray-500 my-4"
            >
                <span class="flex-grow border-t border-gray-200"></span>
                <span class="px-4 text-center">{{ formatDate(message.created_at, 'MMM d') }}</span>
                <span class="flex-grow border-t border-gray-200"></span>
            </time>
            <div
                :key="message.id"
                class="flex items-start gap-2.5 pr-4 mb-8">
                <img
                    class="w-16 h-16 rounded-full"
                     :src="message.user.avatar" :alt="message.user.name">
                <div
                    class="flex flex-col w-full leading-1.5 p-4 rounded-e-xl rounded-es-xl dark:bg-gray-700"
                    :class="{
                        'bg-gray-100 text-gray-600': message.user.id !== $page.props.auth.user.id,
                        'bg-blue-400 border-blue-400 text-white': message.user.id === $page.props.auth.user.id
                    }"
                >
                    <div class="flex items-center space-x-2 rtl:space-x-reverse">
                        <span class="text-sm font-semibold dark:text-white">{{ message.user.name }}</span>
                        <time class="text-sm font-normal dark:text-gray-400"> - {{ formatDistanceFromDate(message.created_at) }}</time>
                    </div>
                    <p v-html="sanitizeInput(message.body)" class="font-normal py-2.5 whitespace-pre-line"></p>
                </div>
            </div>
        </div>
        <div ref="target" class="-translate-y-20"></div>
    </div>
</template>
