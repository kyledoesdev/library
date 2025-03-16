<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import CreateForm from '@/pages/books/Form.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Create Book',
        href: '/book/create'
    }
];

defineProps({
    categories: Array,
    authors: Array
});
</script>

<template>
    <Head title="Add a new book" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-gray-200">
                        <CreateForm :categories="categories" :authors="authors" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
