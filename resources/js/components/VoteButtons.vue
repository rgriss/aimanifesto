<script setup lang="ts">
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { ThumbsUp, ThumbsDown } from 'lucide-vue-next';

interface Props {
    toolSlug: string;
    upvotes: number;
    downvotes: number;
    size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
});

// Local state for optimistic updates
const localUpvotes = ref(props.upvotes);
const localDownvotes = ref(props.downvotes);
const isVoting = ref(false);

// Size variants
const sizes = {
    sm: {
        button: 'px-2 py-1 text-xs',
        icon: 14,
        gap: 'gap-1',
    },
    md: {
        button: 'px-3 py-2 text-sm',
        icon: 16,
        gap: 'gap-1.5',
    },
    lg: {
        button: 'px-4 py-2.5 text-base',
        icon: 18,
        gap: 'gap-2',
    },
};

const sizeConfig = sizes[props.size];

const vote = async (type: 'up' | 'down') => {
    if (isVoting.value) return;

    // Optimistic update
    if (type === 'up') {
        localUpvotes.value++;
    } else {
        localDownvotes.value++;
    }

    isVoting.value = true;

    try {
        const response = await fetch(`/api/tools/${props.toolSlug}/vote`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({ type }),
        });

        const data = await response.json();

        if (data.success) {
            // Update with actual server values
            localUpvotes.value = data.data.upvotes;
            localDownvotes.value = data.data.downvotes;
        } else {
            // Revert optimistic update on error
            if (type === 'up') {
                localUpvotes.value--;
            } else {
                localDownvotes.value--;
            }
        }
    } catch (error) {
        // Revert optimistic update on error
        if (type === 'up') {
            localUpvotes.value--;
        } else {
            localDownvotes.value--;
        }
        console.error('Vote failed:', error);
    } finally {
        isVoting.value = false;
    }
};
</script>

<template>
    <div :class="['flex items-center', sizeConfig.gap]">
        <!-- Upvote Button -->
        <button
            @click="vote('up')"
            :disabled="isVoting"
            :class="[
                'inline-flex items-center',
                sizeConfig.gap,
                sizeConfig.button,
                'rounded-lg font-medium transition-all',
                'bg-success/10 text-success hover:bg-success/20',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                'border border-success/20 hover:border-success/30',
            ]"
        >
            <ThumbsUp :size="sizeConfig.icon" />
            <span>{{ localUpvotes }}</span>
        </button>

        <!-- Downvote Button -->
        <button
            @click="vote('down')"
            :disabled="isVoting"
            :class="[
                'inline-flex items-center',
                sizeConfig.gap,
                sizeConfig.button,
                'rounded-lg font-medium transition-all',
                'bg-danger/10 text-danger hover:bg-danger/20',
                'disabled:opacity-50 disabled:cursor-not-allowed',
                'border border-danger/20 hover:border-danger/30',
            ]"
        >
            <ThumbsDown :size="sizeConfig.icon" />
            <span>{{ localDownvotes }}</span>
        </button>

        <!-- Net Score (optional, shows on larger sizes) -->
        <div
            v-if="size !== 'sm'"
            :class="[
                'px-2 py-1 rounded text-xs font-semibold',
                localUpvotes > localDownvotes ? 'text-success' :
                localUpvotes < localDownvotes ? 'text-danger' :
                'text-muted-foreground'
            ]"
        >
            {{ localUpvotes - localDownvotes > 0 ? '+' : '' }}{{ localUpvotes - localDownvotes }}
        </div>
    </div>
</template>
