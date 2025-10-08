<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: String
});
</script>

<template>
    <div class="mb-4 md:mb-6">
        <h2 class="text-2xl sm:text-3xl font-bold text-foreground mb-2 leading-tight">
            {{ title }}
        </h2>
        <p v-if="subtitle" class="text-sm sm:text-base text-muted-foreground">
            {{ subtitle }}
        </p>
    </div>
</template>
