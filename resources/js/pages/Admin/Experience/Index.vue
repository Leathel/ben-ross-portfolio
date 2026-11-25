<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, destroy, edit, index } from '@/routes/admin/experience';
import type { PortfolioExperience } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Experience', href: index() },
        ],
    },
});

defineProps<{
    experiences: PortfolioExperience[];
}>();
</script>

<template>
    <Head title="Experience" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Experience</h1>
                <p class="mt-1 text-sm text-muted-foreground">Keep your work history up to date.</p>
            </div>
            <Button as-child>
                <Link :href="create()">Add experience</Link>
            </Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">Company</th>
                        <th class="px-4 py-3 font-medium">Period</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-card">
                    <tr v-for="experience in experiences" :key="experience.id">
                        <td class="px-4 py-3 font-medium">{{ experience.role }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ experience.company }}</td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ experience.start_date }} — {{ experience.is_current ? 'Present' : (experience.end_date || '—') }}
                        </td>
                        <td class="px-4 py-3">
                            <Badge :variant="experience.is_current ? 'default' : 'secondary'">
                                {{ experience.is_current ? 'Current' : 'Past' }}
                            </Badge>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="edit(experience.id)">Edit</Link>
                                </Button>
                                <Button as-child variant="ghost" size="sm">
                                    <Link :href="destroy(experience.id)" method="delete" as="button" class="text-destructive">Delete</Link>
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!experiences.length">
                        <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">
                            No experience entries yet. Add your first role to get started.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
