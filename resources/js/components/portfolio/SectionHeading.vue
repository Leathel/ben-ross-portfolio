<script setup lang="ts">
withDefaults(
    defineProps<{
        eyebrow?: string;
        title: string;
        description?: string;
    }>(),
    {
        eyebrow: '',
        description: '',
    },
);
</script>

<template>
    <div class="animate-fade-up mb-10 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
        <div class="max-w-2xl">
            <p
                v-if="eyebrow"
                class="tech-label mb-2 text-zinc-500 dark:text-zinc-400"
            >
                {{ eyebrow }}
            </p>
            <h2 class="text-2xl font-semibold tracking-tight sm:text-3xl">
                {{ title }}
            </h2>
            <p v-if="description" class="mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">
                {{ description }}
            </p>
        </div>
        <slot />
    </div>
</template>
