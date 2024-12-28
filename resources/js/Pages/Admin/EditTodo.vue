<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const props = defineProps({
    'todo': {
        type: Object
    }
})

const form = useForm({
    name: props.todo.name,
    description: props.todo.description,
})

function edit() {
    router.put(route('todo.update', { id: props.todo.id }), form, {
        onFinish: () => {
            toast("Todo updated!", {
                autoClose: 2000,
            })
        }
    })
}

</script>

<template>
<div class="max-w-sm mx-auto">
    <div class="flex justify-between">
        <h1 class="text-3xl">Edit Todo</h1>
        <Link :href="route('home')" class="btn-primary bg-gray-500">Back</Link>
    </div>
    <div class="mt-6">
        <form @submit.prevent="edit">
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
                    Edit
                </PrimaryButton>
            </div>
        </form>
    </div>
</div>
</template>
