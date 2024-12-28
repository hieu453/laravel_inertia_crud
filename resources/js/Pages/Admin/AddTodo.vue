<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const form = useForm({
    name: '',
    description: ''
})

function add() {
    router.post(route('todo.store'), form, {
        onFinish: () => {
            toast("Todo added!", {
                autoClose: 2000,
            });
        },
    })
}
</script>

<template>
<div class="max-w-sm mx-auto">
    <div class="flex justify-between">
        <h1 class="text-3xl">Add Todo</h1>
        <Link :href="route('home')" class="btn-primary bg-gray-500">Back</Link>
    </div>
    <div class="mt-6">
        <form @submit.prevent="add">
            <div class="mb-3">
                <InputLabel value="Name" />
                <TextInput v-model="form.name" />
            </div>
            <div class="mb-3">
                <InputLabel value="Description" />
                <TextInput v-model="form.description" />
            </div>
            <div class="mb-3">
                <PrimaryButton
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Add
                </PrimaryButton>
            </div>
        </form>
    </div>
</div>
</template>
