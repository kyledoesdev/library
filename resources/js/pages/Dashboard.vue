<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import BookTable from '@/pages/partials/tables/BookTable.vue';
import BookTableActions from '@/pages/partials/tables/BookTableActions.vue';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const page = usePage();
const user = page.props.auth.user;

const props = defineProps({
    books: {
        type: Object,
    },
    featured_books: {
        type: Object
    }
});
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <!-- top container -->
            <div v-if="user.is_librarian" class="space-y-8">
                <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                    <BookTableActions />
                    <BookTable :books="books" />
                </div>
            </div>
            <div v-else>
                <div class="space-y-8">
                    <div>
                        <h5 class="my-4">Checked Out Books:</h5>
                        <div
                            class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min"
                        >
                            <BookTableActions />
                            <BookTable :books="books" v-if="(books.data && books.data.length > 0) || books.length" />
                            <div v-else>
                                <span class="text-sm">No Books Found</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="my-4">Featured Books:</h5>
                        <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                            <BookTableActions :table="'featured'" />
                            <BookTable :books="featured_books" :mode="'featured'" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
