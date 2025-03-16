<script setup>
import { Input } from '@/components/ui/input';
import { ref, watch, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';
import { PlusIcon } from '@heroicons/vue/24/solid';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const user = page.props.auth.user;

const props = defineProps({
    table: {
        type: String
    }
});

// Initialize search based on URL parameters
const searchKey = props.table === 'featured' ? 'featured_search' : 'search';
const initialValue = page.props.ziggy?.query?.[searchKey] || '';
const search = ref(initialValue);

const performSearch = debounce((value) => {
    const url = new URL(window.location.href);
    const params = new URLSearchParams(url.search);

    if (value) {
        params.set(searchKey, value);
    } else {
        params.delete(searchKey);
    }
    
    router.get(`/dashboard?${params.toString()}`, {}, {
        preserveState: true,
        preserveScroll: true,
        only: [props.table === 'featured' ? 'featured_books' : 'books']
    });
}, 300);

watch(search, (value) => {
    performSearch(value);
});

const createBook = () => {
    router.visit(route('book.create'));
};
</script>

<template>
    <div class="m-2 p-2">
        <div class="flex justify-between">
            <div>
                <Input
                    v-model="search"
                    type="text"
                    class="border border-dark rounded-xl dark:bg-gray-700 dark:text-white"
                    placeholder="Search..."
                />
            </div>
            <div>
                <button class="btn-primary dark:bg-blue-600 dark:text-white" @click="createBook" v-if="user.is_librarian">
                    <div class="flex">
                        <PlusIcon class="size-6" />
                        <span> Add a book</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</template>