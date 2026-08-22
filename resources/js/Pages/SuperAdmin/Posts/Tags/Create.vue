<script setup>
import { ref, watch } from 'vue';
import { ArrowLeft } from 'lucide-vue-next';
import { Head, Link } from '@inertiajs/vue3';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { Button } from '@/Components/ui/button';
import { useForm } from 'laravel-precognition-vue-inertia';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const form = useForm('post', route('super_admin.tags.store'), {
    name: '',
    slug: '',
    status: true,
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
    () => form.name,
    (n) => {
        if (!slugEdited.value) form.slug = slugify(n);
    },
    { immediate: true },
);

const onSlugInput = () => {
    slugEdited.value = true;
};
</script>

<template>
    <Head title="Create Tag" />

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
                                $page.props.lang['Create Tag'] ??
                                 'Create Tag' 
                            }}
                        </h2>

                        <Button variant="outline" as-child>
                            <Link
                                :href="
                                    route('super_admin.blog-categories.index')
                                "
                            >
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                {{ $page.props.lang['Back'] ?? 'Back' }}
                            </Link>
                        </Button>
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label>Name</Label>
                            <Input type="text" v-model="form.name" />
                            <p
                                v-if="form.errors.name"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <Label>Slug</Label>
                            <Input
                                type="text"
                                v-model="form.slug"
                                autocomplete="slug"
                                @input="onSlugInput"
                            />
                            <p
                                v-if="form.errors.slug"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="border-t pt-8">
                            <div
                                class="flex items-center justify-between rounded-xl border p-4"
                            >
                                <div>
                                    <h4 class="text-xl font-medium">
                                        Active Status
                                    </h4>

                                    <p class="text-sm text-gray-500">
                                        Show this tag on the website.
                                    </p>
                                </div>

                                <Switch v-model="form.status" />
                            </div>
                        </div>
                        <!-- Submit -->
                        <div class="flex justify-end pt-4">
                            <Button type="submit" :disabled="form.processing">
                                Create Tag
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
