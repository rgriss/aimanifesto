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
    return props.tool.company_name || props.tool.popularity_tier || props.tool.momentum_score || props.tool.intelligence;
});

// Helper functions for formatting enum values
const formatCompanyStatus = (status) => {
    const map = {
        private: 'Private',
        public: 'Public',
        acquired: 'Acquired',
        subsidiary: 'Subsidiary',
        open_source: 'Open Source',
    };
    return map[status] || status;
};

const formatMarketPosition = (position) => {
    const map = {
        market_leader: 'Market Leader',
        major_player: 'Major Player',
        challenger: 'Challenger',
        niche_specialist: 'Niche Specialist',
        emerging: 'Emerging',
    };
    return map[position] || position;
};

const formatSentiment = (sentiment) => {
    const map = {
        very_positive: { label: 'Very Positive', icon: '😍', variant: 'success' },
        positive: { label: 'Positive', icon: '😊', variant: 'success' },
        mixed: { label: 'Mixed', icon: '😐', variant: 'default' },
        negative: { label: 'Negative', icon: '😕', variant: 'warning' },
        very_negative: { label: 'Very Negative', icon: '😢', variant: 'danger' },
    };
    return map[sentiment] || null;
};

const formatFundingStage = (stage) => {
    const map = {
        bootstrapped: 'Bootstrapped',
        seed: 'Seed',
        series_a: 'Series A',
        series_b: 'Series B',
        'series_c+': 'Series C+',
        public: 'Public',
        profitable: 'Profitable',
        acquired: 'Acquired',
    };
    return map[stage] || stage;
};

// Check if intelligence data exists and has meaningful content
const hasExtendedIntelligence = computed(() => {
    return props.tool.intelligence && Object.keys(props.tool.intelligence).length > 0;
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
                        <Link
                            v-if="$page.props.auth?.user?.is_admin"
                            :href="`/admin/tools/${tool.slug}/edit`"
                            class="bg-warning text-warning-foreground hover:bg-warning/90 font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            ✏️ Edit Tool
                        </Link>
                    </template>
                </PageHero>

                <!-- Long Description -->
                <Card v-if="tool.long_description" class="mb-8">
                    <SectionHeading title="About" />
                    <p class="text-foreground whitespace-pre-line">
                        {{ tool.long_description }}
                    </p>
                </Card>

                <!-- Screenshot -->
                <Card v-if="tool.screenshot_url" class="mb-8">
                    <SectionHeading title="Screenshot" />
                    <div class="rounded-lg overflow-hidden border-2 border-border">
                        <img
                            :src="tool.screenshot_url"
                            :alt="`Screenshot of ${tool.name}`"
                            class="w-full h-auto"
                            loading="lazy"
                        />
                    </div>
                </Card>

                <!-- Business Intelligence -->
                <div v-if="hasIntelligenceData">
                    <Card class="mb-8 bg-gradient-to-br from-foreground/5 to-foreground/10 border-2 border-foreground/20">
                        <div class="flex justify-between items-start mb-6">
                            <SectionHeading title="Business Intelligence" />
                            <Badge v-if="hasExtendedIntelligence" variant="info" class="text-sm">
                                {{ tool.intelligence.data_completeness_score }}% Complete
                            </Badge>
                        </div>

                        <!-- Phase 1: Basic Intelligence -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
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

                        <!-- Phase 2: Extended Intelligence Data -->
                        <div v-if="hasExtendedIntelligence" class="space-y-8 pt-8 border-t-2 border-foreground/10">
                            <!-- Company Metadata -->
                            <div v-if="tool.intelligence.founded_year || tool.intelligence.company_status || tool.intelligence.headquarters || tool.intelligence.employee_count_range">
                                <h3 class="text-lg font-bold text-foreground mb-4">Company Information</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div v-if="tool.intelligence.founded_year">
                                        <p class="text-sm text-muted-foreground">Founded</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.founded_year }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.tool_launched_year">
                                        <p class="text-sm text-muted-foreground">Tool Launched</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.tool_launched_year }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.company_status">
                                        <p class="text-sm text-muted-foreground">Status</p>
                                        <Badge variant="default">{{ formatCompanyStatus(tool.intelligence.company_status) }}</Badge>
                                    </div>
                                    <div v-if="tool.intelligence.stock_ticker">
                                        <p class="text-sm text-muted-foreground">Stock Ticker</p>
                                        <p class="text-base font-semibold text-foreground">${{ tool.intelligence.stock_ticker }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.parent_company">
                                        <p class="text-sm text-muted-foreground">Parent Company</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.parent_company }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.headquarters">
                                        <p class="text-sm text-muted-foreground">Headquarters</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.headquarters }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.employee_count_range">
                                        <p class="text-sm text-muted-foreground">Employees</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.employee_count_range }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.acquisition_date">
                                        <p class="text-sm text-muted-foreground">Acquired</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.acquisition_date }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Market Position -->
                            <div v-if="tool.intelligence.estimated_users || tool.intelligence.market_position || tool.intelligence.target_market || tool.intelligence.primary_competitors">
                                <h3 class="text-lg font-bold text-foreground mb-4">Market Position</h3>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div v-if="tool.intelligence.estimated_users">
                                            <p class="text-sm text-muted-foreground">Estimated Users</p>
                                            <Badge variant="info" class="text-base">{{ tool.intelligence.estimated_users }}</Badge>
                                        </div>
                                        <div v-if="tool.intelligence.market_position">
                                            <p class="text-sm text-muted-foreground">Market Position</p>
                                            <Badge variant="success" class="text-base">{{ formatMarketPosition(tool.intelligence.market_position) }}</Badge>
                                        </div>
                                    </div>
                                    <div v-if="tool.intelligence.target_market && tool.intelligence.target_market.length > 0">
                                        <p class="text-sm text-muted-foreground mb-2">Target Markets</p>
                                        <div class="flex flex-wrap gap-2">
                                            <Badge v-for="market in tool.intelligence.target_market" :key="market" variant="default">
                                                {{ market.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div v-if="tool.intelligence.primary_competitors && tool.intelligence.primary_competitors.length > 0">
                                        <p class="text-sm text-muted-foreground mb-2">Primary Competitors</p>
                                        <div class="flex flex-wrap gap-2">
                                            <Badge v-for="competitor in tool.intelligence.primary_competitors" :key="competitor" variant="default">
                                                {{ competitor }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Financial Information -->
                            <div v-if="tool.intelligence.funding_stage || tool.intelligence.latest_funding_amount || tool.intelligence.estimated_annual_revenue">
                                <h3 class="text-lg font-bold text-foreground mb-4">Financial</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                    <div v-if="tool.intelligence.funding_stage">
                                        <p class="text-sm text-muted-foreground">Funding Stage</p>
                                        <Badge variant="info" class="text-base">{{ formatFundingStage(tool.intelligence.funding_stage) }}</Badge>
                                    </div>
                                    <div v-if="tool.intelligence.latest_funding_amount">
                                        <p class="text-sm text-muted-foreground">Latest Funding</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.latest_funding_amount }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.latest_funding_date">
                                        <p class="text-sm text-muted-foreground">Funding Date</p>
                                        <p class="text-base font-semibold text-foreground">{{ tool.intelligence.latest_funding_date }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.estimated_annual_revenue">
                                        <p class="text-sm text-muted-foreground">Est. Revenue</p>
                                        <Badge variant="success" class="text-base">{{ tool.intelligence.estimated_annual_revenue }}</Badge>
                                    </div>
                                </div>
                            </div>

                            <!-- Sentiment & Momentum -->
                            <div v-if="tool.intelligence.customer_sentiment || tool.intelligence.sentiment_notes || tool.intelligence.momentum_notes || tool.intelligence.last_major_update">
                                <h3 class="text-lg font-bold text-foreground mb-4">Customer Sentiment & Momentum</h3>
                                <div class="space-y-4">
                                    <div v-if="tool.intelligence.customer_sentiment" class="flex items-center space-x-3">
                                        <span class="text-2xl">{{ formatSentiment(tool.intelligence.customer_sentiment)?.icon }}</span>
                                        <div>
                                            <p class="text-sm text-muted-foreground">Customer Sentiment</p>
                                            <Badge :variant="formatSentiment(tool.intelligence.customer_sentiment)?.variant" class="text-base">
                                                {{ formatSentiment(tool.intelligence.customer_sentiment)?.label }}
                                            </Badge>
                                        </div>
                                    </div>
                                    <div v-if="tool.intelligence.sentiment_notes">
                                        <p class="text-sm text-muted-foreground mb-1">Sentiment Notes</p>
                                        <p class="text-foreground">{{ tool.intelligence.sentiment_notes }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.momentum_notes">
                                        <p class="text-sm text-muted-foreground mb-1">Momentum Analysis</p>
                                        <p class="text-foreground">{{ tool.intelligence.momentum_notes }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.last_major_update">
                                        <p class="text-sm text-muted-foreground mb-1">Last Major Update</p>
                                        <p class="text-foreground">{{ tool.intelligence.last_major_update }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Competitive Intelligence -->
                            <div v-if="tool.intelligence.key_differentiators || tool.intelligence.strengths || tool.intelligence.weaknesses || tool.intelligence.market_threats || tool.intelligence.growth_opportunities">
                                <h3 class="text-lg font-bold text-foreground mb-4">Competitive Intelligence</h3>
                                <div class="space-y-4">
                                    <div v-if="tool.intelligence.key_differentiators && tool.intelligence.key_differentiators.length > 0">
                                        <p class="text-sm text-muted-foreground mb-2">Key Differentiators</p>
                                        <ul class="space-y-1">
                                            <li v-for="item in tool.intelligence.key_differentiators" :key="item" class="flex items-start">
                                                <span class="text-foreground mr-2">✨</span>
                                                <span class="text-foreground">{{ item }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="tool.intelligence.strengths && tool.intelligence.strengths.length > 0">
                                        <p class="text-sm text-muted-foreground mb-2">Strengths</p>
                                        <ul class="space-y-1">
                                            <li v-for="item in tool.intelligence.strengths" :key="item" class="flex items-start">
                                                <span class="text-success mr-2">✓</span>
                                                <span class="text-foreground">{{ item }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="tool.intelligence.weaknesses && tool.intelligence.weaknesses.length > 0">
                                        <p class="text-sm text-muted-foreground mb-2">Weaknesses</p>
                                        <ul class="space-y-1">
                                            <li v-for="item in tool.intelligence.weaknesses" :key="item" class="flex items-start">
                                                <span class="text-warning mr-2">⚠</span>
                                                <span class="text-foreground">{{ item }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div v-if="tool.intelligence.market_threats">
                                        <p class="text-sm text-muted-foreground mb-1">Market Threats</p>
                                        <p class="text-foreground">{{ tool.intelligence.market_threats }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.growth_opportunities">
                                        <p class="text-sm text-muted-foreground mb-1">Growth Opportunities</p>
                                        <p class="text-foreground">{{ tool.intelligence.growth_opportunities }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Analyst Notes -->
                            <div v-if="tool.intelligence.analyst_summary || tool.intelligence.strategic_notes">
                                <h3 class="text-lg font-bold text-foreground mb-4">Analyst Insights</h3>
                                <div class="space-y-4">
                                    <div v-if="tool.intelligence.analyst_summary" class="bg-foreground/5 rounded-lg p-4 border border-foreground/10">
                                        <p class="text-sm text-muted-foreground mb-2">Summary</p>
                                        <p class="text-foreground leading-relaxed">{{ tool.intelligence.analyst_summary }}</p>
                                    </div>
                                    <div v-if="tool.intelligence.strategic_notes" class="bg-foreground/5 rounded-lg p-4 border border-foreground/10">
                                        <p class="text-sm text-muted-foreground mb-2">Strategic Notes</p>
                                        <p class="text-foreground leading-relaxed">{{ tool.intelligence.strategic_notes }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Research Timestamp -->
                            <div v-if="tool.intelligence.last_researched_at" class="text-xs text-muted-foreground pt-4 border-t border-foreground/10">
                                Last researched: {{ new Date(tool.intelligence.last_researched_at).toLocaleDateString() }}
                            </div>
                        </div>
                    </Card>
                </div>

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
