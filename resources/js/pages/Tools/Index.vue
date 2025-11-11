<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, SectionHeading, Card, Badge } from '@/components';
import { ref } from 'vue';

const props = defineProps({
    tools: Object,
    filters: {
        type: Object,
        default: () => ({})
    },
});

const search = ref(props.filters?.search || '');
const sort = ref(props.filters?.sort || 'name');

const applyFilters = () => {
    router.get('/tools', {
        search: search.value,
        sort: sort.value,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <Head title="AI Tools Directory - AI Manifesto" />

    <GuestLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <PageHeader
                    title="AI Tools Directory"
                    description="Curated AI tools tested and reviewed by Ryan Grissinger. Real insights from years of AI implementation experience."
                    :gradient="true"
                />

                <!-- Search & Sort -->
                <Card class="mb-8">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Search -->
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-foreground mb-2">
                                Search Tools
                            </label>
                            <input
                                v-model="search"
                                @input="applyFilters"
                                type="text"
                                placeholder="Search by name or description..."
                                class="w-full rounded-md border border-border bg-background text-foreground px-4 py-2 focus:outline-none focus:ring-2 focus:ring-foreground focus:border-transparent"
                            />
                        </div>

                        <!-- Sort -->
                        <div class="w-full sm:w-48">
                            <label class="block text-sm font-medium text-foreground mb-2">
                                Sort By
                            </label>
                            <select
                                v-model="sort"
                                @change="applyFilters"
                                class="w-full rounded-md border border-border bg-background text-foreground px-4 py-2 focus:outline-none focus:ring-2 focus:ring-foreground focus:border-transparent"
                            >
                                <option value="name">Name</option>
                                <option value="rating">Highest Rated</option>
                                <option value="views">Most Viewed</option>
                                <option value="recent">Recently Added</option>
                            </select>
                        </div>

                        <!-- Clear Search (only show if search is active) -->
                        <div v-if="search" class="flex items-end">
                            <Link
                                href="/tools"
                                class="px-4 py-2 bg-secondary text-secondary-foreground rounded-md hover:bg-secondary/90 text-center font-semibold transition-colors whitespace-nowrap"
                            >
                                Clear Search
                            </Link>
                        </div>
                    </div>
                </Card>

                <!-- Tools Grid -->
                <div v-if="tools.data && tools.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                        <Link
                            v-for="tool in tools.data"
                            :key="tool.id"
                            :href="`/tools/${tool.slug}`"
                            class="group"
                        >
                            <Card>
                                <div class="flex items-start justify-between mb-3">
                                    <h3 class="text-xl font-bold text-foreground group-hover:text-foreground/70 transition-colors">
                                        {{ tool.name }}
                                    </h3>
                                    <Badge v-if="tool.is_featured" variant="warning" size="sm">
                                        Featured
                                    </Badge>
                                </div>
                                <p class="text-muted-foreground mb-4 text-sm">
                                    {{ tool.description }}
                                </p>
                                <div class="flex items-center justify-between text-sm mb-2">
                                    <span class="text-muted-foreground">
                                        {{ tool.category?.name }}
                                    </span>
                                    <Badge v-if="tool.ryan_rating" variant="success" size="sm">
                                        ⭐ {{ tool.ryan_rating }}/10
                                    </Badge>
                                </div>
                                <Badge variant="default" size="sm" class="capitalize">
                                    {{ tool.pricing_model }}
                                </Badge>
                            </Card>
                        </Link>
                    </div>

                    <!-- Pagination -->
                    <div v-if="tools.links" class="flex justify-center gap-2">
                        <component
                            v-for="(link, index) in tools.links"
                            :key="index"
                            :is="link.url ? Link : 'span'"
                            :href="link.url || undefined"
                            :class="[
                                'px-4 py-2 rounded-md font-semibold transition-colors',
                                link.active
                                    ? 'bg-foreground text-background'
                                    : 'bg-card text-foreground border border-border',
                                link.url ? 'hover:bg-secondary' : 'opacity-50 cursor-not-allowed'
                            ]"
                            v-html="link.label"
                        />
                    </div>
                </div>

                <!-- Empty State -->
                <Card v-else padding="p-12">
                    <div class="text-center">
                        <p class="text-xl text-muted-foreground mb-4">
                            {{ search ? `No tools found for "${search}"` : 'No tools found' }}
                        </p>
                        <Link
                            v-if="search"
                            href="/tools"
                            class="text-foreground hover:text-foreground/70 font-semibold underline"
                        >
                            Clear search
                        </Link>
                    </div>
                </Card>
            </div>
        </div>
    </GuestLayout>
</template>