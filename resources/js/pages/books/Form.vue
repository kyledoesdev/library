<script setup>
import { ref, reactive, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { useForm } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    authors: {
        type: Array,
        required: true
    },
    book: {
        type: Object,
        default: () => ({})
    },
    submitLabel: {
        type: String,
        default: 'Create Book'
    },
    mode: {
        type: String,
        default: 'create' // 'create' or 'edit'
    }
});

const emit = defineEmits(['submit']);

const authorMode = ref('existing');

const newAuthor = reactive({
    first_name: '',
    last_name: ''
});

const form = useForm({
    _method: props.mode === 'edit' ? 'PUT' : 'POST',
    author_id: props.book.author_id || '',
    category_id: props.book.category_id || '',
    title: props.book.title || '',
    description: props.book.description || '',
    cover: null,
    cover_preview: props.book.cover_url || null,
    publisher: props.book.publisher || '',
    published_at: props.book.published_at || '',
    isbn: props.book.isbn || '',
    page_count: props.book.page_count || null,
    new_author: {
        first_name: '',
        last_name: ''
    }
});

const handleCoverUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.cover = file;
        // Create a preview URL
        form.cover_preview = URL.createObjectURL(file);
    }
};

const toggleAuthorMode = (mode) => {
    authorMode.value = mode;
    if (mode === 'existing') {
        form.new_author.first_name = '';
        form.new_author.last_name = '';
    } else {
        form.author_id = '';
    }
};

const updateNewAuthor = () => {
    form.new_author.first_name = newAuthor.first_name;
    form.new_author.last_name = newAuthor.last_name;
};

const submit = () => {
    if (props.mode === 'edit') {
        router.post(route('book.update', props.book), form);
    } else {
        // For create mode, handle submission directly
        router.post(route('book.store'), form);
    }
};
</script>

<template>
    <div class="mx-auto">
        <form @submit.prevent="submit">
            <!-- Title -->
            <div class="mb-6">
                <Label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Title *</label>
                <Input 
                    id="title" 
                    v-model="form.title" 
                    type="text" 
                    class="w-full dark:bg-gray-700 dark:text-white" 
                    required
                />
                <InputError class="mt-2" :message="form.errors.title" />
            </div>
            
            <!-- Author Selection -->
            <div class="mb-6">
                <Label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Author *</Label>
                
                <div class="flex space-x-4 mb-3">
                    <button 
                        type="button"
                        :class="{
                            'bg-blue-500 text-white': authorMode === 'existing', 
                            'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white': authorMode !== 'existing'
                        }"
                        class="px-4 py-2 rounded"
                        @click="toggleAuthorMode('existing')"
                    >
                        Select Existing Author
                    </button>
                    
                    <button 
                        type="button"
                        :class="{
                            'bg-blue-500 text-white': authorMode === 'new', 
                            'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white': authorMode !== 'new'
                        }"
                        class="px-4 py-2 rounded"
                        @click="toggleAuthorMode('new')"
                    >
                        Add New Author
                    </button>
                </div>
                
                <div v-if="authorMode === 'existing'">
                    <select 
                        v-model="form.author_id" 
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white"
                        required
                    >
                        <option value="" disabled>Select an author</option>
                        <option v-for="author in props.authors" :key="author.id" :value="author.id">
                            {{ author.first_name }} {{ author.last_name }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.author_id" />
                </div>
                
                <div v-else class="grid grid-cols-2 gap-4">
                    <div>
                        <Label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">First Name</Label>
                        <Input 
                            id="first_name" 
                            v-model="newAuthor.first_name" 
                            type="text" 
                            class="w-full dark:bg-gray-700 dark:text-white" 
                            required
                            @input="updateNewAuthor"
                        />
                    </div>
                    <div>
                        <Label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Last Name</Label>
                        <Input 
                            id="last_name" 
                            v-model="newAuthor.last_name" 
                            type="text" 
                            class="w-full dark:bg-gray-700 dark:text-white" 
                            required
                            @input="updateNewAuthor"
                        />
                    </div>
                    <InputError class="mt-2" :message="form.errors['new_author.first_name']" />
                    <InputError class="mt-2" :message="form.errors['new_author.last_name']" />
                </div>
            </div>
            
            <!-- Category -->
            <div class="mb-6">
                <Label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Category *</Label>
                <select 
                    id="category"
                    v-model="form.category_id" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white"
                    required
                >
                    <option value="" disabled>Select a category</option>
                    <option v-for="category in props.categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
                <InputError class="mt-2" :message="form.errors.category_id" />
            </div>
            
            <!-- Description -->
            <div class="mb-6">
                <Label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Description</Label>
                <textarea 
                    id="description"
                    v-model="form.description" 
                    rows="4" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white"
                ></textarea>
                <InputError class="mt-2" :message="form.errors.description" />
            </div>
            
            <!-- Cover Image -->
            <div class="mb-6">
                <Label for="cover" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Cover Image</Label>
                
                <div v-if="form.cover_preview" class="mb-2">
                    <p v-if="mode === 'edit'" class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Cover:</p>
                    <img :src="form.cover_preview" alt="Cover preview" class="h-40 object-cover rounded-md" />
                </div>
                
                <Input 
                    id="cover"
                    type="file" 
                    accept="image/*"
                    @change="handleCoverUpload" 
                    class="w-full border border-gray-300 dark:border-gray-600 rounded-md px-3 py-2 dark:bg-gray-700 dark:text-white"
                />
                
                <p v-if="mode === 'edit'" class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Leave empty to keep the current cover image
                </p>
                
                <div v-if="form.errors.cover" class="text-red-500 dark:text-red-400 text-sm mt-1">{{ form.errors.cover }}</div>
            </div>
            
            <!-- Publisher -->
            <div class="mb-6">
                <label for="publisher" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Publisher</label>
                <Input 
                    id="publisher"
                    v-model="form.publisher" 
                    type="text" 
                    class="w-full dark:bg-gray-700 dark:text-white"
                />
                <InputError class="mt-2" :message="form.errors.publisher" />
            </div>
            
            <!-- Published Date -->
            <div class="mb-6">
                <label for="published_at" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">
                    Publication Date
                </label>
                <div class="relative">
                    <Input 
                        id="published_at"
                        v-model="form.published_at" 
                        type="date" 
                        class="w-full dark:bg-gray-700 dark:text-white"
                    />
                </div>
                <InputError class="mt-2" :message="form.errors.published_at" />
            </div>
            
            <!-- ISBN -->
            <div class="mb-6">
                <Label for="isbn" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">ISBN</Label>
                <Input 
                    id="isbn"
                    v-model="form.isbn" 
                    type="text" 
                    class="w-full dark:bg-gray-700 dark:text-white"
                />
                <InputError class="mt-2" :message="form.errors.isbn" />
            </div>
            
            <!-- Page Count -->
            <div class="mb-6">
                <Label for="page_count" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Page Count</Label>
                <Input 
                    id="page_count"
                    v-model="form.page_count" 
                    type="number" 
                    min="1"
                    class="w-full dark:bg-gray-700 dark:text-white"
                />
                <InputError class="mt-2" :message="form.errors.page_count" />
            </div>
            
            <!-- Submit Buttons -->
            <div class="flex justify-end" :class="{'space-x-3': mode === 'edit'}">
                <a 
                    v-if="mode === 'edit'"
                    href="/dashboard" 
                    class="px-6 py-2 bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500"
                >
                    Cancel
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600"
                    :disabled="form.processing"
                >
                    {{ form.processing ? 'Saving...' : submitLabel }}
                </button>
            </div>
        </form>
    </div>
</template>