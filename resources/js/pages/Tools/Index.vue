<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge, ToolCard, HelpWantedSign } from '@/components';
import { ChevronDown, Plus, Code2 } from 'lucide-vue-next';
import { ref } from 'vue';

const props = defineProps({
    tools: Object,
    totalCount: Number,
    filters: {
        type: Object,
        default: () => ({})
    },
});

const search = ref(props.filters?.search || '');
const sort = ref(props.filters?.sort || 'name');

// Expandable sections state
const showHowWeBuild = ref(false);
const showHowYouCanHelp = ref(false);
const showForToolCreators = ref(false);

// Help Wanted modal state
const showHelpWanted = ref(false);

const applyFilters = () => {
    router.get('/tools', {
        search: search.value,
        sort: sort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="AI Tools Directory - The AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <PageHeader
                    title="The AI Tools Directory"
                    description="A living, community-driven directory of AI tools built to help business owners, developers, and anyone curious about exploring modern AI."
                    :gradient="true"
                />

                <!-- Tool Count -->
                <div class="mb-6 text-center">
                    <p class="text-lg text-muted-foreground">
                        <span class="font-bold text-foreground">{{ totalCount }}</span> {{ totalCount === 1 ? 'tool' : 'tools' }} in the database
                        <span v-if="tools.total && tools.total !== totalCount" class="text-sm">
                            (showing {{ tools.total }} {{ tools.total === 1 ? 'result' : 'results' }})
                        </span>
                    </p>
                </div>

                <!-- Expandable Information Sections -->
                <div class="mb-8 space-y-3">
                    <!-- How We Build This Directory -->
                    <Card class="overflow-hidden">
                        <button
                            @click="showHowWeBuild = !showHowWeBuild"
                            class="w-full flex items-center justify-between p-4 hover:bg-muted/50 transition-colors text-left"
                        >
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🛠️</span>
                                <h3 class="text-lg font-bold text-foreground">How We Build This Directory</h3>
                            </div>
                            <ChevronDown
                                :class="['w-5 h-5 text-muted-foreground transition-transform', showHowWeBuild && 'rotate-180']"
                            />
                        </button>
                        <Transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-[1000px] opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-[1000px] opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-show="showHowWeBuild" class="px-4 pb-4 text-muted-foreground space-y-3 text-sm sm:text-base leading-relaxed overflow-hidden">
                                <p>
                                    This directory came to life through an "eat your own dogfood" approach—we use AI tools to curate AI tools.
                                    New tools are launching literally every week, so we built a system that harnesses several AI applications
                                    for different steps in the curation journey.
                                </p>
                                <p>
                                    We started by asking: <span class="font-semibold text-foreground">what are the major categories of AI tools people should know in 2025?</span>
                                    We got a list, then asked the AI to categorize them. Then we asked again: "What's missing?" We kept iterating—polishing
                                    our list, refining our categories, and adding tools to the directory.
                                </p>
                                <p>
                                    Then comes the deep dive on individual tools. Each tool is classified with <span class="font-semibold text-foreground">momentum scoring</span>,
                                    categorized into an appropriate primary category, and includes detailed pricing information. Tools with the
                                    <Badge variant="info" size="sm" class="mx-1">💼 CTO Insights</Badge> badge feature in-depth business analysis to help
                                    you make informed decisions based on your company size and needs.
                                </p>
                                <p>
                                    The aim? To be a practical resource for business owners, developers, and anyone exploring the world of modern AI tools.
                                </p>

                                <!-- Tech Note for Developers -->
                                <div class="mt-4 p-4 border border-info/50 bg-info/5 rounded-lg">
                                    <div class="flex gap-3">
                                        <Code2 class="h-5 w-5 text-info flex-shrink-0 mt-0.5" />
                                        <div class="flex-1 space-y-2">
                                            <p class="font-semibold text-sm text-foreground">Developer Note:</p>
                                            <p class="text-sm leading-relaxed text-foreground">
                                                Under the hood, we've built a <span class="font-semibold">custom MCP server</span> that powers this directory.
                                                This isn't just about "vibe coding"—it's a demonstration of promoting <span class="font-semibold">voice as a first-class user interface</span>.
                                            </p>
                                            <p class="text-sm leading-relaxed text-foreground">
                                                We use <span class="font-semibold">Claude Code</span>, <span class="font-semibold">Obsidian</span>,
                                                <span class="font-semibold">GitHub</span>, <span class="font-semibold">N8N</span>, and the
                                                <span class="font-semibold">MCP protocol</span> to create, populate, and maintain this entire site.
                                                This is interesting not just because it's useful for this project—it's a sandbox for advanced patterns
                                                and techniques you can apply to your own work.
                                            </p>
                                            <p class="text-sm leading-relaxed text-foreground">
                                                Fellow developer interested in modern application architecture and multi-system integration?
                                                <button
                                                    @click="showHelpWanted = true"
                                                    class="text-info hover:underline font-semibold"
                                                >
                                                    Get in touch
                                                </button>
                                                —we'd love to talk with you and connect you with other like-minded developers in our community.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </Transition>
                    </Card>

                    <!-- How You Can Help -->
                    <Card class="overflow-hidden">
                        <button
                            @click="showHowYouCanHelp = !showHowYouCanHelp"
                            class="w-full flex items-center justify-between p-4 hover:bg-muted/50 transition-colors text-left"
                        >
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🤝</span>
                                <h3 class="text-lg font-bold text-foreground">How You Can Help</h3>
                            </div>
                            <ChevronDown
                                :class="['w-5 h-5 text-muted-foreground transition-transform', showHowYouCanHelp && 'rotate-180']"
                            />
                        </button>
                        <Transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-[1000px] opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-[1000px] opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-show="showHowYouCanHelp" class="px-4 pb-4 text-muted-foreground space-y-3 text-sm sm:text-base leading-relaxed overflow-hidden">
                                <p class="font-semibold text-foreground">We need your help in three ways:</p>

                                <div class="space-y-2">
                                    <p>
                                        <span class="font-semibold text-foreground">1. Vote on tools:</span> We have a unique public voting system—just click
                                        the thumbs up or down on any tool, whether you're logged in or not. Your votes help others discover the best tools.
                                    </p>

                                    <p>
                                        <span class="font-semibold text-foreground">2. Add missing tools:</span> Know a tool we don't have? Click the
                                        <span class="inline-flex items-center gap-1 font-semibold text-foreground">Add Tool</span> button and enter the tool's name.
                                        Our system will do the rest to curate and add it to the directory.
                                    </p>

                                    <p>
                                        <span class="font-semibold text-foreground">3. Become a contributor:</span> We need maintainers, developers, and designers.
                                        If you have any inclination to contribute time or feedback—whether it's about individual tools or the directory as a whole—we'd
                                        be honored to work with you.
                                        <button
                                            @click="showHelpWanted = true"
                                            class="text-foreground hover:underline font-semibold"
                                        >
                                            Get in touch
                                        </button>
                                        and let's make your acquaintance.
                                    </p>
                                </div>
                            </div>
                        </Transition>
                    </Card>

                    <!-- For Tool Creators -->
                    <Card class="overflow-hidden">
                        <button
                            @click="showForToolCreators = !showForToolCreators"
                            class="w-full flex items-center justify-between p-4 hover:bg-muted/50 transition-colors text-left"
                        >
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">🚀</span>
                                <h3 class="text-lg font-bold text-foreground">For Tool Creators</h3>
                            </div>
                            <ChevronDown
                                :class="['w-5 h-5 text-muted-foreground transition-transform', showForToolCreators && 'rotate-180']"
                            />
                        </button>
                        <Transition
                            enter-active-class="transition-all duration-300 ease-out"
                            enter-from-class="max-h-0 opacity-0"
                            enter-to-class="max-h-[1000px] opacity-100"
                            leave-active-class="transition-all duration-200 ease-in"
                            leave-from-class="max-h-[1000px] opacity-100"
                            leave-to-class="max-h-0 opacity-0"
                        >
                            <div v-show="showForToolCreators" class="px-4 pb-4 text-muted-foreground space-y-3 text-sm sm:text-base leading-relaxed overflow-hidden">
                                <p>
                                    If you're an owner, manager, or representative of a tool in this directory, we'd love to engage with you.
                                    We're interested in potentially adding statements from you and your company to provide more context and insight.
                                </p>
                                <p>
                                    We want this directory to remain <span class="font-semibold text-foreground">objective forever</span>—we don't anticipate
                                    any sponsorship or monetization. But if you've built a tool that appears here, we probably share the same passion for
                                    building modern software and helping people. So let's help each other.
                                </p>
                                <p>
                                    <button
                                        @click="showHelpWanted = true"
                                        class="text-foreground hover:underline font-semibold"
                                    >
                                        Get in touch
                                    </button>
                                    and let's start a conversation.
                                </p>
                            </div>
                        </Transition>
                    </Card>
                </div>

                <!-- Search & Sort -->
                <Card class="mb-8">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <!-- Search -->
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-foreground mb-2">
                                    Search Tools
                                </label>
                                <input
                                    v-model="search"
                                    @input="applyFilters"
                                    type="text"
                                    placeholder="Search by name or description..."
                                    class="w-full rounded-md border border-border bg-background text-foreground px-4 py-2 focus:outline-none focus:ring-2 focus:ring-foreground focus:border-transparent"
                                />
                            </div>

                            <!-- Sort -->
                            <div class="w-full sm:w-48">
                                <label class="block text-sm font-medium text-foreground mb-2">
                                    Sort By
                                </label>
                                <select
                                    v-model="sort"
                                    @change="applyFilters"
                                    class="w-full rounded-md border border-border bg-background text-foreground px-4 py-2 focus:outline-none focus:ring-2 focus:ring-foreground focus:border-transparent"
                                >
                                    <option value="name">Name</option>
                                    <option value="rating">Highest Rated</option>
                                    <option value="views">Most Viewed</option>
                                    <option value="recent">Recently Added</option>
                                </select>
                            </div>

                            <!-- Clear Search (only show if search is active) -->
                            <div v-if="search" class="flex items-end">
                                <Link
                                    href="/tools"
                                    class="px-4 py-2 bg-secondary text-secondary-foreground rounded-md hover:bg-secondary/90 text-center font-semibold transition-colors whitespace-nowrap"
                                >
                                    Clear Search
                                </Link>
                            </div>
                        </div>

                        <!-- Add Tool Button -->
                        <div class="flex justify-center sm:justify-start pt-2 border-t border-border">
                            <button
                                @click="showHelpWanted = true"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-foreground text-background hover:bg-foreground/90 font-semibold rounded-lg transition-all shadow-sm hover:shadow-md"
                            >
                                <Plus class="w-4 h-4" />
                                Add Tool
                            </button>
                            <p class="ml-4 text-xs sm:text-sm text-muted-foreground flex items-center">
                                Know a tool we're missing? Let us know and we'll add it!
                            </p>
                        </div>
                    </div>
                </Card>

                <!-- Tools Grid -->
                <div v-if="tools.data && tools.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <ToolCard
                            v-for="tool in tools.data"
                            :key="tool.id"
                            :tool="tool"
                        />
                    </div>

                    <!-- Pagination -->
                    <div v-if="tools.links" class="flex flex-col sm:flex-row justify-center items-center gap-4">
                        <!-- Mobile: Simple Prev/Next with Page Info -->
                        <div class="md:hidden flex flex-col items-center gap-2 w-full">
                            <div class="text-sm text-muted-foreground">
                                Page {{ tools.current_page }} of {{ tools.last_page }}
                            </div>
                            <div class="flex gap-2 w-full max-w-sm">
                                <component
                                    :is="tools.links[0].url ? Link : 'span'"
                                    :href="tools.links[0].url || undefined"
                                    :class="[
                                        'flex-1 px-4 py-2 rounded-md font-semibold transition-colors text-center',
                                        tools.links[0].url
                                            ? 'bg-card text-foreground border border-border hover:bg-secondary'
                                            : 'bg-card/50 text-muted-foreground border border-border opacity-50 cursor-not-allowed'
                                    ]"
                                >
                                    Previous
                                </component>
                                <component
                                    :is="tools.links[tools.links.length - 1].url ? Link : 'span'"
                                    :href="tools.links[tools.links.length - 1].url || undefined"
                                    :class="[
                                        'flex-1 px-4 py-2 rounded-md font-semibold transition-colors text-center',
                                        tools.links[tools.links.length - 1].url
                                            ? 'bg-card text-foreground border border-border hover:bg-secondary'
                                            : 'bg-card/50 text-muted-foreground border border-border opacity-50 cursor-not-allowed'
                                    ]"
                                >
                                    Next
                                </component>
                            </div>
                        </div>

                        <!-- Desktop: Full Pagination -->
                        <div class="hidden md:flex gap-2 flex-wrap justify-center">
                            <component
                                v-for="(link, index) in tools.links"
                                :key="index"
                                :is="link.url ? Link : 'span'"
                                :href="link.url || undefined"
                                :class="[
                                    'px-4 py-2 rounded-md font-semibold transition-colors',
                                    link.active
                                        ? 'bg-foreground text-background'
                                        : 'bg-card text-foreground border border-border',
                                    link.url ? 'hover:bg-secondary' : 'opacity-50 cursor-not-allowed'
                                ]"
                                v-html="link.label"
                            />
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <Card v-else padding="p-12">
                    <div class="text-center">
                        <p class="text-xl text-muted-foreground mb-4">
                            {{ search ? `No tools found for "${search}"` : 'No tools found' }}
                        </p>
                        <Link
                            v-if="search"
                            href="/tools"
                            class="text-foreground hover:text-foreground/70 font-semibold underline"
                        >
                            Clear search
                        </Link>
                    </div>
                </Card>

                <!-- Help Wanted Sign -->
                <div class="mt-12 max-w-md mx-auto">
                    <HelpWantedSign />
                </div>
            </div>
        </div>

        <!-- Help Wanted Modal (for "Get in touch" buttons) -->
        <HelpWantedSign :show="showHelpWanted" @close="showHelpWanted = false" hide-sign />
    </GuestLayout>
</template>