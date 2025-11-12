<script setup>
import { ref, watch, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { Dialog, DialogContent, DialogDescription, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { Button } from '@/components';
import { Github } from 'lucide-vue-next';

const props = defineProps({
    show: {
        type: Boolean,
        default: null, // null means use internal state
    },
    hideSign: {
        type: Boolean,
        default: false, // Hide the visual sign, only show dialog
    },
});

const emit = defineEmits(['close']);

const page = usePage();
const user = computed(() => page.props.auth?.user);
const internalOpen = ref(false);
const contactEmail = 'info@polarispixels.com';
const githubUrl = 'https://github.com/rgriss/aimanifesto';
const githubIssuesUrl = 'https://github.com/rgriss/aimanifesto/issues';

// Use external control if show prop is provided, otherwise use internal state
const open = computed({
    get: () => props.show !== null ? props.show : internalOpen.value,
    set: (value) => {
        if (props.show !== null) {
            // Externally controlled - emit close event
            if (!value) emit('close');
        } else {
            // Internally controlled
            internalOpen.value = value;
        }
    }
});
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger v-if="!hideSign" as-child>
            <button class="group block w-full">
                <!-- Help Wanted Sign -->
                <div class="relative bg-danger p-5 rounded-lg transition-transform hover:scale-105 cursor-pointer">
                    <!-- Inset white border using box-shadow -->
                    <div class="absolute inset-3 border-4 border-white rounded pointer-events-none"></div>

                    <!-- Content -->
                    <div class="relative z-10 text-center py-4">
                        <div class="text-3xl md:text-4xl font-black text-white leading-none tracking-tight uppercase" style="font-family: 'Impact', 'Arial Black', sans-serif; letter-spacing: 0.05em;">
                            HELP WANTED
                        </div>
                    </div>

                    <!-- Handwritten note section -->
                    <div class="relative z-10 flex justify-center mt-3 mb-2">
                        <div class="bg-white px-6 py-3 rounded-lg">
                            <p class="text-gray-900 text-2xl md:text-3xl italic font-semibold" style="font-family: 'Brush Script MT', 'Lucida Handwriting', cursive;">
                                Developers & Designers
                            </p>
                        </div>
                    </div>

                    <!-- Subtle "click here" text -->
                    <div class="relative z-10 text-center mt-2">
                        <span class="text-xs text-white/70 group-hover:text-white transition-colors">
                            click here
                        </span>
                    </div>
                </div>
            </button>
        </DialogTrigger>

        <DialogContent class="max-w-2xl">
            <DialogHeader>
                <DialogTitle class="text-2xl font-bold">Join Our Open Source Community</DialogTitle>
                <DialogDescription class="sr-only">Information about contributing to The AI Manifesto</DialogDescription>
            </DialogHeader>

            <div class="space-y-4 py-4">
                <p class="text-foreground leading-relaxed">
                    The AI Manifesto is an <strong>open source project</strong> built by developers and designers who believe in responsible AI development.
                </p>

                <div class="space-y-3">
                    <p class="font-semibold text-foreground">We would love to have you join our community if you are:</p>
                    <ul class="space-y-2 ml-6 list-disc text-muted-foreground">
                        <li>A brand new developer or designer honing your skills</li>
                        <li>Looking for an open source project to contribute to</li>
                        <li>Interested in learning about technology and artificial intelligence</li>
                        <li>Seeking opportunities to grow and connect with others</li>
                    </ul>
                </div>

                <div class="bg-secondary/30 border border-border rounded-lg p-4 space-y-2">
                    <p class="font-semibold text-foreground">How to get involved:</p>
                    <ul class="space-y-2 ml-6 list-disc text-muted-foreground text-sm">
                        <li>Raise your hand—we'd love to talk with you</li>
                        <li>Introduce yourself and share what you're interested in</li>
                        <li>Looking for work? Send your resume—we may know someone who's hiring</li>
                    </ul>
                </div>

                <!-- CTAs for Non-Logged-In Users -->
                <div v-if="!user" class="pt-4 space-y-4">
                    <div class="text-center">
                        <p class="text-sm text-muted-foreground mb-3">Ready to join us?</p>
                        <Link href="/register">
                            <Button size="lg" class="font-semibold w-full sm:w-auto">
                                Sign the Manifesto
                            </Button>
                        </Link>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a
                            :href="`mailto:${contactEmail}?subject=I Want to Join The AI Manifesto Community`"
                            class="inline-block"
                        >
                            <Button variant="outline" size="lg" class="w-full sm:w-auto">
                                Email Us
                            </Button>
                        </a>
                        <a
                            :href="githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block"
                        >
                            <Button variant="outline" size="lg" class="w-full sm:w-auto">
                                <Github class="mr-2 h-4 w-4" />
                                View on GitHub
                            </Button>
                        </a>
                    </div>

                    <div class="flex justify-center">
                        <a
                            :href="githubIssuesUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block"
                        >
                            <Button variant="outline" size="lg" class="w-full sm:w-auto">
                                <Github class="mr-2 h-4 w-4" />
                                Report Issues & Contribute
                            </Button>
                        </a>
                    </div>
                </div>

                <!-- CTAs for Logged-In Users -->
                <div v-else class="pt-4 space-y-4">
                    <div class="text-center">
                        <p class="text-sm text-muted-foreground mb-3">Ready to join us?</p>
                        <a
                            :href="`mailto:${contactEmail}?subject=I Want to Join The AI Manifesto Community`"
                            class="inline-block"
                        >
                            <Button size="lg" class="font-semibold w-full sm:w-auto">
                                Email Us at {{ contactEmail }}
                            </Button>
                        </a>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a
                            :href="githubUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block"
                        >
                            <Button variant="outline" size="lg" class="w-full sm:w-auto">
                                <Github class="mr-2 h-4 w-4" />
                                View on GitHub
                            </Button>
                        </a>
                        <a
                            :href="githubIssuesUrl"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-block"
                        >
                            <Button variant="outline" size="lg" class="w-full sm:w-auto">
                                <Github class="mr-2 h-4 w-4" />
                                Report Issues & Contribute
                            </Button>
                        </a>
                    </div>
                </div>
            </div>
        </DialogContent>
    </Dialog>
</template>
