<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

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
            @submit.prevent="form.patch(route('profile.update'), {
                preserveScroll: true,
            })"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="name" value="First name" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="first-name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="last_name" value="Last name" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autocomplete="family-name"
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div>
                <InputLabel for="telephone" value="Telephone" />

                <TextInput
                    id="telephone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.telephone"
                    required
                    autocomplete="tel"
                />

                <InputError class="mt-2" :message="form.errors.telephone" />
            </div>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="email"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
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

            <h2 class="text-lg font-medium text-gray-900"> Address Information </h2>

            <div>
                <InputLabel for="country" value="Postcode" />

                <TextInput
                    id="postcode"
                    type="text"
                    class="mt-1 block w-32"
                    v-model="form.postcode"
                    required
                    autocomplete="postal-code"
                />

                <InputError class="mt-2" :message="form.errors.postcode" />
            </div>

            <div>
                <InputLabel for="address_1" value="Address Line 1" />

                <TextInput
                    id="address_1"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address_1"
                    required
                    autocomplete="address-line1"
                />

                <InputError class="mt-2" :message="form.errors.address_1" />
            </div>

            <div>
                <InputLabel for="address_2" value="Address Line 2" />

                <TextInput
                    id="address_2"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.address_2"
                    autocomplete="address-line2"
                />

                <InputError class="mt-2" :message="form.errors.address_2" />
            </div>

            <div>
                <InputLabel for="county" value="County" />

                <TextInput
                    id="county"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.county"
                    autocomplete="administrative-area"
                />

                <InputError class="mt-2" :message="form.errors.county" />
            </div>

            <div>
                <InputLabel for="city" value="City" />

                <TextInput
                    id="city"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.city"
                    required
                    autocomplete="address-level2"
                />

                <InputError class="mt-2" :message="form.errors.city" />
            </div>

            <div>
                <InputLabel for="country" value="Country" />

                <TextInput
                    id="country"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.country"
                    required
                    autocomplete="country"
                />

                <InputError class="mt-2" :message="form.errors.country" />
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
                        class="p-1.5 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert"
                    >
                        Successfully saved!
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
