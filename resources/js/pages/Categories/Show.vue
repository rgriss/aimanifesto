<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineProps({
    category: Object,
    toolCount: Number,
});
</script>

<template>
    <Head :title="`${category.name} - AI Manifesto`" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Category Header -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="text-6xl">{{ category.icon }}</div>
                            <div>
                                <h1 class="text-4xl font-bold">{{ category.name }}</h1>
                                <p class="text-gray-600 dark:text-gray-400 mt-2">
                                    {{ category.description }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-500">
                            <span>{{ toolCount }} tools</span>
                        </div>
                    </div>
                </div>

                <!-- Back to Categories -->
                <div class="mb-6">
                    <Link
                        :href="route('categories.index')"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        ← Back to all categories
                    </Link>
                </div>

                <!-- Tools in Category -->
                <div v-if="category.active_tools && category.active_tools.length > 0">
                    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">Tools</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="tool in category.active_tools"
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
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-500 capitalize">
                                        {{ tool.pricing_model }}
                                    </span>
                                    <span v-if="tool.ryan_rating" class="text-yellow-500 font-semibold">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400">
                        <p>No tools in this category yet.</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>