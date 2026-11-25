<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, Inbox } from '@lucide/vue';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index as experienceIndex } from '@/routes/admin/experience';
import { index as messagesIndex, show as messageShow } from '@/routes/admin/messages';
import { edit as profileEdit } from '@/routes/admin/profile';
import { index as projectsIndex } from '@/routes/admin/projects';
import { index as skillsIndex } from '@/routes/admin/skills';
import type { ContactMessage, PortfolioProfile } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'Overview', href: adminDashboard() }],
    },
});

defineProps<{
    profile: PortfolioProfile;
    stats: {
        projects: number;
        skills: number;
        experience: number;
        unreadMessages: number;
    };
    recentMessages: ContactMessage[];
}>();

const quickLinks = [
    { title: 'Projects', description: 'Add and arrange your work.', href: projectsIndex() },
    { title: 'Skills', description: 'Update your toolkit.', href: skillsIndex() },
    { title: 'Experience', description: 'Manage your work history.', href: experienceIndex() },
    { title: 'Profile', description: 'Edit your bio and contact details.', href: profileEdit() },
];
</script>

<template>
    <Head title="Portfolio overview" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Portfolio overview</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Welcome back, {{ profile.name || 'there' }}. Manage everything visitors see on your site here.
            </p>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div
                v-for="stat in [
                    { label: 'Projects', value: stats.projects },
                    { label: 'Skills', value: stats.skills },
                    { label: 'Experience entries', value: stats.experience },
                    { label: 'Unread messages', value: stats.unreadMessages },
                ]"
                :key="stat.label"
                class="rounded-xl border border-border bg-card p-5"
            >
                <p class="text-sm text-muted-foreground">{{ stat.label }}</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight">{{ stat.value }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1.3fr_0.7fr]">
            <section class="rounded-xl border border-border bg-card p-5">
                <h2 class="text-base font-semibold">Quick links</h2>
                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <Link
                        v-for="item in quickLinks"
                        :key="item.title"
                        :href="item.href"
                        class="group rounded-lg border border-border p-4 transition-colors hover:border-emerald-600 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-semibold">{{ item.title }}</span>
                            <ArrowUpRight class="size-4 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                        </div>
                        <p class="mt-1 text-xs text-muted-foreground">{{ item.description }}</p>
                    </Link>
                </div>
            </section>

            <section class="rounded-xl border border-border bg-card p-5">
                <div class="flex items-center justify-between gap-3">
                    <h2 class="text-base font-semibold">Recent messages</h2>
                    <Inbox class="size-4 text-muted-foreground" />
                </div>
                <div v-if="recentMessages.length" class="mt-4 divide-y divide-border">
                    <Link
                        v-for="message in recentMessages"
                        :key="message.id"
                        :href="messageShow(message.id)"
                        class="block py-3 first:pt-0 last:pb-0"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <span class="text-sm font-medium">{{ message.name }}</span>
                            <span v-if="!message.read_at" class="size-2 rounded-full bg-emerald-600"></span>
                        </div>
                        <p class="mt-0.5 truncate text-xs text-muted-foreground">{{ message.subject || message.message }}</p>
                    </Link>
                </div>
                <p v-else class="mt-4 text-sm text-muted-foreground">No messages yet.</p>
                <Button as-child variant="outline" size="sm" class="mt-5 w-full">
                    <Link :href="messagesIndex()">View all messages</Link>
                </Button>
            </section>
        </div>
    </div>
</template>
