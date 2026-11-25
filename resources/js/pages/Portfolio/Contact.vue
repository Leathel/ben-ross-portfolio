<script setup lang="ts">
import { Form, Head } from '@inertiajs/vue3';
import { ArrowUpRight, Mail, MapPin } from '@lucide/vue';
import InputError from '@/components/InputError.vue';
import { store } from '@/routes/contact';
import type { PortfolioProfile } from '@/types/portfolio';

defineProps<{
    profile: PortfolioProfile;
    status?: string | null;
}>();

const inputClass =
    'mt-1.5 w-full rounded-lg border border-zinc-300 bg-white px-3.5 py-2.5 text-sm text-zinc-900 shadow-sm outline-none transition-colors placeholder:text-zinc-400 focus:border-emerald-600 focus:ring-2 focus:ring-emerald-600/20 dark:border-zinc-700 dark:bg-zinc-900 dark:text-zinc-100 dark:placeholder:text-zinc-500 dark:focus:border-emerald-500';
const labelClass = 'text-sm font-medium text-zinc-800 dark:text-zinc-200';
</script>

<template>
    <Head title="Contact" />

    <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8 sm:py-20">
        <div class="animate-fade-up">
            <p class="tech-label text-zinc-500 dark:text-zinc-400">Contact</p>
            <h1 class="mt-3 text-4xl font-semibold tracking-tight sm:text-5xl">Let's talk</h1>
            <p class="mt-5 max-w-2xl text-base leading-relaxed text-zinc-600 dark:text-zinc-400">
                Have a project in mind, a question, or just want to say hello? Drop me a message and I'll get back to you as soon as I can.
            </p>
        </div>

        <div class="mt-12 grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <aside class="space-y-6">
                <div class="animate-fade-up rounded-lg bg-zinc-100/70 p-6 dark:bg-zinc-900">
                    <h2 class="text-sm font-semibold">Reach me directly</h2>
                    <div class="mt-5 space-y-5 text-sm">
                        <div class="flex gap-3">
                            <Mail class="mt-0.5 size-4 shrink-0 text-zinc-500 dark:text-zinc-400" />
                            <div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Email</p>
                                <a :href="`mailto:${profile.email}`" class="mt-0.5 block break-all font-medium hover:text-emerald-700 dark:hover:text-emerald-400">
                                    {{ profile.email }}
                                </a>
                            </div>
                        </div>
                        <div v-if="profile.location" class="flex gap-3">
                            <MapPin class="mt-0.5 size-4 shrink-0 text-zinc-500 dark:text-zinc-400" />
                            <div>
                                <p class="text-xs text-zinc-500 dark:text-zinc-400">Location</p>
                                <p class="mt-0.5 font-medium">{{ profile.location }}</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="profile.github_url || profile.linkedin_url" class="mt-6 flex flex-wrap gap-4 text-sm">
                        <a v-if="profile.github_url" :href="profile.github_url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-medium text-zinc-700 hover:text-emerald-700 dark:text-zinc-300 dark:hover:text-emerald-400">
                            GitHub <ArrowUpRight class="size-3.5" />
                        </a>
                        <a v-if="profile.linkedin_url" :href="profile.linkedin_url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-medium text-zinc-700 hover:text-emerald-700 dark:text-zinc-300 dark:hover:text-emerald-400">
                            LinkedIn <ArrowUpRight class="size-3.5" />
                        </a>
                    </div>
                </div>

                <div v-if="profile.resume_url" class="animate-fade-up rounded-lg bg-zinc-100/70 p-6 dark:bg-zinc-900" style="animation-delay: 80ms">
                    <h2 class="text-sm font-semibold">Résumé</h2>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Prefer the short version? Take a look at my CV.</p>
                    <a :href="profile.resume_url" target="_blank" rel="noopener noreferrer" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 hover:text-emerald-600 dark:text-emerald-400">
                        View résumé <ArrowUpRight class="size-4" />
                    </a>
                </div>
            </aside>

            <div class="animate-fade-up rounded-lg bg-zinc-100/50 p-6 sm:p-8 dark:bg-zinc-900" style="animation-delay: 120ms">
                <Form v-bind="store.form()" :reset-on-success="true" class="space-y-5" v-slot="{ errors, processing, wasSuccessful }">
                    <div
                        v-if="status || wasSuccessful"
                        class="rounded-lg bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900 dark:bg-emerald-950/40 dark:text-emerald-200"
                    >
                        {{ status || 'Thanks for getting in touch. I will be in touch soon.' }}
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label for="name" :class="labelClass">Your name</label>
                            <input id="name" name="name" type="text" autocomplete="name" required placeholder="Jane Smith" :class="inputClass" />
                            <InputError class="mt-1.5" :message="errors.name" />
                        </div>
                        <div>
                            <label for="email" :class="labelClass">Email address</label>
                            <input id="email" name="email" type="email" autocomplete="email" required placeholder="jane@example.com" :class="inputClass" />
                            <InputError class="mt-1.5" :message="errors.email" />
                        </div>
                    </div>

                    <div>
                        <label for="subject" :class="labelClass">Subject <span class="font-normal text-zinc-400">(optional)</span></label>
                        <input id="subject" name="subject" type="text" placeholder="What is this about?" :class="inputClass" />
                        <InputError class="mt-1.5" :message="errors.subject" />
                    </div>

                    <div>
                        <label for="message" :class="labelClass">Message</label>
                        <textarea id="message" name="message" rows="6" required placeholder="Tell me a little about your project..." :class="inputClass"></textarea>
                        <InputError class="mt-1.5" :message="errors.message" />
                    </div>

                    <button
                        type="submit"
                        :disabled="processing"
                        class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-zinc-700 disabled:cursor-not-allowed disabled:opacity-60 dark:bg-white dark:text-zinc-900 dark:hover:bg-zinc-200"
                    >
                        Send message
                        <ArrowUpRight class="size-4" />
                    </button>
                </Form>
            </div>
        </div>
    </div>
</template>
