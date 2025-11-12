<script setup>
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHero, Card, Badge, SectionHeading } from '@/components';

defineProps({
    category: Object,
    toolCount: Number,
});
</script>

<template>
    <Head :title="`${category.name} - The AI Manifesto`" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Back to Categories -->
                <div class="mb-6">
                    <Link
                        href="/categories"
                        class="text-foreground hover:text-foreground/70 font-semibold transition-colors"
                    >
                        ← Back to all categories
                    </Link>
                </div>

                <!-- Category Header -->
                <PageHero
                    :title="category.name"
                    :description="category.description"
                    :icon="category.icon"
                >
                    <template #badges>
                        <Badge variant="default">
                            {{ toolCount }} tools
                        </Badge>
                    </template>
                </PageHero>

                <!-- Tools in Category -->
                <div v-if="category.active_tools && category.active_tools.length > 0">
                    <SectionHeading
                        title="Tools in this Category"
                        :subtitle="`Explore ${toolCount} curated tools`"
                    />
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <Link
                            v-for="tool in category.active_tools"
                            :key="tool.id"
                            :href="`/tools/${tool.slug}`"
                            class="group"
                        >
                            <Card>
                                <h3 class="text-xl font-bold text-foreground group-hover:text-foreground/70 transition-colors mb-2">
                                    {{ tool.name }}
                                </h3>
                                <p class="text-muted-foreground mb-4 text-sm">
                                    {{ tool.description }}
                                </p>
                                <div class="flex items-center justify-between">
                                    <Badge variant="default" size="sm" class="capitalize">
                                        {{ tool.pricing_model }}
                                    </Badge>
                                    <Badge v-if="tool.ryan_rating" variant="success" size="sm">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </Badge>
                                </div>
                            </Card>
                        </Link>
                    </div>
                </div>

                <!-- Empty State -->
                <Card v-else padding="p-12">
                    <div class="text-center">
                        <p class="text-xl text-muted-foreground">No tools in this category yet.</p>
                    </div>
                </Card>
            </div>
        </div>
    </GuestLayout>
</template>