<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHero, Card, Badge, SectionHeading } from '@/components';

interface Property {
    name: string;
    type: string;
    required: boolean;
    description: string;
    enum?: string[] | null;
    minLength?: number | null;
    maxLength?: number | null;
    minimum?: number | null;
    maximum?: number | null;
    format?: string | null;
    pattern?: string | null;
    items?: any | null;
    examples?: any[];
}

interface PropertyGroup {
    title: string;
    description: string;
    properties: Property[];
}

interface Schema {
    title: string;
    description: string;
    version: string;
    grouped: Record<string, PropertyGroup>;
    examples: any[];
    type_definitions?: string | null;
}

const props = defineProps<{
    schema: Schema;
}>();

const openGroups = ref<Set<string>>(new Set(['required']));

const toggleGroup = (key: string) => {
    if (openGroups.value.has(key)) {
        openGroups.value.delete(key);
    } else {
        openGroups.value.add(key);
    }
};

const copyToClipboard = async (text: string) => {
    try {
        await navigator.clipboard.writeText(text);
        alert('Copied to clipboard!');
    } catch (err) {
        console.error('Failed to copy:', err);
    }
};

const getTypeColor = (type: string): string => {
    if (type.includes('String')) return 'text-blue-600 dark:text-blue-400';
    if (type.includes('Integer') || type.includes('Number')) return 'text-green-600 dark:text-green-400';
    if (type.includes('Boolean')) return 'text-purple-600 dark:text-purple-400';
    if (type.includes('Array')) return 'text-orange-600 dark:text-orange-400';
    return 'text-gray-600 dark:text-gray-400';
};
</script>

<template>
    <Head title="Tool Schema - Developer Documentation" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back Navigation -->
                <div class="mb-6">
                    <Link
                        href="/docs"
                        class="text-foreground hover:text-foreground/70 font-semibold transition-colors"
                    >
                        ← Back to documentation
                    </Link>
                </div>

                <!-- Page Header -->
                <PageHero
                    :title="schema.title + ' Documentation'"
                    :description="schema.description"
                >
                    <template #subtitle>
                        <span class="text-background/80">Developer Reference v{{ schema.version }}</span>
                    </template>

                    <template #metadata>
                        <div>
                            <Badge variant="success">JSON Schema</Badge>
                            <Badge variant="default" class="ml-2">TypeScript Definitions</Badge>
                        </div>
                    </template>
                </PageHero>

                <!-- Introduction -->
                <Card class="mb-8">
                    <SectionHeading
                        title="Introduction"
                        subtitle="Understanding the Tool model schema"
                    />
                    <div class="prose prose-slate dark:prose-invert max-w-none">
                        <p class="text-foreground">
                            This page documents the complete schema for the <strong>Tool</strong> model in The AI Manifesto directory.
                            Use this reference when:
                        </p>
                        <ul class="text-foreground">
                            <li>Creating tools via the API</li>
                            <li>Building MCP integrations</li>
                            <li>Understanding data validation rules</li>
                            <li>Importing/exporting tool data</li>
                        </ul>
                    </div>
                </Card>

                <!-- Property Groups -->
                <div class="space-y-6">
                    <div
                        v-for="(group, key) in schema.grouped"
                        :key="key"
                        class="bg-card rounded-lg shadow border border-border"
                    >
                        <!-- Group Header -->
                        <button
                            @click="toggleGroup(key)"
                            class="w-full flex items-center justify-between p-6 text-left hover:bg-muted/50 transition-colors"
                        >
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-foreground mb-1">
                                    {{ group.title }}
                                </h3>
                                <p class="text-sm text-muted-foreground">
                                    {{ group.description }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <Badge variant="default">
                                    {{ group.properties.length }} {{ group.properties.length === 1 ? 'field' : 'fields' }}
                                </Badge>
                                <span class="text-2xl text-muted-foreground">
                                    {{ openGroups.has(key) ? '−' : '+' }}
                                </span>
                            </div>
                        </button>

                        <!-- Group Content -->
                        <div v-show="openGroups.has(key)" class="border-t border-border">
                            <div class="p-6 space-y-6">
                                <div
                                    v-for="prop in group.properties"
                                    :key="prop.name"
                                    class="border-l-4 pl-4"
                                    :class="prop.required ? 'border-red-500' : 'border-gray-300 dark:border-gray-700'"
                                >
                                    <!-- Property Header -->
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <code class="text-lg font-mono font-bold text-foreground">
                                                    {{ prop.name }}
                                                </code>
                                                <Badge v-if="prop.required" variant="destructive" size="sm">
                                                    required
                                                </Badge>
                                            </div>
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="text-sm font-mono font-semibold" :class="getTypeColor(prop.type)">
                                                    {{ prop.type }}
                                                </span>
                                                <span v-if="prop.format" class="text-xs text-muted-foreground">
                                                    ({{ prop.format }})
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Property Description -->
                                    <p class="text-sm text-foreground mb-3">
                                        {{ prop.description }}
                                    </p>

                                    <!-- Property Constraints -->
                                    <div v-if="prop.enum || prop.minLength || prop.maxLength || prop.minimum || prop.maximum || prop.pattern" class="bg-muted rounded-md p-3 mb-3">
                                        <h5 class="text-xs font-semibold text-muted-foreground uppercase mb-2">
                                            Validation Rules
                                        </h5>
                                        <div class="space-y-1 text-sm text-foreground">
                                            <div v-if="prop.enum">
                                                <span class="font-semibold">Allowed values:</span>
                                                <code class="ml-2 text-xs bg-background px-1.5 py-0.5 rounded">
                                                    {{ prop.enum.join(', ') }}
                                                </code>
                                            </div>
                                            <div v-if="prop.minLength">
                                                <span class="font-semibold">Min length:</span>
                                                <span class="ml-2">{{ prop.minLength }}</span>
                                            </div>
                                            <div v-if="prop.maxLength">
                                                <span class="font-semibold">Max length:</span>
                                                <span class="ml-2">{{ prop.maxLength }}</span>
                                            </div>
                                            <div v-if="prop.minimum">
                                                <span class="font-semibold">Minimum:</span>
                                                <span class="ml-2">{{ prop.minimum }}</span>
                                            </div>
                                            <div v-if="prop.maximum">
                                                <span class="font-semibold">Maximum:</span>
                                                <span class="ml-2">{{ prop.maximum }}</span>
                                            </div>
                                            <div v-if="prop.pattern">
                                                <span class="font-semibold">Pattern:</span>
                                                <code class="ml-2 text-xs bg-background px-1.5 py-0.5 rounded">
                                                    {{ prop.pattern }}
                                                </code>
                                            </div>
                                            <div v-if="prop.items && prop.items.type">
                                                <span class="font-semibold">Array item type:</span>
                                                <code class="ml-2 text-xs bg-background px-1.5 py-0.5 rounded">
                                                    {{ prop.items.type }}
                                                </code>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Property Examples -->
                                    <div v-if="prop.examples && prop.examples.length > 0" class="bg-slate-50 dark:bg-slate-900 rounded-md p-3">
                                        <h5 class="text-xs font-semibold text-muted-foreground uppercase mb-2">
                                            Example {{ prop.examples.length > 1 ? 'Values' : 'Value' }}
                                        </h5>
                                        <div class="space-y-1">
                                            <code
                                                v-for="(example, idx) in prop.examples"
                                                :key="idx"
                                                class="block text-sm font-mono text-foreground break-all"
                                            >
                                                {{ typeof example === 'object' ? JSON.stringify(example) : example }}
                                            </code>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Complete Examples -->
                <Card v-if="schema.examples && schema.examples.length > 0" class="mt-8">
                    <SectionHeading
                        title="Complete Examples"
                        subtitle="Full tool objects showing all fields in context"
                    />
                    <div
                        v-for="(example, idx) in schema.examples"
                        :key="idx"
                        class="mb-6 last:mb-0"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-foreground">
                                Example {{ idx + 1 }}: {{ example.name }}
                            </h4>
                            <button
                                @click="copyToClipboard(JSON.stringify(example, null, 2))"
                                class="text-sm px-3 py-1 bg-muted hover:bg-muted/80 text-foreground rounded-md transition-colors"
                            >
                                Copy JSON
                            </button>
                        </div>
                        <pre class="bg-slate-50 dark:bg-slate-900 rounded-lg p-4 overflow-x-auto text-sm">
<code class="text-foreground">{{ JSON.stringify(example, null, 2) }}</code></pre>
                    </div>
                </Card>

                <!-- Quick Reference -->
                <Card class="mt-8">
                    <SectionHeading
                        title="Quick Reference"
                        subtitle="Key points to remember"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-foreground mb-2">
                                ✅ Required Fields (4)
                            </h4>
                            <ul class="space-y-1 text-sm text-foreground">
                                <li><code class="text-xs bg-muted px-1.5 py-0.5 rounded">name</code> - Tool name</li>
                                <li><code class="text-xs bg-muted px-1.5 py-0.5 rounded">description</code> - Brief description</li>
                                <li><code class="text-xs bg-muted px-1.5 py-0.5 rounded">website_url</code> - Official website</li>
                                <li><code class="text-xs bg-muted px-1.5 py-0.5 rounded">category</code> - Category name</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-semibold text-foreground mb-2">
                                💡 Best Practices
                            </h4>
                            <ul class="space-y-1 text-sm text-foreground list-disc list-inside">
                                <li>Keep descriptions concise (1-2 sentences)</li>
                                <li>Use long_description for detailed info</li>
                                <li>Include pricing_model when known</li>
                                <li>Add features array for key capabilities</li>
                            </ul>
                        </div>
                    </div>
                </Card>
            </div>
        </div>
    </GuestLayout>
</template>
