<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import EditForm from '@/pages/books/Form.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    book: Object,
    categories: Array,
    authors: Array
});


const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Edit Book',
        href: `/book/${props.book}/edit`
    }
];

const form = useForm({
    ...props.book,
    cover: null,
    _method: 'PUT'
});

const submit = (formData) => {
    formData._method = 'PUT'; // Ensure method spoofing
    formData.post(`/books/${props.book.id}`);
};
</script>

<template>
    <Head :title="`Edit Book: ${book.title}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-gray-200">
                        <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Book: {{ book.title }}</h2>
                        <EditForm 
                            :categories="categories" 
                            :authors="authors"
                            :book="book"
                            submit-label="Update Book"
                            mode="edit"
                            @submit="submit"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>