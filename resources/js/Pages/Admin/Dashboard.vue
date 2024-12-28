<script setup>
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';
import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import DeleteTodoModal from './DeleteTodoModal.vue';
import { toast } from 'vue3-toastify';

const props = defineProps({
    'todos': {
        type: Object
    }
})

const term = ref('');
const isOpen = ref(false)
const todoItem = ref(0)

function done(todo) {
    router.put(route('todo.done'), todo, {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            toast("Status updated!", {
                autoClose: 2000,
            })
        },
    })
}

function search() {
    router.get(route('home'), { term: term.value }, {
        preserveScroll: true,
        preserveState: true,
    })
}

function deleteTodo() {
    router.delete(route('todo.delete', todoItem.value), {
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            toast("Todo deleted!", {
                autoClose: 2000,
            })
        },
    })

    isOpen.value = false
}

function openAndSetTodo(todo) {
    isOpen.value = true
    todoItem.value = todo.id
}

</script>

<template>
<DeleteTodoModal
    :isOpen="isOpen"
    @close-modal="isOpen = false"
    @delete-todo="deleteTodo"
/>

<h1 class="text-center text-3xl">Todos list</h1>
<div class="mx-auto px-20">
    <div class="max-w-sm mx-auto flex justify-end">
        <Link :href="route('todo.add')" class="btn-primary bg-violet-600 hover:bg-violet-500">Add</Link>
    </div>
    <div class="max-w-sm mx-auto flex justify-center">
        <TextInput v-model="term" @keyup="search" />
    </div>
    <template v-if="todos.data.length">
        <ul class="bg-white shadow overflow-hidden sm:rounded-md max-w-sm mx-auto mt-5">
            <li v-for="todo in todos.data" :key="todo.id">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">{{ todo.name }}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ todo.description }}</p>
                    </div>
                    <div class="mt-4 flex items-center justify-between">
                        <!-- Status -->
                        <p v-if="todo.is_done" class="text-sm font-medium text-gray-500">Status: <span class="text-green-600">Done</span></p>
                        <p v-else class="text-sm font-medium text-gray-500">Status: <span class="text-red-600">Undone</span></p>

                        <!-- Menu -->
                        <div>
                            <button v-if="todo.is_done" @click="done(todo)" class="font-medium text-indigo-600 hover:text-indigo-500 mr-2">Undone</button>
                            <button v-else @click="done(todo)" class="font-medium text-indigo-600 hover:text-indigo-500 mr-2">Done</button>
                            <Link :href="route('todo.edit', {id: todo.id})" class="font-medium text-green-600 hover:text-green-500 mr-2">Edit</Link>
                            <button @click="openAndSetTodo(todo)" class="font-medium text-red-600 hover:text-red-500">Remove</button>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="pt-5 max-w-sm mx-auto flex justify-center">
            <Pagination :links="todos.links" />
        </div>
    </template>
    <template v-else>
        <p class="max-w-sm mx-auto flex justify-center">No todos</p>
    </template>
</div>
</template>
