<script setup lang="ts">
import { ref } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Tabs,
    TabsList,
    TabsTrigger,
    TabsContent,
} from '@/components/ui/tabs';
import { Badge } from '@/components';
import { HelpCircle, Wrench, Users, Rocket, Code2 } from 'lucide-vue-next';

interface Props {
    onContactClick?: () => void;
}

const props = defineProps<Props>();

const open = ref(false);
const currentTab = ref('how-we-build');

const handleContactClick = () => {
    if (props.onContactClick) {
        props.onContactClick();
    }
    open.value = false;
};
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <button
                class="group absolute top-0 right-0 w-20 h-20 overflow-hidden cursor-pointer"
                style="clip-path: polygon(100% 0, 100% 100%, 0 0);"
                title="Directory Guide & Information"
            >
                <!-- Folded Corner Triangle -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-info hover:bg-info/90 transition-colors shadow-lg">
                    <!-- Diagonal fold line shadow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent via-black/10 to-black/20"></div>
                </div>
                <!-- Question Mark Icon -->
                <div class="absolute top-2 right-2 text-info-foreground">
                    <HelpCircle :size="20" class="drop-shadow-md" />
                </div>
            </button>
        </DialogTrigger>

        <DialogContent class="max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <DialogHeader>
                <DialogTitle class="text-2xl font-bold">AI Tools Directory Guide</DialogTitle>
                <DialogDescription>
                    Learn how we build this directory, ways you can help, and information for tool creators
                </DialogDescription>
            </DialogHeader>

            <Tabs v-model="currentTab" class="flex-1 flex flex-col overflow-hidden">
                <TabsList class="grid grid-cols-3 w-full mb-4">
                    <TabsTrigger value="how-we-build" class="text-xs sm:text-sm">
                        <Wrench :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">How We Build</span>
                        <span class="sm:hidden">Build</span>
                    </TabsTrigger>
                    <TabsTrigger value="how-you-help" class="text-xs sm:text-sm">
                        <Users :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">How You Help</span>
                        <span class="sm:hidden">Help</span>
                    </TabsTrigger>
                    <TabsTrigger value="for-creators" class="text-xs sm:text-sm">
                        <Rocket :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">For Creators</span>
                        <span class="sm:hidden">Creators</span>
                    </TabsTrigger>
                </TabsList>

                <div class="flex-1 overflow-y-auto pr-2">
                    <!-- How We Build This Directory Tab -->
                    <TabsContent value="how-we-build" class="space-y-4 mt-0">
                        <div class="space-y-3 text-muted-foreground text-sm sm:text-base leading-relaxed">
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
                                                @click="handleContactClick"
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
                    </TabsContent>

                    <!-- How You Can Help Tab -->
                    <TabsContent value="how-you-help" class="space-y-4 mt-0">
                        <div class="space-y-3 text-muted-foreground text-sm sm:text-base leading-relaxed">
                            <p class="font-semibold text-foreground">We need your help in three ways:</p>

                            <div class="space-y-3">
                                <div class="p-4 border border-border rounded-lg">
                                    <p>
                                        <span class="font-semibold text-foreground">1. Vote on tools:</span> We have a unique public voting system—just click
                                        the thumbs up or down on any tool, whether you're logged in or not. Your votes help others discover the best tools.
                                    </p>
                                </div>

                                <div class="p-4 border border-border rounded-lg">
                                    <p>
                                        <span class="font-semibold text-foreground">2. Add missing tools:</span> Know a tool we don't have? Click the
                                        <span class="inline-flex items-center gap-1 font-semibold text-foreground">Add Tool</span> button and enter the tool's name.
                                        Our system will do the rest to curate and add it to the directory.
                                    </p>
                                </div>

                                <div class="p-4 border border-border rounded-lg">
                                    <p>
                                        <span class="font-semibold text-foreground">3. Become a contributor:</span> We need maintainers, developers, and designers.
                                        If you have any inclination to contribute time or feedback—whether it's about individual tools or the directory as a whole—we'd
                                        be honored to work with you.
                                        <button
                                            @click="handleContactClick"
                                            class="text-foreground hover:underline font-semibold"
                                        >
                                            Get in touch
                                        </button>
                                        and let's make your acquaintance.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </TabsContent>

                    <!-- For Tool Creators Tab -->
                    <TabsContent value="for-creators" class="space-y-4 mt-0">
                        <div class="space-y-3 text-muted-foreground text-sm sm:text-base leading-relaxed">
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
                                    @click="handleContactClick"
                                    class="text-foreground hover:underline font-semibold"
                                >
                                    Get in touch
                                </button>
                                and let's start a conversation.
                            </p>

                            <div class="mt-6 p-4 border border-success/50 bg-success/5 rounded-lg">
                                <p class="font-semibold text-sm text-foreground mb-2">Why Engage With Us?</p>
                                <ul class="space-y-2 text-sm">
                                    <li class="flex items-start gap-2">
                                        <span class="text-success">✓</span>
                                        <span class="text-foreground">Provide accurate information about your tool directly to potential users</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success">✓</span>
                                        <span class="text-foreground">Share your vision and unique value proposition in your own words</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success">✓</span>
                                        <span class="text-foreground">Connect with a community that values innovation and quality</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <span class="text-success">✓</span>
                                        <span class="text-foreground">No cost, no sponsorship—just honest collaboration</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </TabsContent>
                </div>
            </Tabs>
        </DialogContent>
    </Dialog>
</template>
