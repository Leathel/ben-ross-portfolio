<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowRight } from '@lucide/vue';
import SectionHeading from '@/components/portfolio/SectionHeading.vue';
import TimelineItem from '@/components/portfolio/TimelineItem.vue';
import { contact } from '@/routes';
import { show as projectShow } from '@/routes/projects';
import type { PortfolioExperience, PortfolioProfile } from '@/types/portfolio';

defineProps<{
    profile: PortfolioProfile;
    experience: PortfolioExperience[];
}>();
</script>

<template>
    <Head title="Experience" />

    <div>
        <section>
            <div class="animate-fade-up mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20">
                <p class="tech-label text-zinc-500 dark:text-zinc-400">Experience</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-semibold tracking-tight sm:text-5xl">
                    Work history
                </h1>
                <p class="mt-5 max-w-2xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-400">
                    Over {{ profile.years_experience || 10 }} years across custom application development, e-commerce systems and infrastructure work.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20">
            <SectionHeading
                eyebrow="Career"
                title="Where I've worked"
                description="A chronological view of my professional roles and co-op placements."
            />

            <div class="mx-auto mt-12 max-w-3xl">
                <TimelineItem
                    v-for="(item, index) in experience"
                    :key="item.id"
                    :experience="item"
                    :style="{ animationDelay: `${index * 70}ms` }"
                />
            </div>

            <div class="animate-fade-up mx-auto mt-8 max-w-3xl rounded-lg bg-zinc-100/70 p-6 dark:bg-zinc-900">
                <p class="text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                    The Ontario government roles were three four-month co-op placements completed as part of my Computer Programming studies at Georgian College.
                </p>
                <div class="mt-5 flex flex-wrap gap-4">
                    <Link :href="projectShow()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-400">
                        See the XRecipes case study
                        <ArrowRight class="size-4" />
                    </Link>
                    <Link :href="contact()" class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-400">
                        Get in touch
                        <ArrowRight class="size-4" />
                    </Link>
                </div>
            </div>
        </section>
    </div>
</template>
