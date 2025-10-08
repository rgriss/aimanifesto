<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    tools: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const category = ref(props.filters.category || '');
const pricing = ref(props.filters.pricing || '');
const sort = ref(props.filters.sort || 'name');

const applyFilters = () => {
    router.get(route('tools.index'), {
        search: search.value,
        category: category.value,
        pricing: pricing.value,
        sort: sort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="AI Tools - AI Manifesto" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h1 class="text-4xl font-bold mb-4">AI Tools Directory</h1>
                        <p class="text-xl text-gray-600 dark:text-gray-400">
                            Curated AI tools tested and reviewed by Ryan Grissinger
                        </p>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Search -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Search
                                </label>
                                <input
                                    v-model="search"
                                    @input="applyFilters"
                                    type="text"
                                    placeholder="Search tools..."
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                />
                            </div>

                            <!-- Pricing Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Pricing
                                </label>
                                <select
                                    v-model="pricing"
                                    @change="applyFilters"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                >
                                    <option value="">All</option>
                                    <option value="free">Free</option>
                                    <option value="freemium">Freemium</option>
                                    <option value="paid">Paid</option>
                                    <option value="enterprise">Enterprise</option>
                                </select>
                            </div>

                            <!-- Sort -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sort By
                                </label>
                                <select
                                    v-model="sort"
                                    @change="applyFilters"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
                                >
                                    <option value="name">Name</option>
                                    <option value="rating">Rating</option>
                                    <option value="views">Most Viewed</option>
                                    <option value="recent">Recently Added</option>
                                </select>
                            </div>

                            <!-- Clear Filters -->
                            <div class="flex items-end">
                                <Link
                                    :href="route('tools.index')"
                                    class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-center"
                                >
                                    Clear Filters
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tools Grid -->
                <div v-if="tools.data && tools.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="tool in tools.data"
                            :key="tool.id"
                            :href="route('tools.show', tool.slug)"
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow"
                        >
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                        {{ tool.name }}
                                    </h3>
                                    <span
                                        v-if="tool.is_featured"
                                        class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded"
                                    >
                                        Featured
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    {{ tool.description }}
                                </p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-500 dark:text-gray-500">
                                        {{ tool.category?.name }}
                                    </span>
                                    <span v-if="tool.ryan_rating" class="text-yellow-500 font-semibold">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <span class="text-xs text-gray-500 dark:text-gray-500 capitalize">
                                        {{ tool.pricing_model }}
                                    </span>
                                </div>
                            </div>
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div v-if="tools.links" class="mt-8 flex justify-center gap-2">
                        <Link
                            v-for="link in tools.links"
                            :key="link.label"
                            :href="link.url"
                            :class="[
                                'px-4 py-2 rounded-md',
                                link.active
                                    ? 'bg-blue-600 text-white'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700',
                                !link.url && 'opacity-50 cursor-not-allowed'
                            ]"
                            :disabled="!link.url"
                            v-html="link.label"
                        />
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                        <p class="text-xl mb-4">No tools found</p>
                        <Link
                            :href="route('tools.index')"
                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                        >
                            Clear filters
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>