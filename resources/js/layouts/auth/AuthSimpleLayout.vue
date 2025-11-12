<script setup lang="ts">
import { home } from '@/routes';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useToast } from '@/components/ui/toast';

defineProps<{
    title?: string;
    description?: string;
}>();

const { toast } = useToast();
const clickCount = ref(0);
const clickTimeout = ref<number | null>(null);

const handleLogoClick = async (e: MouseEvent) => {
    // Prevent navigation
    e.preventDefault();
    e.stopPropagation();

    clickCount.value++;

    // Reset counter after 2 seconds of no clicks
    if (clickTimeout.value) {
        clearTimeout(clickTimeout.value);
    }
    clickTimeout.value = window.setTimeout(() => {
        clickCount.value = 0;
    }, 2000);

    // Show progress hints
    if (clickCount.value === 5) {
        toast({
            title: 'Keep clicking...',
            description: `${10 - clickCount.value} more clicks to trigger emergency admin creation`,
        });
    }

    // Trigger emergency admin creation at 10 clicks
    if (clickCount.value >= 10) {
        clickCount.value = 0;
        if (clickTimeout.value) {
            clearTimeout(clickTimeout.value);
        }

        toast({
            title: 'Creating emergency admin...',
            description: 'Please wait...',
        });

        try {
            const response = await fetch('/emergency-admin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
            });

            const data = await response.json();

            if (data.success) {
                toast({
                    title: 'Success!',
                    description: data.message + ' You can now log in.',
                    variant: 'default',
                });
            } else {
                toast({
                    title: 'Info',
                    description: data.message,
                    variant: 'default',
                });
            }
        } catch (error) {
            toast({
                title: 'Error',
                description: 'Emergency admin creation is not available.',
                variant: 'destructive',
            });
        }
    }
};
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <div class="w-full max-w-sm">
            <div class="flex flex-col gap-8">
                <div class="flex flex-col items-center gap-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div
                            class="mb-1 flex h-12 items-center justify-center cursor-pointer select-none"
                            @click="handleLogoClick"
                        >
                            <img src="/images/wave-logo-black.png" alt="The AI Manifesto" class="h-12 w-auto dark:hidden" />
                            <img src="/images/wave-logo-white.png" alt="The AI Manifesto" class="h-12 w-auto hidden dark:block" />
                        </div>
                        <span class="sr-only">{{ title }}</span>
                    </Link>
                    <div class="space-y-2 text-center">
                        <h1 class="text-xl font-medium">{{ title }}</h1>
                        <p class="text-center text-sm text-muted-foreground">
                            {{ description }}
                        </p>
                    </div>
                </div>
                <slot />
            </div>
        </div>
    </div>
</template>
