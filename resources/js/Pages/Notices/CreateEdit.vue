<script setup>
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps(['notice']);
const isEdit = props?.notice != null;

console.log('image:', `/${props?.notice?.image}`);

const previewImage = ref(isEdit ? `/${props.notice.image}` : null);

const form = useForm({
    title: isEdit ? props.notice.title : '',
    content: isEdit ? props.notice.content : '',
    image: null,
    distribution_start_date: isEdit ? props.notice.distribution_start_date : null,
    distribution_end_date: isEdit ? props.notice.distribution_end_date : null,
});

function uploadImage(e) {
    const image = e.target.files[0];
    console.log('image', image);
    form.image = image;
    const reader = new FileReader();
    reader.readAsDataURL(image);
    reader.onload = e => {
        previewImage.value = e.target.result;
    };
}

function submitHandler() {
    if (isEdit) {
        router.post(route('notices.update', props.notice.id), {
            _method: 'put',
            ...form
        })
    } else {
        form.post(route('notices.store'), { onSuccess: () => form.reset() })
    }
}

</script>

<template>
    <Head :title="isEdit ? 'Notices | Edit' : 'Notices | Create'" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="submitHandler">
                <InputLabel>Title</InputLabel>
                <input v-model="form.title" placeholder="What's your title?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                <InputError :message="form.errors.title" class="mt-2" />

                <InputLabel class="mt-4 mb-1">Content</InputLabel>
                <textarea v-model="form.content" placeholder="Write your content."
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"></textarea>
                <InputError :message="form.errors.content" class="mt-2" />

                <div class="w-40">
                    <label class="cursor-pointer">
                        <span class="block font-medium text-sm text-gray-700 mt-4 mb-1">
                            Image
                            <span class="text-gray-400">&nbsp;(Optional)</span>
                        </span>
                        <div
                            class="flex items-center justify-center w-40 h-40 p-1 border-2 border-gray-400 border-dotted rounded-xl">
                            <img v-if="previewImage !== null" :src="previewImage" />
                            <span v-else class="text-gray-500">Upload Image</span>
                        </div>
                        <input type="file" @change=uploadImage class="hidden" />
                    </label>
                </div>
                <InputError :message="form.errors.image" class="mt-2" />

                <InputLabel class="mt-4 mb-1">Distribution Start Date</InputLabel>
                <input type="date" v-model="form.distribution_start_date"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                <InputError :message="form.errors.distribution_start_date" class="mt-2" />

                <InputLabel class="mt-4 mb-1">Distribution End Date</InputLabel>
                <input type="date" v-model="form.distribution_end_date" :disabled="!form.distribution_start_date"
                    :min="form.distribution_start_date"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                <InputError :message="form.errors.distribution_end_date" class="mt-2" />

                <PrimaryButton class="mt-8">{{ isEdit ? "Update" : "Add" }}</PrimaryButton>
            </form>
        </div>
    </AuthenticatedLayout>
</template>