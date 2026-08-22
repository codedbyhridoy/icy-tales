<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

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
import useSearchAndSort from '@/Composables/useSearchAndSort';
import { ArrowDownUp, Ellipsis } from 'lucide-vue-next';

const {
    search,
    sort_by,
    sort_type,
    per_page,
    updateSearch,
    updateSort,
    updatePerPage,
} = useSearchAndSort(route('super_admin.roles.index'));

const props = defineProps({
    roles: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: false,
    },
});

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head title="{{ $page.props.lang['Role List']?? 'Role List' }}" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage role-permission']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-[20px]">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{ $page.props.lang['Role List'] ?? 'Role List' }}
                        </h2>
                    </div>
                    <SearchInput
                        v-model="search"
                        :per-page="per_page"
                        @update:modelValue="updateSearch"
                        @updatePerPage="updatePerPage"
                    />
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
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
                                                    sort_type === 'desc' &&
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
                                                $page.props.lang['Guard'] ??
                                                'Guard'
                                            }}</span>
                                        </div>
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
                                    v-for="role in roles.data"
                                    :key="role.id"
                                >
                                    <TableCell>
                                        {{ role.name }}
                                    </TableCell>
                                    <TableCell>
                                        {{ role.guard_name }}
                                    </TableCell>
                                    <TableCell>
                                        <DropdownMenu>
                                            <DropdownMenuTrigger as-child>
                                                <Button
                                                    variant="ghost"
                                                    class="data-[state=open]:bg-muted flex h-8 w-8 p-0"
                                                >
                                                    <Ellipsis class="h-4 w-4" />
                                                    <span class="sr-only">{{
                                                        $page.props.lang[
                                                            'Open menu'
                                                        ] ?? 'Open menu'
                                                    }}</span>
                                                </Button>
                                            </DropdownMenuTrigger>
                                            <DropdownMenuContent class="w-56">
                                                <DropdownMenuLabel>{{
                                                    $page.props.lang[
                                                        'Actions'
                                                    ] ?? 'Actions'
                                                }}</DropdownMenuLabel>
                                                <DropdownMenuSeparator />
                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem
                                                        v-if="
                                                            can[
                                                                'manage role-permission'
                                                            ]
                                                        "
                                                    >
                                                        <Link
                                                            :href="`/roles/assign-permission/${role.id}`"
                                                            class="w-full"
                                                        >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Assign Permission'
                                                                ] ??
                                                                'Assign Permission'
                                                            }}
                                                        </Link>
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="roles.data.length === 0">
                                    <TableCell
                                        colspan="4"
                                        class="h-24 text-center"
                                    >
                                        {{
                                            $page.props.lang['No results.'] ??
                                            'No results.'
                                        }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <div class="space-x-2">
                            <Pagination :links="props.roles.links" />
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="bg-white p-4 text-center shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
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
