<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';

interface Props {
    href: string;
    label?: string;
    icon?: any;
    color?: 'primary' | 'success' | 'warning' | 'danger' | 'info';
}

const props = withDefaults(defineProps<Props>(), {
    label: 'Learn More',
    color: 'primary',
});

const colorClasses = {
    primary: 'bg-primary hover:bg-primary/90 text-primary-foreground',
    success: 'bg-success hover:bg-success/90 text-success-foreground',
    warning: 'bg-warning hover:bg-warning/90 text-warning-foreground',
    danger: 'bg-danger hover:bg-danger/90 text-danger-foreground',
    info: 'bg-info hover:bg-info/90 text-info-foreground',
};
</script>

<template>
    <Link
        :href="href"
        class="group absolute top-0 right-0 w-20 h-20 overflow-hidden cursor-pointer"
        style="clip-path: polygon(100% 0, 100% 100%, 0 0);"
        :title="label"
    >
        <!-- Folded Corner Triangle -->
        <div :class="['absolute top-0 right-0 w-20 h-20 transition-colors shadow-lg', colorClasses[color]]">
            <!-- Diagonal fold line shadow -->
            <div class="absolute inset-0 bg-gradient-to-br from-transparent via-black/10 to-black/20"></div>
        </div>
        <!-- Icon -->
        <div :class="['absolute top-2 right-2', props.color === 'primary' ? 'text-primary-foreground' : props.color === 'success' ? 'text-success-foreground' : props.color === 'warning' ? 'text-warning-foreground' : props.color === 'danger' ? 'text-danger-foreground' : 'text-info-foreground']">
            <component :is="icon || ArrowRight" :size="20" class="drop-shadow-md" />
        </div>
    </Link>
</template>
