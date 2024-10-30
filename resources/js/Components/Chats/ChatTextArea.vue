<script setup>
import { ref } from 'vue'
let emit = defineEmits();

let model = ref('');
let shift = ref(false);
let focus = ref(false);

let handleEnter = () => {

    // Hit shift and enter to move the cursor to a new line
    if (shift.value) {
        model.value = model.value + '\n'
        return
    }

    // If shift is not pressed send message
    if ( model.value.length ) {
        emit('send', model.value);

        model.value = '';

        handleFinishedTyping()
    }

}

let typingTimeout = null

let handleTyping = () => {

    // clear timeout each time so it only sends the finished typing event once at the end
    clearTimeout(typingTimeout);

    emit('typing', true)

    typingTimeout = setTimeout(handleFinishedTyping, 3000)
}

let handleFinishedTyping = () => {

    clearTimeout(typingTimeout);

    emit('typing', false)
}
</script>


<template>
    <div
        class="flex items-center rounded-md w-full border-2 shadow-sm border-gray-400 ring-gray-400"
        :class="{ 'border-indigo-500 ring-indigo-500': focus }"
    >

        <textarea
            v-model="model"
            class="border-none flex-1 bg-transparent focus:outline-none focus:ring-0 focus:border-0 p-2 resize-none"
            @focus="focus = true"
            @blur="focus = false"
            @keydown.shift="shift = true"
            @keyup="shift = false"
            @keydown="handleTyping"
            @keydown.enter.prevent="handleEnter"
        ></textarea>

        <svg  class="svg-arrow w-6 h-6 text-blue-500 ml-2" height="48" viewBox="0 0 48 48" width="48" xmlns="http://www.w3.org/2000/svg">
            <path d="M4.02 42l41.98-18-41.98-18-.02 14 30 4-30 4z"/><path d="M0 0h48v48h-48z" fill="none"/>
        </svg>
    </div>
</template>
