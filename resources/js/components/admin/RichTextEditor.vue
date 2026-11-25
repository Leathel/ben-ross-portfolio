<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref, watch } from 'vue';
import InputError from '@/components/InputError.vue';
import { Label } from '@/components/ui/label';
import { store as uploadImage } from '@/routes/admin/images';

const props = defineProps<{
    modelValue: string;
    label: string;
    placeholder?: string;
    error?: string;
    imageField?: string;
    hint?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string];
}>();

const editor = ref<HTMLElement | null>(null);
const uploadField = props.imageField ?? 'case_study_image';
const defaultHint = 'Use headings and short paragraphs to structure the content however you like. Paste an image to add it inline.';
const imageForm = useForm({
    field: uploadField,
    image: null as File | null,
});

let savedRange: Range | null = null;

onMounted(() => {
    if (editor.value) {
        editor.value.innerHTML = props.modelValue;
    }
});

watch(
    () => props.modelValue,
    (value) => {
        if (editor.value && document.activeElement !== editor.value && editor.value.innerHTML !== value) {
            editor.value.innerHTML = value;
        }
    },
);

function sync(): void {
    emit('update:modelValue', editor.value?.innerHTML ?? '');
}

function format(command: string, value?: string): void {
    editor.value?.focus();
    document.execCommand(command, false, value);
    sync();
}

function addLink(): void {
    const url = window.prompt('Link URL');

    if (url) {
        format('createLink', url);
    }
}

function imageFromClipboard(event: ClipboardEvent): File | null {
    const items = event.clipboardData?.items;

    if (! items) {
        return null;
    }

    for (const item of Array.from(items)) {
        if (item.kind === 'file' && item.type.startsWith('image/')) {
            const file = item.getAsFile();

            if (file) {
                return file;
            }
        }
    }

    return null;
}

function escapeAttribute(value: string): string {
    return value
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

function insertAtSavedRange(html: string): void {
    const selection = window.getSelection();

    editor.value?.focus();

    if (! selection) {
        return;
    }

    if (savedRange && editor.value?.contains(savedRange.commonAncestorContainer)) {
        selection.removeAllRanges();
        selection.addRange(savedRange);
    } else if (editor.value) {
        const range = document.createRange();
        range.selectNodeContents(editor.value);
        range.collapse(false);
        selection.removeAllRanges();
        selection.addRange(range);
    }

    document.execCommand('insertHTML', false, html);
    sync();
}

function handlePaste(event: ClipboardEvent): void {
    const image = imageFromClipboard(event);

    if (! image) {
        return;
    }

    event.preventDefault();

    const selection = window.getSelection();
    savedRange = selection && selection.rangeCount > 0 ? selection.getRangeAt(0).cloneRange() : null;

    imageForm.image = image;
    imageForm.post(uploadImage.url(), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: (page) => {
            if (
                page.props.uploaded_field === uploadField &&
                typeof page.props.uploaded_url === 'string'
            ) {
                insertAtSavedRange(`<img src="${escapeAttribute(page.props.uploaded_url)}" alt="">`);
            }

            imageForm.image = null;
            savedRange = null;
        },
        onError: () => {
            imageForm.image = null;
            savedRange = null;
        },
    });
}

const toolbar = [
    { label: 'B', title: 'Bold', command: 'bold' },
    { label: 'I', title: 'Italic', command: 'italic' },
    { label: 'H2', title: 'Heading', command: 'formatBlock', value: 'h2' },
    { label: 'H3', title: 'Subheading', command: 'formatBlock', value: 'h3' },
    { label: '• List', title: 'Bulleted list', command: 'insertUnorderedList' },
    { label: '1. List', title: 'Numbered list', command: 'insertOrderedList' },
    { label: 'Quote', title: 'Quote', command: 'formatBlock', value: 'blockquote' },
    { label: 'Code', title: 'Code block', command: 'formatBlock', value: 'pre' },
];
</script>

<template>
    <div class="grid gap-2">
        <Label>{{ label }}</Label>

        <div class="overflow-hidden rounded-md border border-input bg-transparent focus-within:border-ring focus-within:ring-2 focus-within:ring-ring/30">
            <div class="flex flex-wrap items-center gap-1 border-b border-input bg-muted/40 px-2 py-1.5">
                <button
                    v-for="item in toolbar"
                    :key="item.label"
                    type="button"
                    :title="item.title"
                    class="rounded px-2 py-1 text-xs font-medium text-muted-foreground transition-colors hover:bg-background hover:text-foreground"
                    @mousedown.prevent="format(item.command, item.value)"
                >
                    {{ item.label }}
                </button>
                <button
                    type="button"
                    title="Link"
                    class="rounded px-2 py-1 text-xs font-medium text-muted-foreground transition-colors hover:bg-background hover:text-foreground"
                    @mousedown.prevent="addLink"
                >
                    Link
                </button>
                <button
                    type="button"
                    title="Paragraph"
                    class="rounded px-2 py-1 text-xs font-medium text-muted-foreground transition-colors hover:bg-background hover:text-foreground"
                    @mousedown.prevent="format('formatBlock', 'p')"
                >
                    ¶
                </button>
            </div>

            <div
                ref="editor"
                class="rich-text min-h-56 px-3 py-3 text-sm outline-none"
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                :data-placeholder="placeholder || 'Write the project story here…'"
                @input="sync"
                @blur="sync"
                @paste="handlePaste"
            ></div>
        </div>

        <p class="text-xs text-muted-foreground">
            {{ imageForm.processing ? 'Uploading image…' : (hint ?? defaultHint) }}
        </p>
        <InputError :message="error || imageForm.errors.image || imageForm.errors.field" />
    </div>
</template>

<style scoped>
div[contenteditable='true']:empty::before {
    content: attr(data-placeholder);
    color: var(--color-muted-foreground);
    pointer-events: none;
}
</style>
