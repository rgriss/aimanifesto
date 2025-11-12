<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    href: {
        type: String,
        default: '/tools'
    },
    external: {
        type: Boolean,
        default: false
    }
});
</script>

<template>
    <component
        :is="external ? 'a' : Link"
        :href="href"
        :target="external ? '_blank' : undefined"
        :rel="external ? 'noopener noreferrer' : undefined"
        class="group block"
    >
        <!-- Help Wanted Sign -->
        <div class="relative bg-danger p-5 rounded-lg transition-transform hover:scale-105">
            <!-- Inset white border using box-shadow -->
            <div class="absolute inset-3 border-4 border-background rounded pointer-events-none"></div>

            <!-- Content -->
            <div class="relative z-10 text-center py-4">
                <div class="text-3xl md:text-4xl font-black text-background leading-none tracking-tight uppercase" style="font-family: 'Impact', 'Arial Black', sans-serif; letter-spacing: 0.05em;">
                    HELP WANTED
                </div>
            </div>

            <!-- Handwritten note section -->
            <div class="relative z-10 flex justify-center mt-3 mb-2">
                <div class="bg-background px-6 py-3 rounded-lg">
                    <p class="text-foreground text-2xl md:text-3xl italic font-semibold" style="font-family: 'Brush Script MT', 'Lucida Handwriting', cursive;">
                        reviews, coders, educators
                    </p>
                </div>
            </div>

            <!-- Subtle "click here" text -->
            <div class="relative z-10 text-center mt-2">
                <span class="text-xs text-background/70 group-hover:text-background transition-colors">
                    click here
                </span>
            </div>
        </div>
    </component>
</template>
