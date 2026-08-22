<script setup>
import SelectWithSearch from '@/Components/Frontend/SelectWithSearch.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { Textarea } from '@/Components/ui/textarea';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ArrowLeft } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

const editor = ClassicEditor;

const props = defineProps({
    categories: {
        type: Array,
        required: true,
    },
    tags: {
        type: Array,
        required: true,
    },
});

const form = useForm('post', route('super_admin.blogs.store'), {
    category_id: null,
    title: '',
    slug: '',
    excerpt: '',
    content: '',
    featured_image: null,
    is_featured: false,
    status: 'draft',
    tags: [],
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onError: (e) => {
            console.log(e);
        },
    });
};

const slugEdited = ref(false);

const slugify = (text) =>
    (text || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');

watch(
    () => form.title,
    (n) => {
        if (!slugEdited.value) form.slug = slugify(n);
    },
    { immediate: true },
);

const onSlugInput = () => {
    slugEdited.value = true;
};

const published = computed({
    get: () => form.status === 'published',
    set: (value) => (form.status = value ? 'published' : 'draft'),
});

const tagOptions = computed(() =>
    props.tags.map((tag) => ({
        value: tag.id,
        label: tag.name,
    })),
);

const handleImage = (e) => {
    form.featured_image = e.target.files[0];
};
</script>

<template>
    <Head title="Create Blog" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                        >
                            {{
                                $page.props.lang['Create Blog'] ?? 'Create Blog'
                            }}
                        </h2>

                        <Button variant="outline" as-child>
                            <Link :href="route('super_admin.blogs.index')">
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                {{ $page.props.lang['Back'] ?? 'Back' }}
                            </Link>
                        </Button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div>
                            <Label>Title</Label>
                            <Input
                                type="text"
                                v-model="form.title"
                                class="mt-2"
                            />
                            <p
                                v-if="form.errors.title"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div>
                            <Label>Slug</Label>
                            <Input
                                type="text"
                                v-model="form.slug"
                                autocomplete="slug"
                                @input="onSlugInput"
                                class="mt-2"
                            />
                            <p
                                v-if="form.errors.slug"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <div>
                            <Label>
                                {{ $page.props.lang['Category'] ?? 'Category' }}
                            </Label>

                            <Select v-model="form.category_id">
                                <SelectTrigger class="mt-2">
                                    <SelectValue
                                        placeholder="Select category"
                                    />
                                </SelectTrigger>

                                <SelectContent>
                                    <SelectItem
                                        v-for="category in categories"
                                        :key="category.id"
                                        :value="String(category.id)"
                                    >
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Tags -->
                        <div class="space-y-2">
                            <Label>Tags</Label>

                            <SelectWithSearch
                                v-model="form.tags"
                                :options="tagOptions"
                                label="Select Tags"
                            />

                            <p
                                v-if="form.errors.tags"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.tags }}
                            </p>
                        </div>

                        <div>
                            <Label>Excerpt</Label>
                            <Textarea
                                v-model="form.excerpt"
                                rows="4"
                                class="mt-2 w-full rounded-md border px-3 py-2"
                            />
                            <p
                                v-if="form.errors.excerpt"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.excerpt }}
                            </p>
                        </div>

                        <div>
                            <Label>Content</Label>
                            <ckeditor :editor="editor" v-model="form.content" class="h-[120px]" />
                            <p
                                v-if="form.errors.content"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.content }}
                            </p>
                        </div>

                        <div>
                            <Label>Featured Image</Label>

                            <Input
                                type="file"
                                class="mt-2"
                                @change="handleImage"
                            />

                            <p
                                v-if="form.errors.featured_image"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.featured_image }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="space-y-4">
                            <!-- Featured Product -->
                            <div
                                class="flex items-center justify-between rounded-lg border p-4"
                            >
                                <div>
                                    <Label class="font-medium">
                                        {{
                                            $page.props.lang['Featured Blog'] ??
                                            'Featured Blog'
                                        }}
                                    </Label>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{
                                            $page.props.lang[
                                                'Display this blog on the featured sections.'
                                            ] ??
                                            'Display this blog on the featured sections.'
                                        }}
                                    </p>
                                </div>

                                <Switch v-model="form.is_featured" />
                            </div>

                            <!-- Active Product -->
                            <div
                                class="flex items-center justify-between rounded-lg border p-4"
                            >
                                <div>
                                    <Label class="font-medium">
                                        {{
                                            $page.props.lang['Published'] ??
                                            'Published'
                                        }}
                                    </Label>

                                    <p class="mt-1 text-sm text-gray-500">
                                        {{
                                            $page.props.lang[
                                                'Make this blog visible on the website.'
                                            ] ??
                                            'Make this blog visible on the website.'
                                        }}
                                    </p>
                                </div>

                                <Switch v-model="published" />
                            </div>
                        </div>
                        <!-- Submit -->
                        <div class="flex justify-end pt-4">
                            <Button type="submit" :disabled="form.processing">
                                Create Blog
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
:deep(.ck-editor__editable) {
    min-height: 180px;
}
</style>