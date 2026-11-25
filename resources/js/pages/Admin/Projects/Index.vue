<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { dashboard as adminDashboard } from '@/routes/admin';
import { create, destroy, edit, index } from '@/routes/admin/projects';
import type { PortfolioProject } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Projects', href: index() },
        ],
    },
});

defineProps<{
    projects: PortfolioProject[];
}>();
</script>

<template>
    <Head title="Projects" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h1 class="text-2xl font-semibold tracking-tight">Projects</h1>
                <p class="mt-1 text-sm text-muted-foreground">Add, edit and reorder the projects shown on your site.</p>
            </div>
            <Button as-child>
                <Link :href="create()">New project</Link>
            </Button>
        </div>

        <div class="overflow-hidden rounded-xl border border-border">
            <table class="w-full text-left text-sm">
                <thead class="bg-muted/50 text-xs uppercase tracking-wide text-muted-foreground">
                    <tr>
                        <th class="px-4 py-3 font-medium">Project</th>
                        <th class="px-4 py-3 font-medium">Category</th>
                        <th class="px-4 py-3 font-medium">Order</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border bg-card">
                    <tr v-for="project in projects" :key="project.id">
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ project.title }}</p>
                            <p class="mt-0.5 max-w-md truncate text-xs text-muted-foreground">{{ project.summary }}</p>
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">{{ project.category || '—' }}</td>
                        <td class="px-4 py-3 text-muted-foreground">{{ project.sort_order }}</td>
                        <td class="px-4 py-3">
                            <div class="flex flex-wrap gap-1.5">
                                <Badge :variant="project.is_published === false ? 'outline' : 'default'">
                                    {{ project.is_published === false ? 'Draft' : 'Published' }}
                                </Badge>
                                <Badge v-if="project.is_featured" variant="secondary">Featured</Badge>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex justify-end gap-2">
                                <Button as-child variant="outline" size="sm">
                                    <Link :href="edit(project.slug)">Edit</Link>
                                </Button>
                                <Button as-child variant="ghost" size="sm">
                                    <Link :href="destroy(project.slug)" method="delete" as="button" class="text-destructive">Delete</Link>
                                </Button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="!projects.length">
                        <td colspan="5" class="px-4 py-10 text-center text-muted-foreground">
                            No projects yet. Create your first one to get started.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
