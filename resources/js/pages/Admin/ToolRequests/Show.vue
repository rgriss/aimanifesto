<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { FileQuestion, User, Calendar, CheckCircle2, XCircle, AlertCircle } from 'lucide-vue-next';

interface User {
    id: number;
    name: string;
    email: string;
}

interface Tool {
    id: number;
    name: string;
    slug: string;
}

interface ToolRequest {
    id: number;
    user: User;
    user_input: string;
    status: string;
    validation_result: any;
    rejection_reason: string | null;
    tool?: Tool;
    tool_id: number | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    toolRequest: ToolRequest;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tool Requests', href: '/admin/tool-requests' },
    { title: `Request #${props.toolRequest.id}`, href: `/admin/tool-requests/${props.toolRequest.id}` },
];

const getStatusColor = (status: string) => {
    const colors = {
        pending: 'bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-500/20',
        approved: 'bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-500/20',
        rejected: 'bg-red-500/10 text-red-700 dark:text-red-400 border-red-500/20',
        completed: 'bg-green-500/10 text-green-700 dark:text-green-400 border-green-500/20',
        failed: 'bg-red-500/10 text-red-700 dark:text-red-400 border-red-500/20',
    };
    return colors[status as keyof typeof colors] || 'bg-gray-500/10 text-gray-700 dark:text-gray-400 border-gray-500/20';
};

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'completed': return CheckCircle2;
        case 'failed':
        case 'rejected': return XCircle;
        default: return AlertCircle;
    }
};

const formatDate = (date: string) => {
    return new Date(date).toLocaleString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head :title="`Tool Request #${toolRequest.id} - Admin`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <FileQuestion class="h-8 w-8 text-primary" />
                        Tool Request #{{ toolRequest.id }}
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Submitted by {{ toolRequest.user.name }}
                    </p>
                </div>
                <Link :href="'/admin/tool-requests'">
                    <Button variant="outline">
                        Back to Requests
                    </Button>
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Request Details -->
                <Card class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Request Details</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">User Input</label>
                            <div class="mt-1 font-medium">{{ toolRequest.user_input }}</div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Status</label>
                            <div class="mt-1">
                                <Badge :class="getStatusColor(toolRequest.status)" class="capitalize border">
                                    <component :is="getStatusIcon(toolRequest.status)" class="h-3 w-3 mr-1" />
                                    {{ toolRequest.status }}
                                </Badge>
                            </div>
                        </div>
                        <div v-if="toolRequest.tool">
                            <label class="text-sm font-medium text-muted-foreground">Created Tool</label>
                            <div class="mt-1">
                                <Link :href="`/admin/tools/${toolRequest.tool.slug}/edit`" class="text-primary hover:underline font-medium">
                                    {{ toolRequest.tool.name }}
                                </Link>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground flex items-center gap-2">
                                <Calendar class="h-4 w-4" />
                                Created At
                            </label>
                            <div class="mt-1">{{ formatDate(toolRequest.created_at) }}</div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Updated At</label>
                            <div class="mt-1">{{ formatDate(toolRequest.updated_at) }}</div>
                        </div>
                    </div>
                </Card>

                <!-- User Information -->
                <Card class="p-6">
                    <h2 class="text-lg font-semibold mb-4 flex items-center gap-2">
                        <User class="h-5 w-5" />
                        User Information
                    </h2>
                    <div class="space-y-4">
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Name</label>
                            <div class="mt-1 font-medium">{{ toolRequest.user.name }}</div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">Email</label>
                            <div class="mt-1">
                                <a :href="`mailto:${toolRequest.user.email}`" class="text-primary hover:underline">
                                    {{ toolRequest.user.email }}
                                </a>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-muted-foreground">User ID</label>
                            <div class="mt-1 font-mono text-sm">#{{ toolRequest.user.id }}</div>
                        </div>
                    </div>
                </Card>
            </div>

            <!-- Validation Result -->
            <Card v-if="toolRequest.validation_result" class="p-6">
                <h2 class="text-lg font-semibold mb-4">Validation Result</h2>
                <pre class="bg-muted p-4 rounded-lg overflow-x-auto text-sm">{{ JSON.stringify(toolRequest.validation_result, null, 2) }}</pre>
            </Card>

            <!-- Error Details (for failed/rejected requests) -->
            <Card v-if="toolRequest.rejection_reason" class="p-6 border-destructive/20 bg-destructive/5">
                <h2 class="text-lg font-semibold mb-4 text-destructive flex items-center gap-2">
                    <XCircle class="h-5 w-5" />
                    Error Details
                </h2>
                <div class="bg-background p-4 rounded-lg">
                    <pre class="text-sm whitespace-pre-wrap text-destructive">{{ toolRequest.rejection_reason }}</pre>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
