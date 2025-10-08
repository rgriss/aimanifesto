<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navigation -->
        <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <Link 
                            :href="route('home')" 
                            class="flex items-center text-2xl font-bold text-gray-900 dark:text-white hover:text-gray-700 dark:hover:text-gray-300"
                        >
                            AI Manifesto
                        </Link>
                    </div>
                    <div class="flex items-center space-x-8">
                        <Link 
                            :href="route('tools.index')" 
                            class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium"
                        >
                            Tools
                        </Link>
                        <Link 
                            :href="route('categories.index')" 
                            class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white font-medium"
                        >
                            Categories
                        </Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <p class="text-center text-gray-600 dark:text-gray-400 text-sm">
                    AI Manifesto © 2025 - Curated by Ryan Grissinger
                </p>
            </div>
        </footer>
    </div>
</template>