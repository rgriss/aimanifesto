<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

defineProps({
    featuredTools: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
});
</script>

<template>
    <Head title="AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Test - Simple Text -->
                <h1 class="text-4xl font-bold text-white mb-8">AI Manifesto</h1>
                
                <div class="bg-white rounded-lg shadow p-6 mb-8">
                    <h2 class="text-2xl font-bold mb-4">Welcome</h2>
                    <p class="text-gray-600 mb-4">
                        A curated collection of AI tools, tested and reviewed.
                    </p>
                    <div class="flex gap-4">
                        <Link
                            href="/tools"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg"
                        >
                            Browse Tools
                        </Link>
                        <Link
                            href="/categories"
                            class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg"
                        >
                            Categories
                        </Link>
                    </div>
                </div>

                <!-- Featured Tools -->
                <div v-if="featuredTools.length > 0" class="mb-8">
                    <h2 class="text-2xl font-bold text-white mb-4">Featured Tools</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div
                            v-for="tool in featuredTools"
                            :key="tool.id"
                            class="bg-white rounded-lg shadow p-6"
                        >
                            <h3 class="text-xl font-semibold mb-2">{{ tool.name }}</h3>
                            <p class="text-gray-600 mb-4">{{ tool.description }}</p>
                            <Link
                                :href="`/tools/${tool.slug}`"
                                class="text-blue-600 hover:underline"
                            >
                                Learn more →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div v-if="categories.length > 0">
                    <h2 class="text-2xl font-bold text-white mb-4">Categories</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <Link
                            v-for="category in categories"
                            :key="category.id"
                            :href="`/categories/${category.slug}`"
                            class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition"
                        >
                            <div class="text-4xl mb-2">{{ category.icon }}</div>
                            <h3 class="font-semibold mb-2">{{ category.name }}</h3>
                            <p class="text-sm text-gray-600">{{ category.active_tools_count }} tools</p>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>