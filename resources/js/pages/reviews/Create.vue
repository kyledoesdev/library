<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import CreateForm from '@/pages/reviews/Form.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    book_id: {
        type: Number,
        required: true
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'View Book',
        href: `/book/${props.book_id}`
    },
    {
        title: 'Create Review',
        href: '/review/create'
    }
];
</script>

<template>
    <Head title="Add a new book" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-gray-200">
                        <CreateForm :model="'create'" :book_id="book_id" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
