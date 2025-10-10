<script setup>
import { Head } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';

const colors = {
    primary: [
        {
            name: 'Polaris Blue',
            subtitle: 'Primary Brand Color',
            hex: '#1B3A5F',
            rgb: '27, 58, 95',
            cmyk: '72, 39, 0, 63',
            description: 'A sophisticated dark blue that evokes trust, depth, and technical expertise. Named after the North Star (Polaris), suggesting guidance and direction.',
            token: 'primary'
        },
        {
            name: 'Constellation Navy',
            subtitle: 'Deep Accent',
            hex: '#0A1929',
            rgb: '10, 25, 41',
            cmyk: '76, 39, 0, 84',
            description: 'A rich, almost-black navy for backgrounds and creating depth. Great for headers and establishing hierarchy.',
            token: 'secondary'
        }
    ],
    secondary: [
        {
            name: 'Stellar White',
            subtitle: 'Primary Light',
            hex: '#F8FAFC',
            rgb: '248, 250, 252',
            cmyk: '2, 1, 0, 1',
            description: 'A crisp, clean off-white for backgrounds and text contrast.'
        },
        {
            name: 'Nebula Gray',
            subtitle: 'Neutral',
            hex: '#64748B',
            rgb: '100, 116, 139',
            cmyk: '28, 17, 0, 45',
            description: 'A balanced gray for secondary text and UI elements.'
        }
    ],
    accent: [
        {
            name: 'North Star Cyan',
            subtitle: 'Highlight/CTA/Info',
            hex: '#06B6D4',
            rgb: '6, 182, 212',
            cmyk: '97, 14, 0, 17',
            description: 'A vibrant cyan that pops against the dark blues. Perfect for CTAs, links, and highlighting key information. Also used for informational states and help text.',
            token: 'info'
        },
        {
            name: 'Aurora Teal',
            subtitle: 'Success/Growth',
            hex: '#14B8A6',
            rgb: '20, 184, 166',
            cmyk: '89, 0, 10, 28',
            description: 'Suggests innovation and forward movement. Use for success states and growth metrics.',
            token: 'success'
        },
        {
            name: 'Horizon Slate',
            subtitle: 'Subtle Accent/Dark',
            hex: '#475569',
            rgb: '71, 85, 105',
            cmyk: '32, 19, 0, 59',
            description: 'A muted slate for borders, dividers, and subtle elements. Can also serve as a dark variant for text on light backgrounds.',
            token: 'dark'
        }
    ],
    functional: [
        {
            name: 'Solar Amber',
            subtitle: 'Warning/Caution',
            hex: '#F59E0B',
            rgb: '245, 158, 11',
            cmyk: '0, 36, 96, 4',
            description: 'A warm amber for warning states, important notices, and cautionary alerts. Use sparingly to maintain impact.',
            token: 'warning'
        },
        {
            name: 'Supernova Red',
            subtitle: 'Error/Danger',
            hex: '#EF4444',
            rgb: '239, 68, 68',
            cmyk: '0, 72, 72, 6',
            description: 'A vibrant red for error states and destructive actions. Use only for critical alerts and validation errors.',
            token: 'danger'
        }
    ]
};

const colorTokens = [
    { token: 'primary', name: 'Polaris Blue', hex: '#1B3A5F', use: 'Primary brand, headers, sections' },
    { token: 'secondary', name: 'Constellation Navy', hex: '#0A1929', use: 'Text, dark backgrounds' },
    { token: 'success', name: 'Aurora Teal', hex: '#14B8A6', use: 'Success states, growth metrics' },
    { token: 'info', name: 'North Star Cyan', hex: '#06B6D4', use: 'Informational states, tips, help text' },
    { token: 'warning', name: 'Solar Amber', hex: '#F59E0B', use: 'Warnings, caution, important notices' },
    { token: 'danger', name: 'Supernova Red', hex: '#EF4444', use: 'Errors, destructive actions' },
    { token: 'light', name: 'Stellar White', hex: '#F8FAFC', use: 'Light backgrounds, text on dark' },
    { token: 'dark', name: 'Horizon Slate', hex: '#475569', use: 'Dark text, borders, dividers' },
    { token: 'muted', name: 'Nebula Gray', hex: '#64748B', use: 'Secondary text, subtle UI elements' },
];

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
};
</script>

<template>
    <Head title="Brand Color Palette - Polaris Pixels" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-gradient-to-br from-[#1B3A5F] via-[#0A1929] to-[#06B6D4] rounded-lg shadow-lg p-8 mb-8 text-white">
                    <div class="flex items-center gap-6 mb-6">
                        <img src="/images/Polaris Pixels Star Logo.png" alt="Polaris Pixels Logo" class="h-20 w-auto" />
                        <div>
                            <h1 class="text-5xl font-bold">
                                Polaris Pixels
                            </h1>
                            <p class="text-xl text-[#06B6D4] font-semibold">
                                Brand Guidelines
                            </p>
                        </div>
                    </div>
                    <p class="text-xl text-[#06B6D4] mb-2 font-semibold">
                        Version 1.0 - October 2025
                    </p>
                    <p class="text-gray-200">
                        A sophisticated color system designed to communicate technical expertise, innovation, and guidance.
                    </p>
                </div>

                <!-- Primary Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Primary Colors</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="color in colors.primary" :key="color.hex" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-48 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ color.hex }}</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-2xl font-bold">{{ color.name }}</h3>
                                    <span v-if="color.token" class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">{{ color.token }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ color.subtitle }}</p>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ color.description }}</p>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">HEX:</span>
                                        <button @click="copyToClipboard(color.hex)" class="font-mono hover:text-blue-600">{{ color.hex }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">RGB:</span>
                                        <span class="font-mono">{{ color.rgb }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">CMYK:</span>
                                        <span class="font-mono">{{ color.cmyk }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Secondary Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Secondary Colors</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="color in colors.secondary" :key="color.hex" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-48 flex items-center justify-center border border-gray-200">
                                <span :class="color.hex === '#F8FAFC' ? 'text-gray-900' : 'text-white'" class="text-2xl font-bold">{{ color.hex }}</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-2xl font-bold mb-1">{{ color.name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ color.subtitle }}</p>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ color.description }}</p>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">HEX:</span>
                                        <button @click="copyToClipboard(color.hex)" class="font-mono hover:text-blue-600">{{ color.hex }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">RGB:</span>
                                        <span class="font-mono">{{ color.rgb }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">CMYK:</span>
                                        <span class="font-mono">{{ color.cmyk }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Accent Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Accent Colors</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div v-for="color in colors.accent" :key="color.hex" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-48 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ color.hex }}</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xl font-bold">{{ color.name }}</h3>
                                    <span v-if="color.token" class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">{{ color.token }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ color.subtitle }}</p>
                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">{{ color.description }}</p>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">HEX:</span>
                                        <button @click="copyToClipboard(color.hex)" class="font-mono hover:text-blue-600">{{ color.hex }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">RGB:</span>
                                        <span class="font-mono text-xs">{{ color.rgb }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Functional Colors -->
                <div class="mb-12">
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">Functional Colors</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div v-for="color in colors.functional" :key="color.hex" class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <div :style="{ backgroundColor: color.hex }" class="h-48 flex items-center justify-center">
                                <span class="text-white text-2xl font-bold">{{ color.hex }}</span>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center justify-between mb-1">
                                    <h3 class="text-xl font-bold">{{ color.name }}</h3>
                                    <span v-if="color.token" class="text-xs bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded font-mono">{{ color.token }}</span>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ color.subtitle }}</p>
                                <p class="text-gray-600 dark:text-gray-300 mb-4 text-sm">{{ color.description }}</p>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">HEX:</span>
                                        <button @click="copyToClipboard(color.hex)" class="font-mono hover:text-blue-600">{{ color.hex }}</button>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">RGB:</span>
                                        <span class="font-mono text-xs">{{ color.rgb }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">CMYK:</span>
                                        <span class="font-mono text-xs">{{ color.cmyk }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                            <strong>Note:</strong> Functional colors should be used sparingly and only for their intended purpose (warnings, errors). They are not part of the primary brand expression.
                        </p>
                    </div>
                </div>

                <!-- Brand Personality -->
                <div class="bg-gradient-to-r from-[#1B3A5F] to-[#0A1929] rounded-lg shadow p-8 mb-8 text-white">
                    <h2 class="text-3xl font-bold mb-6">Brand Personality</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-xl font-bold mb-3">The Polaris Pixels color palette communicates:</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="text-[#06B6D4] mr-2">✓</span>
                                    <span>Technical expertise without being cold</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#06B6D4] mr-2">✓</span>
                                    <span>Innovation without being flashy</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#06B6D4] mr-2">✓</span>
                                    <span>Guidance and navigation (celestial theme)</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#06B6D4] mr-2">✓</span>
                                    <span>Professional but human</span>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold mb-3">Color Psychology:</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start">
                                    <span class="text-[#14B8A6] mr-2">→</span>
                                    <span><strong>Professional Authority:</strong> Dark blue foundation establishes credibility</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#14B8A6] mr-2">→</span>
                                    <span><strong>Innovation:</strong> Cyan and teal suggest forward-thinking</span>
                                </li>
                                <li class="flex items-start">
                                    <span class="text-[#14B8A6] mr-2">→</span>
                                    <span><strong>Navigation:</strong> Celestial naming reinforces guidance theme</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Color Tokens (Bootstrap-style) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 mb-8">
                    <h2 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">Color Tokens</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Bootstrap-style semantic color tokens for consistent usage across the application.</p>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 dark:border-gray-700">
                                    <th class="text-left py-3 px-4">Token</th>
                                    <th class="text-left py-3 px-4">Color Name</th>
                                    <th class="text-left py-3 px-4">Hex Code</th>
                                    <th class="text-left py-3 px-4">Primary Use</th>
                                    <th class="py-3 px-4">Swatch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="token in colorTokens" :key="token.token" class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                    <td class="py-3 px-4">
                                        <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded text-xs font-mono">{{ token.token }}</code>
                                    </td>
                                    <td class="py-3 px-4 font-semibold">{{ token.name }}</td>
                                    <td class="py-3 px-4">
                                        <button @click="copyToClipboard(token.hex)" class="font-mono hover:text-blue-600">{{ token.hex }}</button>
                                    </td>
                                    <td class="py-3 px-4 text-gray-600 dark:text-gray-400">{{ token.use }}</td>
                                    <td class="py-3 px-4">
                                        <div class="w-16 h-8 rounded shadow-sm border border-gray-200 dark:border-gray-600" :style="{ background: token.hex }"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Reference -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
                    <h2 class="text-3xl font-bold mb-4 text-gray-900 dark:text-white">Complete Color Reference</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">All colors in the Polaris Pixels palette at a glance.</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="color in [...colors.primary, ...colors.secondary, ...colors.accent, ...colors.functional]" :key="color.hex" class="text-center">
                            <div :style="{ backgroundColor: color.hex }" class="h-24 rounded-lg shadow-md mb-2 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                <span :class="[color.hex === '#F8FAFC' ? 'text-gray-900' : 'text-white']" class="text-xs font-bold">{{ color.hex }}</span>
                            </div>
                            <p class="font-semibold text-sm mb-1">{{ color.name }}</p>
                            <button @click="copyToClipboard(color.hex)" class="text-xs font-mono text-gray-500 hover:text-blue-600">{{ color.hex }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
