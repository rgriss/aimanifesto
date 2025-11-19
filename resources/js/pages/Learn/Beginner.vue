<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Alert, AlertDescription } from '@/components/ui/alert';
import {
    Sparkles,
    BookOpen,
    CheckCircle2,
    ExternalLink,
    Lightbulb,
    Target,
    GraduationCap
} from 'lucide-vue-next';

const aiTools = [
    {
        name: 'ChatGPT',
        url: 'https://chat.openai.com',
        description: 'Most popular and accessible',
        recommended: true,
    },
    {
        name: 'Claude',
        url: 'https://claude.ai',
        description: 'Great for conversation',
    },
    {
        name: 'Gemini',
        url: 'https://gemini.google.com',
        description: 'Google\'s AI assistant',
    },
    {
        name: 'Microsoft Copilot',
        url: 'https://copilot.microsoft.com',
        description: 'Integrated with Microsoft',
    },
];

const lessons = [
    {
        number: '101',
        title: 'Understanding AI Conversations',
        description: 'Learn how to talk to AI like you would to a helpful friend. Understand what AI can and can\'t do, and how to set realistic expectations.',
        topics: [
            'What is a prompt?',
            'How AI understands you',
            'Common misconceptions',
            'Setting expectations'
        ],
        duration: '15 min',
        comingSoon: true,
    },
    {
        number: '102',
        title: 'Getting Better Responses',
        description: 'Simple tips to improve the quality of answers you get. Learn to be specific, provide context, and ask follow-up questions.',
        topics: [
            'Being specific vs. vague',
            'Providing helpful context',
            'Asking follow-up questions',
            'When to start over'
        ],
        duration: '20 min',
        comingSoon: true,
    },
    {
        number: '103',
        title: 'Practical Everyday Uses',
        description: 'Discover real-world ways to use AI in your daily life, from planning meals to writing emails to learning new topics.',
        topics: [
            'Meal planning & recipes',
            'Email and message writing',
            'Learning new concepts',
            'Brainstorming ideas'
        ],
        duration: '25 min',
        comingSoon: true,
    },
];

const exercises = [
    {
        title: 'Exercise 1: Your First Conversation',
        description: 'Have a simple conversation with an AI assistant. Ask it about a hobby you\'re interested in or a topic you want to learn about.',
        goal: 'Get comfortable having a back-and-forth conversation',
        tasks: [
            'Pick a topic you\'re curious about',
            'Ask an open-ended question',
            'Ask at least 3 follow-up questions',
            'Notice how the AI builds on previous answers'
        ],
        timeEstimate: '10-15 minutes',
        difficulty: 'Easy',
    },
    {
        title: 'Exercise 2: Recipe Generator',
        description: 'Use AI to create a recipe based on ingredients you have at home. Learn how to provide context and refine results.',
        goal: 'Practice giving context and making adjustments',
        tasks: [
            'List 5-7 ingredients you have',
            'Ask for a recipe suggestion',
            'Request modifications (dietary preferences, cooking time)',
            'Have it explain any cooking terms you don\'t know'
        ],
        timeEstimate: '15-20 minutes',
        difficulty: 'Easy',
    },
    {
        title: 'Exercise 3: Email Helper',
        description: 'Draft a professional email with AI assistance. Learn how to set the tone and make revisions.',
        goal: 'Use AI as a writing assistant',
        tasks: [
            'Describe the email you need to write',
            'Specify the tone (formal, casual, friendly)',
            'Review the draft and ask for changes',
            'Learn how to make it sound more like you'
        ],
        timeEstimate: '15-20 minutes',
        difficulty: 'Easy',
    },
];
</script>

<template>
    <Head title="Beginner Path - Learn AI" />

    <GuestLayout>
        <div class="py-8 md:py-12">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <Link
                        href="/learn"
                        class="inline-flex items-center text-sm text-muted-foreground hover:text-foreground mb-4"
                    >
                        ← Back to Learn
                    </Link>
                    <div class="flex items-center gap-3 mb-3">
                        <Sparkles class="h-8 w-8 text-info" />
                        <h1 class="text-3xl sm:text-4xl font-bold text-foreground">
                            Beginner Path
                        </h1>
                    </div>
                    <p class="text-lg text-muted-foreground">
                        Welcome! Let's start your AI journey together.
                    </p>
                </div>

                <!-- Opening Section -->
                <section class="mb-12">
                    <Card class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <GraduationCap class="h-6 w-6 text-info" />
                            Getting Started
                        </h2>

                        <div class="prose prose-gray dark:prose-invert max-w-none space-y-4 text-foreground/90">
                            <p>
                                Hey there! I'm excited to help you get started with AI. Think of this like learning to use a new tool. It might feel a bit unfamiliar at first, but you'll get the hang of it quickly.
                            </p>

                            <p>
                                <strong>I generally recommend starting with ChatGPT</strong> because their goal is to make their product very accessible for a lot of people. That said, the other tools are fantastic as well. They're all very, very similar, just like all soda is similar, but you probably have your own favorite.
                            </p>

                            <p>
                                <strong>For the following exercises, it doesn't matter which system you use.</strong> Here are buttons that will take you to any of them:
                            </p>

                            <!-- AI Tool Buttons -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 my-6 not-prose">
                                <a
                                    v-for="tool in aiTools"
                                    :key="tool.name"
                                    :href="tool.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="relative"
                                >
                                    <Button
                                        :variant="tool.recommended ? 'default' : 'outline'"
                                        class="w-full h-auto py-3 px-4 flex items-center justify-between gap-2"
                                    >
                                        <div class="flex flex-col items-start gap-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold">{{ tool.name }}</span>
                                                <Badge v-if="tool.recommended" variant="info" class="text-xs">
                                                    Recommended
                                                </Badge>
                                            </div>
                                            <span class="text-xs text-muted-foreground">
                                                {{ tool.description }}
                                            </span>
                                        </div>
                                        <ExternalLink class="h-4 w-4 flex-shrink-0" />
                                    </Button>
                                </a>
                            </div>

                            <p class="text-sm text-muted-foreground">
                                <strong>Note:</strong> You may or may not need to create an account. You can get started with any of these for free.
                            </p>

                            <Alert class="my-6">
                                <Lightbulb class="h-4 w-4" />
                                <AlertDescription class="text-sm leading-relaxed">
                                    <p class="mb-0"><strong>About paying for AI:</strong> There are significant benefits to paying for the models later. Once you get to a certain point, you'll start to find yourself frustrated with time limits or the window running out of context. That all goes away once you pay, but you don't have to pay to get started. You'll know when it's time to graduate.</p>
                                </AlertDescription>
                            </Alert>

                            <p>
                                If you start to get to that point and find yourself regularly frustrated by context limits, it's time to pay for it. I don't know anything else about your life, but if you have a cell phone, Internet access, a cable bill, or an electric bill, <strong>AI is becoming ubiquitous enough that it is like a utility.</strong>
                            </p>

                            <p>
                                I personally maintain the most expensive subscription with OpenAI, Claude, and Gemini. I use all three for different things. I consider myself a power user, and again, <strong>you don't need to do this on day one</strong>, but don't let the cost stand in the way when the time is right.
                            </p>
                        </div>
                    </Card>
                </section>

                <!-- Lessons Section -->
                <section class="mb-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-foreground mb-2 flex items-center gap-2">
                            <BookOpen class="h-6 w-6 text-info" />
                            Lessons
                        </h2>
                        <p class="text-muted-foreground">
                            Core concepts to help you understand and use AI effectively
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card
                            v-for="lesson in lessons"
                            :key="lesson.number"
                            class="p-6 flex flex-col hover:border-info/40 transition-colors"
                        >
                            <!-- Lesson Number Badge -->
                            <div class="mb-4">
                                <Badge variant="info" class="text-lg font-bold px-3 py-1">
                                    {{ lesson.number }}
                                </Badge>
                            </div>

                            <!-- Title -->
                            <h3 class="text-xl font-bold text-foreground mb-2">
                                {{ lesson.title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-sm text-muted-foreground mb-4 flex-grow">
                                {{ lesson.description }}
                            </p>

                            <!-- Topics -->
                            <div class="mb-4">
                                <p class="text-xs font-semibold text-foreground mb-2">
                                    Topics covered:
                                </p>
                                <ul class="space-y-1">
                                    <li
                                        v-for="(topic, idx) in lesson.topics"
                                        :key="idx"
                                        class="flex items-start gap-2 text-xs text-muted-foreground"
                                    >
                                        <CheckCircle2 class="h-3 w-3 text-info mt-0.5 flex-shrink-0" />
                                        <span>{{ topic }}</span>
                                    </li>
                                </ul>
                            </div>

                            <!-- Footer -->
                            <div class="mt-auto pt-4 border-t border-border">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs text-muted-foreground">
                                        {{ lesson.duration }}
                                    </span>
                                </div>
                                <Button
                                    v-if="lesson.comingSoon"
                                    variant="outline"
                                    class="w-full"
                                    disabled
                                >
                                    Coming Soon
                                </Button>
                                <Button
                                    v-else
                                    variant="info"
                                    class="w-full"
                                >
                                    Start Lesson
                                </Button>
                            </div>
                        </Card>
                    </div>
                </section>

                <!-- Exercises Section -->
                <section>
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-foreground mb-2 flex items-center gap-2">
                            <Target class="h-6 w-6 text-info" />
                            Exercises
                        </h2>
                        <p class="text-muted-foreground">
                            Hands-on practice to build your confidence using AI
                        </p>
                    </div>

                    <div class="space-y-6">
                        <Card
                            v-for="(exercise, idx) in exercises"
                            :key="idx"
                            class="p-6 hover:border-info/40 transition-colors"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Left: Title & Description -->
                                <div class="md:col-span-2">
                                    <div class="flex items-start gap-3 mb-3">
                                        <Badge variant="info" class="text-sm font-bold px-2 py-1 mt-1">
                                            {{ idx + 1 }}
                                        </Badge>
                                        <div>
                                            <h3 class="text-xl font-bold text-foreground mb-2">
                                                {{ exercise.title }}
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-4">
                                                {{ exercise.description }}
                                            </p>
                                            <div class="inline-flex items-center gap-2 bg-info/10 px-3 py-1 rounded-full">
                                                <Lightbulb class="h-3 w-3 text-info" />
                                                <span class="text-xs font-medium text-info">
                                                    Goal: {{ exercise.goal }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right: Tasks -->
                                <div>
                                    <p class="text-xs font-semibold text-foreground mb-2">
                                        Tasks:
                                    </p>
                                    <ul class="space-y-2 mb-4">
                                        <li
                                            v-for="(task, taskIdx) in exercise.tasks"
                                            :key="taskIdx"
                                            class="flex items-start gap-2 text-xs text-muted-foreground"
                                        >
                                            <CheckCircle2 class="h-3 w-3 text-info mt-0.5 flex-shrink-0" />
                                            <span>{{ task }}</span>
                                        </li>
                                    </ul>
                                    <div class="space-y-1 text-xs text-muted-foreground">
                                        <div class="flex justify-between">
                                            <span>Time:</span>
                                            <span>{{ exercise.timeEstimate }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Difficulty:</span>
                                            <Badge variant="info" class="text-xs">
                                                {{ exercise.difficulty }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </div>
                </section>

                <!-- Bottom CTA -->
                <div class="mt-12 text-center">
                    <Card class="inline-block px-8 py-6 bg-info/5 border-info/20">
                        <p class="text-sm text-muted-foreground mb-4">
                            Ready to level up? Check out the intermediate path for more advanced techniques.
                        </p>
                        <Link href="/learn/intermediate">
                            <Button variant="info">
                                Explore Intermediate Path →
                            </Button>
                        </Link>
                    </Card>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
