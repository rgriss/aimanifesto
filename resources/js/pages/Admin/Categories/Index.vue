<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { FolderKanban, Plus, Pencil, Trash2, Eye, EyeOff } from 'lucide-vue-next';
import { ref } from 'vue';

interface Category {
    id: number;
    name: string;
    slug: string;
    description: string | null;
    icon: string | null;
    sort_order: number;
    is_active: boolean;
    tools_count: number;
}

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();
const page = usePage();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Categories', href: '/admin/categories' },
];

const deleteCategory = (category: Category) => {
    if (category.tools_count > 0) {
        alert('Cannot delete category with associated tools.');
        return;
    }

    if (confirm(`Are you sure you want to delete "${category.name}"?`)) {
        router.delete(`/admin/categories/${category.id}`);
    }
};
</script>

<template>
    <Head title="Manage Categories - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <FolderKanban class="h-8 w-8 text-primary" />
                        Categories
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Manage tool categories and organization
                    </p>
                </div>
                <Link :href="'/admin/categories/create'">
                    <Button>
                        <Plus class="h-4 w-4 mr-2" />
                        Add Category
                    </Button>
                </Link>
            </div>

            <!-- Success Message -->
            <div v-if="page.props.flash?.success" class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 text-green-800 dark:text-green-200 px-4 py-3 rounded">
                {{ page.props.flash.success }}
            </div>

            <!-- Error Message -->
            <div v-if="page.props.flash?.error" class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-800 dark:text-red-200 px-4 py-3 rounded">
                {{ page.props.flash.error }}
            </div>

            <!-- Categories List -->
            <Card class="overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-muted/50 border-b">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Order
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Category
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Description
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                                    Tools
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
                            <tr v-for="category in categories" :key="category.id" class="hover:bg-muted/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-muted-foreground">
                                    {{ category.sort_order }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">{{ category.icon || '📁' }}</span>
                                        <div>
                                            <div class="font-medium">{{ category.name }}</div>
                                            <div class="text-sm text-muted-foreground">{{ category.slug }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 max-w-md">
                                    <p class="text-sm text-muted-foreground truncate">
                                        {{ category.description || '-' }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge variant="secondary">
                                        {{ category.tools_count }} tools
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <Badge :variant="category.is_active ? 'success' : 'default'">
                                        <Eye v-if="category.is_active" class="h-3 w-3 mr-1" />
                                        <EyeOff v-else class="h-3 w-3 mr-1" />
                                        {{ category.is_active ? 'Active' : 'Inactive' }}
                                    </Badge>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <Link :href="`/admin/categories/${category.id}/edit`">
                                            <Button variant="ghost" size="sm">
                                                <Pencil class="h-4 w-4" />
                                            </Button>
                                        </Link>
                                        <Button
                                            variant="ghost"
                                            size="sm"
                                            @click="deleteCategory(category)"
                                            :disabled="category.tools_count > 0"
                                        >
                                            <Trash2 class="h-4 w-4 text-destructive" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Empty State -->
                <div v-if="categories.length === 0" class="text-center py-12">
                    <FolderKanban class="h-12 w-12 text-muted-foreground mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-foreground mb-2">No categories yet</h3>
                    <p class="text-muted-foreground mb-4">Get started by creating your first category.</p>
                    <Link :href="'/admin/categories/create'">
                        <Button>
                            <Plus class="h-4 w-4 mr-2" />
                            Add Category
                        </Button>
                    </Link>
                </div>
            </Card>
        </div>
    </AppLayout>
</template>
