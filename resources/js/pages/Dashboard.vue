<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface ToolRequestStats {
    total: number;
    pending: number;
    approved: number;
    rejected: number;
    completed: number;
    failed: number;
}

interface RecentRequest {
    id: number;
    user_input: string;
    status: string;
    user_name: string;
    created_at: string;
}

interface QueueStats {
    pending: number;
    failed: number;
    processed_today: number;
}

interface FailedJob {
    id: string;
    queue: string;
    exception: string;
    failed_at: string;
}

const props = defineProps<{
    toolRequestStats: ToolRequestStats;
    recentRequests: RecentRequest[];
    queueStats: QueueStats;
    failedJobs: FailedJob[];
}>();

const getStatusColor = (status: string) => {
    const colors = {
        pending: 'bg-yellow-500/10 text-yellow-700 dark:text-yellow-400',
        approved: 'bg-blue-500/10 text-blue-700 dark:text-blue-400',
        rejected: 'bg-red-500/10 text-red-700 dark:text-red-400',
        completed: 'bg-green-500/10 text-green-700 dark:text-green-400',
        failed: 'bg-red-500/10 text-red-700 dark:text-red-400',
    };
    return colors[status as keyof typeof colors] || 'bg-gray-500/10 text-gray-700 dark:text-gray-400';
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <!-- Tool Requests Card -->
                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border"
                >
                    <h3 class="mb-4 text-lg font-semibold">Tool Requests</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ toolRequestStats.total }}</div>
                            <div class="text-xs text-muted-foreground">Total</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ toolRequestStats.pending }}</div>
                            <div class="text-xs text-muted-foreground">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ toolRequestStats.completed }}</div>
                            <div class="text-xs text-muted-foreground">Completed</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ toolRequestStats.failed }}</div>
                            <div class="text-xs text-muted-foreground">Failed</div>
                        </div>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div v-for="request in recentRequests.slice(0, 3)" :key="request.id" class="flex items-center justify-between text-sm">
                            <span class="truncate flex-1">{{ request.user_input }}</span>
                            <span :class="['ml-2 rounded-full px-2 py-0.5 text-xs font-medium', getStatusColor(request.status)]">
                                {{ request.status }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Queue Monitor Card -->
                <div
                    class="relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-background p-6 dark:border-sidebar-border"
                >
                    <h3 class="mb-4 text-lg font-semibold">Queue Monitor</h3>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center">
                            <div class="text-2xl font-bold">{{ queueStats.pending }}</div>
                            <div class="text-xs text-muted-foreground">Pending</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-red-600">{{ queueStats.failed }}</div>
                            <div class="text-xs text-muted-foreground">Failed</div>
                        </div>
                        <div class="text-center col-span-2">
                            <div class="text-2xl font-bold text-blue-600">{{ queueStats.processed_today }}</div>
                            <div class="text-xs text-muted-foreground">Processed Today</div>
                        </div>
                    </div>
                    <div v-if="failedJobs.length > 0" class="mt-4 space-y-2">
                        <div class="text-xs font-semibold text-muted-foreground">Recent Failures:</div>
                        <div v-for="job in failedJobs.slice(0, 2)" :key="job.id" class="text-xs">
                            <div class="font-medium">{{ job.queue }}</div>
                            <div class="truncate text-muted-foreground">{{ job.exception }}</div>
                        </div>
                    </div>
                    <div v-else class="mt-4 text-sm text-green-600">
                        ✓ No failed jobs
                    </div>
                </div>

                <!-- Placeholder cards for future use -->
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-sidebar-border/20 to-transparent dark:border-sidebar-border"
                >
                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">
                        Coming Soon
                    </div>
                </div>
            </div>
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-sidebar-border/20 to-transparent dark:border-sidebar-border"
                >
                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">
                        Coming Soon
                    </div>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-sidebar-border/20 to-transparent dark:border-sidebar-border"
                >
                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">
                        Coming Soon
                    </div>
                </div>
                <div
                    class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-sidebar-border/20 to-transparent dark:border-sidebar-border"
                >
                    <div class="flex h-full items-center justify-center text-sm text-muted-foreground">
                        Coming Soon
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
