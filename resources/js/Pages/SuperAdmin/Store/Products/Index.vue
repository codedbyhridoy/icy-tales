<script setup>
import Confirm from '@/Components/Confirm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { formatPrice } from '@/lib/utils';

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
import { Ellipsis } from 'lucide-vue-next';

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
});

console.log(props.products.data);

const page = usePage();
const can = computed(() => page.props.auth.can);

const confirmDialog = ref(null);
const selectedProductId = ref(null);

const deleteProduct = (product) => {
    selectedProductId.value = product;
    confirmDialog.value.open();
}

const handleConfirm = () => {
    router.delete(
        route('super_admin.products.destroy', selectedProductId.value)
    ),
    {
        preserveScroll: true
    }
};
</script>

<template>
    <Head :title="$page.props.lang['Product List'] ?? 'Product List'" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage products']"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-[20px]">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{
                                $page.props.lang['Product List'] ??
                                'Product List'
                            }}
                        </h2>
                        <Link
                            v-if="can['manage products'] ?? false"
                            :href="route('super_admin.products.create')"
                            class="ml-2 rounded-md border border-transparent bg-gray-600 px-4 py-2 text-center text-sm text-white shadow-md transition-all hover:bg-gray-700 hover:shadow-lg focus:bg-gray-700 focus:shadow-none active:bg-gray-700 active:shadow-none disabled:pointer-events-none disabled:opacity-50 disabled:shadow-none"
                        >
                            {{ $page.props.lang['Add new'] ?? 'Add new' }}
                        </Link>
                    </div>

                    <Confirm 
                        ref="confirmDialog" 
                        @confirm="handleConfirm" 
                        :title="$page.props.lang['Delete Product'] ?? 'Delete Product'" 
                        :description="$page.props.lang['Are you sure you want to delete this product?']" 
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
                                        {{ $page.props.lang['Name'] ?? 'Name' }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Category'] ??
                                            'Category'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Price'] ?? 'Price'
                                        }}
                                    </TableHead>

                                    <TableHead>
                                        {{
                                            $page.props.lang['Stock'] ?? 'Stock'
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
                                            $page.props.lang['Status'] ??
                                            'Status'
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
                                    v-for="product in products.data"
                                    :key="product.id"
                                >
                                    <TableCell>
                                        <div class="flex items-center gap-2">
                                            <img
                                                v-if="product.images?.length"
                                                :src="`/storage/${product.images[0]?.image_path}`"
                                                class="h-14 w-14 rounded-md border object-cover"
                                            />

                                            <div
                                                v-else
                                                class="flex h-14 w-14 items-center justify-center rounded-md border text-xs"
                                            >
                                                {{
                                                    $page.props.lang[
                                                        'No Image'
                                                    ] ?? 'No Image'
                                                }}
                                            </div>

                                            <span
                                                v-if="
                                                    product.images?.length > 1
                                                "
                                                class="text-muted-foreground text-xs"
                                            >
                                                +{{ product.images.length - 1 }}
                                            </span>
                                        </div>
                                    </TableCell>

                                    <TableCell>
                                        <div class="font-medium">
                                            {{ product.name }}
                                        </div>
                                        <div
                                            class="text-muted-foreground text-xs"
                                        >
                                            {{ product.slug }}
                                        </div>
                                    </TableCell>

                                    <TableCell>
                                        {{ product.category?.name }}
                                    </TableCell>

                                    <TableCell>
                                        {{
                                            product.min_price ===
                                            product.max_price
                                                ? `${formatPrice(product.min_price)}`
                                                : `${formatPrice(product.min_price)} - ${formatPrice(product.min_price)}`
                                        }}
                                    </TableCell>

                                    <TableCell>
                                        {{ product.total_stock }}
                                    </TableCell>

                                    <TableCell>
                                        <span
                                            v-if="product.is_featured"
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

                                    <!-- Status Column -->
                                    <TableCell>
                                        <span
                                            v-if="product.is_active"
                                            class="rounded-full bg-green-100 px-2 py-1 text-xs font-medium text-green-800"
                                        >
                                            Active
                                        </span>

                                        <span
                                            v-else
                                            class="rounded-full bg-red-100 px-2 py-1 text-xs font-medium text-red-800"
                                        >
                                            Inactive
                                        </span>
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
                                                    Actions
                                                </DropdownMenuLabel>

                                                <DropdownMenuSeparator />

                                                <DropdownMenuGroup>
                                                    <DropdownMenuItem as-child>
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'super_admin.products.show',
                                                                    product.id,
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

                                                    <DropdownMenuItem as-child>
                                                        <Link
                                                            :href="
                                                                route(
                                                                    'super_admin.products.edit',
                                                                    product,
                                                                )
                                                            "
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
                                                        @click="deleteProduct(product)"
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
                                <TableRow v-if="products.data.length === 0">
                                    <TableCell
                                        colspan="11"
                                        class="py-8 text-center"
                                    >
                                        No products found.
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
