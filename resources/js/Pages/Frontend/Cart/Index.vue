<script setup>
import { Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3';
import { ArrowRight } from 'lucide-vue-next';
import { computed, ref } from 'vue';
defineOptions({ layout: FrontendLayout });

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import { formatPrice } from '@/lib/utils';

const props = defineProps({
    items: {
        type: Array,
        required: true
    },
    subtotal: {
        type: Number,
        default: 0
    },
    shipping: {
        type: Number,
        default: 0
    },
    total: {
        type: Number,
        default: 0
    }
});

const updatingQuantity = ref(null);

const increase = (item) => {
    router.patch(
        route('cart.update', { cartItem: item.id }),
        {
            quantity: item.quantity + 1,
        },
        {
            preserveScroll: true,
            onStart: () => {
                updatingQuantity.value = true;
            },
            onFinish: () => {
                updatingQuantity.value = false;
            }
        },
    );
};

const decrease = (item) => {
    if (item.quantity === 1) return;

    router.patch(
        route('cart.update', { cartItem: item.id }),
        {
            quantity: item.quantity - 1,
        },
        {
            preserveScroll: true,
            onStart: () => {
                updatingQuantity.value = true;
            },
            onFinish: () => {
                updatingQuantity.value = false;
            }
        },
    );
};

const remove = (item) => {
    router.delete(
        route('cart.remove', { cartItem: item.id}),
        {
            preserveScroll: true
        }
    )
} 


const clear = () => {
    router.delete(route('cart.clear'), {
        preserveScroll: true,
    });
};

const isEmpty = computed(() => props.items.length < 1);

const getItemTotal = (item) => {
    return (item.price_snapshot * item.quantity).toFixed(2);
};

const getColor = (item) => item.variant?.option_values?.[0]?.value || 'N/A';
const getSize = (item) => item.variant?.option_values?.[1]?.value || 'N/A';
// const getColorHex = (item) => item.variant?.option_values?.[0]?.hex_code || '#000000';
</script>

<template>
    <section class="w-full px-[30px] py-[60px]">
        <div class="mx-auto flex items-start w-full max-w-[1140px] gap-16 relative">
            <div class="w-full">
                <div
                    class="border-gray-soft flex items-center justify-between gap-10 border-b pb-[30px]"
                >
                    <h3 class="text-oxblood font-archivo text-base font-bold">
                        Shopping Cart
                    </h3>
                    <span class="text-oxblood font-archivo text-base font-bold">
                        ({{ items.length.toString().padStart(2, 0) }} items)
                    </span>
                </div>
                <Table>
                    <TableHeader>
                        <TableRow class="border-gray-soft border-b py-6!">
                            <TableHead
                                class="text-gray-medium pl-0 text-base font-medium"
                            >
                                Product Details
                            </TableHead>
                            <TableHead
                                class="text-gray-medium text-base font-medium"
                                >Price</TableHead
                            >
                            <TableHead
                                class="text-gray-medium text-base font-medium"
                            >
                                Quantity
                            </TableHead>
                            <TableHead
                                class="text-gray-medium text-base font-medium"
                                >Total</TableHead
                            >
                            <TableHead
                                class="text-gray-medium text-base font-medium"
                            ></TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow
                            v-for="item in items"
                            :key="item.id"
                            class="border-gray-soft"
                        >
                            <!-- Product -->
                            <TableCell class="py-[30px] pl-0">
                                <div class="flex items-center gap-4.5">
                                    <div
                                        class="bg-muted rounded-1[10px] aspect-square w-full max-w-[93px]"
                                    >
                                        <img
                                            :src="`/storage/${item.image_snapshot}`"
                                            class="h-full w-full rounded-[10px] object-cover"
                                        />
                                    </div>

                                    <div>
                                        <h3
                                            class="font-archivo text-oxblood text-base font-semibold"
                                        >
                                            {{ item.name_snapshot }}
                                        </h3>

                                        <div
                                            class="text-gray-medium mt-4 text-xs"
                                        >
                                            Color:
                                            <span
                                                class="text-oxblood text-xs font-bold"
                                            >
                                                {{ getColor(item) }}
                                            </span>
                                        </div>

                                        <div class="text-gray-medium text-xs">
                                            Size:
                                            <span
                                                class="text-oxblood text-xs font-bold"
                                            >
                                                {{ getSize(item) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </TableCell>

                            <!-- Price -->
                            <TableCell
                                class="text-rose-aura py-[30px] text-base font-bold"
                            >
                                {{ formatPrice(item.price_snapshot) }}
                            </TableCell>

                            <!-- Quantity -->
                            <TableCell>
                                <div
                                    class="border-gray-soft grid h-[36px] w-full grid-cols-3 rounded-full border"
                                >
                                    <button
                                        @click="decrease(item)"
                                        :disabled="updatingQuantity === item.id"
                                        class="border-gray-soft hover:bg-frost-light m-0 h-full w-full cursor-pointer rounded-tl-full rounded-bl-full border-r p-0 text-xl font-bold text-black disabled:cursor-not-allowed transition duration-300"
                                    >
                                        -
                                    </button>
                                    <div
                                        class="flex items-center justify-center text-lg text-black"
                                    >
                                        {{ item.quantity }}
                                    </div>
                                    <button
                                        @click="increase(item)"
                                        :disabled="updatingQuantity === item.id"
                                        class="border-gray-soft hover:bg-frost-light m-0 h-full w-full cursor-pointer rounded-tr-full rounded-br-full border-l p-0 text-center text-lg font-bold text-black transition duration-300"
                                    >
                                        +
                                    </button>
                                </div>
                            </TableCell>

                            <!-- Total -->
                            <TableCell class="text-oxblood text-base font-bold">
                                <!-- ${{ (item.price * item.quantity).toFixed(2) }} -->
                                 {{ getItemTotal(item) }}
                            </TableCell>

                            <!-- Remove -->
                            <TableCell class="p-0">
                                <button
                                    @click="remove(item)"
                                    class="border-lemon-warm/50 hover:bg-lemon-warm/20 ml-auto flex h-[37px] w-[37px] items-center justify-center rounded-full border cursor-pointer transition duration-300"
                                >
                                    <img src="/assets/images/x_mark.svg" alt="Remove Cart" class="w-4 ml-px" />
                                </button>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="isEmpty">
                            <TableCell colspan="11" class="py-8 text-center">
                                No Cart
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
            <div class="sticky top-10 w-full md:w-[320px] md:min-w-[320px]">
                <div
                    class="border-purple-rich transiton w-full rounded-[20px] border-t-4 shadow-sm duration-300 hover:shadow-md"
                >
                    <div class="border-gray-soft border-b px-8 py-5">
                        <h3
                            class="text-oxblood font-archivo text-base font-bold"
                        >
                            Order Summary
                        </h3>
                    </div>
                    <div
                        class="border-gray-soft flex items-center justify-between gap-2 border-b px-8 py-5"
                    >
                        <h4 class="text-oxblood font-archivo text-sm font-bold">
                            Apply Coupons
                        </h4>
                        <button
                            class="bg-purple-rich cursor-pointer rounded-full px-4.5 py-2 text-sm font-semibold text-white transition duration-300 hover:shadow-[0_8px_24px_rgba(104,50,146,0.35)]"
                        >
                            Appy
                        </button>
                    </div>
                    <div class="px-8 py-[25px_30px]">
                        <div class="border-gray-soft border-b">
                            <h4
                                class="text-oxblood font-archivo text-sm font-bold"
                            >
                                Product Details
                            </h4>
                            <div class="space-y-4 py-5">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-gray-medium text-sm font-medium"
                                    >
                                        Sub Total
                                    </span>
                                    <span
                                        class="text-oxblood text-sm font-medium"
                                    >
                                        {{ formatPrice(subtotal) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-gray-medium text-sm font-medium"
                                    >
                                        Shipping
                                    </span>
                                    <span
                                        class="text-oxblood text-sm font-medium"
                                    >
                                        {{ formatPrice(shipping) }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-gray-medium text-sm font-medium"
                                    >
                                        Total
                                    </span>
                                    <span
                                        class="text-oxblood text-sm font-medium"
                                    >
                                        {{ formatPrice(total) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="border-gray-soft border-b py-7.5">
                            <div
                                class="flex items-center justify-between gap-2"
                            >
                                <h4
                                    class="text-oxblood font-archivo text-sm font-bold"
                                >
                                    Apply Coupons
                                </h4>
                                <span
                                    class="text-rose-aura text-sm font-semibold"
                                >
                                    $89
                                </span>
                            </div>
                            <Link
                                :href="route('checkout.index')"
                                class="bg-rose-aura mt-5 flex h-[50px] w-full cursor-pointer items-center justify-center gap-4.5 rounded-full text-white transition duration-300 hover:shadow-[0_8px_24px_rgba(248,61,142,0.35)]"
                            >
                                Proceed to checkout
                                <ArrowRight class="size-4" />
                            </Link>
                        </div>
                        <div class="mt-5 flex items-start gap-2">
                            <img
                                src="/assets/images/Symbol.svg"
                                alt="Symbol"
                                class="h-4 w-4"
                            />
                            <p
                                class="text-gray-medium -translate-y-0.5 text-xs leading-4.5"
                            >
                                Safe and Secure Payments, Easy Returns. 100%
                                Authentic Products
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
