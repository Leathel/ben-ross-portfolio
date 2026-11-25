<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { destroy, index } from '@/routes/admin/messages';
import type { ContactMessage } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Messages', href: index() },
            { title: 'Message', href: '#' },
        ],
    },
});

defineProps<{
    message: ContactMessage;
}>();

function formatDateTime(value: string): string {
    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '—';
    }

    return date.toLocaleString('en', { timeZone: 'UTC' });
}
</script>

<template>
    <Head title="Message" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">
                    {{ message.subject || 'Message without subject' }}
                </h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    From {{ message.name }} · {{ formatDateTime(message.created_at) }}
                </p>
            </div>
            <Button as-child variant="outline">
                <Link :href="index()">Back to messages</Link>
            </Button>
        </div>

        <article class="max-w-3xl rounded-xl border border-border bg-card p-6">
            <div class="border-b border-border pb-4">
                <p class="text-sm font-medium">Reply to</p>
                <a :href="`mailto:${message.email}`" class="mt-1 block break-all text-sm text-emerald-700 hover:underline dark:text-emerald-400">
                    {{ message.email }}
                </a>
            </div>
            <div class="mt-5 whitespace-pre-wrap text-sm leading-relaxed text-foreground">
                {{ message.message }}
            </div>
        </article>

        <div>
            <Button as-child variant="ghost" class="text-destructive">
                <Link :href="destroy(message.id)" method="delete" as="button">Delete message</Link>
            </Button>
        </div>
    </div>
</template>
