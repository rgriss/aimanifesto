<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge } from '@/components';

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
    { icon: '🌱', title: 'Adaptive systems', description: 'Evolve responsibly as contexts and capabilities change.' }
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
                <div class="bg-gradient-to-br from-primary via-secondary to-info text-white rounded-lg shadow-xl p-6 sm:p-8 mb-8 text-center">
                    <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-bold mb-2 md:mb-3 leading-tight flex items-center justify-center gap-3 md:gap-4">
                        <img src="/images/Polaris Pixels Star Logo.png" alt="Polaris Pixels" class="h-10 w-auto sm:h-12 md:h-14 lg:h-16 flex-shrink-0" />
                        <span>Artificial Intelligence Manifesto</span>
                    </h1>
                    <p class="text-sm sm:text-base md:text-lg lg:text-xl text-white/90 mb-3 md:mb-4 px-2">
                        A framework for responsible AI development and deployment
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs sm:text-sm text-white/80">
                        <span class="px-2 sm:px-3 py-1 bg-white/20 rounded-full whitespace-nowrap">Draft 0.2</span>
                        <span class="hidden sm:inline">Last updated October 10, 2025</span>
                        <span class="sm:hidden">Oct 10, 2025</span>
                    </div>
                </div>

                <!-- What is AI Manifesto? -->
                <div class="mb-12 md:mb-16">
                    <Card class="bg-gradient-to-br from-primary/5 to-secondary/5 border-2 border-primary/20">
                        <div class="text-center max-w-4xl mx-auto">
                            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-foreground mb-4">
                                What is AI Manifesto?
                            </h2>
                            <p class="text-base sm:text-lg text-muted-foreground mb-6 leading-relaxed">
                                AI Manifesto is your trusted guide to navigating the world of artificial intelligence.
                                We believe AI should enhance human capabilities, not replace them. This site helps you discover
                                and understand AI software, apps, and services through our curated directory with honest reviews,
                                alongside principles for using AI responsibly.
                            </p>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6 text-left">
                                <Link href="/tools" class="group block">
                                    <div class="flex items-start gap-3 p-4 rounded-lg border border-border hover:shadow-md transition-all">
                                        <div class="text-3xl flex-shrink-0">🔍</div>
                                        <div>
                                            <h3 class="font-bold text-foreground mb-2 group-hover:text-info transition-colors">
                                                Browse AI Software & Apps
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-2 leading-relaxed">
                                                Explore ChatGPT, Claude, Midjourney, and hundreds of other AI applications.
                                                Each one includes our personal review, rating, and pricing details.
                                            </p>
                                            <span class="text-sm font-semibold text-info group-hover:underline">
                                                View All AI Tools →
                                            </span>
                                        </div>
                                    </div>
                                </Link>
                                <div class="flex items-start gap-3 p-4">
                                    <div class="text-3xl flex-shrink-0">✨</div>
                                    <div>
                                        <h3 class="font-bold text-foreground mb-2">Learn Our Principles</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">
                                            Understand the core values and guidelines for using AI responsibly—prioritizing
                                            transparency, fairness, and human judgment.
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3 p-4">
                                    <div class="text-3xl flex-shrink-0">🎯</div>
                                    <div>
                                        <h3 class="font-bold text-foreground mb-2">Make Informed Choices</h3>
                                        <p class="text-sm text-muted-foreground leading-relaxed">
                                            Find the right AI software for your needs—whether you're writing, coding,
                                            creating images, or analyzing data.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Core Values -->
                <div class="mb-12 md:mb-16">
                    <SectionHeading
                        title="Core Values"
                        subtitle="Inspired by the Agile Manifesto"
                    />
                    <Card class="bg-gradient-to-br from-info/5 to-success/5 border-2 border-info/20">
                        <div class="mb-4 pb-4 border-b border-border">
                            <p class="text-sm sm:text-base text-muted-foreground leading-relaxed">
                                Following the structure of the
                                <a href="https://agilemanifesto.org/" target="_blank" rel="noopener noreferrer" class="text-info hover:underline font-medium">Agile Manifesto</a>,
                                we value the items on the left more than those on the right.
                                This is not to say that the items on the right have no value—they do.
                                However, we prioritize the items on the left as more important for responsible AI development.
                            </p>
                        </div>
                        <div class="space-y-3 md:space-y-4">
                            <div
                                v-for="(value, index) in coreValues"
                                :key="index"
                                class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4 text-sm sm:text-base md:text-lg"
                            >
                                <div class="flex items-center gap-2 sm:gap-3">
                                    <span class="text-info font-bold text-xl sm:text-2xl flex-shrink-0">→</span>
                                    <span class="font-bold text-info">{{ value.priority }}</span>
                                </div>
                                <div class="flex items-center gap-2 sm:gap-3 pl-7 sm:pl-0">
                                    <span class="text-muted-foreground text-xs sm:text-sm">over</span>
                                    <span class="text-foreground/70">{{ value.over }}</span>
                                </div>
                            </div>
                        </div>
                    </Card>
                </div>

                <!-- Guiding Principles -->
                <div class="mb-12 md:mb-16">
                    <SectionHeading
                        title="Guiding Principles"
                        subtitle="Practical guidelines for implementing our core values"
                    />
                    <Card class="mb-6 bg-gradient-to-br from-secondary/5 to-warning/5 border-2 border-secondary/20">
                        <p class="text-sm sm:text-base text-muted-foreground leading-relaxed">
                            These principles translate our core values into actionable guidelines.
                            They serve as practical checkpoints when developing, deploying, or evaluating AI systems.
                            Use them to ask: "Does this AI implementation respect human agency? Is it transparent?
                            Does it enhance rather than replace human judgment?"
                        </p>
                    </Card>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <Card
                            v-for="(principle, index) in guidingPrinciples"
                            :key="index"
                            class="hover:shadow-lg transition-shadow"
                        >
                            <div class="text-3xl sm:text-4xl mb-2 md:mb-3">{{ principle.icon }}</div>
                            <h3 class="text-base md:text-lg font-bold text-info mb-2 leading-tight">{{ principle.title }}</h3>
                            <p class="text-xs sm:text-sm text-muted-foreground leading-relaxed">{{ principle.description }}</p>
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

                <!-- Divider -->
                <div class="border-t-2 border-border my-12 md:my-16"></div>

                <!-- Supporting Content: Tools & Categories -->
                <div class="mb-8 md:mb-12">
                    <SectionHeading
                        title="Curated AI Tools"
                        subtitle="Tested and reviewed tools that align with our principles"
                    />

                    <!-- Featured Tools Preview -->
                    <div v-if="featuredTools.length > 0" class="mb-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                            <Link
                                v-for="tool in featuredTools.slice(0, 3)"
                                :key="tool.id"
                                :href="`/tools/${tool.slug}`"
                                class="group"
                            >
                                <Card>
                                    <div class="flex items-start justify-between mb-2 gap-2">
                                        <h3 class="text-base md:text-lg font-bold text-foreground group-hover:text-info transition-colors leading-tight">
                                            {{ tool.name }}
                                        </h3>
                                        <Badge v-if="tool.ryan_rating" variant="success" size="sm" class="flex-shrink-0">
                                            ⭐ {{ tool.ryan_rating }}/10
                                        </Badge>
                                    </div>
                                    <p class="text-xs sm:text-sm text-muted-foreground mb-3 leading-relaxed">
                                        {{ tool.description }}
                                    </p>
                                    <Badge variant="default" size="sm" class="capitalize">
                                        {{ tool.pricing_model }}
                                    </Badge>
                                </Card>
                            </Link>
                        </div>
                        <div class="text-center mt-6">
                            <Link
                                href="/tools"
                                class="inline-block bg-info text-info-foreground hover:bg-info/90 font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg transition-colors text-sm sm:text-base"
                            >
                                Browse All Tools →
                            </Link>
                        </div>
                    </div>

                    <!-- Categories Preview -->
                    <div v-if="categories.length > 0" class="mt-8 md:mt-12">
                        <h3 class="text-xl md:text-2xl font-bold text-foreground mb-4 md:mb-6">Explore by Category</h3>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 md:gap-4">
                            <Link
                                v-for="category in categories.slice(0, 6)"
                                :key="category.id"
                                :href="`/categories/${category.slug}`"
                                class="group"
                            >
                                <Card class="text-center hover:shadow-lg transition-shadow">
                                    <div class="text-3xl sm:text-4xl mb-2">{{ category.icon }}</div>
                                    <h4 class="text-xs sm:text-sm font-bold text-foreground group-hover:text-info transition-colors mb-1 leading-tight px-1">
                                        {{ category.name }}
                                    </h4>
                                    <Badge variant="default" size="sm">
                                        {{ category.active_tools_count }}
                                    </Badge>
                                </Card>
                            </Link>
                        </div>
                        <div class="text-center mt-6">
                            <Link
                                href="/categories"
                                class="inline-block bg-secondary text-secondary-foreground hover:bg-secondary/90 font-semibold py-2 sm:py-3 px-6 sm:px-8 rounded-lg transition-colors text-sm sm:text-base"
                            >
                                View All Categories →
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Footer Navigation -->
                <div class="border-t-2 border-border mt-12 md:mt-16 pt-8 md:pt-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <!-- About Section -->
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-4">About</h3>
                            <ul class="space-y-2">
                                <li>
                                    <Link
                                        href="/why"
                                        class="text-muted-foreground hover:text-info transition-colors"
                                    >
                                        Why This Manifesto
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/brand"
                                        class="text-muted-foreground hover:text-info transition-colors"
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
                                        class="text-muted-foreground hover:text-info transition-colors"
                                    >
                                        AI Tools Directory
                                    </Link>
                                </li>
                                <li>
                                    <Link
                                        href="/categories"
                                        class="text-muted-foreground hover:text-info transition-colors"
                                    >
                                        Browse Categories
                                    </Link>
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

                    <!-- Copyright -->
                    <div class="text-center pt-8 border-t border-border">
                        <p class="text-sm text-muted-foreground">
                            © {{ new Date().getFullYear() }} AI Manifesto. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>