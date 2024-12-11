<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import {Link, useForm, usePage} from '@inertiajs/vue3';
import axios from "axios";
import {ref} from "vue";
import LargeButton from "@/Components/LargeButton.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const addressSuggestions = ref([]);
const isLoading = ref(false);
const postcodeError = ref("");
const selectedPlaceholder = ref("Select an address");
const addressId = ref("");

const form = useForm({
    name: user.name || '',
    last_name: user.last_name || '',
    email: user.email,
    telephone: user.telephone || '',
    address_1: user.address_1 || '',
    address_2: user.address_2 || '',
    county: user.county || '',
    city: user.city || '',
    country: user.country || '',
    postcode: user.postcode || '',
});

const findAddress = () => {
    const term = removeWhitespace(form.postcode);

    postcodeError.value = "";
    if (!form.postcode.trim()) {
        postcodeError.value = "A postcode is required.";
        return;
    }

    isLoading.value = true;
    axios.get(`/my-account/address/autocomplete`, {
        params: {term: term}
    })
        .then(response => {
            const result = response.data;
            addressSuggestions.value = result ? result.suggestions : [];
            addressId.value = "";
            selectedPlaceholder.value = "Select an address";
        })
        .catch(error => {
            console.log(error);
        })
        .finally(() => {
            isLoading.value = false;
        })
}

const getAddressDetails = () => {
    if (addressId.value.length > 0) {
        axios.get(`/my-account/address/${addressId.value}`)
            .then(response => {
                const result = response.data;
                if (result) {
                    form.address_1 = result.line_1;
                    form.address_2 = result.line_2;
                    form.county = result.county;
                    form.city = result.town_or_city;
                    form.country = result.country;
                }
            })
            .catch(error => {
                console.log(error);
            })
            .finally(() => {
                isLoading.value = false;
            })
    }
}

const removeWhitespace = (input) => {
    return input.replace(/\s+/g, "");
}
</script>

<template>
    <section>

        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Information
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Update your account's profile information and email address.
            </p>
        </header>

        <form
            class="mt-6 space-y-6"
            @submit.prevent="form.patch(route('profile.update'), {
                preserveScroll: true,
            })"
        >
            <div>
                <InputLabel for="name" value="First name"/>

                <TextInput
                    id="name"
                    v-model="form.name"
                    autocomplete="first-name"
                    autofocus
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.name" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="last_name" value="Last name"/>

                <TextInput
                    id="last_name"
                    v-model="form.last_name"
                    autocomplete="family-name"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.last_name" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="telephone" value="Telephone"/>

                <TextInput
                    id="telephone"
                    v-model="form.telephone"
                    autocomplete="tel"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.telephone" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="email" value="Email"/>

                <TextInput
                    id="email"
                    v-model="form.email"
                    autocomplete="email"
                    class="mt-1 block w-full"
                    required
                    type="email"
                />

                <InputError :message="form.errors.email" class="mt-2"/>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        method="post"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <hr>

            <h2 class="text-lg font-medium text-gray-900 pb-8"> Address Information </h2>

            <div class="flex items-start space-x-4">
                <!-- Postcode Input with Label -->
                <div class="relative w-32">
                    <InputLabel
                        class="absolute -top-6 left-0 text-gray-700 text-sm"
                        for="postcode"
                        value="Postcode"
                    />
                    <TextInput
                        id="postcode"
                        v-model="form.postcode"
                        autocomplete="postal-code"
                        class="block w-full"
                        required
                        type="text"
                    />
                </div>

                <!-- Address Lookup Button -->
                <div>
                    <LargeButton
                        :disabled="isLoading"
                        type="button"
                        @click="findAddress"
                    >
                        Address Lookup
                    </LargeButton>
                </div>
            </div>


            <InputError :message="form.errors.postcode" class="mt-2"/>
            <InputError :message="postcodeError" class="mt-2"/>

            <div v-if="addressSuggestions.length > 0">
                <select
                    id="address-suggestions"
                    v-model="addressId"
                    class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                    @change="getAddressDetails">
                    <option disabled value="">{{ selectedPlaceholder }}</option>
                    <option
                        v-for="(suggestion, key) in addressSuggestions"
                        :key="key"
                        :value="suggestion.id"
                    >
                        {{ suggestion.address }}
                    </option>
                </select>
            </div>


            <div>
                <InputLabel for="address_1" value="Address Line 1"/>

                <TextInput
                    id="address_1"
                    v-model="form.address_1"
                    autocomplete="address-line1"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.address_1" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="address_2" value="Address Line 2"/>

                <TextInput
                    id="address_2"
                    v-model="form.address_2"
                    autocomplete="address-line2"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.address_2" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="county" value="County"/>

                <TextInput
                    id="county"
                    v-model="form.county"
                    autocomplete="administrative-area"
                    class="mt-1 block w-full"
                    type="text"
                />

                <InputError :message="form.errors.county" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="city" value="City"/>

                <TextInput
                    id="city"
                    v-model="form.city"
                    autocomplete="address-level2"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.city" class="mt-2"/>
            </div>

            <div>
                <InputLabel for="country" value="Country"/>

                <TextInput
                    id="country"
                    v-model="form.country"
                    autocomplete="country"
                    class="mt-1 block w-full"
                    required
                    type="text"
                />

                <InputError :message="form.errors.country" class="mt-2"/>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="p-1.5 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                        role="alert"
                    >
                        Successfully saved!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
