<script setup lang="ts">
import { computed } from 'vue';

const props = withDefaults(
    defineProps<{
        src?: string | null;
        name: string;
        orientation?: 'portrait' | 'wide';
    }>(),
    {
        src: null,
        orientation: 'portrait',
    },
);

const initials = computed(() =>
    props.name
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((part) => part[0]?.toUpperCase())
        .join('') || 'BR',
);
</script>

<template>
    <figure class="animate-image-in w-full">
        <div
            class="relative overflow-hidden rounded-lg bg-zinc-100 dark:bg-zinc-900"
            :class="orientation === 'wide' ? 'aspect-[4/3]' : 'aspect-[4/5]'"
        >
            <img
                v-if="src"
                :src="src"
                :alt="name"
                class="size-full object-cover"
            />
            <div v-else class="flex size-full items-center justify-center">
                <span class="text-5xl font-semibold tracking-tight text-zinc-400 dark:text-zinc-600">
                    {{ initials }}
                </span>
            </div>
        </div>
    </figure>
</template>
