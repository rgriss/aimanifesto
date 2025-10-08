<script setup>
defineProps({
    hover: {
        type: Boolean,
        default: true
    },
    padding: {
        type: String,
        default: 'p-6'
    }
});
</script>

<template>
    <div
        :class="[
            'bg-card rounded-lg shadow-sm',
            hover && 'hover:shadow-lg transition-shadow duration-300',
            padding
        ]"
    >
        <slot />
    </div>
</template>
