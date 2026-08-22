<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import Confirm from '@/Components/Confirm.vue';
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';

import { Button } from '@/Components/ui/button';
import useSearchAndSort from '@/Composables/useSearchAndSort';

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

import { ArrowDownUp, Ellipsis } from 'lucide-vue-next';
import { dateFormat } from '@/lib/date-eu';

const {
    search,
    sort_by,
    sort_type,
    per_page,
    updateSearch,
    updateSort,
    updatePerPage,
} = useSearchAndSort(route('super_admin.categories.index'));

const props = defineProps({
    categories: {
        type: Object,
        required: true,
    },
});

const page = usePage();
const can = computed(() => page.props.auth.can);

const confirmDialog = ref(null);
const selectedCategoryId = ref(null);

const deleteCategory = (category) => {
    selectedCategoryId.value = category;
    confirmDialog.value.open();
};

const handleConfirm = () => {
    router.delete(
        route('super_admin.categories.destroy', selectedCategoryId.value),
    );
};
</script>

<template>
    <Head title="Category List" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage categories']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                        >
                            Category List
                        </h2>

                        <Link
                            v-if="can['manage categories'] ?? false"
                            :href="route('super_admin.categories.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <Confirm
                        @confirm="handleConfirm"
                        ref="confirmDialog"
                        :title="$page.props.lang['Delete User']"
                        :description="
                            $page.props.lang[
                                'Are you sure you want to delete this User?'
                            ]
                        "
                        :confirm-text="$page.props.lang['Confirm']"
                        :cancel-text="$page.props.lang['Cancel']"
                    />

                    <!-- Search -->
                    <SearchInput
                        v-model="search"
                        :per-page="per_page"
                        @update:modelValue="updateSearch"
                        @updatePerPage="updatePerPage"
                    />

                    <!-- Table -->
                    <div class="mt-4 rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Image'] ?? 'Image'
                                        }}
                                    </TableHead>
                                    <TableHead>
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <span>{{
                                                $page.props.lang['Name'] ??
                                                'Name'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('name')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'asc' &&
                                                    sort_by === 'name'
                                                        ? 'text-slate-900'
                                                        : ''
                                                "
                                            />
                                        </div>
                                    </TableHead>
                                    <TableHead>
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <span>{{
                                                $page.props.lang['Slug'] ?? 'Slug'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('slug')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'asc' &&
                                                    sort_by === 'slug'
                                                        ? 'text-slate-900'
                                                        : ''
                                                "
                                            />
                                        </div>
                                    </TableHead>
                                   <TableHead>
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <span>{{
                                                $page.props.lang['Status'] ?? 'Status'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('status')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'desc' &&
                                                    sort_by === 'status'
                                                        ? 'text-slate-900'
                                                        : ''
                                                "
                                            />
                                        </div>
                                    </TableHead>
                                   <TableHead>
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <span>{{
                                                $page.props.lang['Created At'] ?? 'Created At'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('created_at')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'desc' &&
                                                    sort_by === 'created_at'
                                                        ? 'text-slate-900'
                                                        : ''
                                                "
                                            />
                                        </div>
                                    </TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>

                            <TableBody>
                                <!-- Row 1 -->
                                <TableRow
                                    v-for="category in categories.data"
                                    :key="category.id"
                                >
                                    <TableCell>
                                        <img
                                            :src="`/storage/${category.image}`"
                                            alt="Category"
                                            class="h-12 w-12 rounded-md border object-cover"
                                        />
                                    </TableCell>

                                    <TableCell>
                                        <div class="font-medium">
                                            {{ category.name }}
                                        </div>
                                    </TableCell>

                                    <TableCell>
                                        {{ category.slug }}
                                    </TableCell>

                                    <TableCell>
                                        <span
                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium"
                                            :class="
                                                category.status
                                                    ? 'text-green-500'
                                                    : 'text-red-600'
                                            "
                                        >
                                            {{
                                                category.status
                                                    ? 'Active'
                                                    : 'Inactive'
                                            }}
                                        </span>
                                    </TableCell>

                                    <TableCell>
                                        {{ dateFormat(category.created_at) }}
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
                                                                    'super_admin.categories.edit',
                                                                    category,
                                                                )
                                                            "
                                                        >
                                                            Edit
                                                        </Link>
                                                    </DropdownMenuItem>

                                                    <DropdownMenuItem
                                                        @click="
                                                            deleteCategory(
                                                                category,
                                                            )
                                                        "
                                                    >
                                                        Delete
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="categories.data.length === 0">
                                    <TableCell
                                        colspan="11"
                                        class="py-8 text-center"
                                    >
                                        No categories found.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-end py-4">
                        <Pagination :links="[]" />
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
