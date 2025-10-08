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
    <div class="mb-6">
        <h2 class="text-3xl font-bold text-foreground mb-2">
            {{ title }}
        </h2>
        <p v-if="subtitle" class="text-muted-foreground">
            {{ subtitle }}
        </p>
    </div>
</template>
