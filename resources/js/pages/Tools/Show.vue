<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    tool: Object,
    relatedTools: Array,
});
</script>

<template>
    <Head :title="`${tool.name} - AI Manifesto`" />

    <AppLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Back Navigation -->
                <div class="mb-6">
                    <Link
                        :href="route('tools.index')"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        ← Back to all tools
                    </Link>
                </div>

                <!-- Tool Header -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-8">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <h1 class="text-4xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                    {{ tool.name }}
                                </h1>
                                <Link
                                    :href="route('categories.show', tool.category.slug)"
                                    class="text-blue-600 hover:text-blue-800 dark:text-blue-400"
                                >
                                    {{ tool.category.name }}
                                </Link>
                            </div>
                            <span
                                v-if="tool.is_featured"
                                class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold"
                            >
                                Featured
                            </span>
                        </div>

                        <p class="text-xl text-gray-600 dark:text-gray-400 mb-6">
                            {{ tool.description }}
                        </p>

                        <!-- Metadata Row -->
                        <div class="flex flex-wrap gap-6 text-sm">
                            <div v-if="tool.ryan_rating">
                                <span class="text-gray-500 dark:text-gray-500">Ryan's Rating:</span>
                                <span class="ml-2 text-yellow-500 font-semibold text-lg">
                                    ⭐ {{ tool.ryan_rating }}/10
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-500">Pricing:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100 capitalize">
                                    {{ tool.pricing_model }}
                                </span>
                            </div>
                            <div v-if="tool.price_description">
                                <span class="text-gray-500 dark:text-gray-500">Cost:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">
                                    {{ tool.price_description }}
                                </span>
                            </div>
                            <div v-if="tool.views_count">
                                <span class="text-gray-500 dark:text-gray-500">Views:</span>
                                <span class="ml-2 text-gray-900 dark:text-gray-100">
                                    {{ tool.views_count.toLocaleString() }}
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-6 flex gap-4">
                            <a
                                :href="tool.website_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg"
                            >
                                Visit Website →
                            </a>
                            <a
                                v-if="tool.documentation_url"
                                :href="tool.documentation_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-6 rounded-lg"
                            >
                                Documentation
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Long Description -->
                <div v-if="tool.long_description" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">About</h2>
                        <p class="text-gray-600 dark:text-gray-400 whitespace-pre-line">
                            {{ tool.long_description }}
                        </p>
                    </div>
                </div>

                <!-- Ryan's Notes -->
                <div v-if="tool.ryan_notes" class="bg-blue-50 dark:bg-blue-900/20 overflow-hidden shadow-sm sm:rounded-lg mb-8">
                    <div class="p-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                            Ryan's Take
                        </h2>
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line italic">
                            "{{ tool.ryan_notes }}"
                        </p>
                        <p v-if="tool.ryan_last_used" class="text-sm text-gray-500 dark:text-gray-500 mt-4">
                            Last used: {{ new Date(tool.ryan_last_used).toLocaleDateString() }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Features -->
                    <div v-if="tool.features && tool.features.length > 0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                Key Features
                            </h3>
                            <ul class="space-y-2">
                                <li
                                    v-for="(feature, index) in tool.features"
                                    :key="index"
                                    class="flex items-start"
                                >
                                    <span class="text-blue-600 mr-2">✓</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ feature }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Use Cases -->
                    <div v-if="tool.use_cases && tool.use_cases.length > 0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                Use Cases
                            </h3>
                            <ul class="space-y-2">
                                <li
                                    v-for="(useCase, index) in tool.use_cases"
                                    :key="index"
                                    class="flex items-start"
                                >
                                    <span class="text-green-600 mr-2">→</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ useCase }}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Integrations -->
                    <div v-if="tool.integrations && tool.integrations.length > 0" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                Integrations
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <span
                                    v-for="(integration, index) in tool.integrations"
                                    :key="index"
                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm"
                                >
                                    {{ integration }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Related Tools -->
                <div v-if="relatedTools && relatedTools.length > 0" class="mt-8">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                        More in {{ tool.category.name }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Link
                            v-for="relatedTool in relatedTools"
                            :key="relatedTool.id"
                            :href="route('tools.show', relatedTool.slug)"
                            class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg hover:shadow-lg transition-shadow"
                        >
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2 text-gray-900 dark:text-gray-100">
                                    {{ relatedTool.name }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">
                                    {{ relatedTool.description }}
                                </p>
                                <span v-if="relatedTool.ryan_rating" class="text-yellow-500 font-semibold">
                                    ⭐ {{ relatedTool.ryan_rating }}/10
                                </span>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>