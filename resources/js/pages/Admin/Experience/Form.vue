<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index, store, update } from '@/routes/admin/experience';
import type { ExperienceFormValues } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Experience', href: index() },
        ],
    },
});

const props = defineProps<{
    experience: ExperienceFormValues | null;
}>();

const form = useForm({
    role: props.experience?.role ?? '',
    company: props.experience?.company ?? '',
    location: props.experience?.location ?? '',
    employment_type: props.experience?.employment_type ?? '',
    start_date: props.experience?.start_date ?? '',
    end_date: props.experience?.end_date ?? '',
    sort_order: props.experience?.sort_order ?? 0,
    description: props.experience?.description ?? '',
    skill_tags: props.experience?.skill_tags ?? '',
    is_current: props.experience?.is_current ?? false,
});

function submit(): void {
    if (props.experience) {
        form.put(update.url(props.experience.id));
    } else {
        form.post(store.url());
    }
}

const skillTagPreview = computed(() =>
    form.skill_tags
        .split(',')
        .map((tag) => tag.trim())
        .filter(Boolean),
);
</script>

<template>
    <Head :title="experience ? 'Edit experience' : 'Add experience'" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ experience ? `Edit ${experience.role}` : 'Add experience' }}
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ experience ? 'Update the details of this role.' : 'Add a role to your work history.' }}
            </p>
        </div>

        <form class="max-w-3xl space-y-6" @submit.prevent="submit">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="role">Role</Label>
                    <Input id="role" v-model="form.role" name="role" required placeholder="Senior Full Stack Developer" />
                    <InputError :message="form.errors.role" />
                </div>
                <div class="grid gap-2">
                    <Label for="company">Company</Label>
                    <Input id="company" v-model="form.company" name="company" required placeholder="Current role" />
                    <InputError :message="form.errors.company" />
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="location">Location <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="location" v-model="form.location" name="location" placeholder="Ontario, Canada" />
                    <InputError :message="form.errors.location" />
                </div>
                <div class="grid gap-2">
                    <Label for="employment_type">Employment type <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="employment_type" v-model="form.employment_type" name="employment_type" placeholder="Full-time" />
                    <InputError :message="form.errors.employment_type" />
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-3">
                <div class="grid gap-2">
                    <Label for="start_date">Start date</Label>
                    <Input id="start_date" v-model="form.start_date" name="start_date" type="date" required />
                    <InputError :message="form.errors.start_date" />
                </div>
                <div class="grid gap-2">
                    <Label for="end_date">End date</Label>
                    <Input id="end_date" v-model="form.end_date" name="end_date" type="date" />
                    <InputError :message="form.errors.end_date" />
                </div>
                <div class="grid gap-2">
                    <Label for="sort_order">Display order</Label>
                    <Input id="sort_order" v-model.number="form.sort_order" name="sort_order" type="number" min="0" />
                    <InputError :message="form.errors.sort_order" />
                </div>
            </div>

            <RichTextEditor
                v-model="form.description"
                label="Description"
                placeholder="What did you do in this role?"
                image-field="description_image"
                hint="Use short paragraphs and lists to describe your responsibilities and outcomes. Paste an image to add it inline."
                :error="form.errors.description"
            />

            <div class="grid gap-2">
                <Label for="skill_tags">Skill tags <span class="font-normal text-muted-foreground">(optional)</span></Label>
                <Input
                    id="skill_tags"
                    v-model="form.skill_tags"
                    name="skill_tags"
                    placeholder="PHP, JavaScript, MySQL"
                />
                <p class="text-xs text-muted-foreground">Separate each tag with a comma. These appear as chips on the experience timeline.</p>
                <div v-if="skillTagPreview.length" class="mt-1 flex flex-wrap gap-1.5">
                    <span
                        v-for="tag in skillTagPreview"
                        :key="tag"
                        class="rounded-md bg-zinc-200/70 px-2 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                    >
                        {{ tag }}
                    </span>
                </div>
                <InputError :message="form.errors.skill_tags" />
            </div>

            <label class="flex items-center gap-2 text-sm font-medium">
                <input v-model="form.is_current" type="checkbox" name="is_current" value="1" class="size-4 rounded border-input accent-emerald-600" />
                This is my current role
            </label>

            <div class="flex flex-wrap items-center gap-3">
                <Button type="submit" :disabled="form.processing">{{ experience ? 'Save changes' : 'Add experience' }}</Button>
                <Button as-child variant="ghost">
                    <Link :href="index()">Cancel</Link>
                </Button>
            </div>
        </form>
    </div>
</template>
