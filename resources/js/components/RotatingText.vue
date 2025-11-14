<script setup lang="ts">
import { ref, onMounted, onUnmounted, computed } from 'vue';

interface Props {
    messages: string[];
    interval?: number; // milliseconds between rotations
    fixedWidth?: boolean; // Use fixed width to prevent layout shift
}

const props = withDefaults(defineProps<Props>(), {
    interval: 2500, // 2.5 seconds default
    fixedWidth: true,
});

const currentIndex = ref(0);
const isVisible = ref(true);

let rotationInterval: ReturnType<typeof setInterval> | null = null;

// Calculate min-width based on longest message to prevent layout shift
const longestMessage = computed(() => {
    return props.messages.reduce((longest, current) =>
        current.length > longest.length ? current : longest
    , '');
});

const rotateText = () => {
    // Fade out
    isVisible.value = false;

    // Wait for fade out, then change text
    setTimeout(() => {
        currentIndex.value = (currentIndex.value + 1) % props.messages.length;
        // Fade in
        isVisible.value = true;
    }, 200); // Match this to CSS transition duration
};

onMounted(() => {
    // Start rotation
    rotationInterval = setInterval(rotateText, props.interval);
});

onUnmounted(() => {
    // Clean up interval
    if (rotationInterval) {
        clearInterval(rotationInterval);
    }
});
</script>

<template>
    <span class="inline-block relative">
        <!-- Invisible text to reserve space (prevents layout shift) - adds extra space for proper centering -->
        <span v-if="fixedWidth" class="invisible" aria-hidden="true">
            {{ longestMessage }}&nbsp;&nbsp;
        </span>
        <!-- Rotating text positioned absolutely over the reserved space -->
        <span :class="fixedWidth ? 'absolute inset-0' : ''">
            <Transition name="fade" mode="out-in">
                <span :key="currentIndex" class="inline-block">
                    {{ messages[currentIndex] }}
                </span>
            </Transition>
        </span>
    </span>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
