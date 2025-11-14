<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge, HelpWantedSign, SiteGuideModal, FoldedCornerLink } from '@/components';
import { TrendingUp, TrendingDown, Activity, Circle, BookOpen } from 'lucide-vue-next';

defineProps({
    featuredTools: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
    totalToolCount: {
        type: Number,
        default: 0
    },
});

// Get momentum icon and styling based on score
const getMomentumDisplay = (score) => {
    if (!score) {
        return { icon: Circle, color: 'text-muted-foreground/30', title: 'No momentum data' };
    }

    const displays = {
        1: { icon: TrendingDown, color: 'text-danger', title: 'Strongly Declining' },
        2: { icon: TrendingDown, color: 'text-warning', title: 'Declining' },
        3: { icon: Activity, color: 'text-foreground/50', title: 'Stable' },
        4: { icon: TrendingUp, color: 'text-success', title: 'Growing' },
        5: { icon: TrendingUp, color: 'text-success', title: 'Rapidly Growing' },
    };

    return displays[score] || { icon: Circle, color: 'text-muted-foreground/30', title: 'Unknown' };
};

const coreValues = [
    { priority: 'Human judgment', over: 'algorithmic automation' },
    { priority: 'Transparency', over: 'black-box efficiency' },
    { priority: 'Augmentation', over: 'replacement' },
    { priority: 'Ethical responsibility', over: 'unchecked innovation' },
    { priority: 'Long-term trust', over: 'short-term gain' }
];

const guidingPrinciples = [
    { icon: '🎯', title: 'AI is a tool, not a master', description: 'It amplifies human creativity, decision-making, and productivity.' },
    { icon: '⚖️', title: 'Fairness & accountability are non-negotiable', description: 'Measure, mitigate, and disclose risks and bias.' },
    { icon: '🌍', title: 'Opportunity for all scales', description: 'Individuals, SMBs, and enterprises should benefit without widening gaps.' },
    { icon: '🤝', title: 'Human connection matters', description: 'Design to enhance empathy, dignity, and community.' },
    { icon: '🔄', title: 'Iterate responsibly', description: 'Pair innovation with governance, safety, and continuous learning.' },
    { icon: '🔒', title: 'Data stewardship is a duty', description: 'Privacy, consent, security, and minimal collection by default.' },
    { icon: '🤲', title: 'Open collaboration', description: 'Policymakers, businesses, academia, and the public co-create the path.' },
    { icon: '🌱', title: 'Adaptive systems', description: 'Evolve responsibly as contexts and capabilities change.' },
    { icon: '💪', title: 'Expertise and execution are essential', description: 'AI requires skilled implementation, strategy, and sustained effort—not just access to tools.' },
    { icon: '⏱️', title: 'Measured progress over rushed deployment', description: 'Moving beyond demonstrations to sustainable impact takes deliberate progression and patience.' },
    { icon: '👁️', title: 'Clear-eyed realism about challenges', description: 'Acknowledge high failure rates, real difficulties, and learning from the industry\'s setbacks.' },
    { icon: '🌿', title: 'Environmental responsibility', description: 'Consider energy costs and resource impacts in deployment decisions.' }
];

const scopes = [
    { title: 'Personal', description: 'Use AI to learn, create, and decide—own your inputs & outputs.', color: 'info' },
    { title: 'Small Business', description: 'Focus on productivity, customer value, and clear ROI with lightweight governance.', color: 'success' },
    { title: 'Enterprise', description: 'Formal risk management, model lifecycle oversight, and transparent vendor controls.', color: 'warning' },
    { title: 'Society', description: 'Safety, rights, and equitable access as first-order concerns.', color: 'danger' }
];
</script>

<template>
    <Head title="Artificial Intelligence Manifesto - Responsible AI Principles" />

    <GuestLayout>
        <div class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Manifesto Hero -->
                <div class="bg-background border-2 border-border rounded-lg shadow-lg p-6 sm:p-8 mb-8 text-center">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-3 leading-tight flex items-center justify-center gap-3 md:gap-4 text-foreground">
                        <img src="/images/wave-logo-blue.png" alt="The AI Manifesto" class="h-10 w-auto sm:h-12 md:h-14 lg:h-16 flex-shrink-0 dark:hidden" />
                        <img src="/images/wave-logo-white.png" alt="The AI Manifesto" class="h-10 w-auto sm:h-12 md:h-14 lg:h-16 flex-shrink-0 hidden dark:block" />
                        <span>The Artificial Intelligence Manifesto</span>
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl text-muted-foreground mb-3 md:mb-4 px-2">
                        A framework for responsible AI development and deployment
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs sm:text-sm text-muted-foreground">
                        <span class="px-2 sm:px-3 py-1 bg-secondary rounded-full whitespace-nowrap">Version {{ $page.props.manifestoVersion }}</span>
                        <span class="hidden sm:inline">Last updated {{ $page.props.manifestoLastUpdated }}</span>
                        <span class="sm:hidden">{{ new Date($page.props.manifestoLastUpdated).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) }}</span>
                    </div>
                </div>

                <!-- What is The AI Manifesto? -->
                <div class="mb-12 md:mb-16">
                    <Card class="relative bg-background border border-border overflow-hidden">
                        <!-- Folded Corner - Why This Manifesto -->
                        <FoldedCornerLink href="/why" label="Why This Manifesto" :icon="BookOpen" color="info" />

                        <div class="text-center max-w-4xl mx-auto">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-foreground mb-4">
                                What is The AI Manifesto?
                            </h2>
                            <p class="text-base sm:text-lg text-muted-foreground mb-6 leading-relaxed">
                                The AI Manifesto is your trusted guide to navigating the world of artificial intelligence.
                                We believe AI should enhance human capabilities, not replace them. This site helps you discover
                                and understand AI software, apps, and services through our curated directory with honest reviews,
                                alongside principles for using AI responsibly.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-left">
                                <Link href="/tools" class="group block h-full">
                                    <div class="flex items-start gap-3 p-4 rounded-lg border border-border hover:shadow-md transition-all h-full">
                                        <div class="text-3xl flex-shrink-0">🔍</div>
                                        <div class="flex flex-col h-full flex-1">
                                            <h3 class="font-bold text-foreground mb-2 group-hover:text-foreground/70 transition-colors">
                                                Browse AI Software & Apps
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-2 leading-relaxed">
                                                Explore ChatGPT, Claude, Midjourney, and hundreds of other AI applications.
                                                Each one includes our personal review, rating, and pricing details.
                                            </p>
                                            <div class="mt-auto text-right">
                                                <span class="text-sm font-semibold text-foreground group-hover:underline">
                                                    View All AI Tools →
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                                <a href="#core-values" class="group block h-full">
                                    <div class="flex items-start gap-3 p-4 rounded-lg border border-border hover:shadow-md transition-all h-full">
                                        <div class="text-3xl flex-shrink-0">✨</div>
                                        <div class="flex flex-col h-full flex-1">
                                            <h3 class="font-bold text-foreground mb-2 group-hover:text-foreground/70 transition-colors">
                                                Learn Our Principles
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-2 leading-relaxed">
                                                Understand the core values and guidelines for using AI responsibly—prioritizing
                                                transparency, fairness, and human judgment.
                                            </p>
                                            <div class="mt-auto text-right">
                                                <span class="text-sm font-semibold text-foreground group-hover:underline">
                                                    View Core Values →
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                <Link href="/tools" class="group block h-full">
                                    <div class="flex items-start gap-3 p-4 rounded-lg border border-border hover:shadow-md transition-all h-full">
                                        <div class="text-3xl flex-shrink-0">🎯</div>
                                        <div class="flex flex-col h-full flex-1">
                                            <h3 class="font-bold text-foreground mb-2 group-hover:text-foreground/70 transition-colors">
                                                Make Informed Choices
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-2 leading-relaxed">
                                                Find the right AI software for your needs—whether you're writing, coding,
                                                creating images, or analyzing data.
                                            </p>
                                            <div class="mt-auto text-right">
                                                <span class="text-sm font-semibold text-foreground group-hover:underline">
                                                    Explore AI Tools →
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </Link>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Discover AI Tools -->
                <div class="mb-12 md:mb-16">
                    <div class="relative bg-gradient-to-br from-primary/5 via-background to-secondary/5 border-2 border-border rounded-lg p-6 sm:p-8 md:p-10 overflow-hidden">
                        <!-- Help Button - Folded Corner -->
                        <SiteGuideModal />

                        <div class="text-center max-w-4xl mx-auto mb-8">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-foreground mb-4">
                                Discover AI Tools
                            </h2>
                            <p class="text-base sm:text-lg text-muted-foreground leading-relaxed mb-2">
                                Whether you're a <span class="font-semibold text-foreground">beginner</span> exploring AI for the first time,
                                an <span class="font-semibold text-foreground">intermediate user</span> looking to level up, or an
                                <span class="font-semibold text-foreground">expert</span> seeking specialized solutions—we've compiled
                                a curated list of tools to guide you through the next step of your journey.
                            </p>
                            <p class="text-sm sm:text-base text-muted-foreground/80 italic">
                                If you're new here, click on any of these tools to immediately discover something new.
                            </p>
                        </div>

                        <!-- Quick Access Tools Grid -->
                        <div v-if="featuredTools.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 mb-8">
                            <Link
                                v-for="tool in featuredTools.slice(0, 6)"
                                :key="tool.id"
                                :href="`/tools/${tool.slug}`"
                                class="group block h-full"
                            >
                                <div class="h-full bg-background border-2 border-border rounded-lg p-4 hover:shadow-xl hover:border-foreground/20 transition-all flex flex-col">
                                    <div class="flex items-start justify-between mb-3 gap-2">
                                        <div class="flex items-center gap-2 flex-1 min-w-0">
                                            <component
                                                :is="getMomentumDisplay(tool.momentum_score).icon"
                                                :size="18"
                                                :class="getMomentumDisplay(tool.momentum_score).color"
                                                :title="getMomentumDisplay(tool.momentum_score).title"
                                                class="flex-shrink-0"
                                            />
                                            <h3 class="text-lg md:text-xl font-bold text-foreground group-hover:text-foreground/70 transition-colors truncate">
                                                {{ tool.name }}
                                            </h3>
                                        </div>
                                        <Badge v-if="tool.ryan_rating" variant="success" size="sm" class="flex-shrink-0">
                                            ⭐ {{ tool.ryan_rating }}
                                        </Badge>
                                    </div>
                                    <p class="text-sm text-muted-foreground mb-4 leading-relaxed line-clamp-3 flex-grow">
                                        {{ tool.description }}
                                    </p>
                                    <div class="flex items-center justify-between gap-2 mt-auto">
                                        <Badge variant="default" size="sm" class="capitalize">
                                            {{ tool.pricing_model }}
                                        </Badge>
                                        <span class="text-xs font-semibold text-foreground group-hover:underline">
                                            Learn More →
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>

                        <!-- Or Explore by Category -->
                        <div v-if="categories.length > 0" class="mb-8">
                            <div class="text-center mb-6">
                                <p class="text-sm text-muted-foreground/70 italic">or browse by category</p>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 md:gap-4 mb-6">
                                <Link
                                    v-for="category in categories.slice(0, 6)"
                                    :key="category.id"
                                    :href="`/categories/${category.slug}`"
                                    class="group block h-full"
                                >
                                    <div class="h-full bg-background border-2 border-border rounded-lg p-4 hover:shadow-md hover:border-foreground/20 transition-all text-center">
                                        <div class="text-3xl sm:text-4xl mb-2">{{ category.icon }}</div>
                                        <h4 class="text-xs sm:text-sm font-bold text-foreground group-hover:text-foreground/70 transition-colors mb-2 leading-tight">
                                            {{ category.name }}
                                        </h4>
                                        <Badge variant="default" size="sm">
                                            {{ category.active_tools_count }}
                                        </Badge>
                                    </div>
                                </Link>
                            </div>
                            <div class="text-center">
                                <Link
                                    href="/categories"
                                    class="inline-block bg-secondary text-secondary-foreground hover:bg-secondary/90 font-semibold py-2 px-6 rounded-lg transition-colors text-sm"
                                >
                                    View All {{ categories.length }} Categories →
                                </Link>
                            </div>
                        </div>

                        <!-- CTA Button -->
                        <div class="text-center">
                            <Link
                                href="/tools"
                                class="inline-block bg-foreground text-background hover:bg-foreground/90 font-bold py-3 px-8 rounded-lg transition-all text-base sm:text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                            >
                                Browse All {{ totalToolCount }} Tools →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Who is The AI Manifesto for? -->
                <div class="mb-12 md:mb-16">
                    <SectionHeading title="Who is The AI Manifesto for?">
                        <template #subtitle>
                            <p class="text-sm sm:text-base text-muted-foreground leading-relaxed">
                                Whether you're building with AI or leading teams through AI transformation, we're here to help.
                                Our mission is to spread awareness and achieve unity, consistency, and mutual commitment across
                                companies, organizations, and the world. We need your voice.
                            </p>
                        </template>
                    </SectionHeading>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <!-- Developers & Builders -->
                        <Card class="hover:shadow-lg transition-shadow">
                            <div class="flex flex-col h-full">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="text-3xl flex-shrink-0">👨‍💻</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-2">Developers & Builders</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed mb-3">
                                            Find tools to accelerate your workflow, compare AI coding assistants, and discover
                                            APIs that power the next generation of applications. Your insights help others
                                            choose wisely.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4 border-t border-border">
                                    <p class="text-xs font-semibold text-muted-foreground mb-2">Help Wanted:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <Link
                                            href="/tools"
                                            class="inline-block bg-foreground text-background hover:bg-foreground/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Suggest a Tool
                                        </Link>
                                        <a
                                            href="https://github.com/rgriss/aimanifesto"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-block bg-secondary text-secondary-foreground hover:bg-secondary/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Contribute Code
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </Card>

                        <!-- Business Leaders -->
                        <Card class="hover:shadow-lg transition-shadow">
                            <div class="flex flex-col h-full">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="text-3xl flex-shrink-0">💼</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-2">Business Leaders</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed mb-3">
                                            Navigate AI adoption with confidence. Learn which tools deliver ROI, understand
                                            responsible implementation, and make informed decisions that align with your values
                                            and business goals.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4 border-t border-border">
                                    <p class="text-xs font-semibold text-muted-foreground mb-2">Help Wanted:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <a
                                            :href="`mailto:${$page.props.contactEmail}?subject=My AI Story`"
                                            class="inline-block bg-foreground text-background hover:bg-foreground/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Share Your AI Story
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </Card>

                        <!-- AI Beginners -->
                        <Card class="hover:shadow-lg transition-shadow">
                            <div class="flex flex-col h-full">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="text-3xl flex-shrink-0">🌱</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-2">AI Beginners</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed mb-3">
                                            Just getting started? Perfect. We break down the jargon, show you what's actually
                                            useful, and help you take your first steps with AI tools that won't overwhelm you.
                                            Start simple, learn fast.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4 border-t border-border">
                                    <p class="text-xs font-semibold text-muted-foreground mb-2">Get Started:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <Link
                                            href="/tools"
                                            class="inline-block bg-foreground text-background hover:bg-foreground/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Browse Beginner-Friendly Tools
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </Card>

                        <!-- AI Experts -->
                        <Card class="hover:shadow-lg transition-shadow">
                            <div class="flex flex-col h-full">
                                <div class="flex items-start gap-3 mb-4">
                                    <div class="text-3xl flex-shrink-0">🚀</div>
                                    <div class="flex-1">
                                        <h3 class="text-lg md:text-xl font-bold text-foreground mb-2">AI Experts</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed mb-3">
                                            You've mastered the tools. Now help others. Share your deep knowledge, review
                                            emerging platforms, and contribute to building responsible AI practices that
                                            scale across the industry.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-auto pt-4 border-t border-border">
                                    <p class="text-xs font-semibold text-muted-foreground mb-2">Help Wanted:</p>
                                    <div class="flex flex-wrap gap-2">
                                        <Link
                                            href="/tools"
                                            class="inline-block bg-foreground text-background hover:bg-foreground/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Review & Rate Tools
                                        </Link>
                                        <a
                                            href="https://github.com/rgriss/aimanifesto"
                                            target="_blank"
                                            rel="noopener noreferrer"
                                            class="inline-block bg-secondary text-secondary-foreground hover:bg-secondary/90 font-semibold py-2 px-4 rounded-lg transition-colors text-xs sm:text-sm"
                                        >
                                            Join the Project
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </div>
                </div>

                <!-- Core Values -->
                <div id="core-values" class="mb-12 md:mb-16">
                    <SectionHeading title="Core Values">
                        <template #subtitle>
                            <p class="text-sm sm:text-base text-muted-foreground leading-relaxed">
                                Following the structure of the
                                <a href="https://agilemanifesto.org/" target="_blank" rel="noopener noreferrer" class="text-foreground underline hover:text-foreground/70 font-medium">Agile Manifesto</a>,
                                we value the items on the left more than those on the right.
                                While there is value in the items on the right, we value the items on the left more.
                            </p>
                        </template>
                    </SectionHeading>
                    <Card class="bg-background border border-border">
                        <div class="space-y-4 md:space-y-5 text-center">
                            <div
                                v-for="(value, index) in coreValues"
                                :key="index"
                                class="text-base sm:text-lg md:text-xl leading-relaxed"
                            >
                                <span class="font-bold text-foreground text-lg sm:text-xl md:text-2xl">{{ value.priority }}</span>
                                <span class="text-muted-foreground mx-2 sm:mx-3">over</span>
                                <span class="text-foreground/70">{{ value.over }}</span>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Guiding Principles -->
                <div class="mb-12 md:mb-16">
                    <SectionHeading title="Guiding Principles for AI Implementation">
                        <template #subtitle>
                            <p class="text-sm sm:text-base text-muted-foreground leading-relaxed mb-3">
                                These twelve principles translate our core values into actionable guidance. They address the real-world challenges
                                of using AI responsibly—from protecting privacy and ensuring fairness to acknowledging limitations and measuring
                                true progress.
                            </p>
                            <p class="text-sm sm:text-base text-muted-foreground leading-relaxed">
                                Whether you're an individual exploring AI tools, a business evaluating AI solutions, or an organization developing
                                AI systems, these principles provide a practical framework for making informed, ethical decisions that keep
                                human judgment and accountability at the center.
                            </p>
                        </template>
                    </SectionHeading>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                        <Card
                            v-for="(principle, index) in guidingPrinciples"
                            :key="index"
                            class="hover:shadow-lg transition-shadow relative text-center p-6 sm:p-8"
                        >
                            <!-- Subtle Number - Top Left Corner -->
                            <span class="absolute top-4 left-4 text-sm text-muted-foreground/40">
                                {{ index + 1 }}
                            </span>

                            <!-- Centered Content -->
                            <div class="flex flex-col items-center space-y-3">
                                <div class="text-2xl sm:text-3xl mb-3">{{ principle.icon }}</div>
                                <h3 class="text-base md:text-lg font-bold text-foreground leading-tight">{{ principle.title }}</h3>
                                <p class="text-xs sm:text-sm text-muted-foreground leading-relaxed" style="line-height: 1.6;">{{ principle.description }}</p>
                            </div>
                        </Card>
                    </div>
                </div>

                <!-- Scopes of Application -->
                <div class="mb-12 md:mb-16">
                    <SectionHeading
                        title="Scopes of Application"
                        subtitle="AI for everyone, adapted to your context"
                    />
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <Card
                            v-for="(scope, index) in scopes"
                            :key="index"
                        >
                            <Badge :variant="scope.color" class="mb-3">{{ scope.title }}</Badge>
                            <p class="text-xs sm:text-sm text-foreground leading-relaxed">{{ scope.description }}</p>
                        </Card>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="border-t-2 border-border mt-12 md:mt-16 pt-8 md:pt-12">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                        <!-- About Section -->
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-4">About</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link
                                        href="/why"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        Why This Manifesto
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/brand"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        Brand Guidelines
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Resources Section -->
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-4">Resources</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link
                                        href="/tools"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        AI Tools Directory
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/categories"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        Browse Categories
                                    </Link>
                                </li>
                            </ul>
                        </div>

                        <!-- Developer Section -->
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-4">Developer</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link
                                        href="/developer/tool-schema"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        Tool Schema
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/docs"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        Documentation
                                    </Link>
                                </li>
                                <li>
                                    <a
                                        href="https://github.com/rgriss/aimanifesto"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-muted-foreground hover:text-foreground transition-colors"
                                    >
                                        GitHub →
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Manifesto Section -->
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-4">The Manifesto</h3>
                            <p class="text-sm text-muted-foreground leading-relaxed">
                                A framework for responsible AI development that prioritizes human judgment,
                                transparency, and ethical accountability.
                            </p>
                        </div>
                    </div>

                    <!-- Help Wanted Sign -->
                    <div class="flex justify-center mb-8">
                        <div class="w-full max-w-xs md:max-w-sm">
                            <HelpWantedSign />
                        </div>
                    </div>

                    <!-- Copyright -->
                    <div class="text-center pt-8 border-t border-border">
                        <p class="text-sm text-muted-foreground">
                            © {{ new Date().getFullYear() }} The AI Manifesto. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>