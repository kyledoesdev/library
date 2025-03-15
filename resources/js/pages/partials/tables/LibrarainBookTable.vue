<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

// Define the books prop
const props = defineProps({
  books: {
    type: Object,
    required: true
  }
});
</script>

<template>
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200 border">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Cover</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Title</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Description</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Author</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Publisher</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Published At</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">ISBN</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Page Count</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Due On</th>
                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Action</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="book in books.data">
                <td class="text-center">
                    <img class="p-2 mx-auto" :src="book.cover" width="40" height="60" />
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.title }}
                </td>
                <td class="px-4 py-2 text-xs text-center text-gray-700">
                    {{ book.description }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.author.full_name }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.publisher }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.published_at }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.isbn }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    {{ book.page_count }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    <span v-if="book.checkouts[0]" class="font-bold">{{ book.checkouts[0].due_at }}</span>
                    <span v-else>Available</span>
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700">
                    <div class="flex gap-1">
                        <div v-if="book.checkouts[0]">
                            <button class="btn-primary">Return</button>
                        </div>

                        <button class="btn-secondary p-2">Edit</button>
                        <button class="btn-danger p-2">Delete</button>
                    </div>
                </td>
            </tr>
        </tbody>
        </table>
    </div>
    

    <div class="mt-3 flex justify-between p-1">
        <div class="text-xs text-gray-600">
            Showing {{ books.from }} to {{ books.to }} of {{ books.total }} results
        </div>
        
        <div class="flex space-x-1">
            <Link
                v-for="link in books.links"
                :key="link.url"
                :href="link.url"
                v-html="link.label"
                class="px-2 py-1 border rounded text-xs"
                :class="{ 'bg-blue-500 text-white': link.active }"
            />
        </div>
    </div>
</template>