<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Card } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Alert, AlertDescription } from '@/components/ui/alert';
import {
    Rocket,
    BookOpen,
    CheckCircle2,
    Lightbulb,
    Target,
    GraduationCap,
    Sparkles,
    Zap,
    Code2,
    Server,
    Terminal
} from 'lucide-vue-next';

const lessons = [
    {
        number: '301',
        title: 'MCP Servers and Protocols',
        description: 'Dive into Model Context Protocol (MCP) servers. Learn how to extend AI capabilities with custom tools, data sources, and integrations.',
        topics: [
            'Understanding MCP architecture',
            'Installing and configuring MCP servers',
            'Creating custom MCP tools',
            'Integration patterns and best practices',
        ],
        duration: '45 min',
        comingSoon: true,
    },
    {
        number: '302',
        title: 'Self-Hosted LLM Models',
        description: 'Run AI models locally on your own hardware. Explore open-source models, quantization techniques, and deployment strategies.',
        topics: [
            'Open-source model ecosystem (Llama, Mistral, etc.)',
            'Hardware requirements and optimization',
            'Quantization and model compression',
            'Local deployment with Ollama and LM Studio',
        ],
        duration: '50 min',
        comingSoon: true,
    },
    {
        number: '303',
        title: 'Agentic Development Patterns',
        description: 'Build autonomous AI agents that can plan, execute, and iterate. Learn agent architectures, tool use, and orchestration patterns.',
        topics: [
            'Agent architectures (ReAct, Plan-and-Execute)',
            'Tool use and function calling',
            'Multi-agent orchestration',
            'Error handling and guardrails',
        ],
        duration: '60 min',
        comingSoon: true,
    },
];

const exercises = [
    {
        title: 'Exercise 1: Set Up Your First MCP Server',
        description: 'Install and configure an MCP server to extend AI capabilities. Connect it to Claude Desktop and test custom tool integration.',
        goal: 'Understand MCP architecture through hands-on implementation',
        tasks: [
            'Install Node.js and required dependencies',
            'Set up a basic MCP server (filesystem or weather)',
            'Configure Claude Desktop to use the server',
            'Test custom tools and verify functionality',
        ],
        timeEstimate: '45-60 minutes',
        difficulty: 'Advanced',
        prerequisites: ['Basic command line skills', 'Node.js familiarity', 'Claude Desktop installed'],
    },
    {
        title: 'Exercise 2: Run a Local LLM',
        description: 'Deploy a local language model using Ollama. Compare performance, privacy, and capabilities with cloud-based solutions.',
        goal: 'Gain practical experience with self-hosted AI',
        tasks: [
            'Install Ollama or LM Studio',
            'Download and run a quantized model (Llama 3, Mistral)',
            'Test various model sizes and configurations',
            'Compare response quality and speed with cloud LLMs',
        ],
        timeEstimate: '60-90 minutes',
        difficulty: 'Advanced',
        prerequisites: ['Sufficient RAM (16GB+ recommended)', 'GPU optional but helpful', 'Terminal comfort'],
    },
    {
        title: 'Exercise 3: Build a Simple AI Agent',
        description: 'Create an autonomous agent that can use tools, make decisions, and accomplish multi-step tasks without constant human guidance.',
        goal: 'Understand agent architectures and tool orchestration',
        tasks: [
            'Define an agent task (research assistant, code reviewer)',
            'Implement tool calling with function definitions',
            'Add planning and decision-making logic',
            'Test with complex, multi-step scenarios',
        ],
        timeEstimate: '90-120 minutes',
        difficulty: 'Advanced',
        prerequisites: ['Programming experience (Python/JavaScript)', 'API access (OpenAI/Anthropic)', 'Prompt engineering knowledge'],
    },
];
</script>

<template>
    <Head title="Advanced Path - Learn AI" />

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
                        <Rocket class="h-8 w-8 text-warning" />
                        <h1 class="text-3xl sm:text-4xl font-bold text-foreground">
                            Advanced Path
                        </h1>
                    </div>
                    <p class="text-lg text-muted-foreground">
                        Power user territory. Build, deploy, and orchestrate AI systems.
                    </p>
                </div>

                <!-- Opening Section -->
                <section class="mb-12">
                    <Card class="p-6 md:p-8">
                        <h2 class="text-2xl font-bold text-foreground mb-4 flex items-center gap-2">
                            <GraduationCap class="h-6 w-6 text-warning" />
                            Power User Territory
                        </h2>

                        <div class="prose prose-gray dark:prose-invert max-w-none space-y-4 text-foreground/90">
                            <p>
                                Welcome to the advanced path. If you're here, you're ready to move beyond using AI tools to building with them. This is where theory meets practice, and where you gain programmatic control over AI systems.
                            </p>

                            <p>
                                At this level, we're talking about <strong>extending AI capabilities</strong> through custom integrations, running models on your own hardware, and building autonomous agents that can accomplish complex tasks. These aren't just consumer use cases. This is infrastructure.
                            </p>

                            <Alert class="my-6">
                                <Code2 class="h-4 w-4" />
                                <AlertDescription class="text-sm leading-relaxed">
                                    <p class="mb-0"><strong>Technical requirements:</strong> You'll need programming experience, comfort with the command line, and familiarity with concepts like APIs, environment variables, and configuration files. If terms like "localhost," "port," and "API endpoint" are new to you, spend more time at the intermediate level first.</p>
                                </AlertDescription>
                            </Alert>

                            <p>
                                <strong>MCP (Model Context Protocol)</strong> is one of the most powerful recent developments for extending AI. It lets you connect AI assistants to external data sources, tools, and services. Think of it as giving AI access to your file system, databases, APIs, or any custom functionality you build.
                            </p>

                            <p>
                                <strong>Self-hosted models</strong> give you complete control. No rate limits, no usage tracking, and your data never leaves your machine. The tradeoff is hardware requirements and slightly lower quality compared to frontier models. But for many use cases, especially privacy-sensitive work, it's worth it.
                            </p>

                            <p>
                                <strong>Agentic development</strong> is where AI stops being a chatbot and starts being a system. Agents can plan, use tools, handle errors, and iterate toward goals. This is cutting-edge territory. The patterns are still emerging, and you'll be learning alongside the industry.
                            </p>

                            <div class="bg-muted/50 rounded-lg p-4 my-6 not-prose">
                                <h3 class="text-sm font-semibold text-foreground mb-3 flex items-center gap-2">
                                    <Terminal class="h-4 w-4 text-warning" />
                                    Prerequisites for this path:
                                </h3>
                                <ul class="space-y-2 text-sm text-muted-foreground">
                                    <li class="flex items-start gap-2">
                                        <span class="text-warning mt-0.5">✓</span>
                                        <span>Solid understanding of prompt engineering</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-warning mt-0.5">✓</span>
                                        <span>Programming experience (Python or JavaScript preferred)</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-warning mt-0.5">✓</span>
                                        <span>Comfortable with command line and package managers</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-warning mt-0.5">✓</span>
                                        <span>Familiar with APIs and authentication</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-warning mt-0.5">✓</span>
                                        <span>Ready to read documentation and troubleshoot</span>
                                    </li>
                                </ul>
                            </div>

                            <p>
                                These exercises assume you can debug issues, read error messages, and search documentation. The advanced path isn't hand-holding. It's showing you what's possible and giving you the foundation to explore further.
                            </p>
                        </div>
                    </Card>
                </section>

                <!-- Lessons Section -->
                <section class="mb-12">
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold text-foreground mb-2 flex items-center gap-2">
                            <BookOpen class="h-6 w-6 text-warning" />
                            Lessons
                        </h2>
                        <p class="text-muted-foreground">
                            Deep dives into advanced AI development and deployment
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <Card
                            v-for="lesson in lessons"
                            :key="lesson.number"
                            class="p-6 flex flex-col hover:border-warning/40 transition-colors"
                        >
                            <!-- Lesson Number Badge -->
                            <div class="mb-4">
                                <Badge variant="warning" class="text-lg font-bold px-3 py-1">
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
                                        <CheckCircle2 class="h-3 w-3 text-warning mt-0.5 flex-shrink-0" />
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
                                    variant="warning"
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
                            <Target class="h-6 w-6 text-warning" />
                            Exercises
                        </h2>
                        <p class="text-muted-foreground">
                            Hands-on implementation of advanced AI concepts
                        </p>
                    </div>

                    <div class="space-y-6">
                        <Card
                            v-for="(exercise, idx) in exercises"
                            :key="idx"
                            class="p-6 hover:border-warning/40 transition-colors"
                        >
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <!-- Left: Title & Description -->
                                <div class="md:col-span-2">
                                    <div class="flex items-start gap-3 mb-3">
                                        <Badge variant="warning" class="text-sm font-bold px-2 py-1 mt-1">
                                            {{ idx + 1 }}
                                        </Badge>
                                        <div>
                                            <h3 class="text-xl font-bold text-foreground mb-2">
                                                {{ exercise.title }}
                                            </h3>
                                            <p class="text-sm text-muted-foreground mb-4">
                                                {{ exercise.description }}
                                            </p>
                                            <div class="inline-flex items-center gap-2 bg-warning/10 px-3 py-1 rounded-full">
                                                <Lightbulb class="h-3 w-3 text-warning" />
                                                <span class="text-xs font-medium text-warning">
                                                    Goal: {{ exercise.goal }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Prerequisites -->
                                    <div v-if="exercise.prerequisites" class="mt-4 p-3 bg-muted/50 rounded-lg">
                                        <p class="text-xs font-semibold text-foreground mb-2">
                                            Prerequisites:
                                        </p>
                                        <ul class="space-y-1">
                                            <li
                                                v-for="(prereq, prereqIdx) in exercise.prerequisites"
                                                :key="prereqIdx"
                                                class="flex items-start gap-2 text-xs text-muted-foreground"
                                            >
                                                <Server class="h-3 w-3 text-warning mt-0.5 flex-shrink-0" />
                                                <span>{{ prereq }}</span>
                                            </li>
                                        </ul>
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
                                            <CheckCircle2 class="h-3 w-3 text-warning mt-0.5 flex-shrink-0" />
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
                                            <Badge variant="warning" class="text-xs">
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
                                <h3 class="font-bold text-foreground">Review Fundamentals</h3>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Back to basics. Sometimes starting fresh clarifies advanced concepts.
                            </p>
                        </Card>
                    </Link>
                    <Link href="/learn/intermediate">
                        <Card class="p-6 hover:border-success/40 transition-colors cursor-pointer h-full">
                            <div class="flex items-center gap-3 mb-2">
                                <Zap class="h-5 w-5 text-success" />
                                <h3 class="font-bold text-foreground">Intermediate Techniques</h3>
                            </div>
                            <p class="text-sm text-muted-foreground">
                                Brush up on prompt engineering and context management before diving deep.
                            </p>
                        </Card>
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
