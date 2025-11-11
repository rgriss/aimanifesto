<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHero, Card, Badge, SectionHeading } from '@/components';
import { Building2, TrendingUp, TrendingDown, Activity } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    tool: Object,
    relatedTools: Array,
});

// Helper to get popularity tier display info
const popularityInfo = computed(() => {
    const tier = props.tool.popularity_tier;
    if (!tier) return null;

    const info = {
        mainstream: { label: 'Mainstream', variant: 'success', icon: '🌟', description: 'Household name' },
        well_known: { label: 'Well Known', variant: 'info', icon: '⭐', description: 'Known in industry' },
        growing: { label: 'Growing', variant: 'default', icon: '📈', description: 'Gaining recognition' },
        niche: { label: 'Niche', variant: 'default', icon: '🎯', description: 'Specialized audience' },
        emerging: { label: 'Emerging', variant: 'default', icon: '🌱', description: 'New/unknown' },
    };

    return info[tier] || null;
});

// Helper to get momentum score display info
const momentumInfo = computed(() => {
    const score = props.tool.momentum_score;
    if (!score) return null;

    const info = {
        1: { label: 'Strongly Declining', variant: 'danger', color: 'text-danger', bgColor: 'bg-danger/10' },
        2: { label: 'Declining', variant: 'warning', color: 'text-warning', bgColor: 'bg-warning/10' },
        3: { label: 'Stable', variant: 'default', color: 'text-foreground', bgColor: 'bg-foreground/10' },
        4: { label: 'Growing', variant: 'success', color: 'text-success', bgColor: 'bg-success/10' },
        5: { label: 'Rapidly Growing', variant: 'success', color: 'text-success', bgColor: 'bg-success/10' },
    };

    return info[score] || null;
});

// Show the intelligence section if any field is present
const hasIntelligenceData = computed(() => {
    return props.tool.company_name || props.tool.popularity_tier || props.tool.momentum_score;
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
                        class="text-foreground hover:text-foreground/70 font-semibold transition-colors"
                    >
                        ← Back to all tools
                    </Link>
                </div>

                <!-- Tool Header -->
                <PageHero
                    :title="tool.name"
                    :description="tool.description"
                >
                    <template #subtitle>
                        <Link
                            :href="`/categories/${tool.category.slug}`"
                            class="text-background/80 hover:text-background font-semibold transition-colors"
                        >
                            {{ tool.category.name }}
                        </Link>
                    </template>

                    <template #actions>
                        <Badge v-if="tool.is_featured" variant="warning">
                            Featured
                        </Badge>
                    </template>

                    <template #metadata>
                        <div v-if="tool.ryan_rating">
                            <span class="text-background/80">Ryan's Rating:</span>
                            <Badge variant="success" class="ml-2">
                                ⭐ {{ tool.ryan_rating }}/10
                            </Badge>
                        </div>
                        <div>
                            <span class="text-background/80">Pricing:</span>
                            <Badge variant="default" class="ml-2 capitalize">
                                {{ tool.pricing_model }}
                            </Badge>
                        </div>
                        <div v-if="tool.price_description">
                            <span class="text-background/80">Cost:</span>
                            <span class="ml-2 text-background font-semibold">
                                {{ tool.price_description }}
                            </span>
                        </div>
                        <div v-if="tool.views_count">
                            <span class="text-background/80">Views:</span>
                            <span class="ml-2 text-background font-semibold">
                                {{ tool.views_count.toLocaleString() }}
                            </span>
                        </div>
                        <div v-if="tool.updated_at">
                            <span class="text-background/80">Last Updated:</span>
                            <span class="ml-2 text-background font-semibold">
                                {{ new Date(tool.updated_at).toLocaleDateString() }}
                            </span>
                        </div>
                    </template>

                    <template #buttons>
                        <a
                            :href="tool.website_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-background text-foreground hover:bg-background/90 font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Visit Website →
                        </a>
                        <a
                            v-if="tool.documentation_url"
                            :href="tool.documentation_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="bg-background/10 text-background hover:bg-background/20 font-semibold py-3 px-6 rounded-lg transition-colors border border-background/30"
                        >
                            Documentation
                        </a>
                    </template>
                </PageHero>

                <!-- Long Description -->
                <Card v-if="tool.long_description" class="mb-8">
                    <SectionHeading title="About" />
                    <p class="text-foreground whitespace-pre-line">
                        {{ tool.long_description }}
                    </p>
                </Card>

                <!-- Business Intelligence -->
                <Card v-if="hasIntelligenceData" class="mb-8 bg-gradient-to-br from-foreground/5 to-foreground/10 border-2 border-foreground/20">
                    <SectionHeading title="Business Intelligence" />

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Company -->
                        <div v-if="tool.company_name" class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <Building2 :size="24" class="text-foreground/70" />
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-muted-foreground mb-1">
                                    Company
                                </h4>
                                <p class="text-lg font-bold text-foreground">
                                    {{ tool.company_name }}
                                </p>
                            </div>
                        </div>

                        <!-- Popularity Tier -->
                        <div v-if="popularityInfo" class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1 text-2xl">
                                {{ popularityInfo.icon }}
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-muted-foreground mb-1">
                                    Market Recognition
                                </h4>
                                <Badge :variant="popularityInfo.variant" class="text-base">
                                    {{ popularityInfo.label }}
                                </Badge>
                                <p class="text-xs text-muted-foreground mt-1">
                                    {{ popularityInfo.description }}
                                </p>
                            </div>
                        </div>

                        <!-- Momentum Score -->
                        <div v-if="momentumInfo" class="flex items-start space-x-3">
                            <div class="flex-shrink-0 mt-1">
                                <TrendingUp v-if="tool.momentum_score >= 4" :size="24" :class="momentumInfo.color" />
                                <Activity v-else-if="tool.momentum_score === 3" :size="24" :class="momentumInfo.color" />
                                <TrendingDown v-else :size="24" :class="momentumInfo.color" />
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-muted-foreground mb-1">
                                    Momentum
                                </h4>
                                <Badge :variant="momentumInfo.variant" class="text-base">
                                    {{ momentumInfo.label }}
                                </Badge>
                                <div class="flex items-center space-x-1 mt-2">
                                    <div
                                        v-for="i in 5"
                                        :key="i"
                                        class="h-2 w-8 rounded-full"
                                        :class="i <= tool.momentum_score ? momentumInfo.bgColor : 'bg-foreground/10'"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </Card>

                <!-- Ryan's Notes -->
                <div v-if="tool.ryan_notes" class="bg-foreground/5 rounded-lg shadow p-8 mb-8 border-2 border-foreground/10">
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
                        <h3 class="text-xl font-bold text-foreground mb-4">
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
                        <h3 class="text-xl font-bold text-foreground mb-4">
                            Use Cases
                        </h3>
                        <ul class="space-y-2">
                            <li
                                v-for="(useCase, index) in tool.use_cases"
                                :key="index"
                                class="flex items-start"
                            >
                                <span class="text-foreground/70 mr-2 font-bold">→</span>
                                <span class="text-foreground">{{ useCase }}</span>
                            </li>
                        </ul>
                    </Card>

                    <!-- Integrations -->
                    <Card v-if="tool.integrations && tool.integrations.length > 0">
                        <h3 class="text-xl font-bold text-foreground mb-4">
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
                                <h3 class="text-xl font-bold text-foreground group-hover:text-foreground/70 transition-colors mb-2">
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
