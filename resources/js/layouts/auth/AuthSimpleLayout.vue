<script setup lang="ts">
import { home } from '@/routes';
import { Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Info, CheckCircle2, AlertCircle } from 'lucide-vue-next';

defineProps<{
    title?: string;
    description?: string;
}>();

const clickCount = ref(0);
const clickTimeout = ref<number | null>(null);
const message = ref<{ type: 'info' | 'success' | 'error', title: string, description: string } | null>(null);
const messageTimeout = ref<number | null>(null);

const showMessage = (type: 'info' | 'success' | 'error', title: string, description: string) => {
    message.value = { type, title, description };

    if (messageTimeout.value) {
        clearTimeout(messageTimeout.value);
    }

    messageTimeout.value = window.setTimeout(() => {
        message.value = null;
    }, 5000);
};

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
        showMessage('info', 'Keep clicking...', `${10 - clickCount.value} more clicks to trigger emergency admin creation`);
    }

    // Trigger emergency admin creation at 10 clicks
    if (clickCount.value >= 10) {
        clickCount.value = 0;
        if (clickTimeout.value) {
            clearTimeout(clickTimeout.value);
        }

        showMessage('info', 'Creating emergency admin...', 'Please wait...');

        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            if (!csrfToken) {
                showMessage('error', 'Error', 'CSRF token not found. Please refresh the page.');
                return;
            }

            const response = await fetch('/emergency-admin', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            if (!response.ok) {
                const errorText = await response.text();
                console.error('Emergency admin error:', response.status, errorText);

                if (response.status === 404) {
                    showMessage('error', 'Error', 'Emergency admin feature is disabled.');
                } else if (response.status === 429) {
                    showMessage('error', 'Rate Limited', 'Too many attempts. Please try again later.');
                } else {
                    showMessage('error', 'Error', `Request failed (${response.status}). Check console for details.`);
                }
                return;
            }

            const data = await response.json();

            if (data.success) {
                showMessage('success', 'Success!', data.message + ' You can now log in.');
            } else {
                showMessage('info', 'Info', data.message);
            }
        } catch (error) {
            console.error('Emergency admin exception:', error);
            showMessage('error', 'Error', `Emergency admin creation failed: ${error.message}`);
        }
    }
};
</script>

<template>
    <div
        class="flex min-h-svh flex-col items-center justify-center gap-6 bg-background p-6 md:p-10"
    >
        <!-- Emergency Admin Message - Floating, doesn't cause layout shift -->
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 -translate-y-4"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-4"
        >
            <div
                v-if="message"
                class="fixed top-6 left-1/2 -translate-x-1/2 w-full max-w-sm px-6 md:px-0 z-50"
            >
                <Alert :variant="message.type === 'error' ? 'destructive' : 'default'" class="shadow-lg">
                    <CheckCircle2 v-if="message.type === 'success'" class="h-4 w-4" />
                    <Info v-else-if="message.type === 'info'" class="h-4 w-4" />
                    <AlertCircle v-else class="h-4 w-4" />
                    <AlertTitle>{{ message.title }}</AlertTitle>
                    <AlertDescription>{{ message.description }}</AlertDescription>
                </Alert>
            </div>
        </Transition>

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
