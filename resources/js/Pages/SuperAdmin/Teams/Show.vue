<script setup>
import { Button } from '@/Components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    member: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <Head title="Member Details" />

    <div class="py-8">
        <div class="mx-auto max-w-5xl space-y-6 sm:px-6 lg:px-8">
            <div class="rounded-lg bg-white p-8 shadow">
                <!-- Header -->

                <div class="flex items-center justify-between border-b pb-6">
                    <div>
                        <h2 class="text-2xl font-bold">Team Member Details</h2>

                        <p class="mt-1 text-sm text-gray-500">
                            View complete member information.
                        </p>
                    </div>

                    <Button as-child>
                        <Link
                            :href="
                                route('super_admin.team-members.edit', member)
                            "
                        >
                            Edit Member
                        </Link>
                    </Button>
                </div>

                <!-- Profile -->

                <div class="mt-8 flex flex-col gap-8 lg:flex-row">
                    <!-- Left -->

                    <div class="w-full max-w-xs">
                        <img
                            v-if="member.image"
                            :src="`/storage/${member.image}`"
                            class="aspect-square w-full rounded-xl border object-cover"
                        />

                        <div
                            v-else
                            class="flex aspect-square items-center justify-center rounded-xl border bg-gray-100"
                        >
                            No Image
                        </div>
                    </div>

                    <!-- Right -->

                    <div class="flex-1 space-y-8">
                        <!-- Basic Information -->

                        <div>
                            <h3 class="mb-4 text-lg font-semibold">
                                Basic Information
                            </h3>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div>
                                    <p class="text-sm text-gray-500">
                                        First Name
                                    </p>

                                    <p class="font-medium">
                                        {{ member.first_name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">
                                        Last Name
                                    </p>

                                    <p class="font-medium">
                                        {{ member.last_name }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">
                                        Designation
                                    </p>

                                    <p class="font-medium">
                                        {{ member.designation }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">Status</p>

                                    <span
                                        v-if="member.is_active"
                                        class="rounded-full bg-green-100 px-3 py-1 text-sm text-green-700"
                                    >
                                        Active
                                    </span>

                                    <span
                                        v-else
                                        class="rounded-full bg-red-100 px-3 py-1 text-sm text-red-700"
                                    >
                                        Inactive
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Bio -->

                        <div>
                            <h3 class="mb-4 text-lg font-semibold">
                                Biography
                            </h3>

                            <p class="leading-7 text-gray-600">
                                {{ member.bio || 'No biography available.' }}
                            </p>
                        </div>

                        <!-- Social Links -->

                        <div>
                            <h3 class="mb-4 text-lg font-semibold">
                                Social Media
                            </h3>

                            <div class="space-y-3">
                                <div v-if="member.facebook">
                                    <strong>Facebook:</strong>

                                    <a
                                        :href="member.facebook"
                                        target="_blank"
                                        class="ml-2 text-blue-600 hover:underline"
                                    >
                                        {{ member.facebook }}
                                    </a>
                                </div>

                                <div v-if="member.instagram">
                                    <strong>Instagram:</strong>

                                    <a
                                        :href="member.instagram"
                                        target="_blank"
                                        class="ml-2 text-pink-600 hover:underline"
                                    >
                                        {{ member.instagram }}
                                    </a>
                                </div>

                                <div v-if="member.youtube">
                                    <strong>YouTube:</strong>

                                    <a
                                        :href="member.youtube"
                                        target="_blank"
                                        class="ml-2 text-red-600 hover:underline"
                                    >
                                        {{ member.youtube }}
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
