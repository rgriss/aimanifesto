<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Database, Settings, Users, BarChart3, FileJson, Shield } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin',
    },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-start gap-6">
                <img src="/images/wave-logo-blue.png" alt="AI Manifesto" class="h-16 w-auto flex-shrink-0 dark:hidden" />
                <img src="/images/wave-logo-white.png" alt="AI Manifesto" class="h-16 w-auto flex-shrink-0 hidden dark:block" />
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <Shield class="h-8 w-8 text-primary" />
                        Admin Dashboard
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Manage your AI Manifesto application
                    </p>
                </div>
            </div>

            <!-- Admin Actions Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <!-- Data Management -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer">
                    <Link :href="'/admin/data'" class="block">
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <Database class="h-10 w-10 text-muted-foreground" />
                                <div class="rounded-full bg-secondary px-3 py-1 text-xs font-semibold text-secondary-foreground">
                                    Active
                                </div>
                            </div>
                            <CardTitle class="mt-4">Data Management</CardTitle>
                            <CardDescription>
                                Import and export your tools and categories database
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <FileJson class="h-4 w-4" />
                                <span>JSON Import/Export</span>
                            </div>
                        </CardContent>
                    </Link>
                </Card>

                <!-- Category Management -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer">
                    <Link :href="'/admin/categories'" class="block">
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <BarChart3 class="h-10 w-10 text-green-500" />
                                <div class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900 dark:text-green-300">
                                    Active
                                </div>
                            </div>
                            <CardTitle class="mt-4">Category Management</CardTitle>
                            <CardDescription>
                                Organize and manage tool categories
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <Settings class="h-4 w-4" />
                                <span>Category Organization</span>
                            </div>
                        </CardContent>
                    </Link>
                </Card>

                <!-- Tool Management -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer">
                    <Link :href="'/admin/tools'" class="block">
                        <CardHeader>
                            <div class="flex items-center justify-between">
                                <Settings class="h-10 w-10 text-purple-500" />
                                <div class="rounded-full bg-purple-100 px-3 py-1 text-xs font-semibold text-purple-700 dark:bg-purple-900 dark:text-purple-300">
                                    Active
                                </div>
                            </div>
                            <CardTitle class="mt-4">Tool Management</CardTitle>
                            <CardDescription>
                                Create, edit, and delete AI tools in your directory
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                <Database class="h-4 w-4" />
                                <span>CRUD Operations</span>
                            </div>
                        </CardContent>
                    </Link>
                </Card>

                <!-- User Management -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer opacity-60">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <Users class="h-10 w-10 text-orange-500" />
                            <div class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                Coming Soon
                            </div>
                        </div>
                        <CardTitle class="mt-4">User Management</CardTitle>
                        <CardDescription>
                            Manage users and admin permissions
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <Shield class="h-4 w-4" />
                            <span>User Roles & Permissions</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Analytics -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer opacity-60">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <BarChart3 class="h-10 w-10 text-red-500" />
                            <div class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                Coming Soon
                            </div>
                        </div>
                        <CardTitle class="mt-4">Analytics</CardTitle>
                        <CardDescription>
                            View statistics and usage metrics
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <BarChart3 class="h-4 w-4" />
                            <span>Usage Statistics</span>
                        </div>
                    </CardContent>
                </Card>

                <!-- Site Settings -->
                <Card class="hover:shadow-lg transition-shadow cursor-pointer opacity-60">
                    <CardHeader>
                        <div class="flex items-center justify-between">
                            <Settings class="h-10 w-10 text-indigo-500" />
                            <div class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-300">
                                Coming Soon
                            </div>
                        </div>
                        <CardTitle class="mt-4">Site Settings</CardTitle>
                        <CardDescription>
                            Configure application settings and preferences
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                            <Settings class="h-4 w-4" />
                            <span>Configuration</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Quick Info -->
            <Card>
                <CardHeader>
                    <CardTitle>Quick Info</CardTitle>
                    <CardDescription>Important information about admin access</CardDescription>
                </CardHeader>
                <CardContent class="space-y-2 text-sm">
                    <div class="flex items-start gap-3">
                        <Shield class="h-5 w-5 text-primary mt-0.5" />
                        <div>
                            <p class="font-medium">Admin Access</p>
                            <p class="text-muted-foreground">
                                Only users with admin privileges can access this area. Admin status is managed through the database.
                            </p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <Database class="h-5 w-5 text-primary mt-0.5" />
                        <div>
                            <p class="font-medium">Data Management</p>
                            <p class="text-muted-foreground">
                                Export your database regularly to keep backups. Import/export uses JSON format for easy portability.
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
