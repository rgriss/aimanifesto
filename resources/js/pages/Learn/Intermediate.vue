<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Alert, AlertDescription } from '@/components/ui/alert';
import {
    Zap,
    BookOpen,
    CheckCircle2,
    Lightbulb,
    Target,
    GraduationCap,
    Sparkles,
    Rocket
} from 'lucide-vue-next';

const lessons = [
    {
        number: '201',
        title: 'Prompt Engineering Fundamentals',
        description: 'Master the art of crafting effective prompts. Learn techniques like few-shot prompting, chain-of-thought reasoning, and role assignment to get dramatically better results.',
        topics: [
            'Zero-shot vs. few-shot prompting',
            'Chain-of-thought reasoning',
            'Role and persona assignment',
            'Temperature and token settings',
        ],
        duration: '30 min',
        comingSoon: true,
    },
    {
        number: '202',
        title: 'Context Management Strategies',
        description: 'Learn how to manage conversation context effectively. Understand token limits, when to start fresh, and how to maintain continuity across sessions.',
        topics: [
            'Understanding token limits',
            'Context window optimization',
            'Conversation threading',
            'Session management techniques',
        ],
        duration: '25 min',
        comingSoon: true,
    },
    {
        number: '203',
        title: 'Custom GPTs and Assistants',
        description: 'Create specialized AI assistants tailored to your specific needs. Learn how to configure custom instructions, knowledge bases, and capabilities.',
        topics: [
            'Creating custom GPTs',
            'Writing effective system prompts',
            'Adding custom knowledge',
            'Function calling basics',
        ],
        duration: '35 min',
        comingSoon: true,
    },
];

const exercises = [
    {
        title: 'Exercise 1: Advanced Prompting Techniques',
        description: 'Practice using few-shot examples and chain-of-thought prompting to solve complex problems. Learn when and how to provide examples to guide AI responses.',
        goal: 'Master structured prompting techniques for better accuracy',
        tasks: [
            'Create a prompt with 2-3 examples (few-shot)',
            'Use chain-of-thought: "Let\'s think step by step"',
            'Assign a specific role or expertise level',
            'Compare results with and without these techniques',
        ],
        timeEstimate: '20-25 minutes',
        difficulty: 'Intermediate',
    },
    {
        title: 'Exercise 2: Context Window Management',
        description: 'Practice maintaining complex, multi-turn conversations. Learn to summarize previous context and manage long discussions without losing thread.',
        goal: 'Keep AI focused and contextually aware in long conversations',
        tasks: [
            'Start a conversation about a complex topic',
            'After 10+ exchanges, summarize key points',
            'Ask the AI to continue based on the summary',
            'Notice when to start fresh vs. continue',
        ],
        timeEstimate: '25-30 minutes',
        difficulty: 'Intermediate',
    },
    {
        title: 'Exercise 3: Custom Instruction Templates',
        description: 'Create reusable instruction templates for common tasks. Build your own prompt library for tasks you do frequently.',
        goal: 'Develop a personal toolkit of effective prompts',
        tasks: [
            'Identify 3 tasks you do regularly with AI',
            'Write a detailed instruction template for each',
            'Test and refine each template',
            'Save them for future reuse',
        ],
        timeEstimate: '30-40 minutes',
        difficulty: 'Intermediate',
    },
];
</script>

<template>
    <Head title="Intermediate Path - Learn AI" />

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
                        <Zap class="h-8 w-8 text-success" />
                        <h1 class="text-3xl sm:text-4xl font-bold text-foreground">
                            Intermediate Path
                        </h1>
                    </div>
                    <p class="text-lg text-muted-foreground">
                        Ready to level up? Let's dive deeper into AI capabilities.
                    </p>
                </div>

                <!-- Opening Section -->
                <section class="mb-12">
                    <Card class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <GraduationCap class="h-6 w-6 text-success" />
                            Taking It Further
                        </h2>

                        <div class="prose prose-gray dark:prose-invert max-w-none space-y-4 text-foreground/90">
                            <p>
                                Great! You've gotten comfortable with basic AI conversations. Now it's time to learn techniques that will dramatically improve the quality of responses you get. Think of this as moving from casual user to power user territory.
                            </p>

                            <p>
                                At this level, you'll learn <strong>prompt engineering</strong>, which is just a fancy way of saying "how to ask AI questions more effectively." You'll discover that small changes in how you phrase things can lead to dramatically different (and better) results.
                            </p>

                            <Alert class="my-6">
                                <Lightbulb class="h-4 w-4" />
                                <AlertDescription class="text-sm leading-relaxed">
                                    <p class="mb-0"><strong>What you'll gain:</strong> At the intermediate level, you'll start to understand why AI responds the way it does. You'll learn to guide conversations, maintain context over longer exchanges, and even create custom AI assistants tailored to your specific needs. These skills apply across all AI tools.</p>
                                </AlertDescription>
                            </Alert>

                            <p>
                                The good news is that these techniques work across all major AI platforms. Whether you're using ChatGPT, Claude, Gemini, or any other tool, the core principles are the same. You're learning transferable skills.
                            </p>

                            <p>
                                One thing I've learned as a power user is that <strong>context management is everything.</strong> You'll start hitting token limits and context windows more often at this level. That's actually a good sign. It means you're pushing the tools to do more sophisticated work. Don't be afraid to experiment, start fresh when needed, and iterate on your prompts.
                            </p>

                            <div class="bg-muted/50 rounded-lg p-4 my-6 not-prose">
                                <h3 class="text-sm font-semibold text-foreground mb-3 flex items-center gap-2">
                                    <CheckCircle2 class="h-4 w-4 text-success" />
                                    Before you start, you should:
                                </h3>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <span class="text-success mt-0.5">✓</span>
                                        <span>Be comfortable having multi-turn conversations with AI</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success mt-0.5">✓</span>
                                        <span>Have used AI for at least a few practical tasks</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success mt-0.5">✓</span>
                                        <span>Be curious about getting better results</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success mt-0.5">✓</span>
                                        <span>Be ready to experiment and iterate</span>
                                    </li>
                                </ul>
                            </div>

                            <p>
                                These lessons and exercises will help you understand the mechanics behind AI responses. You'll learn what makes a prompt "good" versus "great," and how to consistently get the quality of output you're looking for.
                            </p>
                        </div>
                    </Card>
                </section>

                <!-- Lessons Section -->
                <section class="mb-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-foreground mb-2 flex items-center gap-2">
                            <BookOpen class="h-6 w-6 text-success" />
                            Lessons
                        </h2>
                        <p class="text-muted-foreground">
                            Advanced techniques for getting better AI responses
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card
                            v-for="lesson in lessons"
                            :key="lesson.number"
                            class="p-6 flex flex-col hover:border-success/40 transition-colors"
                        >
                            <!-- Lesson Number Badge -->
                            <div class="mb-4">
                                <Badge variant="success" class="text-lg font-bold px-3 py-1">
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
                                        <CheckCircle2 class="h-3 w-3 text-success mt-0.5 flex-shrink-0" />
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
                                    variant="success"
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
                            <Target class="h-6 w-6 text-success" />
                            Exercises
                        </h2>
                        <p class="text-muted-foreground">
                            Practice advanced techniques with hands-on challenges
                        </p>
                    </div>

                    <div class="space-y-6">
                        <Card
                            v-for="(exercise, idx) in exercises"
                            :key="idx"
                            class="p-6 hover:border-success/40 transition-colors"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Left: Title & Description -->
                                <div class="md:col-span-2">
                                    <div class="flex items-start gap-3 mb-3">
                                        <Badge variant="success" class="text-sm font-bold px-2 py-1 mt-1">
                                            {{ idx + 1 }}
                                        </Badge>
                                        <div>
                                            <h3 class="text-xl font-bold text-foreground mb-2">
                                                {{ exercise.title }}
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-4">
                                                {{ exercise.description }}
                                            </p>
                                            <div class="inline-flex items-center gap-2 bg-success/10 px-3 py-1 rounded-full">
                                                <Lightbulb class="h-3 w-3 text-success" />
                                                <span class="text-xs font-medium text-success">
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
                                            <CheckCircle2 class="h-3 w-3 text-success mt-0.5 flex-shrink-0" />
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
                                            <Badge variant="success" class="text-xs">
                                                {{ exercise.difficulty }}
                                            </Badge>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Card>
                    </div>
                </section>

                <!-- Navigation CTAs -->
                <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <Link href="/learn/beginner">
                        <Card class="p-6 hover:border-info/40 transition-colors cursor-pointer h-full">
                            <div class="flex items-center gap-3 mb-2">
                                <Sparkles class="h-5 w-5 text-info" />
                                <h3 class="font-bold text-foreground">Review Beginner Path</h3>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Want to brush up on the basics? Go back to fundamentals.
                            </p>
                        </Card>
                    </Link>
                    <Link href="/learn/advanced">
                        <Card class="p-6 bg-warning/5 border-warning/20 hover:border-warning/40 transition-colors cursor-pointer h-full">
                            <div class="flex items-center gap-3 mb-2">
                                <Rocket class="h-5 w-5 text-warning" />
                                <h3 class="font-bold text-foreground">Advance to Power User</h3>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Ready for MCP servers, self-hosted models, and agentic development?
                            </p>
                        </Card>
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
