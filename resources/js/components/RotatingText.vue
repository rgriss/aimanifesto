<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';

interface Props {
    messages: string[];
    interval?: number; // milliseconds between rotations
}

const props = withDefaults(defineProps<Props>(), {
    interval: 2500, // 2.5 seconds default
});

const currentIndex = ref(0);
const isVisible = ref(true);

let rotationInterval: ReturnType<typeof setInterval> | null = null;

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
        <Transition name="fade" mode="out-in">
            <span :key="currentIndex" class="inline-block">
                {{ messages[currentIndex] }}
            </span>
        </Transition>
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
