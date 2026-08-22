<script setup>
import { Button } from '@/Components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { Star } from 'lucide-vue-next';

const props = defineProps({
    review: {
        type: Object,
        required: true,
    },
});

const approve = () => {
    router.patch(route('super_admin.reviews.approve', props.review.id));
};

const reject = () => {
    router.patch(route('super_admin.reviews.reject', props.review.id));
};
</script>

<template>
    <Head :title="$page.props.lang['Review Details'] ?? 'Review Details'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <section>
                        <div class="flex items-center justify-between pb-5">
                            <h2
                                class="text-2xl font-extrabold text-gray-900 dark:text-white"
                            >
                                {{
                                    $page.props.lang['Review Details'] ??
                                    'Review Details'
                                }}
                            </h2>

                            <Link :href="route('super_admin.reviews.index')">
                                <Button variant="outline">
                                    {{ $page.props.lang['Back'] ?? 'Back' }}
                                </Button>
                            </Link>
                        </div>

                        <div class="mt-6 space-y-6">
                            <!-- Product -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Product'] ?? 'Product' }}
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ review.product.name }}
                                </p>
                            </div>

                            <!-- Customer -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Customer'] ?? 'Customer' }}
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ review.user.name }}
                                </p>
                            </div>

                            <!-- Rating -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Rating'] ?? 'Rating' }}
                                </h3>

                                <div class="mt-1 flex items-center gap-1">
                                    <Star
                                        v-for="star in 5"
                                        :key="star"
                                        :class="[
                                            'h-5 w-5',
                                            star <= review.rating
                                                ? 'fill-yellow-400 text-yellow-400'
                                                : 'text-gray-300',
                                        ]"
                                    />

                                    <span class="ml-2 text-gray-700">
                                        ({{ review.rating }}/5)
                                    </span>
                                </div>
                            </div>

                            <!-- Title -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Title'] ?? 'Title' }}
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ review.title }}
                                </p>
                            </div>

                            <!-- Comment -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Comment'] ?? 'Comment' }}
                                </h3>

                                <div
                                    class="mt-1 whitespace-pre-line text-gray-900 dark:text-gray-100"
                                >
                                    {{ review.comment }}
                                </div>
                            </div>

                            <!-- Status -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{ $page.props.lang['Status'] ?? 'Status' }}
                                </h3>

                                <p class="mt-1">
                                    <span
                                        v-if="review.status === 'pending'"
                                        class="rounded bg-yellow-100 px-3 py-1 text-sm text-yellow-700"
                                    >
                                        {{
                                            $page.props.lang['Pending'] ??
                                            'Pending'
                                        }}
                                    </span>

                                    <span
                                        v-else-if="review.status === 'approved'"
                                        class="rounded bg-green-100 px-3 py-1 text-sm text-green-700"
                                    >
                                        {{
                                            $page.props.lang['Approved'] ??
                                            'Approved'
                                        }}
                                    </span>

                                    <span
                                        v-else
                                        class="rounded bg-red-100 px-3 py-1 text-sm text-red-700"
                                    >
                                        {{
                                            $page.props.lang['Rejected'] ??
                                            'Rejected'
                                        }}
                                    </span>
                                </p>
                            </div>

                            <!-- Submitted -->
                            <div>
                                <h3
                                    class="text-sm font-medium text-gray-500 dark:text-gray-400"
                                >
                                    {{
                                        $page.props.lang['Submitted At'] ??
                                        'Submitted At'
                                    }}
                                </h3>

                                <p class="mt-1 text-gray-900 dark:text-gray-100">
                                    {{ review.created_at }}
                                </p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div
                            class="mt-8 flex items-center gap-3 border-t pt-6"
                        >
                            <Button
                                v-if="review.status !== 'approved'"
                                @click="approve"
                            >
                                {{
                                    $page.props.lang['Approve'] ?? 'Approve'
                                }}
                            </Button>

                            <Button
                                v-if="review.status !== 'rejected'"
                                variant="destructive"
                                @click="reject"
                            >
                                {{
                                    $page.props.lang['Reject'] ?? 'Reject'
                                }}
                            </Button>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>