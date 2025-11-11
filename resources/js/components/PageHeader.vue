<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    description: String,
    gradient: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <div
        :class="[
            'rounded-lg p-8 mb-8',
            gradient
                ? 'bg-gradient-to-br from-foreground to-foreground/90 text-background shadow-xl border-2 border-foreground/10'
                : 'bg-card shadow-lg'
        ]"
    >
        <h1
            :class="[
                'text-4xl md:text-5xl font-bold mb-4',
                gradient ? 'text-background' : 'text-foreground'
            ]"
        >
            {{ title }}
        </h1>
        <p
            v-if="description"
            :class="[
                'text-lg md:text-xl',
                gradient ? 'text-background/90' : 'text-muted-foreground'
            ]"
        >
            {{ description }}
        </p>
        <slot />
    </div>
</template>
