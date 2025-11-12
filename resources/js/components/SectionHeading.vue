<script setup>
defineProps({
    title: {
        type: String,
        required: false
    },
    subtitle: String
});
</script>

<template>
    <div class="mb-4 md:mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-foreground mb-3 leading-tight">
            <slot name="title">{{ title }}</slot>
        </h2>
        <p v-if="subtitle" class="text-sm sm:text-base text-muted-foreground leading-relaxed">
            {{ subtitle }}
        </p>
        <slot name="subtitle" />
    </div>
</template>
