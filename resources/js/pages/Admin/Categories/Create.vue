<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { FolderPlus } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Categories', href: '/admin/categories' },
    { title: 'Create', href: '/admin/categories/create' },
];

const form = useForm({
    name: '',
    slug: '',
    description: '',
    icon: '',
    sort_order: 999,
    is_active: true,
});

const submit = () => {
    form.post('/admin/categories');
};
</script>

<template>
    <Head title="Create Category - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                    <FolderPlus class="h-8 w-8 text-primary" />
                    Create Category
                </h1>
                <p class="text-muted-foreground mt-2">
                    Add a new category for organizing tools
                </p>
            </div>

            <!-- Form -->
            <Card class="max-w-2xl">
                <CardHeader>
                    <CardTitle>Category Details</CardTitle>
                </CardHeader>
                <CardContent>
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label for="name">Name *</Label>
                            <Input
                                id="name"
                                v-model="form.name"
                                type="text"
                                required
                                placeholder="e.g., Code Assistants"
                            />
                            <p v-if="form.errors.name" class="text-sm text-destructive">{{ form.errors.name }}</p>
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <Label for="slug">Slug</Label>
                            <Input
                                id="slug"
                                v-model="form.slug"
                                type="text"
                                placeholder="Auto-generated from name if left empty"
                            />
                            <p class="text-xs text-muted-foreground">
                                Used in URLs. Leave empty to auto-generate from name.
                            </p>
                            <p v-if="form.errors.slug" class="text-sm text-destructive">{{ form.errors.slug }}</p>
                        </div>

                        <!-- Icon -->
                        <div class="space-y-2">
                            <Label for="icon">Icon</Label>
                            <Input
                                id="icon"
                                v-model="form.icon"
                                type="text"
                                placeholder="e.g., 💻 or 🤖"
                                maxlength="10"
                            />
                            <p class="text-xs text-muted-foreground">
                                Emoji or short icon text (max 10 characters)
                            </p>
                            <p v-if="form.errors.icon" class="text-sm text-destructive">{{ form.errors.icon }}</p>
                        </div>

                        <!-- Description -->
                        <div class="space-y-2">
                            <Label for="description">Description</Label>
                            <Textarea
                                id="description"
                                v-model="form.description"
                                rows="3"
                                placeholder="Brief description of this category"
                            />
                            <p v-if="form.errors.description" class="text-sm text-destructive">{{ form.errors.description }}</p>
                        </div>

                        <!-- Sort Order -->
                        <div class="space-y-2">
                            <Label for="sort_order">Sort Order</Label>
                            <Input
                                id="sort_order"
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                            />
                            <p class="text-xs text-muted-foreground">
                                Lower numbers appear first (default: 999)
                            </p>
                            <p v-if="form.errors.sort_order" class="text-sm text-destructive">{{ form.errors.sort_order }}</p>
                        </div>

                        <!-- Is Active -->
                        <div class="flex items-center space-x-2">
                            <Checkbox id="is_active" v-model:checked="form.is_active" />
                            <Label for="is_active" class="cursor-pointer">Active (visible on public site)</Label>
                        </div>
                        <p v-if="form.errors.is_active" class="text-sm text-destructive">{{ form.errors.is_active }}</p>

                        <!-- Actions -->
                        <div class="flex items-center gap-4 pt-4">
                            <Button type="submit" :disabled="form.processing">
                                {{ form.processing ? 'Creating...' : 'Create Category' }}
                            </Button>
                            <Link :href="'/admin/categories'">
                                <Button type="button" variant="outline">
                                    Cancel
                                </Button>
                            </Link>
                        </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
