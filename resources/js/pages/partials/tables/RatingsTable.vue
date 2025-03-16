<script setup>
import { defineProps } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { PlusIcon } from '@heroicons/vue/24/solid';
import { router } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid';

const props = defineProps({
    book: {
        type: Object,
        required: true
    }
});

const page = usePage();
const user = page.props.auth.user;

const createReview = () => {
    router.visit(route('review.create', { book_id: props.book.id }));
}

const editReview = (review) => {
    router.visit(route('review.edit', { review: review.id }));
};

const destroyReview = (review) => {
    if (confirm('Are you sure that you want to delete your review?')) {
        router.delete(route('review.destroy', { review: review, review_id: review.id }));
    }
};
</script>

<template>
    <div>
        <div class="flex justify-between my-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">{{ book.title }} reviews</h1>
            </div>
            <div v-if="book.checkout && book.checkout.user.id == user.id && book.reviews && ! book.reviews.find(review => review.user_id == user.id)">
                <button class="btn-primary dark:text-white" @click="createReview(book)">
                    <div class="flex">
                        <PlusIcon class="size-6" />
                        <span> Add a review</span>
                    </div>
                </button>
            </div>
        </div>
        <div v-if="book.reviews.length">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 border border-gray-200 dark:border-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th
                            scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        >
                            Reviewer
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        >
                            Rating
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        >
                            Review
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                        >
                            Reviewed On
                        </th>
                        <th
                            scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider whitespace-nowrap"
                            v-if="! user.is_librarian "
                        >
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="review in book.reviews" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="px-4 py-2 text-center whitespace-nowrap text-xs text-gray-700 dark:text-gray-200">{{ review.user.full_name }}</td>
                        <td class="px-4 py-2 text-center whitespace-nowrap text-xs text-gray-700 dark:text-gray-200">{{ review.stars }} stars</td>
                        <td class="px-4 py-2 text-center text-xs text-gray-700 dark:text-gray-200">{{ review.body }}</td>
                        <td class="px-4 py-2 text-center whitespace-nowrap text-xs text-gray-700 dark:text-gray-200">{{ review.created_at }}</td>
                        <td class="px-4 py-2 text-center whitespace-nowrap text-xs text-gray-700 dark:text-gray-200" v-if="! user.is_librarian ">
                            <div v-if="review.user.id == user.id">
                                <!-- Edit Book -->
                                <button class="btn-secondary p-2 dark:text-white mr-1" @click="editReview(review)">
                                    <PencilIcon class="size-4" />
                                </button>

                                <!-- Delete Book -->
                                <button class="btn-danger p-2 dark:text-white" @click="destroyReview(review)">
                                    <TrashIcon class="size-4" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-center" v-else>
            <span class="text-sm">No reviews found.</span>
        </div>
    </div>
</template>