<script setup lang="ts">
import { computed } from 'vue';
import type {
    PortfolioExperience,
    PortfolioExperiencePosition,
} from '@/types/portfolio';

const props = defineProps<{
    experience: PortfolioExperience | PortfolioExperiencePosition;
    compact?: boolean;
}>();

const formatter = new Intl.DateTimeFormat('en', {
    month: 'short',
    year: 'numeric',
    timeZone: 'UTC',
});

function formatMonthYear(value: string | null | undefined): string | null {
    if (!value) {
        return null;
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return null;
    }

    return formatter.format(date);
}

const period = computed(() => {
    const start = formatMonthYear(props.experience.start_date) ?? '—';
    const end = props.experience.is_current
        ? 'Present'
        : (formatMonthYear(props.experience.end_date) ?? '—');

    return `${start} — ${end}`;
});
</script>

<template>
    <article class="animate-fade-up relative pb-10 pl-8 last:pb-0">
        <span
            class="absolute top-1.5 left-0 size-3 rounded-full border-2 border-zinc-900 bg-zinc-50 dark:border-zinc-100 dark:bg-zinc-950"
        ></span>
        <span
            class="absolute top-5 bottom-0 left-[5px] w-px bg-zinc-200 last:hidden dark:bg-zinc-800"
        ></span>

        <p
            class="text-xs font-medium tracking-wide text-zinc-500 uppercase dark:text-zinc-400"
        >
            {{ period }}
        </p>
        <h3 class="mt-1 text-lg font-semibold tracking-tight">
            {{ experience.role }}
        </h3>
        <p class="mt-0.5 text-sm font-medium text-zinc-700 dark:text-zinc-300">
            {{ experience.company }}
            <span
                v-if="experience.location"
                class="font-normal text-zinc-500 dark:text-zinc-500"
            >
                · {{ experience.location }}
            </span>
        </p>
        <template v-if="!compact">
            <div
                v-if="'description' in experience"
                v-highlight-code
                class="rich-text mt-3 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400"
                v-html="experience.description"
            ></div>
            <div
                v-if="
                    'skill_tags' in experience && experience.skill_tags?.length
                "
                class="mt-4 flex flex-wrap gap-1.5"
            >
                <span
                    v-for="tag in experience.skill_tags"
                    :key="tag"
                    class="rounded-md bg-zinc-200/70 px-2 py-1 text-[11px] font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400"
                >
                    {{ tag }}
                </span>
            </div>
            <span
                v-if="
                    'employment_type' in experience &&
                    experience.employment_type
                "
                class="mt-3 inline-block rounded-md bg-zinc-100 px-2 py-1 text-[11px] font-medium text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400"
            >
                {{ experience.employment_type }}
            </span>
        </template>
    </article>
</template>
