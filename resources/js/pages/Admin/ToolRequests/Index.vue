<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { FileQuestion, Search } from 'lucide-vue-next';
import { ref, watch } from 'vue';

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
    tool?: Tool;
    created_at: string;
}

interface Props {
    toolRequests: {
        data: ToolRequest[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search: string;
        status: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tool Requests', href: '/admin/tool-requests' },
];

const search = ref(props.filters.search);
const statusFilter = ref(props.filters.status);

watch([search, statusFilter], () => {
    router.get('/admin/tool-requests', {
        search: search.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}, { debounce: 300 });

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

const formatDate = (date: string) => {
    return new Date(date).toLocaleString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Tool Requests - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <FileQuestion class="h-8 w-8 text-primary" />
                        Tool Requests
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        View and manage user-submitted tool requests
                    </p>
                </div>
            </div>

            <!-- Filters -->
            <Card class="p-4">
                <div class="grid gap-4 md:grid-cols-2">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Search requests..."
                            class="pl-9"
                        />
                    </div>
                    <select
                        v-model="statusFilter"
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
            </Card>

            <!-- Requests List -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    User
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Request
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Tool Created
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Date
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="request in toolRequests.data" :key="request.id" class="hover:bg-muted/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="font-medium">{{ request.user.name }}</div>
                                        <div class="text-sm text-muted-foreground">{{ request.user.email }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-medium">{{ request.user_input }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge :class="getStatusColor(request.status)" class="capitalize border">
                                        {{ request.status }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Link v-if="request.tool" :href="`/admin/tools/${request.tool.slug}/edit`" class="text-primary hover:underline">
                                        {{ request.tool.name }}
                                    </Link>
                                    <span v-else class="text-muted-foreground">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ formatDate(request.created_at) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="`/admin/tool-requests/${request.id}`">
                                        <Button variant="ghost" size="sm">
                                            View Details
                                        </Button>
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="toolRequests.last_page > 1" class="border-t px-6 py-4 flex items-center justify-between">
                    <div class="text-sm text-muted-foreground">
                        Showing {{ ((toolRequests.current_page - 1) * toolRequests.per_page) + 1 }} to {{ Math.min(toolRequests.current_page * toolRequests.per_page, toolRequests.total) }} of {{ toolRequests.total }} requests
                    </div>
                    <div class="flex gap-2">
                        <Link
                            v-for="page in toolRequests.last_page"
                            :key="page"
                            :href="`/admin/tool-requests?page=${page}&search=${search}&status=${statusFilter}`"
                            preserve-state
                        >
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'bg-primary text-primary-foreground': page === toolRequests.current_page }"
                            >
                                {{ page }}
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="toolRequests.data.length === 0" class="text-center py-12">
                    <FileQuestion class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-foreground mb-2">No requests found</h3>
                    <p class="text-muted-foreground">
                        {{ search || statusFilter ? 'Try adjusting your filters' : 'No tool requests have been submitted yet' }}
                    </p>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
