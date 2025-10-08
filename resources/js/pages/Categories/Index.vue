<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps({
    categories: Array,
});
</script>

<template>
    <Head title="Categories - AI Manifesto" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-4xl font-bold mb-4">AI Tool Categories</h1>
                        <p class="text-xl text-gray-600 dark:text-gray-400">
                            Browse AI tools organized by category
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="route('categories.show', category.slug)"
                        class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow"
                    >
                        <div class="p-6">
                            <div class="text-5xl mb-4">{{ category.icon }}</div>
                            <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-gray-100">
                                {{ category.name }}
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                {{ category.description }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-500">
                                {{ category.active_tools_count }} tools
                            </p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>