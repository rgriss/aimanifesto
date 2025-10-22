<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { PageHeader, Card, Badge } from '@/components';
import { FileText, Calendar, HardDrive } from 'lucide-vue-next';

interface Document {
    slug: string;
    title: string;
    description: string;
    last_modified: string;
    size: string;
    filename: string;
}

defineProps<{
    documents: Document[];
}>();
</script>

<template>
    <Head title="Documentation" />

    <GuestLayout>
        <div class="py-8 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <PageHeader
                    title="Documentation"
                    description="Technical guides, architecture docs, and project information"
                    :gradient="true"
                />

                <div v-if="documents.length === 0" class="text-center py-12">
                    <FileText class="mx-auto h-12 w-12 text-muted-foreground/50" />
                    <h3 class="mt-4 text-lg font-semibold text-foreground">No documentation available</h3>
                    <p class="mt-2 text-sm text-muted-foreground">Check back later for documentation.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <Link
                        v-for="doc in documents"
                        :key="doc.slug"
                        :href="`/docs/${doc.slug}`"
                        class="group"
                    >
                        <Card class="h-full hover:shadow-lg transition-all border-l-4 border-l-info">
                            <div class="flex items-start justify-between mb-4">
                                <div class="bg-info/10 p-3 rounded-lg">
                                    <FileText class="h-6 w-6 text-info" />
                                </div>
                                <Badge variant="secondary" size="sm">{{ doc.size }}</Badge>
                            </div>

                            <h3 class="text-lg font-bold text-foreground mb-2 group-hover:text-info transition-colors">
                                {{ doc.title }}
                            </h3>

                            <p v-if="doc.description" class="text-sm text-muted-foreground mb-4 line-clamp-2 leading-relaxed">
                                {{ doc.description }}
                            </p>

                            <div class="flex items-center gap-4 text-xs text-muted-foreground mt-auto pt-4 border-t border-border">
                                <div class="flex items-center gap-1">
                                    <Calendar class="h-3 w-3" />
                                    <span>{{ doc.last_modified }}</span>
                                </div>
                            </div>

                            <div class="mt-4">
                                <span class="text-sm font-semibold text-info group-hover:underline">
                                    Read Document →
                                </span>
                            </div>
                        </Card>
                    </Link>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>
