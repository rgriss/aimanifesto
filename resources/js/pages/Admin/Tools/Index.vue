<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { Wrench, Plus, Pencil, Trash2, Search, Star, Eye, EyeOff } from 'lucide-vue-next';
import { ref, watch } from 'vue';

interface Category {
    id: number;
    name: string;
}

interface Tool {
    id: number;
    name: string;
    slug: string;
    description: string;
    category: Category;
    pricing_model: string;
    ryan_rating: number | null;
    is_featured: boolean;
    is_active: boolean;
    views_count: number;
}

interface Props {
    tools: {
        data: Tool[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    categories: Category[];
    filters: {
        search: string;
        category: string;
        status: string;
    };
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tools', href: '/admin/tools' },
];

const search = ref(props.filters.search);
const categoryFilter = ref(props.filters.category);
const statusFilter = ref(props.filters.status);

watch([search, categoryFilter, statusFilter], () => {
    router.get('/admin/tools', {
        search: search.value,
        category: categoryFilter.value,
        status: statusFilter.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}, { debounce: 300 });

const deleteTool = (tool: Tool) => {
    if (confirm(`Are you sure you want to delete "${tool.name}"?`)) {
        router.delete(`/admin/tools/${tool.slug}`);
    }
};

const getPricingBadgeVariant = (pricing: string) => {
    const variants: Record<string, string> = {
        free: 'success',
        freemium: 'info',
        paid: 'warning',
        enterprise: 'danger',
    };
    return variants[pricing] || 'default';
};
</script>

<template>
    <Head title="Manage Tools - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <Wrench class="h-8 w-8 text-primary" />
                        Tools
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Manage AI tools in your directory
                    </p>
                </div>
                <Link :href="'/admin/tools/create'">
                    <Button>
                        <Plus class="h-4 w-4 mr-2" />
                        Add Tool
                    </Button>
                </Link>
            </div>

            <!-- Filters -->
            <Card class="p-4">
                <div class="grid gap-4 md:grid-cols-3">
                    <div class="relative">
                        <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                        <Input
                            v-model="search"
                            placeholder="Search tools..."
                            class="pl-9"
                        />
                    </div>
                    <select
                        v-model="categoryFilter"
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        <option value="">All Categories</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id.toString()">
                            {{ cat.name }}
                        </option>
                    </select>
                    <select
                        v-model="statusFilter"
                        class="flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                    >
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </Card>

            <!-- Tools List -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Tool
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Pricing
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Rating
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Views
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Status
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="tool in tools.data" :key="tool.id" class="hover:bg-muted/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-medium flex items-center gap-2">
                                            {{ tool.name }}
                                            <Star v-if="tool.is_featured" class="h-4 w-4 text-yellow-500 fill-yellow-500" />
                                        </div>
                                        <div class="text-sm text-muted-foreground truncate max-w-md">
                                            {{ tool.description }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge variant="secondary">{{ tool.category.name }}</Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge :variant="getPricingBadgeVariant(tool.pricing_model)" class="capitalize">
                                        {{ tool.pricing_model }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span v-if="tool.ryan_rating" class="flex items-center gap-1">
                                        <Star class="h-4 w-4 text-yellow-500 fill-yellow-500" />
                                        {{ tool.ryan_rating }}/10
                                    </span>
                                    <span v-else class="text-muted-foreground">-</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ tool.views_count.toLocaleString() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge :variant="tool.is_active ? 'success' : 'default'">
                                        <Eye v-if="tool.is_active" class="h-3 w-3 mr-1" />
                                        <EyeOff v-else class="h-3 w-3 mr-1" />
                                        {{ tool.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="`/admin/tools/${tool.slug}/edit`">
                                            <Button variant="ghost" size="sm">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            @click="deleteTool(tool)"
                                        >
                                            <Trash2 class="h-4 w-4 text-destructive" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="tools.last_page > 1" class="border-t px-6 py-4 flex items-center justify-between">
                    <div class="text-sm text-muted-foreground">
                        Showing {{ ((tools.current_page - 1) * tools.per_page) + 1 }} to {{ Math.min(tools.current_page * tools.per_page, tools.total) }} of {{ tools.total }} tools
                    </div>
                    <div class="flex gap-2">
                        <Link
                            v-for="page in tools.last_page"
                            :key="page"
                            :href="`/admin/tools?page=${page}&search=${search}&category=${categoryFilter}&status=${statusFilter}`"
                            preserve-state
                        >
                            <Button
                                variant="outline"
                                size="sm"
                                :class="{ 'bg-primary text-primary-foreground': page === tools.current_page }"
                            >
                                {{ page }}
                            </Button>
                        </Link>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-if="tools.data.length === 0" class="text-center py-12">
                    <Wrench class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-foreground mb-2">No tools found</h3>
                    <p class="text-muted-foreground mb-4">
                        {{ search || categoryFilter || statusFilter ? 'Try adjusting your filters' : 'Get started by adding your first tool' }}
                    </p>
                    <Link v-if="!search && !categoryFilter && !statusFilter" :href="'/admin/tools/create'">
                        <Button>
                            <Plus class="h-4 w-4 mr-2" />
                            Add Tool
                        </Button>
                    </Link>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
