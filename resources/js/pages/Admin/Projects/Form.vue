<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ImageUploadField from '@/components/admin/ImageUploadField.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index, store, update } from '@/routes/admin/projects';
import type { ProjectFormValues } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Projects', href: index() },
        ],
    },
});

const props = defineProps<{
    project: ProjectFormValues | null;
}>();

const form = useForm({
    title: props.project?.title ?? '',
    summary: props.project?.summary ?? '',
    case_study_html: props.project?.case_study_html ?? '',
    category: props.project?.category ?? '',
    organization: props.project?.organization ?? '',
    role: props.project?.role ?? '',
    technologies: props.project?.technologies ?? '',
    live_url: props.project?.live_url ?? '',
    source_url: props.project?.source_url ?? '',
    image_url: props.project?.image_url ?? '',
    card_image_url: props.project?.card_image_url ?? '',
    is_featured: props.project?.is_featured ?? false,
    is_published: props.project?.is_published ?? true,
    sort_order: props.project?.sort_order ?? 0,
});

function submit(): void {
    if (props.project) {
        form.put(update.url(props.project.slug ?? String(props.project.id)));
    } else {
        form.post(store.url());
    }
}

const liveUrlPlaceholder = 'https://example.com';
const sourceUrlPlaceholder = 'https://github.com/you/project';
</script>

<template>
    <Head :title="project ? 'Edit project' : 'New project'" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ project ? `Edit ${project.title}` : 'New project' }}
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ project ? 'Update the details visitors see for this project.' : 'Add a new project to your portfolio.' }}
            </p>
        </div>

        <form class="max-w-3xl space-y-6" @submit.prevent="submit">
            <div class="grid gap-2">
                <Label for="title">Title</Label>
                <Input id="title" v-model="form.title" name="title" required placeholder="Atlas Analytics" />
                <InputError :message="form.errors.title" />
            </div>

            <div class="grid gap-2">
                <Label for="summary">Summary</Label>
                <Input id="summary" v-model="form.summary" name="summary" required placeholder="A short description shown on project cards." />
                <InputError :message="form.errors.summary" />
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="organization">Organization</Label>
                    <Input id="organization" v-model="form.organization" name="organization" placeholder="Independent project" />
                    <InputError :message="form.errors.organization" />
                </div>
                <div class="grid gap-2">
                    <Label for="role">Your role</Label>
                    <Input id="role" v-model="form.role" name="role" placeholder="Lead Developer" />
                    <InputError :message="form.errors.role" />
                </div>
            </div>

            <RichTextEditor
                v-model="form.case_study_html"
                label="Case study"
                placeholder="Problem; What I built; Architecture and key technical decisions; What's custom vs. built on WordPress and why; Tradeoffs and what I'd change; Current status and roadmap."
                hint="Use headings and short paragraphs to structure the case study however you like. Paste an image to add it inline."
                :error="form.errors.case_study_html"
            />

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="category">Category</Label>
                    <Input id="category" v-model="form.category" name="category" placeholder="Web App" />
                    <InputError :message="form.errors.category" />
                </div>
                <div class="grid gap-2">
                    <Label for="sort_order">Display order</Label>
                    <Input id="sort_order" v-model.number="form.sort_order" name="sort_order" type="number" min="0" />
                    <InputError :message="form.errors.sort_order" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="technologies">Technologies</Label>
                <Input id="technologies" v-model="form.technologies" name="technologies" placeholder="Laravel, Vue, TypeScript" />
                <p class="text-xs text-muted-foreground">Separate each technology with a comma.</p>
                <InputError :message="form.errors.technologies" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="live_url">Live site URL</Label>
                    <Input id="live_url" v-model="form.live_url" name="live_url" type="url" :placeholder="liveUrlPlaceholder" />
                    <InputError :message="form.errors.live_url" />
                </div>
                <div class="grid gap-2">
                    <Label for="source_url">Source code URL</Label>
                    <Input id="source_url" v-model="form.source_url" name="source_url" type="url" :placeholder="sourceUrlPlaceholder" />
                    <InputError :message="form.errors.source_url" />
                </div>
            </div>

            <ImageUploadField
                v-model="form.image_url"
                field="image_url"
                label="Preview image"
                hint="Shown full size on the project detail page and used as a fallback on cards."
                :error="form.errors.image_url"
            />

            <ImageUploadField
                v-model="form.card_image_url"
                field="card_image_url"
                label="Home page card image"
                hint="Shown on the featured project card. Leave empty to use the preview image."
                :error="form.errors.card_image_url"
            />

            <div class="flex flex-wrap gap-6">
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input v-model="form.is_featured" type="checkbox" name="is_featured" value="1" class="size-4 rounded border-input accent-emerald-600" />
                    Feature this project on the home page
                </label>
                <label class="flex items-center gap-2 text-sm font-medium">
                    <input v-model="form.is_published" type="checkbox" name="is_published" value="1" class="size-4 rounded border-input accent-emerald-600" />
                    Publish on the public site
                </label>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Button type="submit" :disabled="form.processing">{{ project ? 'Save changes' : 'Create project' }}</Button>
                <Button as-child variant="ghost">
                    <Link :href="index()">Cancel</Link>
                </Button>
            </div>
        </form>
    </div>
</template>
