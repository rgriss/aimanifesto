<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { Button } from '@/components';
import { ArrowLeft, Printer, Calendar } from 'lucide-vue-next';

interface Document {
    slug: string;
    title: string;
    html: string;
    last_modified: string;
    filename: string;
}

defineProps<{
    document: Document;
}>();

const handlePrint = () => {
    window.print();
};
</script>

<template>
    <Head :title="document.title" />

    <GuestLayout>
        <div class="py-8 md:py-12">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header with navigation (hidden in print) -->
                <div class="mb-8 flex items-center justify-between print:hidden">
                    <Link href="/docs" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-info transition-colors">
                        <ArrowLeft class="h-4 w-4" />
                        <span>Back to Documentation</span>
                    </Link>

                    <Button @click="handlePrint" variant="outline" size="sm">
                        <Printer class="h-4 w-4 mr-2" />
                        Print
                    </Button>
                </div>

                <!-- Document metadata (visible in print) -->
                <div class="mb-8 pb-6 border-b border-border">
                    <h1 class="text-3xl md:text-4xl font-bold text-foreground mb-4">
                        {{ document.title }}
                    </h1>

                    <div class="flex items-center gap-4 text-sm text-muted-foreground">
                        <div class="flex items-center gap-1">
                            <Calendar class="h-4 w-4" />
                            <span>Last updated: {{ document.last_modified }}</span>
                        </div>
                        <span class="hidden print:inline">•</span>
                        <span class="hidden print:inline">{{ document.filename }}</span>
                    </div>
                </div>

                <!-- Markdown content -->
                <div class="prose prose-slate dark:prose-invert max-w-none prose-img:rounded-lg prose-headings:scroll-mt-24 prose-a:text-info prose-a:no-underline hover:prose-a:underline" v-html="document.html"></div>
            </div>
        </div>
    </GuestLayout>
</template>

<style scoped>
/* Print-specific styles */
@media print {
    /* Remove backgrounds and set to white */
    :global(body) {
        background: white !important;
        color: black !important;
    }

    /* Ensure proper page breaks */
    :deep(h1),
    :deep(h2),
    :deep(h3) {
        page-break-after: avoid;
        page-break-inside: avoid;
    }

    :deep(table),
    :deep(figure),
    :deep(pre) {
        page-break-inside: avoid;
    }

    :deep(img) {
        max-width: 100% !important;
        page-break-inside: avoid;
    }

    /* Fix link colors for print */
    :deep(a) {
        color: #0066cc !important;
        text-decoration: underline !important;
    }

    /* Print URLs after links */
    :deep(a[href^="http"])::after {
        content: " (" attr(href) ")";
        font-size: 0.8em;
        color: #666;
    }

    /* Better table styling for print */
    :deep(table) {
        border-collapse: collapse;
        width: 100%;
        margin: 1em 0;
    }

    :deep(th),
    :deep(td) {
        border: 1px solid #333;
        padding: 8px;
        text-align: left;
    }

    :deep(th) {
        background-color: #f0f0f0 !important;
        font-weight: bold;
    }

    /* Code blocks */
    :deep(pre) {
        background-color: #f5f5f5 !important;
        border: 1px solid #ddd !important;
        padding: 12px !important;
        overflow: visible !important;
        white-space: pre-wrap !important;
        word-wrap: break-word !important;
    }

    :deep(code) {
        background-color: #f5f5f5 !important;
        color: #333 !important;
        padding: 2px 4px !important;
        border-radius: 3px !important;
        font-family: 'Courier New', Courier, monospace !important;
        font-size: 0.9em !important;
    }

    /* Ensure good contrast */
    :deep(blockquote) {
        border-left: 4px solid #333 !important;
        color: #333 !important;
        background-color: #f9f9f9 !important;
    }

    /* Remove shadows and fancy effects */
    :deep(*) {
        box-shadow: none !important;
        text-shadow: none !important;
    }

    /* Show page numbers */
    @page {
        margin: 2cm;

        @bottom-right {
            content: "Page " counter(page) " of " counter(pages);
        }
    }

    /* First page header */
    @page :first {
        @top-left {
            content: "{{ document.title }}";
            font-size: 10pt;
            color: #666;
        }

        @top-right {
            content: "{{ document.last_modified }}";
            font-size: 10pt;
            color: #666;
        }
    }
}

/* Screen-specific Tailwind prose overrides */
:deep(.prose) {
    /* Headers */
    h1 {
        color: var(--foreground);
    }

    h2 {
        color: var(--foreground);
        border-bottom: 1px solid var(--border);
        padding-bottom: 0.5rem;
        margin-top: 2rem;
    }

    h3 {
        color: var(--foreground);
    }

    /* Lists */
    ul,
    ol {
        color: var(--muted-foreground);
    }

    /* Code blocks */
    pre {
        background-color: var(--muted);
        border: 1px solid var(--border);
        overflow-x: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
        word-break: break-word;
    }

    code {
        background-color: var(--muted);
        color: var(--foreground);
        padding: 0.2em 0.4em;
        border-radius: 0.25rem;
        font-size: 0.875em;
        white-space: pre-wrap;
        word-wrap: break-word;
    }

    /* Inline code within prose */
    pre code {
        white-space: pre-wrap;
        word-wrap: break-word;
        word-break: break-word;
        display: block;
    }

    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
    }

    thead {
        background-color: var(--muted);
    }

    th,
    td {
        border: 1px solid var(--border);
        padding: 0.75rem;
    }

    th {
        font-weight: 600;
        text-align: left;
    }

    /* Blockquotes */
    blockquote {
        border-left: 4px solid var(--info);
        background-color: var(--info) / 0.05;
        padding: 1rem 1.5rem;
        font-style: italic;
    }

    /* Links */
    a:hover {
        text-decoration: underline;
    }

    /* Horizontal rules */
    hr {
        border-color: var(--border);
        margin: 2rem 0;
    }

    /* Images */
    img {
        border-radius: 0.5rem;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
    }
}
</style>
