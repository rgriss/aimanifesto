<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { type BreadcrumbItem } from '@/types';
import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Download, Upload, Database, Trash2, FileJson, AlertCircle, CheckCircle } from 'lucide-vue-next';

interface ExportFile {
    name: string;
    size: number;
    lastModified: number;
    path: string;
}

interface Stats {
    categories: number;
    tools: number;
    activeTools: number;
    featuredTools: number;
}

interface Props {
    exports: ExportFile[];
    stats: Stats;
}

const props = defineProps<Props>();

const page = usePage();
const flash = computed(() => page.props.flash as any);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Admin',
        href: '/admin/data',
    },
    {
        title: 'Data Management',
        href: '/admin/data',
    },
];

const exportForm = useForm({
    filename: '',
});

const importForm = useForm({
    file: null as File | null,
    merge: false,
});

const fileInput = ref<HTMLInputElement | null>(null);

const exportData = () => {
    exportForm.post('/admin/data/export', {
        preserveScroll: true,
        onSuccess: () => {
            exportForm.reset();
        },
    });
};

const handleFileSelect = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        importForm.file = target.files[0];
    }
};

const importData = () => {
    if (!importForm.file) return;

    const formData = new FormData();
    formData.append('file', importForm.file);
    formData.append('merge', importForm.merge ? '1' : '0');

    router.post('/admin/data/import', formData, {
        preserveScroll: true,
        onSuccess: () => {
            importForm.reset();
            if (fileInput.value) {
                fileInput.value.value = '';
            }
        },
    });
};

const downloadFile = (filename: string) => {
    window.location.href = `/admin/data/download/${filename}`;
};

const deleteFile = (filename: string) => {
    if (confirm('Are you sure you want to delete this export file?')) {
        router.delete(`/admin/data/delete/${filename}`, {
            preserveScroll: true,
        });
    }
};

const formatFileSize = (bytes: number): string => {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
};

const formatDate = (timestamp: number): string => {
    return new Date(timestamp * 1000).toLocaleString();
};
</script>

<template>
    <Head title="Data Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Flash Messages -->
            <Alert v-if="flash?.success" variant="default" class="border-green-500 bg-green-50 dark:bg-green-950">
                <CheckCircle class="h-4 w-4 text-green-600 dark:text-green-400" />
                <AlertDescription class="text-green-800 dark:text-green-200">
                    {{ flash.success }}
                </AlertDescription>
            </Alert>

            <Alert v-if="flash?.error" variant="destructive">
                <AlertCircle class="h-4 w-4" />
                <AlertDescription>
                    {{ flash.error }}
                </AlertDescription>
            </Alert>

            <!-- Page Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight">Data Management</h1>
                <p class="text-muted-foreground mt-2">
                    Import and export your tools and categories database
                </p>
            </div>

            <!-- Database Stats -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Categories</CardTitle>
                        <Database class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.categories }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Tools</CardTitle>
                        <Database class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.tools }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Active Tools</CardTitle>
                        <Database class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.activeTools }}</div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Featured Tools</CardTitle>
                        <Database class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.featuredTools }}</div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <!-- Export Section -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Download class="h-5 w-5" />
                            Export Database
                        </CardTitle>
                        <CardDescription>
                            Export all categories and tools to a JSON file
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label for="filename" class="text-sm font-medium">
                                Filename (optional)
                            </label>
                            <input
                                id="filename"
                                v-model="exportForm.filename"
                                type="text"
                                placeholder="export-2024-01-01.json"
                                class="mt-1 block w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            />
                        </div>
                        <Button
                            @click="exportData"
                            :disabled="exportForm.processing"
                            class="w-full"
                        >
                            <Download class="mr-2 h-4 w-4" />
                            {{ exportForm.processing ? 'Exporting...' : 'Export Now' }}
                        </Button>
                    </CardContent>
                </Card>

                <!-- Import Section -->
                <Card>
                    <CardHeader>
                        <CardTitle class="flex items-center gap-2">
                            <Upload class="h-5 w-5" />
                            Import Database
                        </CardTitle>
                        <CardDescription>
                            Import categories and tools from a JSON file
                        </CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <label for="file" class="text-sm font-medium">
                                Select JSON File
                            </label>
                            <input
                                id="file"
                                ref="fileInput"
                                type="file"
                                accept=".json"
                                @change="handleFileSelect"
                                class="mt-1 block w-full text-sm text-muted-foreground file:mr-4 file:rounded-md file:border-0 file:bg-primary file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primary-foreground hover:file:bg-primary/90"
                            />
                        </div>
                        <div class="flex items-center space-x-2">
                            <input
                                id="merge"
                                v-model="importForm.merge"
                                type="checkbox"
                                class="h-4 w-4 rounded border-gray-300"
                            />
                            <label for="merge" class="text-sm font-medium">
                                Merge with existing data (don't delete existing)
                            </label>
                        </div>
                        <Button
                            @click="importData"
                            :disabled="!importForm.file || importForm.processing"
                            variant="secondary"
                            class="w-full"
                        >
                            <Upload class="mr-2 h-4 w-4" />
                            {{ importForm.processing ? 'Importing...' : 'Import Now' }}
                        </Button>
                    </CardContent>
                </Card>
            </div>

            <!-- Export Files List -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <FileJson class="h-5 w-5" />
                        Export Files
                    </CardTitle>
                    <CardDescription>
                        Available export files that can be downloaded or deleted
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div v-if="exports.length === 0" class="text-center py-8 text-muted-foreground">
                        No export files available. Create one using the export button above.
                    </div>
                    <div v-else class="space-y-2">
                        <div
                            v-for="file in exports"
                            :key="file.name"
                            class="flex items-center justify-between rounded-lg border border-sidebar-border/70 p-4 dark:border-sidebar-border"
                        >
                            <div class="flex items-center gap-3">
                                <FileJson class="h-5 w-5 text-muted-foreground" />
                                <div>
                                    <div class="font-medium">{{ file.name }}</div>
                                    <div class="text-sm text-muted-foreground">
                                        {{ formatFileSize(file.size) }} • {{ formatDate(file.lastModified) }}
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <Button
                                    @click="downloadFile(file.name)"
                                    size="sm"
                                    variant="outline"
                                >
                                    <Download class="h-4 w-4" />
                                </Button>
                                <Button
                                    @click="deleteFile(file.name)"
                                    size="sm"
                                    variant="outline"
                                    class="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
