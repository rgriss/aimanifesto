<script setup>
const props = defineProps({
    variant: {
        type: String,
        default: 'primary',
        validator: (value) => ['primary', 'secondary', 'success', 'info', 'warning', 'danger', 'ghost', 'outline'].includes(value)
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value)
    },
    href: String,
    as: {
        type: String,
        default: 'button'
    }
});

const variantClasses = {
    primary: 'bg-primary text-primary-foreground hover:bg-primary/90',
    secondary: 'bg-secondary text-secondary-foreground hover:bg-secondary/90',
    success: 'bg-success text-success-foreground hover:bg-success/90',
    info: 'bg-info text-info-foreground hover:bg-info/90',
    warning: 'bg-warning text-warning-foreground hover:bg-warning/90',
    danger: 'bg-danger text-danger-foreground hover:bg-danger/90',
    ghost: 'bg-transparent hover:bg-accent hover:text-accent-foreground',
    outline: 'border-2 border-primary text-primary hover:bg-primary hover:text-primary-foreground'
};

const sizeClasses = {
    sm: 'text-sm px-3 py-1.5',
    md: 'text-base px-4 py-2',
    lg: 'text-lg px-6 py-3'
};
</script>

<template>
    <component
        :is="as"
        :href="href"
        :class="[
            'inline-flex items-center justify-center font-semibold rounded-lg transition-colors duration-200',
            variantClasses[variant],
            sizeClasses[size]
        ]"
    >
        <slot />
    </component>
</template>
