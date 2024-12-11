<script setup>
import {Head} from '@inertiajs/vue3';
import GuestLayout from "@/Layouts/GuestLayout.vue";
import ButtonLink from "@/Components/ButtonLink.vue";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

</script>

<template>
    <GuestLayout :background="false">
        <Head title="Welcome"/>
        <header>
            <h1 class="text-center text-5xl mb-8">My Account</h1>
            <nav v-if="canLogin" class="flex justify-center items-center gap-5">
                <ButtonLink
                    :rounded="true"
                    class="w-32 text-center font-semibold"
                    href="login"
                >
                    Log in
                </ButtonLink>
                <ButtonLink
                    v-if="canRegister"
                    :rounded="true"
                    class="w-32 text-center font-semibold"
                    href="register"
                >
                    Register
                </ButtonLink>
            </nav>
        </header>
    </GuestLayout>

</template>
