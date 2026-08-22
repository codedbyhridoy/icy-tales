<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { Button } from '@/Components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuGroup,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { formatDate } from '@/lib/utils';
import { Ellipsis, Star } from 'lucide-vue-next';

const props = defineProps({
    reviews: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head :title="$page.props.lang['Reviews List'] ?? 'Reviews List'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage reviews']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-5">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{
                                $page.props.lang['Reviews List'] ??
                                'Reviews List'
                            }}
                        </h2>

                        <Link
                            v-if="can['manage reviews'] ?? false"
                            :href="route('super_admin.team-members.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Rating'] ??
                                            'Rating'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Product'] ??
                                            'Product'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Customer'] ??
                                            'Customer'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Review'] ??
                                            'Review'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Status'] ??
                                            'Status'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Submitted'] ??
                                            'Submitted'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Actions'] ??
                                            'Actions'
                                        }}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="review in reviews.data"
                                    :key="review.id"
                                >
                                    <!-- Rating -->
                                    <TableCell>
                                        <div class="flex items-center gap-1">
                                            <Star
                                                v-for="star in 5"
                                                :key="star"
                                                :class="[
                                                    'h-4 w-4',
                                                    star <= review.rating
                                                        ? 'fill-amber-warm text-amber-warm'
                                                        : 'text-gray-300',
                                                ]"
                                            />

                                            <span
                                                class="ml-2 text-sm text-gray-500"
                                            >
                                                ({{ review.rating }})
                                            </span>
                                        </div>
                                    </TableCell>

                                    <TableCell>
                                        {{ review.product?.name }}
                                    </TableCell>

                                    <!-- Customer -->
                                    <TableCell>
                                        {{ review.user?.first_name }}
                                        {{ review.user?.last_name }}
                                    </TableCell>

                                    <!-- Review -->
                                    <TableCell class="max-w-xs truncate">
                                        {{ review.comment }}
                                    </TableCell>

                                    <!-- Status -->
                                    <TableCell>
                                        <span
                                            v-if="review.status === 'pending'"
                                            class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-medium text-yellow-800"
                                        >
                                            {{
                                                $page.props.lang['Pending'] ??
                                                'Pending'
                                            }}
                                        </span>

                                        <span
                                            v-else-if="
                                                review.status === 'approved'
                                            "
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800"
                                        >
                                            {{
                                                $page.props.lang['Approved'] ??
                                                'Approved'
                                            }}
                                        </span>

                                        <span
                                            v-else
                                            class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-800"
                                        >
                                            {{
                                                $page.props.lang['Rejected'] ??
                                                'Rejected'
                                            }}
                                        </span>
                                    </TableCell>

                                    <!-- Submitted -->
                                    <TableCell>
                                        {{ formatDate(review.created_at) }}
                                    </TableCell>
                                    <!-- Actions -->
                                    <TableCell>
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button
                                                    variant="ghost"
                                                    class="h-8 w-8 p-0"
                                                >
                                                    <Ellipsis class="h-4 w-4" />
                                                </Button>
                                            </DropdownMenuTrigger>

                                            <DropdownMenuContent class="w-48">
                                                <DropdownMenuLabel>
                                                    {{
                                                        $page.props.lang[
                                                            'Actions'
                                                        ] ?? 'Actions'
                                                    }}
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem as-child>
                                                        <Link
                                                            :href="route('super_admin.reviews.show', review)"
                                                        >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Show'
                                                                ] ?? 'Show'
                                                            }}
                                                        </Link>
                                                    </DropdownMenuItem>

                                                    <DropdownMenuSeparator />

                                                    <DropdownMenuItem
                                                        class="text-red-600"
                                                    >
                                                        {{
                                                            $page.props.lang[
                                                                'Delete'
                                                            ] ?? 'Delete'
                                                        }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <<TableRow v-if="reviews.data.length === 0">
                                    <TableCell
                                        colspan="6"
                                        class="py-10 text-center text-gray-500"
                                    >
                                        {{
                                            $page.props.lang[
                                                'No reviews found.'
                                            ]
                                        }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <!-- Pagination will go here -->
                    </div>
                </div>
                <div v-else class="rounded-lg bg-white p-8 text-center shadow">
                    {{
                        $page.props.lang["You don't have permission"] ??
                        "You don't have permission"
                    }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
