<script setup>
import { computed } from 'vue';
import { formatPrice } from '@/lib/utils';
import { Head, Link, usePage } from '@inertiajs/vue3';

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
import { Button } from '@/Components/ui/button';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import useSearchAndSort from '@/Composables/useSearchAndSort';
import SearchInput from '@/Components/SearchInput.vue';

import { Ellipsis } from 'lucide-vue-next';
import { dateFormat } from '@/lib/date-eu';

const {
    search,
    sort_by,
    sort_type,
    per_page,
    updateSearch,
    updateSort,
    updatePerPage
} = useSearchAndSort(route('super_admin.orders.index'));

const props = defineProps({
    orders: {
        type: Object,
        required: true,
    },
    filters: Object
});

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head :title="$page.props.lang['Orders List'] ?? 'Orders List'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage orders']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-5">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{
                                $page.props.lang['Orders List'] ??
                                'Orders List'
                            }}
                        </h2>
                    </div>

                    <SearchInput 
                        v-model="search" 
                        :per-page="per_page" 
                        @update:model-value="updateSearch" 
                        @updatePerPage="updatePerPage" 
                    />

                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>
                                        {{
                                            $page.props.lang['Order Number'] ?? 'Order Number'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{ $page.props.lang['Customer'] ?? 'Customer' }}
                                    </TableHead>

                                    <TableHead>
                                        {{ $page.props.lang['Phone'] ?? 'Phone' }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Total'] ??
                                            'Total'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Payment Method'] ?? 'Payment Method'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Order Status'] ?? 'Order Status'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Date'] ??
                                            'Date'
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
                                    v-for="order in orders.data"
                                    :key="order.id"
                                >
                                    <TableCell>
                                        {{ order.order_number }}
                                    </TableCell>

                                    <TableCell>
                                        {{ order.first_name }} {{ order.last_name }}
                                    </TableCell>

                                    <TableCell>
                                        {{ order.phone }}
                                    </TableCell>

                                    <TableCell>
                                        {{ formatPrice(order.total) }}
                                    </TableCell>

                                    <TableCell>
                                        {{ order.latest_payment?.gateway ?? 'n/a' }}
                                    </TableCell>

                                    <TableCell>
                                        {{ order.latest_payment?.status ?? 'pending' }}
                                    </TableCell>

                                     <TableCell>
                                        {{ dateFormat(order.created_at) }}
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
                                                    {{ $page.props.lang['Actions'] ?? 'Actions' }}
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem as-child>
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'super_admin.orders.show',
                                                                    order.id,
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
                                                </DropdownMenuGroup>
                                            </DropdownMenuContent>
                                        </DropdownMenu>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="orders.data.length === 0">
                                    <TableCell
                                        colspan="11"
                                        class="py-8 text-center"
                                    >
                                        No orders found.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <!-- Pagination will go here -->
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
