<script setup>
import { Input } from '@/components/ui/input';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

const search = ref('');

const performSearch = debounce((value) => {
    router.get('/dashboard', { search: value }, {
        preserveState: true,
        preserveScroll: true,
        only: ['books']
    });
}, 300);

watch(search, (value) => {
    performSearch(value);
});
</script>

<template>
    <div class="m-2 p-2">
        <div class="flex justify-between">
            <div>
                <Input
                    v-model="search"
                    type="text"
                    class="border border-dark rounded-xl"
                    autofocus
                    placeholder="Search..."
                />
            </div>
            <div>
                <button class="btn-primary">Add a Book</button>
            </div>
        </div>
    </div>
</template>