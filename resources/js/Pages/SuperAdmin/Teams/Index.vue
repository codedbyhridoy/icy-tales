<script setup>
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

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
import SearchInput from '@/Components/SearchInput.vue';
import Confirm from '@/Components/Confirm.vue';

import { formatDate } from '@/lib/utils';
import { Ellipsis, ArrowDownUp } from 'lucide-vue-next';
import useSearchAndSort from '@/Composables/useSearchAndSort';
import Pagination from '@/Components/Pagination.vue';

const {
    search,
    sort_by,
    sort_type,
    per_page,
    updateSearch,
    updateSort,
    updatePerPage,
} = useSearchAndSort(route('super_admin.team-members.index'));

const props = defineProps({
    members: {
        type: Object,
        required: true,
    },
});

const confirmDialog = ref(null);
const selectedMemberId = ref(null);

const deleteMember = (member) => {
    selectedMemberId.value = member;
    confirmDialog.value.open();
}

const handleConfirm = () => {
    router.delete(
        route('super_admin.team-members.destroy', selectedMemberId.value)
    ),
    {
        preserveScroll: true
    }
}

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head :title="$page.props.lang['Members List'] ?? 'Members List'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage members']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-5">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{
                                $page.props.lang['Members List'] ??
                                'Members List'
                            }}
                        </h2>

                        <Link
                            v-if="can['manage members'] ?? false"
                            :href="route('super_admin.team-members.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                     <Confirm
                        ref="confirmDialog"
                        @confirm="handleConfirm"
                        :title="$page.props.lang['Delete Member']"
                        :description="
                            $page.props.lang[
                                'Are you sure you want to delete this member?'
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
                                        <div
                                            class="inline-flex items-center justify-center gap-2"
                                        >
                                            <span>{{
                                                $page.props.lang['Name'] ?? 'Name'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('first_name')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'asc' &&
                                                    sort_by === 'first_name'
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
                                                $page.props.lang['Degisnation'] ?? 'Designation'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('designation')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'asc' &&
                                                    sort_by === 'designation'
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
                                                $page.props.lang['Status']
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('is_active')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'desc' &&
                                                    sort_by === 'first_name'
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
                                                $page.props.lang['Create At'] ?? 'Created At'
                                            }}</span>
                                            <ArrowDownUp
                                                @click="updateSort('created_at')"
                                                height="16"
                                                class="cursor-pointer"
                                                :class="
                                                    sort_type === 'desc' &&
                                                    sort_by === 'first_name'
                                                        ? 'text-slate-900'
                                                        : ''
                                                "
                                            />
                                        </div>
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
                                    v-for="member in members.data"
                                    :key="member.id"
                                >
                                    <TableCell>
                                        <img
                                            v-if="member.image"
                                            :src="`/storage/${member.image}`"
                                            :alt="member.first_name"
                                            class="h-14 w-14 rounded-full border object-cover"
                                        />

                                        <div
                                            v-else
                                            class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 text-xs text-gray-500"
                                        >
                                            N/A
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        {{ member.first_name }}
                                        {{ member.last_name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ member.designation }}
                                    </TableCell>

                                    <TableCell>
                                        <span
                                            v-if="member.is_active"
                                            class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-700"
                                        >
                                            Active
                                        </span>

                                        <span
                                            v-else
                                            class="rounded-full bg-red-100 px-3 py-1 text-xs font-medium text-red-700"
                                        >
                                            Inactive
                                        </span>
                                    </TableCell>

                                    <TableCell>
                                        {{ formatDate(member.created_at) }}
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
                                                            :href="
                                                                route(
                                                                    'super_admin.team-members.show',
                                                                    member.id,
                                                                )
                                                            "
                                                        >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Show'
                                                                ] ?? 'Show'
                                                            }}
                                                        </Link>
                                                    </DropdownMenuItem>
                                                    <DropdownMenuItem
                                                        @click="deleteMember(member)"
                                                    >
                                                            {{
                                                                $page.props
                                                                    .lang[
                                                                    'Delete'
                                                                ] ?? 'Delete'
                                                            }}
                                                    </DropdownMenuItem>
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="members.data.length === 0">
                                    <TableCell
                                        colspan="6"
                                        class="py-10 text-center text-gray-500"
                                    >
                                        {{
                                            $page.props.lang[
                                                'No team members found.'
                                            ]
                                        }}
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <Pagination :links="members.links" />
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
