<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { router } from '@inertiajs/vue3';
import { ThumbsUp, ThumbsDown } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Button } from '@/components';

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

// Vote tracking for showing the philosophy dialog
const voteClicks = ref<number[]>([]);
const showPhilosophyDialog = ref(false);
const hasSeenPhilosophy = ref(false);

// Check if user has already seen the philosophy message
onMounted(() => {
    const seen = localStorage.getItem('voting_philosophy_acknowledged');
    hasSeenPhilosophy.value = seen === 'true';
});

const acknowledgePhilosophy = () => {
    hasSeenPhilosophy.value = true;
    localStorage.setItem('voting_philosophy_acknowledged', 'true');
    showPhilosophyDialog.value = false;
};

const checkVoteThreshold = () => {
    const now = Date.now();
    // Clean up clicks older than 30 seconds
    voteClicks.value = voteClicks.value.filter(timestamp => now - timestamp < 30000);

    // Add current click
    voteClicks.value.push(now);

    // Show dialog if they've clicked 5+ times in 30 seconds and haven't seen it yet
    if (voteClicks.value.length >= 5 && !hasSeenPhilosophy.value) {
        showPhilosophyDialog.value = true;
    }
};

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

    // Check vote threshold for philosophy dialog
    checkVoteThreshold();

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
    <div>
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

        <!-- Voting Philosophy Dialog -->
        <Dialog v-model:open="showPhilosophyDialog">
            <DialogContent class="max-w-2xl">
                <DialogHeader>
                    <DialogTitle class="text-2xl font-bold">About Our Public Voting System</DialogTitle>
                    <DialogDescription class="sr-only">
                        Understanding the philosophy behind our voting system
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4 text-foreground">
                    <p class="leading-relaxed">
                        We see you're clicking that vote button quite a bit! Before you continue, let's talk about how this voting system works.
                    </p>

                    <div class="bg-muted/30 border border-border rounded-lg p-4 space-y-3">
                        <p class="leading-relaxed">
                            This voting system is <strong>clearly insecure</strong> and <strong>clearly not accurate</strong>—but we're not after perfection.
                            If somebody is motivated to click a button a whole bunch of times, that says something. This might be a terrible idea in the end,
                            but we think it's an interesting and unique way of collecting sentiment.
                        </p>

                        <p class="leading-relaxed">
                            One day we might have detailed views. One day we might tie votes to individual people. But <strong>this is not social media</strong>.
                            We're not trying to replicate your favorite social platform—we're just trying to get something going.
                        </p>
                    </div>

                    <div class="bg-warning/10 border border-warning/30 rounded-lg p-4 space-y-3">
                        <p class="font-semibold text-warning">The Downside: Please Don't Be Obnoxious</p>
                        <p class="leading-relaxed text-foreground">
                            The danger is somebody being obnoxious—somebody spamming. That's why you're seeing this popup.
                        </p>
                        <p class="leading-relaxed text-foreground">
                            <strong>The bottom line:</strong> Please don't be obnoxious. Don't be that guy. Don't be the problem.
                        </p>
                    </div>

                    <p class="leading-relaxed">
                        In some ways, this public voting system is <strong>an experiment to see if as a community we can all get along</strong>.
                    </p>

                    <p class="leading-relaxed font-semibold">
                        So please participate! Click thumbs up on items you like. If you feel like clicking more than once, great—but don't spam click it.
                        Just don't be a jerk. 🤝
                    </p>

                    <div class="text-center pt-4">
                        <Button @click="acknowledgePhilosophy" size="lg" class="font-semibold">
                            Got It, I'll Be Cool
                        </Button>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    </div>
</template>
