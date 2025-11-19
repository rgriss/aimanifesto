<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Sparkles, Zap, Rocket, Database, Shield, Workflow, Users, Target, Lock } from 'lucide-vue-next';

const learnerLevels = [
    {
        title: 'Beginner',
        subtitle: 'New to AI',
        icon: Sparkles,
        color: 'info',
        description: 'Just starting your AI journey? Perfect! Start with simple, practical uses that don\'t require technical setup.',
        characteristics: [
            'Heard of AI but haven\'t used it much',
            'No technical background needed',
            'Looking for quick, practical wins',
            'Want to understand the basics first',
        ],
        examples: [
            'Recipe generation and meal planning',
            'Writing assistance and editing',
            'Personal productivity tips',
            'Creative brainstorming',
            'Language translation',
        ],
        nextSteps: 'Tools that work in your browser, no account required',
    },
    {
        title: 'Intermediate',
        subtitle: 'Exploring Possibilities',
        icon: Zap,
        color: 'success',
        description: 'You\'ve tried AI and want to dive deeper. Learn advanced techniques and customize AI to your needs.',
        characteristics: [
            'Comfortable with basic AI tools',
            'Want to improve your results',
            'Ready to learn best practices',
            'Interested in customization',
        ],
        examples: [
            'Prompt engineering techniques',
            'Custom GPTs and assistants',
            'Context management strategies',
            'Tool integration workflows',
            'Fine-tuning outputs',
        ],
        nextSteps: 'Advanced features and customization options',
    },
    {
        title: 'Advanced',
        subtitle: 'Power User',
        icon: Rocket,
        color: 'warning',
        description: 'You\'re ready to harness AI\'s full potential. Build custom solutions and integrate AI into your workflow.',
        characteristics: [
            'Strong technical foundation',
            'Want programmatic control',
            'Building AI-powered solutions',
            'Interested in cutting-edge tech',
        ],
        examples: [
            'MCP servers and protocols',
            'Self-hosted LLM models',
            'Agentic development patterns',
            'API integrations',
            'Custom AI workflows',
        ],
        nextSteps: 'Development tools and self-hosted solutions',
    },
];

const specializedTopics = [
    {
        number: '401',
        title: 'RAG Systems & Knowledge Bases',
        icon: Database,
        description: 'Build AI systems that work with your proprietary data. Learn vector databases, embeddings, and retrieval strategies.',
        prerequisites: ['Lesson 301: MCP Servers'],
        color: 'warning',
    },
    {
        number: '451',
        title: 'AI Governance & Compliance',
        icon: Shield,
        description: 'Enterprise-grade AI oversight. Policy frameworks, risk management, audit trails, and regulatory compliance.',
        prerequisites: ['Lesson 201: Prompt Engineering'],
        color: 'info',
    },
    {
        number: '452',
        title: 'Intelligent Process Automation',
        icon: Workflow,
        description: 'Make AI work within business processes. Workflow orchestration, decision automation, and RPA integration.',
        prerequisites: ['Lesson 303: Agentic Development'],
        color: 'success',
    },
    {
        number: '404',
        title: 'Multi-Agent Orchestration',
        icon: Users,
        description: 'Coordinate teams of specialized AI agents. Delegation, communication protocols, and conflict resolution.',
        prerequisites: ['Lesson 303: Agentic Development'],
        color: 'warning',
    },
    {
        number: '405',
        title: 'Evaluation & Testing',
        icon: Target,
        description: 'Quality assurance for AI systems. Benchmarking outputs, regression testing, and measuring accuracy.',
        prerequisites: ['Lesson 202: Context Management'],
        color: 'info',
    },
    {
        number: '502',
        title: 'Security & Privacy',
        icon: Lock,
        description: 'Protect data and systems. PII handling, adversarial attacks, prompt injection prevention, and privacy-preserving AI.',
        prerequisites: ['Lesson 303: Agentic Development'],
        color: 'success',
    },
];

const getColorClasses = (color: string) => {
    const colors = {
        info: 'border-info/20 hover:border-info/40 hover:shadow-info/10',
        success: 'border-success/20 hover:border-success/40 hover:shadow-success/10',
        warning: 'border-warning/20 hover:border-warning/40 hover:shadow-warning/10',
    };
    return colors[color as keyof typeof colors] || colors.info;
};
</script>

<template>
    <Head title="Learn - Choose Your Path" />

    <GuestLayout>
        <div class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Page Header -->
                <div class="text-center mb-12">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold text-foreground mb-4">
                        Start Your AI Journey
                    </h1>
                    <p class="text-lg md:text-xl text-muted-foreground max-w-3xl mx-auto">
                        What type of learner are you? Choose your path to discover AI tools and resources tailored to your experience level.
                    </p>
                </div>

                <!-- Learner Level Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8">
                    <Card
                        v-for="level in learnerLevels"
                        :key="level.title"
                        :class="[
                            'p-6 transition-all duration-300 hover:shadow-lg flex flex-col',
                            getColorClasses(level.color)
                        ]"
                    >
                        <!-- Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h2 class="text-2xl font-bold text-foreground mb-1">
                                    {{ level.title }}
                                </h2>
                                <Badge :variant="level.color" class="inline-block">
                                    {{ level.subtitle }}
                                </Badge>
                            </div>
                            <component
                                :is="level.icon"
                                :class="[
                                    'h-10 w-10 flex-shrink-0',
                                    level.color === 'info' && 'text-info',
                                    level.color === 'success' && 'text-success',
                                    level.color === 'warning' && 'text-warning',
                                ]"
                            />
                        </div>

                        <!-- Description -->
                        <p class="text-muted-foreground mb-6">
                            {{ level.description }}
                        </p>

                        <!-- Characteristics -->
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-foreground mb-3">
                                You might be a good fit if you:
                            </h3>
                            <ul class="space-y-2">
                                <li
                                    v-for="(char, idx) in level.characteristics"
                                    :key="idx"
                                    class="flex items-start gap-2 text-sm text-muted-foreground"
                                >
                                    <span class="text-foreground mt-0.5">•</span>
                                    <span>{{ char }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- Examples -->
                        <div class="mb-6 flex-grow">
                            <h3 class="text-sm font-semibold text-foreground mb-3">
                                You'll learn about:
                            </h3>
                            <ul class="space-y-2">
                                <li
                                    v-for="(example, idx) in level.examples"
                                    :key="idx"
                                    class="flex items-start gap-2 text-sm text-muted-foreground"
                                >
                                    <span class="text-foreground mt-0.5">•</span>
                                    <span>{{ example }}</span>
                                </li>
                            </ul>
                        </div>

                        <!-- CTA Button -->
                        <div class="mt-auto pt-4 border-t border-border">
                            <Link :href="`/learn/${level.title.toLowerCase()}`">
                                <Button
                                    :variant="level.color"
                                    class="w-full"
                                >
                                    Explore {{ level.title }} Path
                                </Button>
                            </Link>
                            <p class="text-xs text-muted-foreground text-center mt-2">
                                {{ level.nextSteps }}
                            </p>
                        </div>
                    </Card>
                </div>

                <!-- Coming Soon Notice -->
                <div class="mt-12 text-center">
                    <Card class="inline-block px-8 py-6 bg-muted/50">
                        <p class="text-sm text-muted-foreground">
                            <span class="font-semibold text-foreground">Coming Soon:</span> Curated learning paths, interactive tutorials, and tool recommendations based on your experience level.
                        </p>
                    </Card>
                </div>

                <!-- Specialized Topics Section -->
                <div class="mt-16">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-foreground mb-3">
                            Specialized Topics
                        </h2>
                        <p class="text-base md:text-lg text-muted-foreground max-w-2xl mx-auto">
                            Ready to specialize? Dive deep into specific domains with 400-500 level courses. Choose topics based on your goals and interests.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Card
                            v-for="topic in specializedTopics"
                            :key="topic.number"
                            :class="[
                                'p-6 transition-all duration-300 hover:shadow-lg flex flex-col',
                                getColorClasses(topic.color)
                            ]"
                        >
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-3">
                                <Badge :variant="topic.color" class="text-sm font-bold px-2 py-1">
                                    {{ topic.number }}
                                </Badge>
                                <component
                                    :is="topic.icon"
                                    :class="[
                                        'h-6 w-6 flex-shrink-0',
                                        topic.color === 'info' && 'text-info',
                                        topic.color === 'success' && 'text-success',
                                        topic.color === 'warning' && 'text-warning',
                                    ]"
                                />
                            </div>

                            <!-- Title -->
                            <h3 class="text-lg font-bold text-foreground mb-2">
                                {{ topic.title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-muted-foreground mb-4 flex-grow">
                                {{ topic.description }}
                            </p>

                            <!-- Prerequisites -->
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-foreground mb-1">
                                    Prerequisites:
                                </p>
                                <ul class="space-y-1">
                                    <li
                                        v-for="(prereq, idx) in topic.prerequisites"
                                        :key="idx"
                                        class="text-xs text-muted-foreground"
                                    >
                                        • {{ prereq }}
                                    </li>
                                </ul>
                            </div>

                            <!-- CTA Button -->
                            <div class="mt-auto">
                                <Button
                                    variant="outline"
                                    class="w-full"
                                    disabled
                                >
                                    Coming Soon
                                </Button>
                            </div>
                        </Card>
                    </div>

                    <!-- Specialized Topics Note -->
                    <div class="mt-8 text-center">
                        <p class="text-sm text-muted-foreground max-w-2xl mx-auto">
                            These specialized courses can be taken in any order based on your needs. Each builds on the core 100-300 level curriculum.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
