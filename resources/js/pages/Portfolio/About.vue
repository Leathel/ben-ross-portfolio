<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowUpRight, Mail, MapPin } from '@lucide/vue';
import { computed } from 'vue';
import ProfileImage from '@/components/portfolio/ProfileImage.vue';
import SectionHeading from '@/components/portfolio/SectionHeading.vue';
import { contact } from '@/routes';
import type {
    PortfolioProfile,
    PortfolioSkill,
    SkillGroup,
} from '@/types/portfolio';

const props = defineProps<{
    profile: PortfolioProfile;
    skills: PortfolioSkill[];
}>();

const paragraphs = computed(() =>
    (props.profile.bio ?? '').split('\n\n').filter(Boolean),
);
const workingStyleItems = computed(() =>
    (props.profile.working_style ?? '').split('\n').filter(Boolean),
);
const educationParagraphs = computed(() =>
    (props.profile.education ?? '').split('\n\n').filter(Boolean),
);

const skillGroups = computed<SkillGroup[]>(() => {
    const groups = new Map<string, PortfolioSkill[]>();

    for (const skill of props.skills) {
        const existing = groups.get(skill.category) ?? [];
        existing.push(skill);
        groups.set(skill.category, existing);
    }

    return [...groups.entries()].map(([category, skills]) => ({
        category,
        skills,
    }));
});
</script>

<template>
    <Head title="About" />

    <div>
        <section>
            <div
                class="animate-fade-up mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20"
            >
                <p class="tech-label text-zinc-500 dark:text-zinc-400">About</p>
                <h1
                    class="mt-3 max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl"
                >
                    A little about me
                </h1>
                <p
                    class="mt-5 max-w-2xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400"
                >
                    {{ profile.headline }} — I work across backend systems,
                    integrations and application architecture.
                </p>
            </div>
        </section>

        <section
            class="mx-auto grid max-w-6xl gap-12 px-5 py-16 sm:px-8 lg:grid-cols-[1.4fr_0.6fr]"
        >
            <div class="animate-fade-up" style="animation-delay: 80ms">
                <div
                    class="space-y-5 text-base leading-relaxed text-zinc-700 dark:text-zinc-300"
                >
                    <p v-for="(paragraph, index) in paragraphs" :key="index">
                        {{ paragraph }}
                    </p>
                </div>

                <div v-if="workingStyleItems.length" class="mt-12">
                    <SectionHeading eyebrow="Approach" title="How I work" />
                    <ul
                        class="mt-5 grid gap-3 text-sm leading-relaxed text-zinc-700 sm:grid-cols-2 dark:text-zinc-300"
                    >
                        <li
                            v-for="item in workingStyleItems"
                            :key="item"
                            class="flex gap-2"
                        >
                            <span
                                class="mt-2 size-1.5 shrink-0 rounded-full bg-zinc-400 dark:bg-zinc-500"
                            ></span>
                            <span>{{ item }}</span>
                        </li>
                    </ul>
                </div>

                <div v-if="educationParagraphs.length" class="mt-12">
                    <SectionHeading eyebrow="Education" title="Background" />
                    <div
                        class="mt-5 space-y-3 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300"
                    >
                        <p
                            v-for="(paragraph, index) in educationParagraphs"
                            :key="index"
                            class="whitespace-pre-line"
                        >
                            {{ paragraph }}
                        </p>
                    </div>
                </div>
            </div>

            <aside class="space-y-6">
                <ProfileImage
                    :src="profile.about_image_url"
                    :name="profile.name"
                    orientation="wide"
                />

                <div
                    class="animate-fade-up rounded-lg bg-zinc-100/70 p-6 dark:bg-zinc-900"
                    style="animation-delay: 120ms"
                >
                    <h2 class="text-sm font-semibold">Details</h2>
                    <dl class="mt-4 space-y-4 text-sm">
                        <div v-if="profile.location" class="flex gap-3">
                            <MapPin
                                class="mt-0.5 size-4 shrink-0 text-zinc-500 dark:text-zinc-400"
                            />
                            <div>
                                <dt
                                    class="text-xs text-zinc-500 dark:text-zinc-400"
                                >
                                    Based in
                                </dt>
                                <dd class="mt-0.5 font-medium">
                                    {{ profile.location }}
                                </dd>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <Mail
                                class="mt-0.5 size-4 shrink-0 text-zinc-500 dark:text-zinc-400"
                            />
                            <div>
                                <dt
                                    class="text-xs text-zinc-500 dark:text-zinc-400"
                                >
                                    Email
                                </dt>
                                <dd class="mt-0.5 font-medium break-all">
                                    <a
                                        :href="`mailto:${profile.email}`"
                                        class="hover:text-emerald-700 dark:hover:text-emerald-400"
                                        >{{ profile.email }}</a
                                    >
                                </dd>
                            </div>
                        </div>
                        <div v-if="profile.years_experience" class="flex gap-3">
                            <ArrowUpRight
                                class="mt-0.5 size-4 shrink-0 text-zinc-500 dark:text-zinc-400"
                            />
                            <div>
                                <dt
                                    class="text-xs text-zinc-500 dark:text-zinc-400"
                                >
                                    Experience
                                </dt>
                                <dd class="mt-0.5 font-medium">
                                    {{ profile.years_experience }} years
                                </dd>
                            </div>
                        </div>
                    </dl>
                    <Link
                        :href="contact()"
                        class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-zinc-900 px-4 py-2.5 text-sm font-semibold text-white transition-colors hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Contact me
                        <ArrowUpRight class="size-4" />
                    </Link>
                    <a
                        v-if="profile.resume_url"
                        :href="profile.resume_url"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-200 dark:text-zinc-300 dark:hover:bg-zinc-800"
                    >
                        Download resume
                    </a>
                </div>

                <div
                    id="skills"
                    class="animate-fade-up rounded-lg bg-zinc-100/70 p-6 dark:bg-zinc-900"
                    style="animation-delay: 180ms"
                >
                    <h2 class="text-sm font-semibold">Skills by area</h2>
                    <div
                        v-for="group in skillGroups"
                        :key="group.category"
                        class="mt-5 first:mt-4"
                    >
                        <p
                            class="text-xs font-medium tracking-wide text-zinc-400 uppercase dark:text-zinc-500"
                        >
                            {{ group.category }}
                        </p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            <span
                                v-for="skill in group.skills"
                                :key="skill.id"
                                class="rounded-md bg-zinc-200/70 px-2 py-1 text-xs font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300"
                            >
                                {{ skill.name }}
                            </span>
                        </div>
                    </div>
                </div>
            </aside>
        </section>

        <section
            class="border-y border-zinc-200/70 bg-zinc-100/30 dark:border-zinc-800/70 dark:bg-zinc-900/25"
        >
            <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20">
                <SectionHeading
                    eyebrow="Beyond work"
                    title="Outside the code"
                />
                <div
                    v-if="profile.outside_the_code_html"
                    v-highlight-code
                    class="rich-text mt-8 max-w-3xl text-base leading-relaxed text-zinc-700 dark:text-zinc-300"
                    v-html="profile.outside_the_code_html"
                ></div>
                <p
                    v-else
                    class="mt-8 max-w-3xl text-sm text-zinc-500 dark:text-zinc-400"
                >
                    [TODO: Add things you do outside of work.]
                </p>
            </div>
        </section>
    </div>
</template>
