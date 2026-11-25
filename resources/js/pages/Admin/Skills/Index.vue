<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, destroy, edit, index } from '@/routes/admin/skills';
import type { PortfolioSkill } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Skills', href: index() },
        ],
    },
});

defineProps<{
    skills: PortfolioSkill[];
}>();
</script>

<template>
    <Head title="Skills" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Skills</h1>
                <p class="mt-1 text-sm text-muted-foreground">Manage the skills and tools listed on your portfolio.</p>
            </div>
            <Button as-child>
                <Link :href="create()">New skill</Link>
            </Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Proficiency</th>
                        <th class="px-4 py-3 font-medium">Order</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-card">
                    <tr v-for="skill in skills" :key="skill.id">
                        <td class="px-4 py-3 font-medium">{{ skill.name }}</td>
                        <td class="px-4 py-3"><Badge variant="secondary">{{ skill.category }}</Badge></td>
                        <td class="px-4 py-3 text-muted-foreground">{{ skill.proficiency ? `${skill.proficiency}%` : '—' }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ skill.sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="edit(skill.id)">Edit</Link>
                                </Button>
                                <Button as-child variant="ghost" size="sm">
                                    <Link :href="destroy(skill.id)" method="delete" as="button" class="text-destructive">Delete</Link>
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!skills.length">
                        <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">
                            No skills yet. Create your first one to get started.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
