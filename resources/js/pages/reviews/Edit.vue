<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import EditForm from '@/pages/reviews/Form.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    review: {
        type: Object,
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
        href: `/book/${props.review.book_id}`
    },
    {
        title: 'Edit Review',
        href: `/review/${props.review.id}/edit`
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
                        <EditForm :mode="'edit'" :review="review" :book_id="review.book_id" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
