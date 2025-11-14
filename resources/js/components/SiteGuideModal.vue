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
import { Button, Badge } from '@/components';
import { HelpCircle, TrendingUp, ThumbsUp, ThumbsDown, Layers, BookOpen, Video } from 'lucide-vue-next';

interface Props {
    videoUrl?: string;
}

const props = defineProps<Props>();

const open = ref(false);
const currentTab = ref('overview');
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <button
                class="group absolute top-0 right-0 w-20 h-20 overflow-hidden cursor-pointer"
                style="clip-path: polygon(100% 0, 100% 100%, 0 0);"
                title="How to use this site"
            >
                <!-- Folded Corner Triangle -->
                <div class="absolute top-0 right-0 w-20 h-20 bg-primary group-hover:bg-primary/90 transition-colors shadow-lg">
                    <!-- Diagonal fold line shadow -->
                    <div class="absolute inset-0 bg-gradient-to-br from-transparent via-black/10 to-black/20"></div>
                </div>
                <!-- Question Mark Icon -->
                <div class="absolute top-2 right-2 text-primary-foreground">
                    <HelpCircle :size="20" class="drop-shadow-md" />
                </div>
            </button>
        </DialogTrigger>

        <DialogContent class="max-w-4xl max-h-[90vh] overflow-hidden flex flex-col">
            <DialogHeader>
                <DialogTitle class="text-2xl font-bold">Welcome to The AI Manifesto</DialogTitle>
                <DialogDescription>
                    Your guide to discovering and understanding AI tools
                </DialogDescription>
            </DialogHeader>

            <Tabs v-model="currentTab" class="flex-1 flex flex-col overflow-hidden">
                <TabsList class="grid grid-cols-5 w-full mb-4">
                    <TabsTrigger value="overview" class="text-xs sm:text-sm">
                        <BookOpen :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">Overview</span>
                    </TabsTrigger>
                    <TabsTrigger value="cards" class="text-xs sm:text-sm">
                        <Layers :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">Tool Cards</span>
                    </TabsTrigger>
                    <TabsTrigger value="details" class="text-xs sm:text-sm">
                        <TrendingUp :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">Details</span>
                    </TabsTrigger>
                    <TabsTrigger value="voting" class="text-xs sm:text-sm">
                        <ThumbsUp :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">Voting</span>
                    </TabsTrigger>
                    <TabsTrigger v-if="videoUrl" value="video" class="text-xs sm:text-sm">
                        <Video :size="16" class="mr-1 sm:mr-2" />
                        <span class="hidden sm:inline">Video</span>
                    </TabsTrigger>
                </TabsList>

                <div class="flex-1 overflow-y-auto pr-2">
                    <!-- Overview Tab -->
                    <TabsContent value="overview" class="space-y-4 mt-0">
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-3">Getting Started</h3>
                            <p class="text-muted-foreground leading-relaxed mb-4">
                                The AI Manifesto helps you discover, evaluate, and understand AI tools through our curated directory
                                and honest reviews. Here's how to make the most of this site:
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl flex-shrink-0">🔍</div>
                                    <div>
                                        <h4 class="font-bold text-foreground mb-1">Browse by Category</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Navigate to Categories to explore tools organized by use case: Writing, Coding,
                                            Image Generation, Data Analysis, and more. This is often the fastest way to find
                                            what you need.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl flex-shrink-0">⭐</div>
                                    <div>
                                        <h4 class="font-bold text-foreground mb-1">Personal Ratings</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Tools with ratings (1-10 scale) reflect hands-on experience and honest evaluation.
                                            Not all tools are rated yet—we're continually expanding our reviews.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl flex-shrink-0">📊</div>
                                    <div>
                                        <h4 class="font-bold text-foreground mb-1">Business Intelligence</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Many tools include deep-dive business intelligence: market position, pricing analysis,
                                            momentum indicators, and competitive insights to help you make informed decisions.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl flex-shrink-0">👍</div>
                                    <div>
                                        <h4 class="font-bold text-foreground mb-1">Community Voting</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Vote on tools to help surface the most valuable options. Your votes are public
                                            and help the community discover what works.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TabsContent>

                    <!-- Tool Cards Tab -->
                    <TabsContent value="cards" class="space-y-4 mt-0">
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-3">Anatomy of a Tool Card</h3>
                            <p class="text-muted-foreground leading-relaxed mb-4">
                                Each tool card on the homepage and category pages provides key information at a glance:
                            </p>
                        </div>

                        <div class="border-2 border-border rounded-lg p-4 bg-background">
                            <div class="flex items-start justify-between mb-3 gap-2">
                                <div class="flex items-center gap-2 flex-1">
                                    <TrendingUp :size="18" class="text-success flex-shrink-0" />
                                    <h4 class="text-lg font-bold text-foreground">Example Tool Name</h4>
                                </div>
                                <Badge variant="success" size="sm">⭐ 9</Badge>
                            </div>
                            <p class="text-sm text-muted-foreground mb-4">
                                A brief description of what the tool does and who it's for...
                            </p>
                            <div class="flex items-center justify-between gap-2">
                                <Badge variant="default" size="sm">Freemium</Badge>
                                <span class="text-xs font-semibold text-foreground">Learn More →</span>
                            </div>
                        </div>

                        <div class="space-y-3 mt-4">
                            <div>
                                <h4 class="font-semibold text-foreground mb-2 flex items-center gap-2">
                                    <TrendingUp :size="16" class="text-success" />
                                    Momentum Indicator
                                </h4>
                                <p class="text-sm text-muted-foreground mb-2">
                                    Icons show market momentum based on community activity and growth trends:
                                </p>
                                <div class="grid grid-cols-1 gap-2 text-xs">
                                    <div class="flex items-center gap-2">
                                        <TrendingUp :size="14" class="text-success" />
                                        <span class="text-muted-foreground">Green trending up = Growing or Rapidly Growing</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <TrendingUp :size="14" class="text-foreground/50" />
                                        <span class="text-muted-foreground">Gray activity = Stable</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <TrendingUp :size="14" class="text-warning" />
                                        <span class="text-muted-foreground">Orange trending down = Declining</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-foreground mb-2">⭐ Personal Rating</h4>
                                <p class="text-sm text-muted-foreground">
                                    1-10 scale based on hands-on testing. Higher numbers indicate better overall value,
                                    user experience, and reliability.
                                </p>
                            </div>

                            <div>
                                <h4 class="font-semibold text-foreground mb-2">💰 Pricing Model</h4>
                                <p class="text-sm text-muted-foreground">
                                    <strong>Free:</strong> Fully free to use<br>
                                    <strong>Freemium:</strong> Free tier available with paid upgrades<br>
                                    <strong>Paid:</strong> Subscription or one-time purchase required<br>
                                    <strong>Enterprise:</strong> Custom pricing for large organizations
                                </p>
                            </div>
                        </div>
                    </TabsContent>

                    <!-- Tool Details Tab -->
                    <TabsContent value="details" class="space-y-4 mt-0">
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-3">Tool Detail Pages</h3>
                            <p class="text-muted-foreground leading-relaxed mb-4">
                                Click any tool card to view comprehensive information including business intelligence,
                                community resources, and detailed analysis.
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div class="border border-border rounded-lg p-4">
                                <h4 class="font-bold text-foreground mb-2">📈 Business Intelligence</h4>
                                <p class="text-sm text-muted-foreground mb-2">
                                    For selected tools, we provide deep-dive analysis including:
                                </p>
                                <ul class="text-sm text-muted-foreground space-y-1 ml-4 list-disc">
                                    <li>Company metadata (founded, headquarters, team size)</li>
                                    <li>Market position and user base size</li>
                                    <li>Pricing complexity ratings for individuals, SMBs, and enterprises</li>
                                    <li>Funding stage and revenue estimates</li>
                                    <li>SWOT analysis and competitive differentiators</li>
                                </ul>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <h4 class="font-bold text-foreground mb-2">📊 Momentum Score</h4>
                                <p class="text-sm text-muted-foreground">
                                    Market momentum (1-5 scale) indicates how the tool is trending based on community
                                    engagement, Hacker News mentions, and industry buzz. This helps you spot emerging
                                    leaders and declining platforms.
                                </p>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <h4 class="font-bold text-foreground mb-2">🔗 Community Links</h4>
                                <p class="text-sm text-muted-foreground mb-2">
                                    Quick access to external resources when available:
                                </p>
                                <ul class="text-sm text-muted-foreground space-y-1 ml-4 list-disc">
                                    <li><strong>Reddit Discussions:</strong> Community conversations and user experiences</li>
                                    <li><strong>Discord/Slack:</strong> Official community channels for support</li>
                                    <li><strong>Reviews:</strong> Third-party review sites and aggregators</li>
                                </ul>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <h4 class="font-bold text-foreground mb-2">💬 Hacker News Discussions</h4>
                                <p class="text-sm text-muted-foreground">
                                    Recent Hacker News discussions about each tool appear automatically in the sidebar,
                                    giving you technical perspectives and real-world feedback from the developer community.
                                </p>
                            </div>
                        </div>
                    </TabsContent>

                    <!-- Voting Tab -->
                    <TabsContent value="voting" class="space-y-4 mt-0">
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-3">Community Voting System</h3>
                            <p class="text-muted-foreground leading-relaxed mb-4">
                                Our public voting system helps surface the most valuable tools based on community feedback.
                                Here's how it works:
                            </p>
                        </div>

                        <div class="space-y-3">
                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3 mb-3">
                                    <ThumbsUp :size="24" class="text-success flex-shrink-0" />
                                    <div class="flex-1">
                                        <h4 class="font-bold text-foreground mb-1">Upvotes</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Vote up tools that you find valuable, well-designed, or solve problems effectively.
                                            Upvotes indicate positive experiences and recommendations.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <div class="flex items-start gap-3 mb-3">
                                    <ThumbsDown :size="24" class="text-danger flex-shrink-0" />
                                    <div class="flex-1">
                                        <h4 class="font-bold text-foreground mb-1">Downvotes</h4>
                                        <p class="text-sm text-muted-foreground">
                                            Downvote tools that disappoint, have poor user experience, or don't deliver on
                                            promises. Constructive criticism helps others make informed decisions.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border border-border rounded-lg p-4">
                                <h4 class="font-bold text-foreground mb-2">📊 Net Score</h4>
                                <p class="text-sm text-muted-foreground mb-2">
                                    The net score (upvotes minus downvotes) provides a quick gauge of community sentiment.
                                    Higher positive scores indicate broadly valuable tools, while negative scores suggest
                                    caution or areas for improvement.
                                </p>
                            </div>

                            <div class="border border-border rounded-lg p-4 bg-primary/5">
                                <h4 class="font-bold text-foreground mb-2">💡 Voting Philosophy</h4>
                                <p class="text-sm text-muted-foreground">
                                    <strong>Your votes are public and matter.</strong> We don't use algorithmic manipulation
                                    or hidden scoring. What you see is what the community thinks. Vote thoughtfully, and
                                    remember that your feedback helps others navigate the rapidly evolving AI landscape.
                                </p>
                            </div>
                        </div>
                    </TabsContent>

                    <!-- Video Tab (optional) -->
                    <TabsContent v-if="videoUrl" value="video" class="space-y-4 mt-0">
                        <div>
                            <h3 class="text-lg font-bold text-foreground mb-3">Video Walkthrough</h3>
                            <p class="text-muted-foreground leading-relaxed mb-4">
                                Watch a quick video tour of The AI Manifesto to see all features in action:
                            </p>
                        </div>

                        <div class="aspect-video bg-muted rounded-lg overflow-hidden">
                            <iframe
                                :src="videoUrl"
                                class="w-full h-full"
                                frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                            ></iframe>
                        </div>
                    </TabsContent>
                </div>
            </Tabs>
        </DialogContent>
    </Dialog>
</template>
