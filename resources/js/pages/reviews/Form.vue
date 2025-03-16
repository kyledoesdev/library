<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    book_id: {
        type: Number,
        required: true
    },
    review: {
        type: Object,
        default: () => ({
            stars: null,
            body: ''
        })
    },
    mode: {
        type: String,
        default: 'create'
    }
});

const form = useForm({
    stars: props.review.stars || null,
    body: props.review.body || '',
    _method: props.mode === 'edit' ? 'PUT' : 'POST'
});

const submitReview = () => {
    if (props.mode === 'edit' && props.review.id) {
        router.post(route('review.update', { review: props.review, review_id: props.review.id }), form);
    } else {
        router.post(route('review.store', { book_id: props.book_id }), form);
    }
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-4 bg-white dark:bg-gray-800 rounded-lg shadow">
        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">
            {{ mode === 'edit' ? 'Edit Your Review' : 'Write a Review' }}
        </h2>
        
        <form @submit.prevent="submitReview">
            <!-- Star Rating -->
            <div class="mb-4">
                <label for="stars" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Rating
                </label>
                <select 
                    id="stars" 
                    v-model="form.stars" 
                    class="w-full rounded-md border border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:text-white"
                    required
                >
                    <option value="" disabled>Select a rating</option>
                    <option value="1">1 star</option>
                    <option value="2">2 stars</option>
                    <option value="3">3 stars</option>
                    <option value="4">4 stars</option>
                    <option value="5">5 stars</option>
                </select>
                <div v-if="form.errors.stars" class="text-red-500 dark:text-red-400 text-sm mt-1">
                    {{ form.errors.stars }}
                </div>
            </div>
            
            <!-- Review Text -->
            <div class="mb-4">
                <label for="review" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                    Your Review
                </label>
                <textarea 
                    id="review" 
                    v-model="form.body" 
                    rows="4" 
                    class="w-full rounded-md dark:bg-gray-700 dark:text-white"
                    required
                    placeholder="Share your thoughts about this book..."
                ></textarea>
                <div v-if="form.errors.review" class="text-red-500 dark:text-red-400 text-sm mt-1">
                    {{ form.errors.review }}
                </div>
            </div>
            
            <!-- Submit Button -->
            <div class="flex justify-end">
                <button 
                    type="submit" 
                    class="px-4 py-2 btn-primary"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Submitting...' : (mode === 'edit' ? 'Update Review' : 'Submit Review') }}
                </button>
            </div>
        </form>
    </div>
</template>