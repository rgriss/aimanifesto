<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge } from '@/components';

defineProps({
    featuredTools: {
        type: Array,
        default: () => []
    },
    categories: {
        type: Array,
        default: () => []
    },
});
</script>

<template>
    <Head title="AI Manifesto - Curated AI Tools & Reviews" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Hero Header -->
                <PageHeader
                    title="AI Manifesto"
                    description="A curated collection of AI tools, tested and reviewed by Ryan Grissinger. Real experience from building a $200M company with AI."
                    :gradient="true"
                >
                    <div class="flex flex-wrap gap-4 mt-6">
                        <Link
                            href="/tools"
                            class="bg-info text-info-foreground hover:bg-info/90 font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Browse Tools
                        </Link>
                        <Link
                            href="/categories"
                            class="bg-white/20 backdrop-blur-sm text-white border-2 border-white hover:bg-white hover:text-primary font-semibold py-3 px-6 rounded-lg transition-colors"
                        >
                            Explore Categories
                        </Link>
                    </div>
                </PageHeader>

                <!-- Featured Tools -->
                <div v-if="featuredTools.length > 0" class="mb-12">
                    <SectionHeading
                        title="Featured Tools"
                        subtitle="Hand-picked tools that deliver exceptional value"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="tool in featuredTools"
                            :key="tool.id"
                            :href="`/tools/${tool.slug}`"
                            class="group"
                        >
                            <Card>
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-xl font-bold text-foreground group-hover:text-info transition-colors">
                                        {{ tool.name }}
                                    </h3>
                                    <Badge v-if="tool.is_featured" variant="warning" size="sm">
                                        Featured
                                    </Badge>
                                </div>
                                <p class="text-muted-foreground mb-4 text-sm">
                                    {{ tool.description }}
                                </p>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-muted-foreground">
                                        {{ tool.category.name }}
                                    </span>
                                    <Badge v-if="tool.ryan_rating" variant="success" size="sm">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </Badge>
                                </div>
                            </Card>
                        </Link>
                    </div>
                </div>

                <!-- Categories -->
                <div v-if="categories.length > 0">
                    <SectionHeading
                        title="Browse by Category"
                        subtitle="Explore tools organized by their primary use case"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <Link
                            v-for="category in categories"
                            :key="category.id"
                            :href="`/categories/${category.slug}`"
                            class="group"
                        >
                            <Card>
                                <div class="text-5xl mb-4">{{ category.icon }}</div>
                                <h3 class="text-lg font-bold text-foreground group-hover:text-info transition-colors mb-2">
                                    {{ category.name }}
                                </h3>
                                <p class="text-sm text-muted-foreground mb-3">
                                    {{ category.description }}
                                </p>
                                <Badge variant="default" size="sm">
                                    {{ category.active_tools_count }} tools
                                </Badge>
                            </Card>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>