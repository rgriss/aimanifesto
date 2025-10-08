<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Card, Badge, SectionHeading } from '@/components';

defineProps({
    tool: Object,
    relatedTools: Array,
});
</script>

<template>
    <Head :title="`${tool.name} - AI Manifesto`" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Navigation -->
                <div class="mb-6">
                    <Link
                        href="/tools"
                        class="text-info hover:text-info/80 font-semibold transition-colors"
                    >
                        ← Back to all tools
                    </Link>
                </div>

                <!-- Tool Header -->
                <div class="bg-gradient-to-br from-primary via-secondary to-info text-white rounded-lg shadow-lg p-8 mb-8">
                    <div class="flex items-start justify-between mb-6">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">
                                {{ tool.name }}
                            </h1>
                            <Link
                                :href="`/categories/${tool.category.slug}`"
                                class="text-white/90 hover:text-white font-semibold transition-colors"
                            >
                                {{ tool.category.name }}
                            </Link>
                        </div>
                        <Badge v-if="tool.is_featured" variant="warning">
                            Featured
                        </Badge>
                    </div>

                    <p class="text-xl text-white/90 mb-6">
                        {{ tool.description }}
                    </p>

                    <!-- Metadata Row -->
                    <div class="flex flex-wrap gap-6 text-sm mb-6">
                        <div v-if="tool.ryan_rating">
                            <span class="text-white/80">Ryan's Rating:</span>
                            <Badge variant="success" class="ml-2">
                                ⭐ {{ tool.ryan_rating }}/10
                            </Badge>
                        </div>
                        <div>
                            <span class="text-white/80">Pricing:</span>
                            <Badge variant="default" class="ml-2 capitalize">
                                {{ tool.pricing_model }}
                            </Badge>
                        </div>
                        <div v-if="tool.price_description">
                            <span class="text-white/80">Cost:</span>
                            <span class="ml-2 text-white font-semibold">
                                {{ tool.price_description }}
                            </span>
                        </div>
                        <div v-if="tool.views_count">
                            <span class="text-white/80">Views:</span>
                            <span class="ml-2 text-white font-semibold">
                                {{ tool.views_count.toLocaleString() }}
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a
                            :href="tool.website_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-white text-primary hover:bg-white/90 font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Visit Website →
                        </a>
                        <a
                            v-if="tool.documentation_url"
                            :href="tool.documentation_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-white/10 text-white hover:bg-white/20 font-semibold py-3 px-6 rounded-lg transition-colors border border-white/30"
                        >
                            Documentation
                        </a>
                    </div>
                </div>

                <!-- Long Description -->
                <Card v-if="tool.long_description" class="mb-8">
                    <SectionHeading title="About" />
                    <p class="text-foreground whitespace-pre-line">
                        {{ tool.long_description }}
                    </p>
                </Card>

                <!-- Ryan's Notes -->
                <div v-if="tool.ryan_notes" class="bg-gradient-to-br from-success/20 to-info/20 rounded-lg shadow p-8 mb-8 border-2 border-success/30">
                    <SectionHeading title="Ryan's Take" />
                    <p class="text-foreground italic text-lg leading-relaxed">
                        "{{ tool.ryan_notes }}"
                    </p>
                    <p v-if="tool.ryan_last_used" class="text-sm text-muted-foreground mt-4">
                        Last used: {{ new Date(tool.ryan_last_used).toLocaleDateString() }}
                    </p>
                </div>

                <!-- Features, Use Cases, Integrations -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Features -->
                    <Card v-if="tool.features && tool.features.length > 0">
                        <h3 class="text-xl font-bold text-info mb-4">
                            Key Features
                        </h3>
                        <ul class="space-y-2">
                            <li
                                v-for="(feature, index) in tool.features"
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-success mr-2 font-bold">✓</span>
                                <span class="text-foreground">{{ feature }}</span>
                            </li>
                        </ul>
                    </Card>

                    <!-- Use Cases -->
                    <Card v-if="tool.use_cases && tool.use_cases.length > 0">
                        <h3 class="text-xl font-bold text-info mb-4">
                            Use Cases
                        </h3>
                        <ul class="space-y-2">
                            <li
                                v-for="(useCase, index) in tool.use_cases"
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-info mr-2 font-bold">→</span>
                                <span class="text-foreground">{{ useCase }}</span>
                            </li>
                        </ul>
                    </Card>

                    <!-- Integrations -->
                    <Card v-if="tool.integrations && tool.integrations.length > 0">
                        <h3 class="text-xl font-bold text-info mb-4">
                            Integrations
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                v-for="(integration, index) in tool.integrations"
                                :key="index"
                                variant="default"
                            >
                                {{ integration }}
                            </Badge>
                        </div>
                    </Card>
                </div>

                <!-- Related Tools -->
                <div v-if="relatedTools && relatedTools.length > 0">
                    <SectionHeading
                        :title="`More in ${tool.category.name}`"
                        subtitle="Other tools you might find useful"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Link
                            v-for="relatedTool in relatedTools"
                            :key="relatedTool.id"
                            :href="`/tools/${relatedTool.slug}`"
                            class="group"
                        >
                            <Card>
                                <h3 class="text-xl font-bold text-foreground group-hover:text-info transition-colors mb-2">
                                    {{ relatedTool.name }}
                                </h3>
                                <p class="text-muted-foreground mb-4 text-sm">
                                    {{ relatedTool.description }}
                                </p>
                                <Badge v-if="relatedTool.ryan_rating" variant="success" size="sm">
                                    ⭐ {{ relatedTool.ryan_rating }}/10
                                </Badge>
                            </Card>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
