<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { getInitials } from '@/composables/useInitials';
import { User, Settings, LogOut, LayoutDashboard, Shield } from 'lucide-vue-next';
import ThemeToggle from '@/components/ThemeToggle.vue';

const page = usePage();
const user = computed(() => page.props.auth?.user);
</script>

<template>
    <div class="min-h-screen bg-background">
        <!-- Navigation -->
        <nav class="bg-card border-b border-border">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <Link
                            href="/"
                            class="flex items-center gap-3 text-2xl font-bold text-foreground hover:text-foreground/70"
                        >
                            <img src="/images/wave-logo-blue.png" alt="The AI Manifesto" class="h-8 w-auto dark:hidden" />
                            <img src="/images/wave-logo-white.png" alt="The AI Manifesto" class="h-8 w-auto hidden dark:block" />
                            <span>The AI Manifesto</span>
                        </Link>
                    </div>
                    <div class="flex items-center space-x-8">
                        <Link
                            href="/tools"
                            class="text-muted-foreground hover:text-foreground font-medium"
                        >
                            Tools
                        </Link>
                        <Link
                            href="/categories"
                            class="text-muted-foreground hover:text-foreground font-medium"
                        >
                            Categories
                        </Link>

                        <!-- User Menu / Login -->
                        <div v-if="user">
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <button class="focus:outline-none">
                                        <Avatar class="h-8 w-8 cursor-pointer hover:ring-2 hover:ring-primary transition-all">
                                            <AvatarFallback class="bg-primary text-primary-foreground">
                                                {{ getInitials(user.name) }}
                                            </AvatarFallback>
                                        </Avatar>
                                    </button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end" class="w-56">
                                    <DropdownMenuLabel>
                                        <div class="flex flex-col space-y-1">
                                            <p class="text-sm font-medium">{{ user.name }}</p>
                                            <p class="text-xs text-muted-foreground">{{ user.email }}</p>
                                        </div>
                                    </DropdownMenuLabel>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem as-child>
                                        <Link href="/dashboard" class="flex items-center cursor-pointer">
                                            <LayoutDashboard class="mr-2 h-4 w-4" />
                                            Dashboard
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem v-if="user.is_admin" as-child>
                                        <Link href="/admin" class="flex items-center cursor-pointer">
                                            <Shield class="mr-2 h-4 w-4" />
                                            Admin
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem as-child>
                                        <Link href="/settings/profile" class="flex items-center cursor-pointer">
                                            <User class="mr-2 h-4 w-4" />
                                            Profile
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuItem as-child>
                                        <Link href="/settings" class="flex items-center cursor-pointer">
                                            <Settings class="mr-2 h-4 w-4" />
                                            Settings
                                        </Link>
                                    </DropdownMenuItem>
                                    <DropdownMenuSeparator />
                                    <DropdownMenuItem as-child>
                                        <Link
                                            href="/logout"
                                            method="post"
                                            as="button"
                                            class="flex items-center cursor-pointer w-full text-destructive"
                                        >
                                            <LogOut class="mr-2 h-4 w-4" />
                                            Log Out
                                        </Link>
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
                        </div>
                        <div v-else class="flex items-center space-x-2">
                            <Link href="/login">
                                <Button variant="ghost" size="sm">
                                    Log In
                                </Button>
                            </Link>
                            <Link href="/register">
                                <Button size="sm">
                                    Sign Up
                                </Button>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-card border-t border-border mt-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <!-- Theme Toggle - Far Left -->
                    <ThemeToggle />

                    <!-- Copyright - Center/Right -->
                    <p class="text-muted-foreground text-sm flex-1 text-center sm:text-right">
                        The AI Manifesto © 2025 - Curated by Ryan Grissinger
                    </p>
                </div>
            </div>
        </footer>
    </div>
</template>