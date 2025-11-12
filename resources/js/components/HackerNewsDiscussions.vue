<script setup lang="ts">
import { ref, computed, watch } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/components/ui/tabs';
import { ExternalLink, MessageSquare, TrendingUp, Clock } from 'lucide-vue-next';

interface Props {
    toolName: string;
    customQuery?: string | null;
}

const props = defineProps<Props>();

// Use custom query if provided, otherwise fall back to tool name
const searchQuery = computed(() => props.customQuery || props.toolName);

interface HNResult {
    objectID: string;
    title: string;
    url: string;
    points: number;
    num_comments: number;
    created_at: string;
    story_text?: string;
}

type SortMode = 'popular' | 'recent';

const sortMode = ref<SortMode>('popular');
const popularDiscussions = ref<HNResult[]>([]);
const recentDiscussions = ref<HNResult[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

// Computed to get current discussions based on sort mode
const discussions = computed(() =>
    sortMode.value === 'popular' ? popularDiscussions.value : recentDiscussions.value
);

// Fetch discussions based on sort mode
async function fetchDiscussions(mode: SortMode) {
    // If we already have data for this mode, don't refetch
    if (mode === 'popular' && popularDiscussions.value.length > 0) return;
    if (mode === 'recent' && recentDiscussions.value.length > 0) return;

    loading.value = true;
    error.value = null;

    try {
        let url: string;

        if (mode === 'popular') {
            // Default search - sorted by relevance/points
            url = `https://hn.algolia.com/api/v1/search?query=${encodeURIComponent(searchQuery.value)}&tags=story&hitsPerPage=5`;
        } else {
            // Search by date - sorted by recency, with minimum quality filter
            url = `https://hn.algolia.com/api/v1/search_by_date?query=${encodeURIComponent(searchQuery.value)}&tags=story&hitsPerPage=5&numericFilters=points>5`;
        }

        const response = await fetch(url);

        if (!response.ok) {
            throw new Error('Failed to fetch discussions');
        }

        const data = await response.json();

        if (mode === 'popular') {
            popularDiscussions.value = data.hits;
        } else {
            recentDiscussions.value = data.hits;
        }
    } catch (e) {
        error.value = e instanceof Error ? e.message : 'Failed to load discussions';
        console.error('HN API error:', e);
    } finally {
        loading.value = false;
    }
}

// Fetch popular discussions on mount
fetchDiscussions('popular');

// Watch for sort mode changes and fetch if needed
watch(sortMode, (newMode) => {
    fetchDiscussions(newMode);
});

const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now.getTime() - date.getTime();
    const diffDays = Math.floor(diffMs / (1000 * 60 * 60 * 24));

    if (diffDays === 0) return 'Today';
    if (diffDays === 1) return 'Yesterday';
    if (diffDays < 30) return `${diffDays} days ago`;
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
    return `${Math.floor(diffDays / 365)} years ago`;
};
</script>

<template>
    <Card>
        <CardHeader>
            <CardTitle class="flex items-center gap-2">
                <TrendingUp class="w-5 h-5 text-orange-500" />
                Hacker News Discussions
            </CardTitle>
            <CardDescription>Community discussions and submissions</CardDescription>
        </CardHeader>
        <CardContent>
            <Tabs v-model="sortMode" class="w-full">
                <TabsList class="grid w-full grid-cols-2 mb-4">
                    <TabsTrigger value="popular" class="flex items-center gap-1.5">
                        <TrendingUp class="w-3.5 h-3.5" />
                        Popular
                    </TabsTrigger>
                    <TabsTrigger value="recent" class="flex items-center gap-1.5">
                        <Clock class="w-3.5 h-3.5" />
                        Recent
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="popular">
                    <!-- Loading State -->
                    <div v-if="loading && sortMode === 'popular'" class="space-y-3">
                        <div v-for="i in 3" :key="i" class="animate-pulse">
                            <div class="h-4 bg-muted rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-muted rounded w-1/2"></div>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="error" class="text-sm text-muted-foreground">
                        Unable to load discussions. Try again later.
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="popularDiscussions.length === 0" class="text-sm text-muted-foreground">
                        No discussions found on Hacker News.
                    </div>

                    <!-- Results -->
                    <div v-else class="space-y-4">
                        <a
                            v-for="discussion in popularDiscussions"
                            :key="discussion.objectID"
                            :href="`https://news.ycombinator.com/item?id=${discussion.objectID}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="block group"
                        >
                            <div class="space-y-1">
                                <h4 class="text-sm font-medium leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    {{ discussion.title }}
                                </h4>
                                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                                    <span class="flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" />
                                        {{ discussion.points }} points
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <MessageSquare class="w-3 h-3" />
                                        {{ discussion.num_comments }} comments
                                    </span>
                                    <span>{{ formatDate(discussion.created_at) }}</span>
                                </div>
                            </div>
                        </a>

                        <!-- View All Link -->
                        <a
                            :href="`https://hn.algolia.com/?q=${encodeURIComponent(searchQuery)}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1 text-sm text-primary hover:underline"
                        >
                            View all discussions
                            <ExternalLink class="w-3 h-3" />
                        </a>
                    </div>
                </TabsContent>

                <TabsContent value="recent">
                    <!-- Loading State -->
                    <div v-if="loading && sortMode === 'recent'" class="space-y-3">
                        <div v-for="i in 3" :key="i" class="animate-pulse">
                            <div class="h-4 bg-muted rounded w-3/4 mb-2"></div>
                            <div class="h-3 bg-muted rounded w-1/2"></div>
                        </div>
                    </div>

                    <!-- Error State -->
                    <div v-else-if="error" class="text-sm text-muted-foreground">
                        Unable to load discussions. Try again later.
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="recentDiscussions.length === 0" class="text-sm text-muted-foreground">
                        No recent discussions found on Hacker News.
                    </div>

                    <!-- Results -->
                    <div v-else class="space-y-4">
                        <a
                            v-for="discussion in recentDiscussions"
                            :key="discussion.objectID"
                            :href="`https://news.ycombinator.com/item?id=${discussion.objectID}`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="block group"
                        >
                            <div class="space-y-1">
                                <h4 class="text-sm font-medium leading-tight group-hover:text-primary transition-colors line-clamp-2">
                                    {{ discussion.title }}
                                </h4>
                                <div class="flex items-center gap-3 text-xs text-muted-foreground">
                                    <span class="flex items-center gap-1">
                                        <TrendingUp class="w-3 h-3" />
                                        {{ discussion.points }} points
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <MessageSquare class="w-3 h-3" />
                                        {{ discussion.num_comments }} comments
                                    </span>
                                    <span>{{ formatDate(discussion.created_at) }}</span>
                                </div>
                            </div>
                        </a>

                        <!-- View All Link -->
                        <a
                            :href="`https://hn.algolia.com/?q=${encodeURIComponent(searchQuery)}&sort=byDate`"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-1 text-sm text-primary hover:underline"
                        >
                            View all discussions
                            <ExternalLink class="w-3 h-3" />
                        </a>
                    </div>
                </TabsContent>
            </Tabs>
        </CardContent>
    </Card>
</template>
