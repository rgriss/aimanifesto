<script setup>
defineProps({
    title: {
        type: String,
        required: true
    },
    subtitle: String,
    description: String,
    icon: String,
});
</script>

<template>
    <div class="bg-gradient-to-br from-foreground to-foreground/90 text-background rounded-lg shadow-xl p-8 mb-8 border-2 border-foreground/10">
        <div class="flex items-start gap-6 mb-4">
            <!-- Optional Icon (for categories) -->
            <div v-if="icon" class="text-7xl flex-shrink-0">{{ icon }}</div>

            <div class="flex-1">
                <!-- Subtitle (category name, etc) -->
                <div v-if="subtitle" class="mb-2">
                    <slot name="subtitle">
                        <span class="text-background/80 font-semibold">{{ subtitle }}</span>
                    </slot>
                </div>

                <!-- Title -->
                <h1 class="text-4xl font-bold mb-3">{{ title }}</h1>

                <!-- Description -->
                <p v-if="description" class="text-xl text-background/90 mb-4">
                    {{ description }}
                </p>

                <!-- Metadata slot (badges, stats, etc) -->
                <div v-if="$slots.metadata" class="flex flex-wrap gap-6 text-sm mb-6">
                    <slot name="metadata" />
                </div>

                <!-- Badge slot (for simple badges below description) -->
                <div v-if="$slots.badges" class="flex flex-wrap gap-2 mb-4">
                    <slot name="badges" />
                </div>
            </div>

            <!-- Top-right content (featured badge, etc) -->
            <div v-if="$slots.actions" class="flex-shrink-0">
                <slot name="actions" />
            </div>
        </div>

        <!-- Action buttons slot (Visit Website, Documentation, etc) -->
        <div v-if="$slots.buttons" class="flex flex-wrap gap-4">
            <slot name="buttons" />
        </div>
    </div>
</template>
