<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowUpRight, Menu, X } from '@lucide/vue';
import { computed, ref } from 'vue';
import ParticlesBackground from '@/components/portfolio/ParticlesBackground.vue';
import { about, contact, dashboard, experience, home } from '@/routes';
import { show as projectShow } from '@/routes/projects';
import type { PortfolioProfile } from '@/types/portfolio';

const props = defineProps<{
    profile: PortfolioProfile;
}>();

const page = usePage();
const mobileMenuOpen = ref(false);

const initials = computed(() => {
    return (
        props.profile.name
            ?.split(' ')
            .filter(Boolean)
            .slice(0, 2)
            .map((part) => part[0]?.toUpperCase())
            .join('') || 'BR'
    );
});

const links = computed(() => [
    { label: 'Home', href: home(), active: page.url === '/' },
    { label: 'About', href: about(), active: page.url.startsWith('/about') },
    { label: 'Experience', href: experience(), active: page.url.startsWith('/experience') },
    { label: 'XRecipes', href: projectShow(), active: page.url.startsWith('/projects') },
    { label: 'Contact', href: contact(), active: page.url.startsWith('/contact') },
]);

const socialLinks = computed(() =>
    [
        { label: 'GitHub', href: props.profile.github_url },
        { label: 'LinkedIn', href: props.profile.linkedin_url },
        { label: 'Website', href: props.profile.website_url },
    ].filter((link): link is { label: string; href: string } => Boolean(link.href)),
);

const flashlightStyle = computed(() => {
    const key = page.url || '/';
    let seed = 0;

    for (const character of key) {
        seed = (seed * 31 + character.charCodeAt(0)) % 997;
    }

    const positions = [
        [18, 24],
        [74, 18],
        [48, 66],
        [86, 72],
        [28, 86],
        [62, 42],
    ];

    const selected = [0, 2, 4].map(
        (offset) => positions[(seed + offset) % positions.length],
    );

    const gradients = selected.map(([x, y], index) => {
        const color = index === 0 ? 'var(--flashlight-accent)' : 'var(--flashlight-neutral)';
        const radius = index === 0 ? '34rem' : '26rem';

        return `radial-gradient(circle ${radius} at ${x}% ${y}%, ${color} 0%, transparent 100%)`;
    });

    return { backgroundImage: gradients.join(', ') };
});

const year = new Date().getFullYear();
</script>

<template>
    <div class="relative isolate flex min-h-screen flex-col bg-zinc-50 text-zinc-900 dark:bg-zinc-950 dark:text-zinc-100">
        <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
            <div class="flashlight-field" :style="flashlightStyle"></div>
            <ParticlesBackground />
        </div>

        <header class="sticky top-0 z-40 bg-zinc-50/90 shadow-[0_1px_16px_-12px_rgba(0,0,0,0.35)] backdrop-blur dark:bg-zinc-950/90 dark:shadow-[0_1px_18px_-12px_rgba(0,0,0,0.8)]">
            <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-5 sm:px-8">
                <Link :href="home()" class="flex items-center gap-3 rounded-sm focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-emerald-600" @click="mobileMenuOpen = false">
                    <span class="flex size-9 items-center justify-center overflow-hidden rounded-md bg-zinc-100 dark:bg-zinc-800">
                        <img
                            v-if="profile.logo_url"
                            :src="profile.logo_url"
                            :alt="`${profile.name} logo`"
                            class="size-full object-cover"
                        />
                        <span v-else class="text-xs font-semibold text-zinc-700 dark:text-zinc-200">{{ initials }}</span>
                    </span>
                    <span class="text-sm font-semibold tracking-tight sm:text-base">{{ profile.name }}</span>
                </Link>

                <nav class="hidden items-center gap-1 md:flex" aria-label="Main navigation">
                    <Link
                        v-for="link in links"
                        :key="link.label"
                        :href="link.href"
                        class="rounded-md px-3 py-2 text-sm font-medium transition-colors hover:bg-zinc-200/70 hover:text-zinc-950 dark:hover:bg-zinc-800 dark:hover:text-white"
                        :class="link.active ? 'text-zinc-950 dark:text-white' : 'text-zinc-600 dark:text-zinc-400'"
                    >
                        {{ link.label }}
                    </Link>
                </nav>

                <div class="flex items-center gap-2">
                    <Link
                        :href="contact()"
                        class="hidden items-center gap-1.5 rounded-lg bg-zinc-900 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-zinc-700 sm:inline-flex dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Let's talk
                        <ArrowUpRight class="size-4" />
                    </Link>
                    <button
                        type="button"
                        class="inline-flex size-10 items-center justify-center rounded-lg bg-zinc-100 text-zinc-700 md:hidden dark:bg-zinc-800 dark:text-zinc-300"
                        :aria-expanded="mobileMenuOpen"
                        aria-label="Toggle navigation"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                    >
                        <X v-if="mobileMenuOpen" class="size-5" />
                        <Menu v-else class="size-5" />
                    </button>
                </div>
            </div>

            <nav v-if="mobileMenuOpen" class="bg-white px-5 py-3 shadow-[0_-1px_12px_-10px_rgba(0,0,0,0.25)] md:hidden dark:bg-zinc-900 dark:shadow-[0_-1px_14px_-10px_rgba(0,0,0,0.7)]" aria-label="Mobile navigation">
                <Link
                    v-for="link in links"
                    :key="link.label"
                    :href="link.href"
                    class="block rounded-md px-3 py-2.5 text-sm font-medium"
                    :class="link.active ? 'bg-zinc-100 text-zinc-950 dark:bg-zinc-800 dark:text-white' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-zinc-800'"
                    @click="mobileMenuOpen = false"
                >
                    {{ link.label }}
                </Link>
            </nav>
        </header>

        <main class="flex-1">
            <slot />
        </main>

        <footer class="shadow-[0_-1px_16px_-12px_rgba(0,0,0,0.3)] dark:shadow-[0_-1px_18px_-12px_rgba(0,0,0,0.8)]">
            <div class="mx-auto flex max-w-6xl flex-col gap-6 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-sm font-semibold">{{ profile.name }}</p>
                    <p class="mt-1 max-w-md text-sm text-zinc-500 dark:text-zinc-400">
                        {{ profile.headline }}<span v-if="profile.location"> · {{ profile.location }}</span>
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                    <a
                        v-for="link in socialLinks"
                        :key="link.label"
                        :href="link.href"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400"
                    >
                        {{ link.label }}
                    </a>
                    <Link
                        v-if="$page.props.auth.user"
                        :href="dashboard()"
                        class="text-zinc-600 transition-colors hover:text-emerald-700 dark:text-zinc-400 dark:hover:text-emerald-400"
                    >
                        Manage site
                    </Link>
                </div>
                <p class="text-xs text-zinc-400 dark:text-zinc-500">© {{ year }} {{ profile.name }}</p>
            </div>
        </footer>
    </div>
</template>
