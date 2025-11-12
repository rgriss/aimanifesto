<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ExternalLink, MessageSquare, TrendingUp } from 'lucide-vue-next';

interface Props {
    toolName: string;
}

const props = defineProps<Props>();

interface HNResult {
    objectID: string;
    title: string;
    url: string;
    points: number;
    num_comments: number;
    created_at: string;
    story_text?: string;
}

const discussions = ref<HNResult[]>([]);
const loading = ref(true);
const error = ref<string | null>(null);

onMounted(async () => {
    try {
        // Search HN Algolia API for tool name
        const response = await fetch(
            `https://hn.algolia.com/api/v1/search?query=${encodeURIComponent(props.toolName)}&tags=story&hitsPerPage=5`
        );

        if (!response.ok) {
            throw new Error('Failed to fetch discussions');
        }

        const data = await response.json();
        discussions.value = data.hits;
    } catch (e) {
        error.value = e instanceof Error ? e.message : 'Failed to load discussions';
        console.error('HN API error:', e);
    } finally {
        loading.value = false;
    }
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
            <CardDescription>Recent community discussions and submissions</CardDescription>
        </CardHeader>
        <CardContent>
            <!-- Loading State -->
            <div v-if="loading" class="space-y-3">
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
            <div v-else-if="discussions.length === 0" class="text-sm text-muted-foreground">
                No recent discussions found on Hacker News.
            </div>

            <!-- Results -->
            <div v-else class="space-y-4">
                <a
                    v-for="discussion in discussions"
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
                    :href="`https://hn.algolia.com/?q=${encodeURIComponent(toolName)}`"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-1 text-sm text-primary hover:underline"
                >
                    View all discussions
                    <ExternalLink class="w-3 h-3" />
                </a>
            </div>
        </CardContent>
    </Card>
</template>
