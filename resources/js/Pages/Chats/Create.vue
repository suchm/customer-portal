<script setup>
import {ref} from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, useForm} from '@inertiajs/vue3';
import Panel from "@/Components/Panel.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";


const form = useForm({
    subject: '',
    message: '',
    recipients: []
});

// Mock recipient list
const allRecipients = [
    'Alice Johnson',
    'Bob Smith',
    'Charlie Brown',
    'David Miller',
    'Emma Davis',
    'Fiona Clark',
];

const search = ref('');
const suggestions = ref([]);
const selectedRecipients = ref([]);

// Handle search input
const handleSearch = () => {
    suggestions.value = allRecipients.filter(
        (recipient) =>
            recipient.toLowerCase().includes(search.value.toLowerCase()) &&
            !selectedRecipients.value.includes(recipient)
    );
};

// Add recipient as a tag
const addRecipient = (recipient) => {
    if (!selectedRecipients.value.includes(recipient)) {
        selectedRecipients.value.push(recipient);
        form.recipients.push(recipient); // Add to form data
        search.value = ''; // Clear search field
        suggestions.value = []; // Clear suggestions
    }
};

// Remove recipient tag
const removeRecipient = (recipient) => {
    selectedRecipients.value = selectedRecipients.value.filter(
        (r) => r !== recipient
    );
    form.recipients = form.recipients.filter((r) => r !== recipient); // Update form data
};
</script>

<template>
    <Head title="Chats"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Chats
            </h2>
        </template>

        <div class="mx-auto max-w-[600px]">
            <Panel class="sm:px-4 md:px-8">
                <h3 class="font-semibold text-xl pb-4">Add New Chat</h3>

                <!-- Recipient Input -->
                <InputLabel for="recipients" value="Recipients"/>
                <div class="relative mt-1">
                    <!-- Tag Display -->
                    <div class="flex flex-wrap gap-2">
                        <div
                            v-for="recipient in selectedRecipients"
                            :key="recipient"
                            class="flex items-center bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-sm mb-2"
                        >
                            <span>{{ recipient }}</span>
                            <button
                                class="flex items-center justify-center w-3 h-3 text-white bg-gray-500 rounded-full hover:bg-yellow-400 hover:text-gray-800 focus:outline-none ml-1"
                                type="button"
                                @click="removeRecipient(recipient)"
                            >
                                &times;
                            </button>
                        </div>
                    </div>

                    <!-- Search Input -->
                    <input
                        v-model="search"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Type a name..."
                        type="text"
                        @input="handleSearch"
                    />

                    <!-- Suggestions Dropdown -->
                    <div
                        v-if="suggestions.length > 0"
                        class="absolute z-10 mt-1 w-full bg-white border border-gray-300 rounded-md shadow-lg"
                    >
                        <ul>
                            <li
                                v-for="suggestion in suggestions"
                                :key="suggestion"
                                class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                                @click="addRecipient(suggestion)"
                            >
                                {{ suggestion }}
                            </li>
                        </ul>
                    </div>
                </div>

                <InputLabel
                    class="pt-4"
                    for="subject"
                    value="Subject"
                />

                <TextInput
                    id="subject"
                    v-model="form.subject"
                    autofocus
                    class="mt-1 block w-full"
                    placeholder="Subject"
                    type="text"
                />

                <InputLabel
                    class="pt-4"
                    for="message"
                    value="Message"
                />

                <textarea
                    id="message"
                    v-model="form.message"
                    class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-full"
                    placeholder="Enter your message"
                ></textarea>

                <div class="flex justify-end mt-4 font-semibold">
                    <SecondaryButton href="chats.create">Submit Message</SecondaryButton>
                </div>
            </Panel>
        </div>
    </AuthenticatedLayout>
</template>
