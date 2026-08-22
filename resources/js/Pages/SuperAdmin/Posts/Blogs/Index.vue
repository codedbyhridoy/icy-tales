<script setup>
import Confirm from '@/Components/Confirm.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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
import { Ellipsis } from 'lucide-vue-next';

const props = defineProps({
    blogs: {
        type: Object,
        required: true,
    },
});

console.log(props.blogs);

const confirmDialog = ref(null);
const selectedBlogId = ref(null);

const deleteBlog = (blog) => {
    selectedBlogId.value = blog;
    confirmDialog.value.open();
}

const handleConfirm = () => {
    router.delete(
        route('super_admin.blogs.destroy', selectedBlogId.value),
        {
            preserveScroll: true
        }
    )
}


const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head :title="$page.props.lang['Blogs List'] ?? 'Blogs List'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage blogs']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-5">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{ $page.props.lang['Blogs List'] ?? 'Blogs List' }}
                        </h2>
                        <Link
                            v-if="can['manage products'] ?? false"
                            :href="route('super_admin.blogs.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <Confirm
                        ref="confirmDialog"
                        @confirm="handleConfirm"
                        :title="$page.props.lang['Delete Blog']"
                        :description="
                            $page.props.lang[
                                'Are you sure you want to delete this blog?'
                            ]
                        "
                        :confirm-text="$page.props.lang['Confirm']"
                        :cancel-text="$page.props.lang['Cancel']"
                    />

                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Image'] ?? 'Image'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Title'] ?? 'Title'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['category'] ??
                                            'Category'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Author'] ??
                                            'Author'
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
                                            $page.props.lang['Featured'] ??
                                            'Featured'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Published'] ??
                                            'Published'
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
                                            $page.props.lang['Action'] ??
                                            'Action'
                                        }}
                                    </TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow
                                    v-for="blog in blogs.data"
                                    :key="blog.id"
                                >
                                    <TableCell>
                                        <img
                                            :src="`/storage/${blog.featured_image}`"
                                            alt="Featured Blog Image"
                                            class="h-12 w-12 rounded-md border object-cover"
                                        />
                                    </TableCell>

                                    <TableCell>
                                        <div class="line-clamp-2 max-w-[300px]">
                                            {{ blog.title }}
                                        </div>
                                    </TableCell>

                                    <TableCell>
                                        {{ blog.category?.name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ blog.author?.first_name }}
                                        {{ blog.author?.last_name }}
                                    </TableCell>

                                    <TableCell>
                                        <span
                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium"
                                            :class="
                                                blog.status
                                                    ? 'text-green-500'
                                                    : 'text-red-600'
                                            "
                                        >
                                            {{
                                                blog.status
                                                    ? 'Published'
                                                    : 'Draft'
                                            }}
                                        </span>
                                    </TableCell>

                                    <TableCell>
                                        <span
                                            v-if="blog.is_featured"
                                            class="rounded-full bg-yellow-100 px-2 py-1 text-xs font-medium text-yellow-800"
                                        >
                                            ⭐ Yes
                                        </span>

                                        <span
                                            v-else
                                            class="rounded-full bg-gray-100 px-2 py-1 text-xs text-gray-600"
                                        >
                                            No
                                        </span>
                                    </TableCell>

                                    <TableCell>
                                        {{
                                            blog.published_at
                                                ? formatDate(blog.published_at)
                                                : '-'
                                        }}
                                    </TableCell>

                                    <TableCell>
                                        {{ formatDate(blog.created_at) }}
                                    </TableCell>

                                    <!-- Actions Column -->
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
                                                            :href="route('super_admin.blogs.show', blog)"
                                                        >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Show'
                                                                ] ?? 'Show'
                                                            }}
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem as-child>
                                                        <Link
                                                            :href="route('super_admin.blogs.edit', blog)"
                                                        >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Edit'
                                                                ] ?? 'Edit'
                                                            }}
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        @click="deleteBlog(blog)"
                                                    >
                                                        {{ 
                                                            $page.props.lang['Delete'] ?? 'Delete'
                                                        }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <!-- Pagination will go here -->
                    </div>
                </div>
                <!-- <div
                    v-else
                    class="bg-white p-4 text-center shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    {{
                        $page.props.lang["You don't have permission"] ??
                        "You don't have permission"
                    }}
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
