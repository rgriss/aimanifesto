<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Card, Badge, VoteButtons } from '@/components';
import { TrendingUp, TrendingDown, Activity, Circle } from 'lucide-vue-next';
import { computed } from 'vue';

interface Tool {
    id: number;
    name: string;
    slug: string;
    description: string;
    category?: {
        id: number;
        name: string;
        slug: string;
    };
    pricing_model?: string;
    ryan_rating?: number;
    momentum_score?: number;
    is_featured?: boolean;
    intelligence?: any;
    upvotes?: number;
    downvotes?: number;
}

interface Props {
    tool: Tool;
    showVoting?: boolean;
    showMomentum?: boolean;
    showCategory?: boolean;
    compact?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    showVoting: true,
    showMomentum: true,
    showCategory: true,
    compact: false,
});

// Get momentum icon and styling based on score
const getMomentumDisplay = computed(() => {
    if (!props.tool.momentum_score) {
        return { icon: Circle, color: 'text-muted-foreground/30', title: 'No momentum data' };
    }

    const displays: Record<number, { icon: any; color: string; title: string }> = {
        1: { icon: TrendingDown, color: 'text-danger', title: 'Strongly Declining' },
        2: { icon: TrendingDown, color: 'text-warning', title: 'Declining' },
        3: { icon: Activity, color: 'text-foreground/50', title: 'Stable' },
        4: { icon: TrendingUp, color: 'text-success', title: 'Growing' },
        5: { icon: TrendingUp, color: 'text-success', title: 'Rapidly Growing' },
    };

    return displays[props.tool.momentum_score] || { icon: Circle, color: 'text-muted-foreground/30', title: 'Unknown' };
});
</script>

<template>
    <Link
        :href="`/tools/${tool.slug}`"
        class="group h-full flex"
    >
        <Card class="flex-1 flex flex-col h-full w-full">
            <!-- Header: Name + Badges -->
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <component
                        v-if="showMomentum"
                        :is="getMomentumDisplay.icon"
                        :size="18"
                        :class="getMomentumDisplay.color"
                        :title="getMomentumDisplay.title"
                    />
                    <h3 class="text-xl font-bold text-foreground group-hover:text-foreground/70 transition-colors">
                        {{ tool.name }}
                    </h3>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <Badge v-if="tool.intelligence" variant="info" size="sm">
                        💼 CTO Insights
                    </Badge>
                    <Badge v-if="tool.is_featured" variant="warning" size="sm">
                        Featured
                    </Badge>
                </div>
            </div>

            <!-- Description -->
            <p class="text-muted-foreground mb-4 text-sm flex-grow">
                {{ tool.description }}
            </p>

            <!-- Voting Buttons -->
            <div v-if="showVoting && !compact" class="mb-4" @click.prevent.stop>
                <VoteButtons
                    :tool-slug="tool.slug"
                    :upvotes="tool.upvotes || 0"
                    :downvotes="tool.downvotes || 0"
                    size="sm"
                />
            </div>

            <!-- Footer: Category, Rating, Pricing -->
            <div class="mt-auto">
                <div v-if="showCategory" class="flex items-center justify-between text-sm mb-2">
                    <span class="text-muted-foreground">
                        {{ tool.category?.name }}
                    </span>
                    <Badge v-if="tool.ryan_rating" variant="success" size="sm">
                        ⭐ {{ tool.ryan_rating }}/10
                    </Badge>
                </div>
                <div v-else-if="tool.ryan_rating" class="mb-2">
                    <Badge variant="success" size="sm">
                        ⭐ {{ tool.ryan_rating }}/10
                    </Badge>
                </div>
                <Badge v-if="tool.pricing_model" variant="default" size="sm" class="capitalize">
                    {{ tool.pricing_model }}
                </Badge>
            </div>
        </Card>
    </Link>
</template>
