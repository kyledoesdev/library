<script setup>
import { Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import { router } from '@inertiajs/vue3';
import { CheckIcon, PencilIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/solid';
import { usePage } from '@inertiajs/vue3';

// Define the books prop
const props = defineProps({
    books: {
        type: Object,
        required: true
    },
    mode: {
        type: String
    }
});

const page = usePage();
const user = page.props.auth.user;

const editBook = (book) => {
    router.visit(route('book.edit', book));
};

const showBook = (book) => {
    router.visit(route('book.show', book));
}

const destroyBook = (book) => {
    if (confirm('Are you sure you want to delete this book?')) {
        router.delete(route('book.destroy', book.id));
    }
}

const storeCheckout = (book) => {
    router.post(route('checkout.store', { book_id: book.id }))
}

const destoryCheckout = (book) => {
    if (confirm('Are you ready to return this book?')) {
        router.delete(route('checkout.destroy', book.checkout))
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
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Title
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Description
                    </th>
                    <th
                        scope="col"
                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                    >
                        Author
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
                        Published At
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
                        Due On
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
                    <td class="px-4 py-2 text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.description }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.author.full_name }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.publisher }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.formatted_published_at }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.isbn }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        {{ book.page_count }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        <div>
                            <span v-if="book.checkout" class="font-bold">{{ book.checkout.due_at }}</span>
                            <span v-else-if="user.is_librarian && book.checkout">Available</span>
                            <span v-else>N/A</span>
                        </div>
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-xs text-center text-gray-700 dark:text-gray-200">
                        <div class="flex gap-1">
                            <div v-if="user.is_librarian">
                                <div v-if="book.checkout && book.quantity <= 0">
                                    <button class="btn-primary dark:text-white" title="Return Book" @click="destoryCheckout(book)">
                                        <CheckIcon class="size-6" />
                                    </button>
                                </div>
                            </div>
                            <div v-if="! user.is_librarian && book.quantity == 1">
                                <button class="btn-primary dark:text-white" title="Check Out Book" @click="storeCheckout(book)">
                                    <CheckIcon class="size-6" />
                                </button>
                            </div>

                            <button class="btn-helper dark:text-white" title="View Book Details" @click="showBook(book)">
                                <EyeIcon class="size-6" />
                            </button>

                            <button class="btn-secondary p-2 dark:text-white" @click="editBook(book)" v-if="user.is_librarian">
                                <PencilIcon class="size-6" />
                            </button>
                            <div v-if="book.quantity > 0">
                                <button class="btn-danger p-2 dark:text-white" @click="destroyBook(book)" v-if="user.is_librarian">
                                    <TrashIcon class="size-6" />
                                </button>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    

    <div class="mt-3 flex justify-between p-1" v-if="props.mode !== 'featured'">
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