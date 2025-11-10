<script setup lang="ts">
import { useAppearance } from '@/composables/useAppearance';
import { Moon, Sun } from 'lucide-vue-next';
import { computed } from 'vue';

const { appearance, updateAppearance } = useAppearance();

// Determine the actual current theme (light or dark)
const currentTheme = computed(() => {
    if (appearance.value === 'system') {
        // Check system preference
        if (typeof window !== 'undefined') {
            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        }
        return 'light';
    }
    return appearance.value;
});

const toggleTheme = () => {
    // Toggle between light and dark (skip 'system' for simple toggle)
    const newTheme = currentTheme.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newTheme);
};
</script>

<template>
    <button
        @click="toggleTheme"
        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-muted-foreground hover:text-foreground transition-colors rounded-lg hover:bg-secondary"
        :title="currentTheme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode'"
    >
        <Sun v-if="currentTheme === 'dark'" class="h-4 w-4" />
        <Moon v-else class="h-4 w-4" />
        <span class="hidden sm:inline">{{ currentTheme === 'dark' ? 'Light' : 'Dark' }}</span>
    </button>
</template>
