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
            'rounded-lg shadow-lg p-8 mb-8',
            gradient
                ? 'bg-gradient-to-br from-primary via-secondary to-info text-white'
                : 'bg-card'
        ]"
    >
        <h1
            :class="[
                'text-4xl md:text-5xl font-bold mb-4',
                gradient ? 'text-white' : 'text-primary'
            ]"
        >
            {{ title }}
        </h1>
        <p
            v-if="description"
            :class="[
                'text-lg md:text-xl',
                gradient ? 'text-gray-200' : 'text-muted-foreground'
            ]"
        >
            {{ description }}
        </p>
        <slot />
    </div>
</template>
