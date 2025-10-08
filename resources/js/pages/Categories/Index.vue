<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, Card, Badge } from '@/components';

defineProps({
    categories: Array,
});
</script>

<template>
    <Head title="Categories - AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <PageHeader
                    title="AI Tool Categories"
                    description="Browse AI tools organized by their primary use case and functionality"
                    :gradient="true"
                />

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="category in categories"
                        :key="category.id"
                        :href="`/categories/${category.slug}`"
                        class="group"
                    >
                        <Card>
                            <div class="text-6xl mb-4">{{ category.icon }}</div>
                            <h2 class="text-2xl font-bold mb-2 text-foreground group-hover:text-info transition-colors">
                                {{ category.name }}
                            </h2>
                            <p class="text-muted-foreground mb-4">
                                {{ category.description }}
                            </p>
                            <Badge variant="default">
                                {{ category.active_tools_count }} tools
                            </Badge>
                        </Card>
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>