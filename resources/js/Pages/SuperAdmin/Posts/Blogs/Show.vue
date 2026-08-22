<script setup>
import { Button } from '@/Components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatDate } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    blog: {
        type: Object,
        required: true,
    },
});

import { formatDateTime } from '@/lib/utils';
</script>

<template>
    <Head :title="$page.props.lang['Blog Details'] ?? 'Blog Details'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-6 shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div
                        class="flex items-center justify-between border-b pb-5"
                    >
                        <h2
                            class="text-2xl font-extrabold text-gray-900 dark:text-white"
                        >
                            {{
                                $page.props.lang['Blog Details'] ??
                                'Blog Details'
                            }}
                        </h2>

                        <Link :href="route('super_admin.blogs.index')">
                            <Button variant="outline">
                                {{ $page.props.lang['Back'] ?? 'Back' }}
                            </Button>
                        </Link>
                    </div>

                    <div class="mt-8 space-y-8">
                        <!-- Featured Image -->
                        <div v-if="blog.featured_image">
                            <h3 class="text-sm font-medium text-gray-500">
                                Featured Image
                            </h3>

                            <img
                                :src="`/storage/${blog.featured_image}`"
                                class="mt-2 h-56 w-full rounded-lg object-cover"
                            />
                        </div>

                        <!-- Basic Information -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Title
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ blog.title }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Slug
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ blog.slug }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Category
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ blog.category?.name }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Author
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ blog.author?.first_name }}
                                    {{ blog.author?.last_name }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Status
                                </h3>

                                <span
                                    class="mt-1 inline-flex rounded-full px-3 py-1 text-sm font-medium"
                                    :class="
                                        blog.status === 'published'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700'
                                    "
                                >
                                    {{ blog.status }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Featured
                                </h3>

                                <span
                                    class="mt-1 inline-flex rounded-full px-3 py-1 text-sm font-medium"
                                    :class="
                                        blog.is_featured
                                            ? 'bg-blue-100 text-blue-700'
                                            : 'bg-gray-100 text-gray-700'
                                    "
                                >
                                    {{ blog.is_featured ? 'Yes' : 'No' }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Published At
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ formatDateTime(blog.published_at) }}
                                </p>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500">
                                    Created At
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-white">
                                    {{ formatDate(blog.created_at) }}
                                </p>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">
                                Excerpt
                            </h3>

                            <p
                                class="mt-2 whitespace-pre-line text-gray-900 dark:text-white"
                            >
                                {{ blog.excerpt || '—' }}
                            </p>
                        </div>

                        <!-- Content -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">
                                Content
                            </h3>

                            <div
                                class="prose prose-sm dark:prose-invert mt-2 max-w-none whitespace-pre-line"
                            >
                                {{ blog.content }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
