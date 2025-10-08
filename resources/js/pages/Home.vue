<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

defineProps({
    featuredTools: Array,
    categories: Array,
});
</script>

<template>
    <Head title="AI Manifesto - Curated AI Tools & Reviews" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Hero Section -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-4xl font-bold mb-4">AI Manifesto</h1>
                        <p class="text-xl text-gray-600 dark:text-gray-400 mb-6">
                            A curated collection of AI tools, tested and reviewed by Ryan Grissinger.
                            Real experience from building a $200M company with AI.
                        </p>
                        <div class="flex gap-4">
                            <Link
                                :href="route('tools.index')"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg"
                            >
                                Browse Tools
                            </Link>
                            <Link
                                :href="route('categories.index')"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg"
                            >
                                Explore Categories
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Featured Tools -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Featured Tools</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="tool in featuredTools"
                            :key="tool.id"
                            :href="route('tools.show', tool.slug)"
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow"
                        >
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">
                                    {{ tool.name }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    {{ tool.description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-500 dark:text-gray-500">
                                        {{ tool.category.name }}
                                    </span>
                                    <span v-if="tool.ryan_rating" class="text-yellow-500 font-semibold">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Categories -->
                <div>
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Browse by Category</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Link
                            v-for="category in categories"
                            :key="category.id"
                            :href="route('categories.show', category.slug)"
                            class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm hover:shadow-lg transition-shadow"
                        >
                            <div class="text-4xl mb-2">{{ category.icon }}</div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-gray-100">
                                {{ category.name }}
                            </h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                {{ category.description }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-500">
                                {{ category.active_tools_count }} tools
                            </p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>