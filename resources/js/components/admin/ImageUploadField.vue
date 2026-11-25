<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { store as uploadImage } from '@/routes/admin/images';

const props = defineProps<{
    modelValue: string;
    field: string;
    label: string;
    hint?: string;
    error?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const fileInput = ref<HTMLInputElement | null>(null);
const form = useForm({
    field: props.field,
    image: null as File | null,
});

function selectImage(event: Event): void {
    const input = event.target as HTMLInputElement;
    form.image = input.files?.[0] ?? null;
}

function upload(): void {
    if (! form.image) {
        return;
    }

    form.post(uploadImage.url(), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: (page) => {
            if (
                page.props.uploaded_field === props.field &&
                typeof page.props.uploaded_url === 'string'
            ) {
                emit('update:modelValue', page.props.uploaded_url);
                form.image = null;

                if (fileInput.value) {
                    fileInput.value.value = '';
                }
            }
        },
    });
}

function removeImage(): void {
    emit('update:modelValue', '');
    form.image = null;

    if (fileInput.value) {
        fileInput.value.value = '';
    }
}
</script>

<template>
    <div class="grid gap-2">
        <Label :for="field">{{ label }}</Label>

        <div v-if="modelValue" class="flex items-center gap-3 rounded-md border border-input p-2">
            <img
                :src="modelValue"
                :alt="`${label} preview`"
                class="size-12 rounded-sm border border-zinc-200 bg-zinc-100 object-cover dark:border-zinc-700"
            />
            <span class="min-w-0 flex-1 truncate text-xs text-muted-foreground">{{ modelValue }}</span>
            <Button type="button" variant="ghost" size="sm" @click="removeImage">Remove</Button>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <input
                :id="field"
                ref="fileInput"
                type="file"
                accept="image/*"
                class="block min-w-0 flex-1 text-xs text-muted-foreground file:mr-3 file:cursor-pointer file:rounded-md file:border file:border-input file:bg-background file:px-3 file:py-1.5 file:text-xs file:font-medium file:text-foreground hover:file:bg-accent"
                @change="selectImage"
            />
            <Button
                type="button"
                variant="outline"
                size="sm"
                :disabled="! form.image || form.processing"
                @click="upload"
            >
                {{ form.processing ? 'Uploading…' : 'Upload' }}
            </Button>
        </div>

        <progress
            v-if="form.progress"
            :value="form.progress.percentage"
            max="100"
            class="h-1 w-full"
        ></progress>

        <p v-if="hint" class="text-xs text-muted-foreground">{{ hint }}</p>
        <InputError :message="error || form.errors.image || form.errors.field" />
    </div>
</template>
