<script setup lang="ts">
import { CheckIcon, PencilIcon, TrashIcon, EyeIcon } from '@heroicons/vue/24/solid';
import { usePage } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    book: Object
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
    <div class="flex gap-1">
        <!-- View Book -->
        <button
            class="btn-helper dark:text-white"
            title="View Book Details"
            @click="showBook(book)"
        >
            <EyeIcon class="size-4" />
        </button>

        <!-- Librarian Actions -->
        <div v-if="user.is_librarian">
            <!-- Return Book -->
            <button
                v-if="book.quantity <= 0"
                class="btn-primary dark:text-white"
                title="Return Book"
                @click="destoryCheckout(book)"
            >
                <CheckIcon class="size-4" />
            </button>
            <div v-if="book.quantity > 0">
                <!-- Edit Book -->
                <button
                    class="btn-secondary p-2 dark:text-white mr-1"
                    @click="editBook(book)"
                >
                    <PencilIcon class="size-4" />
                </button>
                <!-- Delete Book -->
                <button
                    class="btn-danger p-2 dark:text-white"
                    @click="destroyBook(book)"
                >
                    <TrashIcon class="size-4" />
                </button>
            </div>
        </div>
        <div v-else>
            <!-- Customer Checks Out Book -->
            <button
                v-if="book.quantity == 1"
                class="btn-primary dark:text-white"
                title="Check Out Book"
                @click="storeCheckout(book)"
            >
                <CheckIcon class="size-4" />
            </button>
        </div>
    </div>
</template>
