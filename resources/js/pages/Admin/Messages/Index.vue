<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { destroy, index, show, update } from '@/routes/admin/messages';
import type { ContactMessage } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Messages', href: index() },
        ],
    },
});

defineProps<{
    messages: ContactMessage[];
}>();

function formatDate(value: string): string {
    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '—';
    }

    return date.toLocaleDateString('en', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        timeZone: 'UTC',
    });
}

function markAsUnread(message: ContactMessage): void {
    router.patch(update(message.id).url, { read: false }, { preserveScroll: true });
}
</script>

<template>
    <Head title="Messages" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Messages</h1>
            <p class="mt-1 text-sm text-muted-foreground">Messages sent through your contact form.</p>
        </div>

        <div class="overflow-hidden rounded-xl border border-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">From</th>
                        <th class="px-4 py-3 font-medium">Subject</th>
                        <th class="px-4 py-3 font-medium">Received</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-card">
                    <tr v-for="message in messages" :key="message.id">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ message.name }}</p>
                            <p class="mt-0.5 text-xs text-muted-foreground">{{ message.email }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <Link :href="show(message.id)" class="hover:underline">
                                {{ message.subject || '(no subject)' }}
                            </Link>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ formatDate(message.created_at) }}</td>
                        <td class="px-4 py-3">
                            <Badge :variant="message.read_at ? 'secondary' : 'default'">
                                {{ message.read_at ? 'Read' : 'Unread' }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="show(message.id)">View</Link>
                                </Button>
                                <Button v-if="message.read_at" variant="ghost" size="sm" @click="markAsUnread(message)">
                                    Mark unread
                                </Button>
                                <Button as-child variant="ghost" size="sm">
                                    <Link :href="destroy(message.id)" method="delete" as="button" class="text-destructive">Delete</Link>
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!messages.length">
                        <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">
                            No messages yet.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
