<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, ArrowUpRight, Code2, ExternalLink } from '@lucide/vue';
import { home } from '@/routes';
import type { PortfolioProfile, PortfolioProject } from '@/types/portfolio';

defineProps<{
    profile: PortfolioProfile;
    project: PortfolioProject;
}>();
</script>

<template>
    <Head :title="project.title" />

    <div>
        <section>
            <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8 sm:py-16">
                <Link
                    :href="home()"
                    class="animate-fade-up inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400"
                >
                    <ArrowLeft class="size-4" />
                    Back to home
                </Link>

                <div
                    class="animate-fade-up mt-8 grid gap-10 lg:grid-cols-[1.3fr_0.7fr] lg:items-end"
                >
                    <div>
                        <p class="tech-label text-zinc-500 dark:text-zinc-400">
                            {{ project.category || 'Project' }}
                        </p>
                        <h1
                            class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl"
                        >
                            {{ project.title }}
                        </h1>
                        <p
                            class="mt-5 max-w-2xl text-lg leading-relaxed text-zinc-600 dark:text-zinc-400"
                        >
                            {{ project.summary }}
                        </p>
                    </div>

                    <div class="flex flex-wrap gap-3 lg:justify-end">
                        <a
                            v-if="project.live_url"
                            :href="project.live_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-5 py-3 text-sm font-semibold text-white transition-colors hover:bg-zinc-700 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                        >
                            Visit site
                            <ExternalLink class="size-4" />
                        </a>
                        <a
                            v-if="project.source_url"
                            :href="project.source_url"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 rounded-lg px-5 py-3 text-sm font-semibold text-zinc-700 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800"
                        >
                            Source code
                            <Code2 class="size-4" />
                        </a>
                    </div>
                </div>

                <dl
                    class="animate-fade-up mt-10 flex flex-wrap gap-x-10 gap-y-6 text-sm"
                    style="animation-delay: 180ms"
                >
                    <div v-if="project.organization">
                        <dt
                            class="text-xs tracking-wide text-zinc-400 uppercase dark:text-zinc-500"
                        >
                            Organization
                        </dt>
                        <dd class="mt-1 font-medium">
                            {{ project.organization }}
                        </dd>
                    </div>
                    <div v-if="project.role">
                        <dt
                            class="text-xs tracking-wide text-zinc-400 uppercase dark:text-zinc-500"
                        >
                            Role
                        </dt>
                        <dd class="mt-1 font-medium">{{ project.role }}</dd>
                    </div>
                    <div class="w-full sm:w-auto sm:flex-1 sm:basis-72">
                        <dt
                            class="text-xs tracking-wide text-zinc-400 uppercase dark:text-zinc-500"
                        >
                            Built with
                        </dt>
                        <dd
                            v-if="project.technologies?.length"
                            class="mt-2 flex flex-wrap gap-2"
                        >
                            <span
                                v-for="technology in project.technologies"
                                :key="technology"
                                class="rounded-md bg-zinc-100 px-2.5 py-1.5 text-xs font-medium text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300"
                            >
                                {{ technology }}
                            </span>
                        </dd>
                        <dd
                            v-else
                            class="mt-2 text-zinc-500 dark:text-zinc-400"
                        >
                            Technologies will be added soon.
                        </dd>
                    </div>
                </dl>

                <img
                    v-if="project.image_url"
                    :src="project.image_url"
                    :alt="`${project.title} preview`"
                    class="animate-image-in mt-10 h-auto w-full rounded-lg"
                />
                <div
                    v-else
                    class="animate-image-in mt-10 flex aspect-[16/7] w-full items-center justify-center rounded-lg bg-zinc-100 dark:bg-zinc-900"
                >
                    <span
                        class="text-5xl font-semibold tracking-tight text-zinc-300 dark:text-zinc-600"
                    >
                        {{ project.title.slice(0, 2).toUpperCase() }}
                    </span>
                </div>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-5 py-16 sm:px-8">
            <div
                class="animate-fade-up mx-auto max-w-3xl"
                style="animation-delay: 120ms"
            >
                <div
                    v-if="project.case_study_html"
                    v-highlight-code
                    class="rich-text text-zinc-700 dark:text-zinc-300"
                    v-html="project.case_study_html"
                ></div>
                <p
                    v-else
                    class="text-base leading-relaxed text-zinc-700 dark:text-zinc-300"
                >
                    {{ project.description || project.summary }}
                </p>
            </div>
        </section>
    </div>
</template>
