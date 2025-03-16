<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import RatingsTable from '@/pages/partials/tables/RatingsTable.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    book: Object
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: props.book.title,
        href: `/book/${props.book}/show`
    }
];

const page = usePage();
const user = page.props.auth.user;
</script>


<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head :title="book.title" />

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">                        
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ book.title }}</h1>
                        
                        <img :src="book.cover" :alt="book.title" class="max-h-64 object-contain rounded-md shadow-sm mb-8" />
                        
                        <div class="space-y-4">
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Author</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.author.full_name }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Category</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.category.name }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Description</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.description || 'No description available' }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Publisher</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.publisher || 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Publication Date</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.formatted_published_at }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">ISBN</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.isbn || 'Not specified' }}</p>
                            </div>
                            
                            <div>
                                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Page Count</h2>
                                <p class="text-gray-700 dark:text-gray-300">{{ book.page_count || 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <RatingsTable :book="book" />
            </div>
        </div>
    </AppLayout>
</template>
