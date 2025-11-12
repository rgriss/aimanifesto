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

interface Tool {
    id: number;
    category_id: number;
    name: string;
    slug: string;
    description: string;
    long_description: string | null;
    website_url: string | null;
    documentation_url: string | null;
    logo_url: string | null;
    screenshot_url: string | null;
    pricing_model: string;
    price_description: string | null;
    ryan_rating: number | null;
    ryan_notes: string | null;
    ryan_last_used: string | null;
    features: string[];
    use_cases: string[];
    integrations: string[];
    is_featured: boolean;
    is_active: boolean;
    first_reviewed_at: string | null;
    upvotes: number;
    downvotes: number;
}

interface Props {
    tool: Tool;
    categories: Category[];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Tools', href: '/admin/tools' },
    { title: 'Edit', href: `/admin/tools/${props.tool.slug}/edit` },
];

const form = useForm({
    _method: 'put',
    category_id: props.tool.category_id.toString(),
    name: props.tool.name,
    slug: props.tool.slug,
    description: props.tool.description,
    long_description: props.tool.long_description || '',
    website_url: props.tool.website_url || '',
    documentation_url: props.tool.documentation_url || '',
    logo_url: props.tool.logo_url || '',
    screenshot_url: props.tool.screenshot_url || '',
    screenshot: null as File | null,
    pricing_model: props.tool.pricing_model,
    price_description: props.tool.price_description || '',
    ryan_rating: props.tool.ryan_rating,
    ryan_notes: props.tool.ryan_notes || '',
    ryan_last_used: props.tool.ryan_last_used || '',
    features: props.tool.features || [],
    use_cases: props.tool.use_cases || [],
    integrations: props.tool.integrations || [],
    is_featured: !!props.tool.is_featured,
    is_active: !!props.tool.is_active,
    first_reviewed_at: props.tool.first_reviewed_at || '',
    upvotes: props.tool.upvotes,
    downvotes: props.tool.downvotes,
});

const submit = () => {
    form.post(`/admin/tools/${props.tool.slug}`, {
        forceFormData: true,
    });
};
</script>

<template>
    <Head :title="`Edit ${tool.name} - Admin`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                    <WrenchIcon class="h-8 w-8 text-primary" />
                    Edit Tool
                </h1>
                <p class="text-muted-foreground mt-2">
                    Update tool details
                </p>
            </div>

            <!-- Form -->
            <div class="max-w-4xl">
                <ToolForm
                    :form="form"
                    :categories="categories"
                    submit-label="Save Changes"
                    @submit="submit"
                />
            </div>
        </div>
    </AppLayout>
</template>
