<!-- resources/js/Components/FlashMessage.vue -->
<script setup>
import { computed, ref, onMounted, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);
const message = ref('');
const type = ref('');

const flash = computed(() => page.props.flash);

watch(flash, (newFlash) => {
    if (newFlash.success) {
        message.value = newFlash.success;
        type.value = 'success';
        show.value = true;
        setTimeout(() => { show.value = false }, 3000);
    } else if (newFlash.error) {
        message.value = newFlash.error;
        type.value = 'error';
        show.value = true;
        setTimeout(() => { show.value = false }, 3000);
    }
}, { deep: true, immediate: true });

const alertClass = computed(() => {
    if (type.value === 'success') {
        return 'bg-green-50 text-green-800 dark:bg-gray-800 dark:text-green-400';
    } else if (type.value === 'error') {
        return 'bg-red-50 text-red-800 dark:bg-gray-800 dark:text-red-400';
    }
    return '';
});

const icon = computed(() => {
    if (type.value === 'success') {
        return 'M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z';
    } else if (type.value === 'error') {
        return 'M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM10 15a1 1 0 1 1 0-2 1 1 0 0 1 0 2Zm1-4a1 1 0 0 1-2 0V6a1 1 0 0 1 2 0v5Z';
    }
    return '';
});
</script>

<template>
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform -translate-y-2 opacity-0"
        enter-to-class="transform translate-y-0 opacity-100"
        leave-active-class="transition duration-300 ease-in"
        leave-from-class="transform translate-y-0 opacity-100"
        leave-to-class="transform -translate-y-2 opacity-0"
    >
        <div v-if="show" class="fixed inset-x-0 top-4 z-50 flex justify-center">
            <div :class="[alertClass, 'flex items-center p-4 rounded-lg shadow-lg max-w-md']" role="alert">
                <svg class="shrink-0 w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path :d="icon" />
                </svg>
                <div class="ms-3 text-sm font-medium">{{ message }}</div>
                <button 
                    type="button" 
                    class="ms-auto -mx-1.5 -my-1.5 rounded-lg focus:ring-2 focus:ring-gray-400 p-1.5 inline-flex h-8 w-8"
                    aria-label="Close"
                    @click="show = false"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            </div>
        </div>
    </transition>
</template>