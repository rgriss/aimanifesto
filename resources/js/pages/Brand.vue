<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

const lightModeColors = [
    {
        name: 'Background',
        hex: '#FFFFFF',
        rgb: '255, 255, 255',
        description: 'Pure white for page backgrounds',
        token: 'background',
        textColor: 'text-foreground'
    },
    {
        name: 'Foreground',
        hex: '#1A1A1A',
        rgb: '26, 26, 26',
        description: 'Near black for primary text and headings',
        token: 'foreground',
        textColor: 'text-background'
    },
    {
        name: 'Muted Text',
        hex: '#737373',
        rgb: '115, 115, 115',
        description: 'Medium grey for secondary text',
        token: 'muted-foreground',
        textColor: 'text-background'
    },
    {
        name: 'Border',
        hex: '#E0E0E0',
        rgb: '224, 224, 224',
        description: 'Light grey for borders and dividers',
        token: 'border',
        textColor: 'text-foreground'
    },
    {
        name: 'Secondary',
        hex: '#F0F0F0',
        rgb: '240, 240, 240',
        description: 'Light grey for subtle backgrounds',
        token: 'secondary',
        textColor: 'text-foreground'
    }
];

const darkModeColors = [
    {
        name: 'Background',
        hex: '#1A1A1A',
        rgb: '26, 26, 26',
        description: 'Near black for page backgrounds',
        token: 'background',
        textColor: 'text-background'
    },
    {
        name: 'Foreground',
        hex: '#F5F5F5',
        rgb: '245, 245, 245',
        description: 'Off-white for primary text',
        token: 'foreground',
        textColor: 'text-foreground'
    },
    {
        name: 'Card',
        hex: '#262626',
        rgb: '38, 38, 38',
        description: 'Dark grey for elevated surfaces',
        token: 'card',
        textColor: 'text-background'
    },
    {
        name: 'Muted Text',
        hex: '#8A8A8A',
        rgb: '138, 138, 138',
        description: 'Medium grey for secondary text',
        token: 'muted-foreground',
        textColor: 'text-foreground'
    },
    {
        name: 'Border',
        hex: '#404040',
        rgb: '64, 64, 64',
        description: 'Dark grey for borders',
        token: 'border',
        textColor: 'text-background'
    }
];

const semanticColors = [
    {
        name: 'Success',
        hex: '#14B8A6',
        rgb: '20, 184, 166',
        description: 'Teal for success states, positive actions',
        token: 'success',
        usage: 'Tool ratings, success messages, positive metrics'
    },
    {
        name: 'Info',
        hex: '#06B6D4',
        rgb: '6, 182, 212',
        description: 'Cyan for informational badges',
        token: 'info',
        usage: 'Informational badges, neutral states'
    },
    {
        name: 'Warning',
        hex: '#F59E0B',
        rgb: '245, 158, 11',
        description: 'Amber for warnings, caution',
        token: 'warning',
        usage: 'Warning messages, cautionary alerts'
    },
    {
        name: 'Danger',
        hex: '#EF4444',
        rgb: '239, 68, 68',
        description: 'Red for errors, destructive actions',
        token: 'danger',
        usage: 'Error states, delete actions, critical alerts'
    }
];

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
};
</script>

<template>
    <Head title="Brand Guidelines - AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-card border-2 border-border rounded-lg shadow-lg p-8 mb-8">
                    <div class="flex items-center gap-6 mb-6">
                        <img src="/images/Polaris Pixels Star Logo.png" alt="AI Manifesto Logo" class="h-20 w-auto" />
                        <div>
                            <h1 class="text-5xl font-bold text-foreground">
                                AI Manifesto
                            </h1>
                            <p class="text-xl text-muted-foreground font-semibold">
                                Brand Guidelines
                            </p>
                        </div>
                    </div>
                    <p class="text-lg text-muted-foreground mb-2">
                        Version 2.0 - November 2025
                    </p>
                    <p class="text-muted-foreground">
                        A minimalist black and white design system that prioritizes clarity, readability, and timeless aesthetics.
                        Semantic colors are used sparingly and only for functional purposes.
                    </p>
                </div>

                <!-- Design Philosophy -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Design Philosophy</h2>
                    <div class="bg-card border border-border rounded-lg p-6">
                        <ul class="space-y-4 text-muted-foreground">
                            <li class="flex items-start gap-3">
                                <span class="text-2xl">🎯</span>
                                <div>
                                    <strong class="text-foreground">Minimalist First:</strong> Predominantly white/black/grey palette creates a clean, unbusy experience
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl">📖</span>
                                <div>
                                    <strong class="text-foreground">Readability:</strong> High contrast text ensures content is accessible and easy to read
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl">🎨</span>
                                <div>
                                    <strong class="text-foreground">Functional Color:</strong> Color is used only for semantic purposes (badges, states, alerts)
                                </div>
                            </li>
                            <li class="flex items-start gap-3">
                                <span class="text-2xl">🌓</span>
                                <div>
                                    <strong class="text-foreground">Dark Mode Native:</strong> Designed with both light and dark themes as first-class experiences
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Light Mode Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Light Mode Palette</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="color in lightModeColors" :key="color.hex" class="bg-card border border-border rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-32 flex items-center justify-center border-b border-border">
                                <span :class="color.textColor + ' text-xl font-mono font-bold'">{{ color.hex }}</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold text-foreground">{{ color.name }}</h3>
                                    <button @click="copyToClipboard(color.hex)" class="text-xs font-mono text-muted-foreground hover:text-foreground transition-colors">
                                        Copy
                                    </button>
                                </div>
                                <p class="text-sm text-muted-foreground mb-3">{{ color.description }}</p>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">RGB:</span>
                                        <button @click="copyToClipboard(color.rgb)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.rgb }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Token:</span>
                                        <button @click="copyToClipboard(color.token)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.token }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dark Mode Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Dark Mode Palette</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="color in darkModeColors" :key="color.hex" class="bg-card border border-border rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-32 flex items-center justify-center border-b border-border">
                                <span :class="color.textColor + ' text-xl font-mono font-bold'">{{ color.hex }}</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold text-foreground">{{ color.name }}</h3>
                                    <button @click="copyToClipboard(color.hex)" class="text-xs font-mono text-muted-foreground hover:text-foreground transition-colors">
                                        Copy
                                    </button>
                                </div>
                                <p class="text-sm text-muted-foreground mb-3">{{ color.description }}</p>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">RGB:</span>
                                        <button @click="copyToClipboard(color.rgb)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.rgb }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Token:</span>
                                        <button @click="copyToClipboard(color.token)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.token }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Semantic Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Semantic Colors</h2>
                    <p class="text-muted-foreground mb-6">
                        These colors are reserved exclusively for functional, semantic purposes. They should be used sparingly
                        and only for badges, alerts, and state indicators—never for decorative elements or general UI.
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="color in semanticColors" :key="color.hex" class="bg-card border border-border rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-24 flex items-center justify-center">
                                <span class="text-white text-xl font-mono font-bold">{{ color.hex }}</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-lg font-bold text-foreground">{{ color.name }}</h3>
                                    <button @click="copyToClipboard(color.hex)" class="text-xs font-mono text-muted-foreground hover:text-foreground transition-colors">
                                        Copy
                                    </button>
                                </div>
                                <p class="text-sm text-muted-foreground mb-2">{{ color.description }}</p>
                                <div class="text-xs text-muted-foreground mb-3">
                                    <strong class="text-foreground">Use for:</strong> {{ color.usage }}
                                </div>
                                <div class="space-y-1 text-xs">
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">RGB:</span>
                                        <button @click="copyToClipboard(color.rgb)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.rgb }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-muted-foreground">Token:</span>
                                        <button @click="copyToClipboard(color.token)" class="font-mono text-foreground hover:text-muted-foreground">{{ color.token }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Guidelines -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Usage Guidelines</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-card border border-border rounded-lg p-6">
                            <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                                <span class="text-2xl">✅</span> Do
                            </h3>
                            <ul class="space-y-2 text-sm text-muted-foreground">
                                <li>• Use white backgrounds with black text for optimal readability</li>
                                <li>• Apply semantic colors only to badges and state indicators</li>
                                <li>• Maintain high contrast ratios (WCAG AA minimum)</li>
                                <li>• Use grey for secondary information and subtle elements</li>
                                <li>• Test designs in both light and dark modes</li>
                                <li>• Keep color usage minimal and intentional</li>
                            </ul>
                        </div>
                        <div class="bg-card border border-border rounded-lg p-6">
                            <h3 class="text-xl font-bold text-foreground mb-4 flex items-center gap-2">
                                <span class="text-2xl">❌</span> Don't
                            </h3>
                            <ul class="space-y-2 text-sm text-muted-foreground">
                                <li>• Use semantic colors for decorative purposes</li>
                                <li>• Create colorful gradients or backgrounds</li>
                                <li>• Mix multiple bright colors in one interface</li>
                                <li>• Use low-contrast color combinations</li>
                                <li>• Override the established color tokens</li>
                                <li>• Add colors "just because" — every color needs a purpose</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Accessibility -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-foreground">Accessibility</h2>
                    <div class="bg-card border border-border rounded-lg p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-foreground mb-2">4.5:1</div>
                                <div class="text-sm text-muted-foreground">Minimum contrast ratio for body text (WCAG AA)</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-foreground mb-2">3:1</div>
                                <div class="text-sm text-muted-foreground">Minimum contrast ratio for large text (WCAG AA)</div>
                            </div>
                            <div class="text-center">
                                <div class="text-4xl font-bold text-foreground mb-2">7:1</div>
                                <div class="text-sm text-muted-foreground">Target contrast ratio for enhanced readability (WCAG AAA)</div>
                            </div>
                        </div>
                        <p class="text-sm text-muted-foreground">
                            All color combinations in this design system meet or exceed WCAG 2.1 Level AA standards.
                            The high-contrast black and white palette ensures excellent readability for all users,
                            including those with visual impairments or color blindness.
                        </p>
                    </div>
                </div>

                <!-- Version History -->
                <div class="border-t border-border pt-8">
                    <h2 class="text-2xl font-bold mb-4 text-foreground">Version History</h2>
                    <div class="space-y-4 text-sm">
                        <div class="flex gap-4">
                            <div class="font-mono font-bold text-foreground">v2.0</div>
                            <div class="flex-1">
                                <div class="font-semibold text-foreground mb-1">November 2025 - Minimalist Redesign</div>
                                <div class="text-muted-foreground">Complete overhaul to black/white/grey palette. Removed all decorative blues. Semantic colors reserved for badges only. Added comprehensive dark mode support.</div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="font-mono font-bold text-muted-foreground">v1.0</div>
                            <div class="flex-1">
                                <div class="font-semibold text-muted-foreground mb-1">October 2025 - Polaris Pixels</div>
                                <div class="text-muted-foreground">Initial brand identity with blue-based color system (deprecated).</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
