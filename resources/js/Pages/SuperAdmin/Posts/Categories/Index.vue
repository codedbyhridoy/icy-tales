<script setup>
import { ref, computed } from 'vue';
import { Button } from '@/Components/ui/button';
import Confirm from '@/Components/Confirm.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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

defineProps({
    categories: {
        type: Object,
        required: true,
    },
});

const confirmDialog = ref(null);
const selectedCategoryId = ref(null);

const deleteCategory = (category) => {
    selectedCategoryId.value = category;
    confirmDialog.value.open();
}

const handleConfirm = () => {
    router.delete(
        route('super_admin.blog-categories.destroy', selectedCategoryId.value),
        {
            preserveScroll: true,
        }
    )
}

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head title="Blog Category List" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage blog-categories']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                        >
                            {{ 
                                $page.props.lang['Blog Category List'] ??
                                 'Blog Category List' 
                            }}
                        </h2>

                        <Link
                            v-if="can['manage blog-categories'] ?? false"
                            :href="route('super_admin.blog-categories.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <Confirm
                        ref="confirmDialog"
                        @confirm="handleConfirm"
                        :title="$page.props.lang['Delete Blog Category?']"
                        :description="
                            $page.props.lang[
                                'Are you sure you want to delete this Blog?'
                            ]
                        "
                        :confirm-text="$page.props.lang['Confirm']"
                        :cancel-text="$page.props.lang['Cancel']"
                     />

                    <!-- Table -->
                    <div class="mt-4 rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{ $page.props.lang['Name'] ?? 'Name' }}
                                    </TableHead>
                                    <TableHead>
                                        {{ $page.props.lang['slug'] ?? 'Slug' }}
                                    </TableHead>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Description'] ??
                                            'Description'
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
                                <TableRow
                                    v-for="category in categories.data"
                                    :key="category.id"
                                >
                                    <TableCell>
                                        {{ category.name }}
                                    </TableCell>
                                    <TableCell>
                                        {{ category.slug }}
                                    </TableCell>

                                    <TableCell>
                                        <p class="line-clamp-2">
                                            {{ category.description }}
                                        </p>
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
                                        {{ formatDate(category.created_at) }}
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
                                                    {{ $page.props.lang['Actions'] ?? 'Actions' }}
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem>
                                                        <Link
                                                            :href="route('super_admin.blog-categories.edit', category)"
                                                        >
                                                            {{ $page.props.lang['Edit'] ?? 'Edit' }}
                                                        </Link>
                                                    </DropdownMenuItem>

                                                    <DropdownMenuItem
                                                        @click="deleteCategory(category)"
                                                    >
                                                        {{ $page.props.lang['Delete'] ?? 'Delete' }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="categories.length === 0">
                                    <div></div>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <!-- Pagination -->
                    <div class="flex items-center justify-end py-4">
                        <Pagination :links="[categories.links]" />
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
