<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import {
    Users,
    Search,
    Shield,
    ShieldCheck,
    Mail,
    MailCheck,
    Trash2,
    ChevronLeft,
    ChevronRight,
    UserCog,
    Calendar,
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface User {
    id: number;
    name: string;
    email: string;
    is_admin: boolean;
    email_verified_at: string | null;
    newsletter_subscribed: boolean;
    created_at: string;
}

interface Props {
    users: {
        data: User[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
        is_admin?: string;
        email_verified?: string;
    };
    stats: {
        total: number;
        admins: number;
        verified: number;
        newsletter_subscribed: number;
    };
}

const props = defineProps<Props>();
const page = usePage();
const currentUser = computed(() => page.props.auth?.user as any);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Admin', href: '/admin' },
    { title: 'Users', href: '/admin/users' },
];

const searchTerm = ref(props.filters.search || '');
const adminFilter = ref(props.filters.is_admin || '');
const verifiedFilter = ref(props.filters.email_verified || '');

const applyFilters = () => {
    router.get('/admin/users', {
        search: searchTerm.value || undefined,
        is_admin: adminFilter.value || undefined,
        email_verified: verifiedFilter.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

const clearFilters = () => {
    searchTerm.value = '';
    adminFilter.value = '';
    verifiedFilter.value = '';
    router.get('/admin/users');
};

const toggleAdmin = (user: User) => {
    if (user.id === currentUser.value?.id && user.is_admin) {
        alert('You cannot remove your own admin privileges.');
        return;
    }

    router.patch(`/admin/users/${user.id}`, {
        is_admin: !user.is_admin,
    }, {
        preserveScroll: true,
    });
};

const toggleVerified = (user: User) => {
    router.patch(`/admin/users/${user.id}`, {
        email_verified_at: user.email_verified_at ? null : new Date().toISOString(),
    }, {
        preserveScroll: true,
    });
};

const confirmDelete = (user: User) => {
    if (user.id === currentUser.value?.id) {
        alert('You cannot delete your own account from here.');
        return;
    }

    if (confirm(`Are you sure you want to delete ${user.name}? This action cannot be undone.`)) {
        router.delete(`/admin/users/${user.id}`, {
            preserveScroll: true,
        });
    }
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};
</script>

<template>
    <Head title="User Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 overflow-x-auto p-6">
            <!-- Page Header -->
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight flex items-center gap-3">
                        <Users class="h-8 w-8 text-primary" />
                        User Management
                    </h1>
                    <p class="text-muted-foreground mt-2">
                        Manage users, permissions, and account status
                    </p>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-4 md:grid-cols-4">
                <Card>
                    <CardHeader class="pb-3">
                        <CardDescription>Total Users</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.total }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                            <Users class="h-4 w-4" />
                            <span>All registered users</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-3">
                        <CardDescription>Administrators</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.admins }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                            <ShieldCheck class="h-4 w-4" />
                            <span>Admin privileges</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-3">
                        <CardDescription>Verified Emails</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.verified }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                            <MailCheck class="h-4 w-4" />
                            <span>Email confirmed</span>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="pb-3">
                        <CardDescription>Newsletter Subscribed</CardDescription>
                        <CardTitle class="text-4xl">{{ stats.newsletter_subscribed }}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-center gap-2 text-xs text-muted-foreground">
                            <Mail class="h-4 w-4" />
                            <span>Opted in</span>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Filters Card -->
            <Card>
                <CardHeader>
                    <CardTitle>Filters</CardTitle>
                    <CardDescription>Search and filter users</CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="grid gap-4 md:grid-cols-4">
                        <div class="md:col-span-2">
                            <Label for="search">Search</Label>
                            <div class="relative mt-1.5">
                                <Search class="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                                <Input
                                    id="search"
                                    v-model="searchTerm"
                                    placeholder="Search by name or email..."
                                    class="pl-9"
                                    @keyup.enter="applyFilters"
                                />
                            </div>
                        </div>

                        <div>
                            <Label for="admin-filter">Admin Status</Label>
                            <select
                                id="admin-filter"
                                v-model="adminFilter"
                                @change="applyFilters"
                                class="mt-1.5 flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                            >
                                <option value="">All users</option>
                                <option value="1">Admins only</option>
                                <option value="0">Non-admins only</option>
                            </select>
                        </div>

                        <div>
                            <Label for="verified-filter">Email Status</Label>
                            <select
                                id="verified-filter"
                                v-model="verifiedFilter"
                                @change="applyFilters"
                                class="mt-1.5 flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2"
                            >
                                <option value="">All emails</option>
                                <option value="1">Verified only</option>
                                <option value="0">Unverified only</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <Button @click="applyFilters">
                            <Search class="h-4 w-4 mr-2" />
                            Apply Filters
                        </Button>
                        <Button variant="outline" @click="clearFilters">
                            Clear Filters
                        </Button>
                    </div>
                </CardContent>
            </Card>

            <!-- Users Table -->
            <Card class="overflow-hidden">
                <CardHeader>
                    <div class="flex items-center justify-between">
                        <div>
                            <CardTitle>Users</CardTitle>
                            <CardDescription>
                                {{ users.total }} total users
                            </CardDescription>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-muted/50 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        User
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Joined
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-muted-foreground">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-border bg-background">
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-muted/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-medium">{{ user.name }}</div>
                                        <div v-if="user.id === currentUser?.id" class="text-xs text-muted-foreground">
                                            (You)
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            {{ user.email }}
                                            <Badge v-if="user.newsletter_subscribed" variant="outline" class="text-xs">
                                                Newsletter
                                            </Badge>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1">
                                            <Badge v-if="user.is_admin" variant="default">
                                                <ShieldCheck class="h-3 w-3 mr-1" />
                                                Admin
                                            </Badge>
                                            <Badge v-if="user.email_verified_at" variant="secondary">
                                                <MailCheck class="h-3 w-3 mr-1" />
                                                Verified
                                            </Badge>
                                            <Badge v-else variant="destructive">
                                                <Mail class="h-3 w-3 mr-1" />
                                                Unverified
                                            </Badge>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-2 text-sm text-muted-foreground">
                                            <Calendar class="h-4 w-4" />
                                            {{ formatDate(user.created_at) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button
                                                variant="outline"
                                                size="sm"
                                                @click="toggleAdmin(user)"
                                                :disabled="user.id === currentUser?.id && user.is_admin"
                                            >
                                                <UserCog class="h-4 w-4 mr-1" />
                                                {{ user.is_admin ? 'Demote' : 'Promote' }}
                                            </Button>

                                            <Button
                                                variant="outline"
                                                size="sm"
                                                @click="toggleVerified(user)"
                                            >
                                                <MailCheck class="h-4 w-4 mr-1" />
                                                {{ user.email_verified_at ? 'Unverify' : 'Verify' }}
                                            </Button>

                                            <Button
                                                variant="destructive"
                                                size="sm"
                                                :disabled="user.id === currentUser?.id"
                                                @click="confirmDelete(user)"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="users.data.length === 0">
                                    <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">
                                        No users found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="users.last_page > 1" class="flex items-center justify-between p-6 border-t">
                        <div class="text-sm text-muted-foreground">
                            Showing {{ (users.current_page - 1) * users.per_page + 1 }} to
                            {{ Math.min(users.current_page * users.per_page, users.total) }} of {{ users.total }} users
                        </div>
                        <div class="flex gap-2">
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="users.current_page === 1"
                                @click="router.get(`/admin/users?page=${users.current_page - 1}`)"
                            >
                                <ChevronLeft class="h-4 w-4 mr-1" />
                                Previous
                            </Button>
                            <Button
                                variant="outline"
                                size="sm"
                                :disabled="users.current_page === users.last_page"
                                @click="router.get(`/admin/users?page=${users.current_page + 1}`)"
                            >
                                Next
                                <ChevronRight class="h-4 w-4 ml-1" />
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
