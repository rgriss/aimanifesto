<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge } from '@/components';

const audiences = [
    {
        icon: '👨‍💻',
        title: 'For Practitioners',
        subtitle: 'Developers, CTOs, Product Leaders',
        description: 'In the rush to ship AI features, we risk building on sand. This manifesto provides shared language and principles that separate serious AI implementation from theater.',
        commitments: [
            { label: 'Quality over hype', detail: 'You build AI systems that actually work, not just demo well' },
            { label: 'Measurable value', detail: 'You can prove ROI, not just claim it' },
            { label: 'Professional standards', detail: 'You hold yourself accountable to principles, not just deadlines' }
        ],
        benefit: 'A badge of credibility. When clients, employers, or colleagues see you\'re a signatory, they know you\'re committed to doing AI right. You can point to these principles in proposals, on your LinkedIn, in client conversations. You helped shape the standard.',
        color: 'info'
    },
    {
        icon: '🏢',
        title: 'For Organizations',
        subtitle: 'Companies, Teams, Clients',
        description: 'Every company is being sold AI solutions. Most can\'t tell the difference between transformative tools and expensive vaporware. This manifesto gives you a framework to evaluate AI initiatives and partners.',
        commitments: [
            { label: 'Transparency', detail: 'No black boxes or "trust us, it\'s AI"' },
            { label: 'Practical implementation', detail: 'Real productivity gains, not science projects' },
            { label: 'Honest assessment', detail: 'They\'ll tell you when AI isn\'t the answer' }
        ],
        benefit: 'Reduced risk. Better outcomes. Partners who care about results, not just selling you the latest model.',
        color: 'success'
    },
    {
        icon: '🌍',
        title: 'For the Broader Community',
        subtitle: 'The AI Ecosystem',
        description: 'We\'re at an inflection point. AI can genuinely transform how we work—or it can become the next blockchain: overhyped, under-delivered, leaving cynicism in its wake.',
        commitments: [
            { label: 'Raise the bar', detail: 'Make "AI-enhanced" mean something real' },
            { label: 'Reduce slop', detail: 'Less AI-generated junk clogging the internet' },
            { label: 'Build trust', detail: 'Show the world that AI practitioners take quality seriously' },
            { label: 'Shape the future', detail: 'Define what responsible AI implementation looks like' }
        ],
        benefit: 'A better AI ecosystem. Higher quality tools. More trust. Less noise. When clients get burned by AI theater, it hurts all of us. When AI delivers real value, everyone wins.',
        color: 'warning'
    }
];

const howItWorks = [
    { step: '1', title: 'Sign the manifesto', description: 'Declare your commitment to these principles.' },
    { step: '2', title: 'Contribute to future iterations', description: 'This is a living document, shaped by practitioners actually doing the work.' },
    { step: '3', title: 'Display your commitment', description: 'Use it on your website, LinkedIn, proposals. Differentiate yourself from the hype merchants.' },
    { step: '4', title: 'Hold each other accountable', description: 'These aren\'t just words—they\'re standards we\'re building together.' }
];
</script>

<template>
    <Head title="Why This Manifesto Matters - AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <PageHeader
                    title="Why This Manifesto Matters"
                    description="Understanding the need for responsible AI principles"
                    :gradient="true"
                />

                <!-- Audience Sections -->
                <div class="space-y-8 md:space-y-12 mb-12 md:mb-16">
                    <Card
                        v-for="(audience, index) in audiences"
                        :key="index"
                        class="border-l-4"
                        :class="{
                            'border-info': audience.color === 'info',
                            'border-success': audience.color === 'success',
                            'border-warning': audience.color === 'warning'
                        }"
                    >
                        <div class="flex items-start gap-4 mb-4">
                            <div class="text-4xl sm:text-5xl">{{ audience.icon }}</div>
                            <div class="flex-1">
                                <h2 class="text-2xl sm:text-3xl font-bold text-foreground mb-1">
                                    {{ audience.title }}
                                </h2>
                                <p class="text-sm sm:text-base text-muted-foreground italic">
                                    {{ audience.subtitle }}
                                </p>
                            </div>
                        </div>

                        <p class="text-base sm:text-lg text-foreground mb-6 leading-relaxed">
                            {{ audience.description }}
                        </p>

                        <div class="bg-secondary rounded-lg p-4 sm:p-6 mb-6 border-2 border-border">
                            <h3 class="text-lg font-bold text-foreground mb-4">When you sign this manifesto, you're declaring:</h3>
                            <ul class="space-y-3">
                                <li
                                    v-for="(commitment, idx) in audience.commitments"
                                    :key="idx"
                                    class="flex flex-col sm:flex-row sm:items-start gap-2"
                                >
                                    <Badge :variant="audience.color" size="sm" class="self-start flex-shrink-0">
                                        {{ commitment.label }}
                                    </Badge>
                                    <span class="text-sm text-foreground/80 pl-0 sm:pl-2">
                                        {{ commitment.detail }}
                                    </span>
                                </li>
                            </ul>
                        </div>

                        <div class="bg-foreground/5 rounded-lg p-4 sm:p-6 border border-border">
                            <h3 class="text-base font-bold text-foreground mb-2">The benefit to you:</h3>
                            <p class="text-sm sm:text-base text-foreground leading-relaxed">
                                {{ audience.benefit }}
                            </p>
                        </div>
                    </Card>
                </div>

                <!-- How This Works -->
                <div class="mb-12">
                    <SectionHeading
                        title="How This Works"
                        subtitle="Join the movement for responsible AI"
                    />

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <Card
                            v-for="item in howItWorks"
                            :key="item.step"
                            class="text-center hover:shadow-lg transition-shadow"
                        >
                            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-foreground text-background rounded-full flex items-center justify-center text-xl sm:text-2xl font-bold mb-3 sm:mb-4 mx-auto">
                                {{ item.step }}
                            </div>
                            <h3 class="text-base sm:text-lg font-bold text-foreground mb-2">{{ item.title }}</h3>
                            <p class="text-xs sm:text-sm text-muted-foreground leading-relaxed">{{ item.description }}</p>
                        </Card>
                    </div>
                </div>

                <!-- CTA -->
                <Card class="bg-foreground text-background text-center border-2 border-foreground">
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4">Ready to Make a Commitment?</h2>
                    <p class="text-base sm:text-lg md:text-xl text-background/90 mb-6 sm:mb-8">
                        Join practitioners who are building AI the right way
                    </p>
                    <Link
                        href="/"
                        class="inline-block bg-background text-foreground hover:bg-background/90 font-semibold py-3 px-8 rounded-lg transition-colors text-sm sm:text-base border-2 border-background"
                    >
                        Read the Manifesto →
                    </Link>
                </Card>
            </div>
        </div>
    </GuestLayout>
</template>
