<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight, ArrowUpRight, Download, MapPin, Mail } from '@lucide/vue';
import ProfileImage from '@/components/portfolio/ProfileImage.vue';
import SectionHeading from '@/components/portfolio/SectionHeading.vue';
import TimelineItem from '@/components/portfolio/TimelineItem.vue';
import { about as aboutRoute, contact, experience as experienceRoute } from '@/routes';
import { show as projectShow } from '@/routes/projects';
import type {
    PortfolioExperiencePosition,
    PortfolioProfile,
    PortfolioProject,
    PortfolioSkill,
} from '@/types/portfolio';

defineProps<{
    profile: PortfolioProfile;
    featuredProject: PortfolioProject | null;
    skills: PortfolioSkill[];
    experience: PortfolioExperiencePosition[];
}>();

const aboutSkillsHref = `${aboutRoute().url}#skills`;
</script>

<template>
    <Head title="Home" />

    <div>
        <section>
            <div class="mx-auto grid max-w-6xl gap-12 px-5 py-20 sm:px-8 lg:grid-cols-[1.15fr_0.85fr] lg:py-28">
                <div class="animate-fade-up">
                    <h1 class="max-w-3xl text-4xl font-semibold leading-[1.1] tracking-tight sm:text-5xl lg:text-6xl">
                        Hi, I'm {{ profile.name }}.
                        <span class="mt-2 block text-[40px] leading-tight text-zinc-500 dark:text-zinc-400">
                            {{ profile.tagline || profile.headline }}
                        </span>
                    </h1>

                    <p
                        v-if="profile.hero_intro"
                        class="mt-6 max-w-2xl text-base leading-relaxed text-zinc-600 sm:text-lg dark:text-zinc-400"
                    >
                        {{ profile.hero_intro }}
                    </p>

                    <div class="mt-9 flex flex-wrap items-center gap-3">
                        <Link
                            :href="experienceRoute()"
                            class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            View my Experience
                            <ArrowRight class="size-4" />
                        </Link>
                        <Link
                            :href="contact()"
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800"
                        >
                            Get in touch
                            <ArrowUpRight class="size-4" />
                        </Link>
                        <a
                            v-if="profile.resume_url"
                            :href="profile.resume_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800"
                        >
                            Download resume
                            <Download class="size-4" />
                        </a>
                    </div>

                    <div class="mt-10 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-zinc-500 dark:text-zinc-400">
                        <span v-if="profile.location" class="inline-flex items-center gap-1.5">
                            <MapPin class="size-4" />
                            {{ profile.location }}
                        </span>
                        <a :href="`mailto:${profile.email}`" class="inline-flex items-center gap-1.5 transition-colors hover:text-emerald-700 dark:hover:text-emerald-400">
                            <Mail class="size-4" />
                            {{ profile.email }}
                        </a>
                    </div>
                </div>

                <div class="animate-fade-up" style="animation-delay: 100ms">
                    <ProfileImage
                        :src="profile.headshot_url"
                        :name="profile.name"
                        class="w-full"
                    />
                </div>
            </div>
        </section>

        <section
            v-if="profile.now_building || profile.now_learning || profile.now_cooking"
            class="border-b border-zinc-200/70 dark:border-zinc-800/70"
        >
            <div class="mx-auto max-w-6xl px-5 py-10 sm:px-8">
                <div class="flex items-center gap-2">
                    <span class="size-1.5 rounded-full bg-emerald-600 dark:bg-emerald-400"></span>
                    <p class="tech-label text-zinc-500 dark:text-zinc-400">Now</p>
                </div>
                <div class="mt-6 grid gap-6 sm:grid-cols-3">
                    <div v-if="profile.now_building">
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Building</p>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ profile.now_building }}</p>
                    </div>
                    <div v-if="profile.now_learning">
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Learning</p>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ profile.now_learning }}</p>
                    </div>
                    <div v-if="profile.now_cooking">
                        <p class="text-xs font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Cooking</p>
                        <p class="mt-2 text-sm leading-relaxed text-zinc-700 dark:text-zinc-300">{{ profile.now_cooking }}</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="border-y border-zinc-200/70 bg-zinc-100/30 dark:border-zinc-800/70 dark:bg-zinc-900/25">
            <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20">
                <div v-if="featuredProject" class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16">
                    <div class="animate-fade-up order-2 lg:order-1" style="animation-delay: 80ms">
                        <p class="tech-label text-zinc-500 dark:text-zinc-400">
                            {{ featuredProject.category || 'Independent project' }}
                        </p>
                        <h3 class="mt-3 text-3xl font-semibold tracking-tight sm:text-4xl">
                            {{ featuredProject.title }}
                        </h3>
                        <p class="mt-4 max-w-xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400">
                            {{ featuredProject.summary }}
                        </p>

                        <ul v-if="featuredProject.technologies?.length" class="mt-6 flex flex-wrap gap-2">
                            <li
                                v-for="technology in featuredProject.technologies"
                                :key="technology"
                                class="rounded-md border border-zinc-200/80 px-2.5 py-1.5 text-xs font-medium text-zinc-600 dark:border-zinc-700/70 dark:text-zinc-300"
                            >
                                {{ technology }}
                            </li>
                        </ul>

                        <Link
                            :href="projectShow()"
                            class="group/link mt-8 inline-flex items-center gap-2 text-sm font-semibold text-zinc-900 transition-colors hover:text-emerald-700 dark:text-zinc-100 dark:hover:text-emerald-400"
                        >
                            <span class="border-b border-zinc-300 pb-0.5 transition-colors group-hover/link:border-emerald-700 dark:border-zinc-700 dark:group-hover/link:border-emerald-400">
                                Read case study
                            </span>
                            <ArrowUpRight class="size-4 transition-transform group-hover/link:translate-x-0.5 group-hover/link:-translate-y-0.5" />
                        </Link>
                    </div>

                    <Link
                        :href="projectShow()"
                        class="relative order-1 block lg:order-2"
                    >
                        <div class="relative mx-auto w-fit overflow-hidden rounded-xl">
                            <img
                                v-if="featuredProject.card_image_url || featuredProject.image_url"
                                :src="featuredProject.card_image_url || featuredProject.image_url || undefined"
                                :alt="`${featuredProject.title} preview`"
                                class="block max-h-[500px] max-w-none"
                            />
                            <div
                                v-else
                                class="flex aspect-[16/10] items-center justify-center text-5xl font-semibold tracking-tight text-zinc-400 dark:text-zinc-600"
                            >
                                {{ featuredProject.title.slice(0, 2).toUpperCase() }}
                            </div>
                        </div>
                    </Link>
                </div>

                <p v-else class="animate-fade-up border border-dashed border-zinc-300 py-10 text-center text-sm text-zinc-500 dark:border-zinc-700 dark:text-zinc-400">
                    The XRecipes case study will appear here once it is published.
                </p>
            </div>
        </section>

        <section>
            <div class="mx-auto max-w-6xl px-5 py-20 sm:px-8">
                <SectionHeading eyebrow="Toolkit" title="Things I work with" description="The languages, frameworks and tools I reach for most often.">
                    <Link :href="aboutSkillsHref" class="inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-900 hover:text-zinc-600 dark:text-zinc-100 dark:hover:text-zinc-300">
                        Skills on my About page
                        <ArrowRight class="size-4" />
                    </Link>
                </SectionHeading>

                <div class="flex flex-wrap gap-2.5">
                    <span
                        v-for="(skill, index) in skills"
                        :key="skill.id"
                        class="animate-fade-up rounded-lg bg-zinc-100/80 px-4 py-2 text-sm font-medium text-zinc-700 dark:bg-zinc-800/60 dark:text-zinc-300"
                        :style="{ animationDelay: `${index * 25}ms` }"
                    >
                        {{ skill.name }}
                    </span>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-5 py-20 sm:px-8">
            <SectionHeading eyebrow="Experience" title="Where I've worked" description="A short version of my work history.">
                <Link :href="experienceRoute()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-zinc-900 hover:text-zinc-600 dark:text-zinc-100 dark:hover:text-zinc-300">
                    Full work history
                    <ArrowRight class="size-4" />
                </Link>
            </SectionHeading>

            <div class="mx-auto max-w-3xl">
                <TimelineItem
                    v-for="(item, index) in experience"
                    :key="item.id"
                    :experience="item"
                    compact
                    :style="{ animationDelay: `${index * 70}ms` }"
                />
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-5 pb-24 sm:px-8">
            <div class="animate-fade-up overflow-hidden rounded-lg bg-zinc-900 px-7 py-12 sm:px-12">
                <div class="flex flex-col items-start justify-between gap-8 md:flex-row md:items-center">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight text-white sm:text-3xl">
                            Have something in mind?
                        </h2>
                        <p class="mt-3 max-w-xl text-sm leading-relaxed text-zinc-300 dark:text-zinc-400">
                            I'm always happy to talk about new projects, collaborations or a tricky problem worth solving.
                        </p>
                    </div>
                    <Link
                        :href="contact()"
                        class="inline-flex shrink-0 items-center gap-2 rounded-lg bg-white px-5 py-3 text-sm font-semibold text-zinc-900 transition-colors hover:bg-zinc-200"
                    >
                        Start a conversation
                        <ArrowUpRight class="size-4" />
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
