<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import ToolForm from './ToolForm.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { WrenchIcon } from 'lucide-vue-next';

interface Category {
    id: number;
    name: string;
}

interface Props {
    categories: Category[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tools', href: '/admin/tools' },
    { title: 'Create', href: '/admin/tools/create' },
];

const form = useForm({
    category_id: '',
    name: '',
    slug: '',
    description: '',
    long_description: '',
    website_url: '',
    documentation_url: '',
    logo_url: '',
    screenshot_url: '',
    screenshot: null as File | null,
    pricing_model: '',
    price_description: '',
    ryan_rating: null as number | null,
    ryan_notes: '',
    ryan_last_used: '',
    features: [] as string[],
    use_cases: [] as string[],
    integrations: [] as string[],
    is_featured: false,
    is_active: true,
    first_reviewed_at: '',
});

const submit = () => {
    form.post('/admin/tools', {
        forceFormData: true,
    });
};
</script>

<template>
    <Head title="Create Tool - Admin" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                    <WrenchIcon class="h-8 w-8 text-primary" />
                    Create Tool
                </h1>
                <p class="text-muted-foreground mt-2">
                    Add a new AI tool to your directory
                </p>
            </div>

            <!-- Form -->
            <div class="max-w-4xl">
                <ToolForm
                    :form="form"
                    :categories="categories"
                    submit-label="Create Tool"
                    @submit="submit"
                />
            </div>
        </div>
    </AppLayout>
</template>
