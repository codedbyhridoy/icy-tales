<script setup>
import Confirm from '@/Components/Confirm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';

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

import { formatDate } from '@/lib/utils';
import { Ellipsis } from 'lucide-vue-next';

const props = defineProps({
    statistics: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const can = computed(() => page.props.auth.can);

const confirmDialog = ref(null);
const selectedStatId = ref(null);

const deleteStat = (stat) => {
    selectedStatId.value = stat;
    confirmDialog.value.open();
};

const handleConfirm = () => {
    router.delete(
        route('super_admin.statistics.destroy', selectedStatId.value),
    );
};
</script>

<template>
    <Head title="Statistic List" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage statistics']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                        >
                            Statistic List
                        </h2>

                        <Link
                            v-if="can['manage statistics'] ?? false"
                            :href="route('super_admin.statistics.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <Confirm
                        @confirm="handleConfirm"
                        ref="confirmDialog"
                        :title="$page.props.lang['Delete Statistic']"
                        :description="
                            $page.props.lang[
                                'Are you sure you want to delete this Statistic?'
                            ]
                        "
                        :confirm-text="$page.props.lang['Confirm']"
                        :cancel-text="$page.props.lang['Cancel']"
                    />

                    <!-- Search -->
                    <SearchInput />

                    <!-- Table -->
                    <div class="mt-4 rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Title'] ?? 'Title'
                                        }}
                                    </TableHead>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Value'] ?? 'Value'
                                        }}
                                    </TableHead>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Suffix'] ??
                                            'Suffix'
                                        }}
                                    </TableHead>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Sort Order'] ??
                                            'Sort Order'
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
                                            $page.props.lang['Created At'] ??
                                            'Created At'
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
                                <!-- Row 1 -->
                                <TableRow
                                    v-for="stat in statistics.data"
                                    :key="stat.id"
                                >
                                    <TableCell>
                                        {{ stat.title }}
                                    </TableCell>
                                    <TableCell>
                                        {{ stat.value }}
                                    </TableCell>
                                    <TableCell>
                                        {{ stat.suffix }}
                                    </TableCell>
                                    <TableCell>
                                        {{ stat.sort_order }}
                                    </TableCell>
                                    <TableCell>
                                        <span
                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium"
                                            :class="
                                                stat.status
                                                    ? 'text-green-500'
                                                    : 'text-red-600'
                                            "
                                        >
                                            {{
                                                stat.status
                                                    ? 'Active'
                                                    : 'Inactive'
                                            }}
                                        </span>
                                    </TableCell>
                                    <TableCell>
                                        {{ formatDate(stat.created_at) }}
                                    </TableCell>

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
                                                    Actions
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem>
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'super_admin.statistics.edit',
                                                                    stat.id,
                                                                )
                                                            "
                                                        >
                                                            Edit
                                                        </Link>
                                                    </DropdownMenuItem>

                                                    <DropdownMenuItem
                                                        @click="
                                                            deleteStat(stat)
                                                        "
                                                    >
                                                        Delete
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="statistics.data.length === 0">
                                    <TableCell
                                        colspan="6"
                                        class="py-10 text-center text-gray-500"
                                    >
                                        {{
                                            $page.props.lang[
                                                'No statistics found.'
                                            ]
                                        }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-end py-4">
                        <Pagination :links="[statistics.links]" />
                    </div>
                </div>
                <div
                    v-else class="rounded-lg bg-white p-8 text-center shadow"
                >
                    {{
                        $page.props.lang["You don't have permission"] ??
                        "You don't have permission"
                    }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
