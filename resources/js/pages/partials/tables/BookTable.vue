<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps, ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Actions from '@/pages/partials/tables/columns/Actions.vue';
import { ArrowUpIcon, ArrowDownIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    books: {
        type: Object,
        required: true
    },
    mode: {
        type: String,
        required: true
    }
});

const page = usePage();
const user = page.props.auth.user;
const urlParams = new URLSearchParams(window.location.search);

const currentOrder = ref(props.mode === 'featured' ? 
    urlParams.get('featured_order') || '' : 
    urlParams.get('order') || '');

const currentDirection = ref(props.mode === 'featured' ? 
    urlParams.get('featured_direction') || 'asc' : 
    urlParams.get('direction') || 'asc');

const isSorted = (column) => currentOrder.value === column;

function sortBy(column) {
    let direction = 'asc';
    if (column === currentOrder.value && currentDirection.value === 'asc') {
        direction = 'desc';
    }
    
    currentOrder.value = column;
    currentDirection.value = direction;
    
    const url = window.location.pathname;
    const query = {};

    for (const [key, value] of urlParams.entries()) {
        if ((props.mode === 'featured' && (key === 'featured_order' || key === 'featured_direction')) ||
            (props.mode !== 'featured' && (key === 'order' || key === 'direction'))) {
            continue;
        }
        query[key] = value;
    }
    
    if (props.mode === 'featured') {
        query.featured_order = column;
        query.featured_direction = direction;
        
        router.visit(url, {
            data: query,
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['featured_books']
        });
    } else {
        query.order = column;
        query.direction = direction;
        
        router.visit(url, {
            data: query,
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['books']
        });
    }
}
</script>

<template>
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Cover
                    </th>
                    <!-- Sortable Title -->
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="sortBy('title')"
                    >
                        <div class="flex items-center justify-center">
                            Title
                            <span v-if="isSorted('title')" class="ml-1">
                                <ArrowUpIcon v-if="currentDirection === 'asc'" class="h-4 w-4" />
                                <ArrowDownIcon v-else class="h-4 w-4" />
                            </span>
                        </div>
                    </th>
                    <!-- Sortable Author -->
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="sortBy('author')"
                    >
                        <div class="flex items-center justify-center">
                            Author
                            <span v-if="isSorted('author')" class="ml-1">
                                <ArrowUpIcon v-if="currentDirection === 'asc'" class="h-4 w-4" />
                                <ArrowDownIcon v-else class="h-4 w-4" />
                            </span>
                        </div>
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Publisher
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        ISBN
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Page Count
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Average Review
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        v-if="user.is_librarian"
                    >
                        Checked Out By
                    </th>
                    <!-- Sortable Availability -->
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-700"
                        @click="sortBy('availability')"
                        v-if="user.is_librarian || (! user.is_librarian && props.mode !== 'featured')"
                    >
                        <div class="flex items-center justify-center">
                            Due On
                            <span v-if="isSorted('availability')" class="ml-1">
                                <ArrowUpIcon v-if="currentDirection === 'asc'" class="h-4 w-4" />
                                <ArrowDownIcon v-else class="h-4 w-4" />
                            </span>
                        </div>
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Action
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="book in books.data ?? books" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                    <td class="text-center">
                        <img class="p-2 mx-auto" :src="book.cover" width="40" height="60" />
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.title }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.author.full_name }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.publisher }} <br />
                        {{ book.formatted_published_at }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.isbn }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.page_count }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.rating == 'N/A' ? 'N/A' : book.rating + ' stars' }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200" v-if="user.is_librarian">
                        {{ book.checkout ? book.checkout.user.full_name : 'N/A' }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200" v-if="user.is_librarian || (! user.is_librarian && props.mode !== 'featured')">
                        <div>
                            <span v-if="book.checkout" class="font-bold">{{ book.checkout.due_at }}</span>
                            <span v-else-if="user.is_librarian && book.checkout"></span>
                            <span v-else>Available</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        <Actions :book="book" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <div class="mt-3 flex justify-between p-1" v-if="books.links && books.links.length > 0">
        <div class="text-xs text-gray-600 dark:text-gray-300">
            Showing {{ books.from ? books.from : 0 }} to {{ books.to ? books.to : 0 }} of {{ books.total ? books.total : 0 }} results
        </div>
        
        <div class="flex space-x-1">
            <Link
                v-for="link in books.links"
                :key="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-2 py-1 border rounded text-xs dark:border-gray-600 dark:text-gray-200"
                :class="{ 
                    'bg-blue-500 text-white': link.active,
                    'dark:bg-blue-600 dark:text-white dark:hover:bg-blue-700': link.active,
                    'dark:hover:bg-gray-700': !link.active
                }"
            />
        </div>
    </div>
</template>