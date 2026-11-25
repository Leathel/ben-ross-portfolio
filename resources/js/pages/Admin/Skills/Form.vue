<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { dashboard as adminDashboard } from '@/routes/admin';
import { index, store, update } from '@/routes/admin/skills';
import type { PortfolioSkill } from '@/types/portfolio';

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Overview', href: adminDashboard() },
            { title: 'Skills', href: index() },
        ],
    },
});

const props = defineProps<{
    skill: PortfolioSkill | null;
}>();

const formAction = computed(() =>
    props.skill ? update.form(props.skill.id) : store.form(),
);
</script>

<template>
    <Head :title="skill ? 'Edit skill' : 'New skill'" />

    <div class="flex h-full flex-1 flex-col gap-6 p-4 sm:p-6">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight">
                {{ skill ? `Edit ${skill.name}` : 'New skill' }}
            </h1>
            <p class="mt-1 text-sm text-muted-foreground">
                {{ skill ? 'Update this skill.' : 'Add a skill or tool to your portfolio.' }}
            </p>
        </div>

        <Form v-bind="formAction" class="max-w-2xl space-y-6" v-slot="{ errors, processing }">
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input id="name" name="name" :default-value="skill?.name" required placeholder="Laravel" />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="category">Category</Label>
                <Input id="category" name="category" :default-value="skill?.category" required placeholder="Frameworks" />
                <InputError :message="errors.category" />
            </div>

            <div class="grid gap-6 sm:grid-cols-2">
                <div class="grid gap-2">
                    <Label for="proficiency">Proficiency <span class="font-normal text-muted-foreground">(0–100, optional)</span></Label>
                    <Input id="proficiency" name="proficiency" type="number" min="0" max="100" :default-value="skill?.proficiency ?? ''" />
                    <InputError :message="errors.proficiency" />
                </div>
                <div class="grid gap-2">
                    <Label for="sort_order">Display order</Label>
                    <Input id="sort_order" name="sort_order" type="number" min="0" :default-value="skill?.sort_order ?? 0" />
                    <InputError :message="errors.sort_order" />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <Button type="submit" :disabled="processing">{{ skill ? 'Save changes' : 'Create skill' }}</Button>
                <Button as-child variant="ghost">
                    <Link :href="index()">Cancel</Link>
                </Button>
            </div>
        </Form>
    </div>
</template>
