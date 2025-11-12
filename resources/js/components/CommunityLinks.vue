<script setup lang="ts">
import { computed } from 'vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { ExternalLink, Users, MessageCircle, Star } from 'lucide-vue-next';

interface Props {
    redditUrl?: string | null;
    communityUrl?: string | null;
    reviewsUrl?: string | null;
}

const props = defineProps<Props>();

interface Link {
    label: string;
    url: string;
    icon: any;
    description: string;
}

const links = computed<Link[]>(() => {
    const result: Link[] = [];

    if (props.redditUrl) {
        result.push({
            label: 'Reddit Community',
            url: props.redditUrl,
            icon: MessageCircle,
            description: 'Join discussions on Reddit'
        });
    }

    if (props.communityUrl) {
        result.push({
            label: 'Official Community',
            url: props.communityUrl,
            icon: Users,
            description: 'Discord, Slack, or Forum'
        });
    }

    if (props.reviewsUrl) {
        result.push({
            label: 'Reviews & Ratings',
            url: props.reviewsUrl,
            icon: Star,
            description: 'G2, Capterra, ProductHunt, etc.'
        });
    }

    return result;
});
</script>

<template>
    <Card v-if="links.length > 0">
        <CardHeader>
            <CardTitle class="flex items-center gap-2">
                <Users class="w-5 h-5" />
                Community & Reviews
            </CardTitle>
            <CardDescription>Connect with users and read reviews</CardDescription>
        </CardHeader>
        <CardContent>
            <div class="space-y-3">
                <a
                    v-for="link in links"
                    :key="link.url"
                    :href="link.url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="flex items-start gap-3 p-3 rounded-lg border border-border hover:bg-muted/50 transition-colors group"
                >
                    <component :is="link.icon" class="w-5 h-5 text-muted-foreground group-hover:text-primary transition-colors mt-0.5 flex-shrink-0" />
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2">
                            <h4 class="text-sm font-medium group-hover:text-primary transition-colors">
                                {{ link.label }}
                            </h4>
                            <ExternalLink class="w-3 h-3 text-muted-foreground opacity-0 group-hover:opacity-100 transition-opacity" />
                        </div>
                        <p class="text-xs text-muted-foreground mt-0.5">
                            {{ link.description }}
                        </p>
                    </div>
                </a>
            </div>
        </CardContent>
    </Card>
</template>
