<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {nextTick, ref} from "vue";
import InputError from "@/Components/InputError.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Modal from "@/Components/Modal.vue";
import {computed} from "vue";

const confirmingProfileImageDeletion = ref(false);

const user = usePage().props.auth.user;
const user_name = user.last_name ? user.name + ' ' + user.last_name : user.name;
const user_profile_image = usePage().props.auth.user_profile_image;

const form = useForm({
    profile_image: null,
});

const imagePreviewUrl = ref(user_profile_image);

const imageUploaded = computed(() =>
    !imagePreviewUrl.value.includes("ui-avatars.com")
);

const confirmProfileImageDeletion = () => {
    confirmingProfileImageDeletion.value = true;
};

const deleteProfileImage = () => {
    form.delete(route('profile-image.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onFinish: () => imagePreviewUrl.value = 'https://ui-avatars.com/api/?name='+encodeURIComponent(user_name)+'&color=7F9CF5&background=EBF4FF'
    });
};

const closeModal = () => {
    confirmingProfileImageDeletion.value = false;
};

function handleFileChange(event) {
    form.profile_image = event.target.files[0];
    if (form.profile_image) {
        imagePreviewUrl.value = URL.createObjectURL(form.profile_image);
    }
}

function submit() {
    form.post(route('profile-image.upload'), {
        preserveScroll: true,
    });
}
</script>

<template>
    <section class="max-w-xl">

        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profile Image
            </h2>

            <p class="mt-1 mb-4 text-sm text-gray-600">
                Update your account's profile image.
            </p>
        </header>

        <form @submit.prevent="submit">
            <div>
                <img
                    v-if="imagePreviewUrl"
                    :src="imagePreviewUrl"
                    class="max-w-48 mb-4 rounded-full"
                    alt="Image preview"
                />
                <input type="file" @change="handleFileChange" />

                <InputError
                    :message="form.errors.profile_image"
                    class="mt-2"
                />
            </div>

            <div class="flex items-center gap-4 mt-6">
                <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

                <DangerButton v-if="imageUploaded" @click="confirmProfileImageDeletion">Delete Image</DangerButton>

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

    <Modal :show="confirmingProfileImageDeletion" @close="closeModal">
        <div class="p-6">
            <h2
                class="text-lg font-medium text-gray-900"
            >
                Are you sure you want to delete your profile image?
            </h2>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    @click="deleteProfileImage"
                >
                    Delete Image
                </DangerButton>
            </div>
        </div>
    </Modal>

</template>
