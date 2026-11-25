<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import ImageUploadField from '@/components/admin/ImageUploadField.vue';
import RichTextEditor from '@/components/admin/RichTextEditor.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard as adminDashboard } from '@/routes/admin';
import { edit, update } from '@/routes/admin/profile';
import type { PortfolioProfile } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Profile', href: edit() },
        ],
    },
});

const props = defineProps<{
    profile: PortfolioProfile;
}>();

const form = useForm({
    name: props.profile.name,
    email: props.profile.email,
    headline: props.profile.headline,
    tagline: props.profile.tagline ?? '',
    hero_intro: props.profile.hero_intro ?? '',
    now_building: props.profile.now_building ?? '',
    now_learning: props.profile.now_learning ?? '',
    now_cooking: props.profile.now_cooking ?? '',
    bio: props.profile.bio,
    working_style: props.profile.working_style ?? '',
    education: props.profile.education ?? '',
    outside_the_code_html: props.profile.outside_the_code_html ?? '',
    location: props.profile.location ?? '',
    phone: props.profile.phone ?? '',
    years_experience: props.profile.years_experience ?? 0,
    resume_url: props.profile.resume_url ?? '',
    website_url: props.profile.website_url ?? '',
    github_url: props.profile.github_url ?? '',
    linkedin_url: props.profile.linkedin_url ?? '',
    logo_url: props.profile.logo_url ?? '',
    headshot_url: props.profile.headshot_url ?? '',
    about_image_url: props.profile.about_image_url ?? '',
    favicon_url: props.profile.favicon_url ?? '',
});

function submit(): void {
    form.put(update.url());
}

const inputClass =
    'flex h-9 w-full rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm outline-none placeholder:text-muted-foreground focus-visible:border-ring focus-visible:ring-2 focus-visible:ring-ring/30';

const resumeUrlPlaceholder = 'https://example.com/cv.pdf';
</script>

<template>
    <Head title="Portfolio profile" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">Portfolio profile</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                This information appears throughout the public site, including the header and contact page.
            </p>
        </div>

        <form class="max-w-3xl space-y-6" @submit.prevent="submit">
            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input id="name" v-model="form.name" name="name" required />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="grid gap-2">
                    <Label for="email">Contact email</Label>
                    <Input id="email" v-model="form.email" name="email" type="email" required />
                    <InputError :message="form.errors.email" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="headline">Headline</Label>
                <Input id="headline" v-model="form.headline" name="headline" required placeholder="Senior Full Stack Developer" />
                <InputError :message="form.errors.headline" />
            </div>

            <div class="grid gap-2">
                <Label for="tagline">Tagline <span class="font-normal text-muted-foreground">(optional)</span></Label>
                <Input id="tagline" v-model="form.tagline" name="tagline" placeholder="Backend engineering, application architecture and systems integration" />
                <InputError :message="form.errors.tagline" />
            </div>

            <div class="grid gap-2">
                <Label for="hero_intro">Home page intro <span class="font-normal text-muted-foreground">(optional)</span></Label>
                <textarea
                    id="hero_intro"
                    v-model="form.hero_intro"
                    name="hero_intro"
                    rows="4"
                    :class="inputClass"
                    placeholder="A short introduction shown under the tagline on the home page."
                ></textarea>
                <InputError :message="form.errors.hero_intro" />
            </div>

            <fieldset class="grid gap-4 rounded-lg border border-border p-4">
                <legend class="px-1 text-sm font-semibold">Now strip</legend>
                <p class="text-xs text-muted-foreground">These short updates appear in the strip on the home page.</p>
                <div class="grid gap-2">
                    <Label for="now_building">Building</Label>
                    <Input id="now_building" v-model="form.now_building" name="now_building" placeholder="What are you building?" />
                    <InputError :message="form.errors.now_building" />
                </div>
                <div class="grid gap-2">
                    <Label for="now_learning">Learning</Label>
                    <Input id="now_learning" v-model="form.now_learning" name="now_learning" placeholder="What are you learning?" />
                    <InputError :message="form.errors.now_learning" />
                </div>
                <div class="grid gap-2">
                    <Label for="now_cooking">Cooking</Label>
                    <Input id="now_cooking" v-model="form.now_cooking" name="now_cooking" placeholder="What are you cooking?" />
                    <InputError :message="form.errors.now_cooking" />
                </div>
            </fieldset>

            <div class="grid gap-2">
                <Label for="bio">About you</Label>
                <textarea
                    id="bio"
                    v-model="form.bio"
                    name="bio"
                    rows="7"
                    :class="inputClass"
                    placeholder="Separate paragraphs with a blank line."
                ></textarea>
                <InputError :message="form.errors.bio" />
            </div>

            <div class="grid gap-2">
                <Label for="working_style">Working style <span class="font-normal text-muted-foreground">(optional, one item per line)</span></Label>
                <textarea id="working_style" v-model="form.working_style" name="working_style" rows="5" :class="inputClass"></textarea>
                <InputError :message="form.errors.working_style" />
            </div>

            <div class="grid gap-2">
                <Label for="education">Education <span class="font-normal text-muted-foreground">(optional)</span></Label>
                <textarea id="education" v-model="form.education" name="education" rows="4" :class="inputClass"></textarea>
                <InputError :message="form.errors.education" />
            </div>

            <RichTextEditor
                v-model="form.outside_the_code_html"
                label="Outside the code"
                placeholder="What do you enjoy outside of work?"
                image-field="outside_the_code_image"
                hint="Use headings and short paragraphs to structure the section. Paste an image to add it inline."
                :error="form.errors.outside_the_code_html"
            />

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="location">Location <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="location" v-model="form.location" name="location" />
                    <InputError :message="form.errors.location" />
                </div>
                <div class="grid gap-2">
                    <Label for="phone">Phone <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="phone" v-model="form.phone" name="phone" />
                    <InputError :message="form.errors.phone" />
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="years_experience">Years of experience <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="years_experience" v-model.number="form.years_experience" name="years_experience" type="number" min="0" />
                    <InputError :message="form.errors.years_experience" />
                </div>
                <div class="grid gap-2">
                    <Label for="resume_url">Resume URL <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="resume_url" v-model="form.resume_url" name="resume_url" type="url" :placeholder="resumeUrlPlaceholder" />
                    <InputError :message="form.errors.resume_url" />
                </div>
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <ImageUploadField
                    v-model="form.logo_url"
                    field="logo_url"
                    label="Logo"
                    hint="Used in the site header. Square images work best."
                    :error="form.errors.logo_url"
                />
                <ImageUploadField
                    v-model="form.headshot_url"
                    field="headshot_url"
                    label="Headshot"
                    hint="Used on the home page. Portrait images work best."
                    :error="form.errors.headshot_url"
                />
                <ImageUploadField
                    v-model="form.favicon_url"
                    field="favicon_url"
                    label="Favicon"
                    hint="Shown in the browser tab. Square PNG, SVG or WebP images work best."
                    :error="form.errors.favicon_url"
                />
            </div>

            <ImageUploadField
                v-model="form.about_image_url"
                field="about_image_url"
                label="Casual photo"
                hint="Used on the about page. Landscape images work best."
                :error="form.errors.about_image_url"
            />

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="website_url">Website URL <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="website_url" v-model="form.website_url" name="website_url" type="url" />
                    <InputError :message="form.errors.website_url" />
                </div>
                <div class="grid gap-2">
                    <Label for="github_url">GitHub URL <span class="font-normal text-muted-foreground">(optional)</span></Label>
                    <Input id="github_url" v-model="form.github_url" name="github_url" type="url" />
                    <InputError :message="form.errors.github_url" />
                </div>
            </div>

            <div class="grid gap-2">
                <Label for="linkedin_url">LinkedIn URL <span class="font-normal text-muted-foreground">(optional)</span></Label>
                <Input id="linkedin_url" v-model="form.linkedin_url" name="linkedin_url" type="url" />
                <InputError :message="form.errors.linkedin_url" />
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Button type="submit" :disabled="form.processing">Save profile</Button>
                <Button as-child variant="ghost">
                    <Link :href="adminDashboard()">Back to overview</Link>
                </Button>
            </div>
        </form>
    </div>
</template>
