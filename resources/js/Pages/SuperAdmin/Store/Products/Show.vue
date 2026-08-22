<script setup>
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatPrice } from '@/lib/utils';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const images = computed(() => props.product.images ?? []);

const activeImage = ref(
    images.value.find((img) => img.is_main)?.image_path ||
        images.value[0]?.image_path ||
        '',
);

const setImage = (imagePath) => {
    activeImage.value = imagePath;
};

const featuredImage = computed(() => {
    return props.product.images?.[0] ?? null;
});
</script>

<template>
    <Head :title="product.name" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="flex items-center justify-between">
                    <Link
                        :href="route('super_admin.products.index')"
                        class="text-sm text-gray-600 hover:text-gray-900"
                    >
                        ← Back to Products
                    </Link>
                    <div class="flex gap-2">
                        <Link
                            :href="
                                route('super_admin.products.edit', product.id)
                            "
                        >
                            <Button variant="outline">Edit Product</Button>
                        </Link>
                    </div>
                </div>

                <!-- Product Header -->
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <div class="flex flex-col gap-6 md:flex-row">
                        <!-- Main Image -->
                        <div class="md:w-1/3">
                            <img
                                :src="`/storage/${activeImage}`"
                                :alt="product.name"
                                class="aspect-square w-full rounded-lg border object-cover"
                            />
                            <!-- Thumbnails -->
                            <div
                                v-if="product.images?.length > 1"
                                class="mt-2 flex gap-2 overflow-x-auto"
                            >
                                <img
                                    v-for="img in product.images"
                                    :key="img.id"
                                    @click="setImage(img.image_path)"
                                    :src="`/storage/${img.image_path}`"
                                    class="w-16 aspect-square cursor-pointer rounded border object-cover hover:opacity-75"
                                    :class="
                                        activeImage === img.image_path
                                            ? 'border-rose-aura/50'
                                            : 'border-transparent'
                                    "
                                />
                                <!-- <div
                                    v-if="product.images.length > 4"
                                    class="flex h-16 w-16 items-center justify-center rounded border bg-gray-100 text-xs"
                                >
                                    +{{ product.images.length - 4 }}
                                </div> -->
                            </div>
                        </div>

                        <!-- Product Info -->
                        <div class="flex-1 space-y-4">
                            <div>
                                <h4 class="text-3xl font-bold">
                                    {{ product.name }}
                                </h4>
                                <p class="text-sm text-gray-500">
                                    {{ product.slug }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <Badge variant="secondary">{{
                                    product.category?.name
                                }}</Badge>
                                <Badge
                                    v-if="product.is_featured"
                                    class="bg-yellow-500"
                                    >⭐ Featured</Badge
                                >
                                <Badge
                                    :class="
                                        product.is_active
                                            ? 'bg-green-500'
                                            : 'bg-red-500'
                                    "
                                >
                                    {{
                                        product.is_active
                                            ? 'Active'
                                            : 'Inactive'
                                    }}
                                </Badge>
                            </div>

                            <!-- Quick Stats -->
                            <div
                                class="grid grid-cols-2 gap-4 border-t pt-4 md:grid-cols-4"
                            >
                                <div>
                                    <p class="text-sm text-gray-500">Price</p>
                                    <p class="text-2xl font-bold">
                                        {{
                                            product.min_price ==
                                            product.max_price
                                                ? `${formatPrice(product.min_price)}`
                                                : `${formatPrice(product.min_price)} - ${formatPrice(product.min_price)}`
                                        }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Total Stock
                                    </p>
                                    <p class="text-2xl font-bold">
                                        {{ product.total_stock }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">
                                        Variants
                                    </p>
                                    <p class="text-2xl font-bold">
                                        {{ product.variants?.length || 0 }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Images</p>
                                    <p class="text-2xl font-bold">
                                        {{ product.images?.length || 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <h2 class="mb-4 text-xl font-bold">Description</h2>

                    <div v-if="product.short_description" class="mb-4">
                        <h3
                            class="font-archivo text-sm font-semibold text-gray-500"
                        >
                            Short Description
                        </h3>
                        <p class="text-gray-700">
                            {{ product.short_description }}
                        </p>
                    </div>

                    <div v-if="product.description">
                        <h3
                            class="font-archivo text-sm font-semibold text-gray-500"
                        >
                            Full Description
                        </h3>
                        <div
                            class="prose max-w-none"
                            v-html="product.description"
                        ></div>
                    </div>
                </div>

                <!-- Options -->
                <div
                    v-if="product.options?.length"
                    class="bg-white p-6 shadow-sm sm:rounded-lg"
                >
                    <h2 class="font-archivo mb-4 text-xl font-bold">
                        Product Options
                    </h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div
                            v-for="option in product.options"
                            :key="option.id"
                            class="rounded-lg border p-4"
                        >
                            <h4 class="font-semibold">{{ option.name }}</h4>
                            <p class="text-sm text-gray-500">
                                Type: {{ option.type }}
                            </p>
                            <div class="mt-2 flex flex-wrap gap-1">
                                <Badge
                                    v-for="value in option.values"
                                    :key="value"
                                    variant="outline"
                                >
                                    {{ value }}
                                </Badge>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Variants Table -->
                <div
                    v-if="product.variants?.length"
                    class="bg-white p-6 shadow-sm sm:rounded-lg"
                >
                    <h2 class="mb-4 text-xl font-bold">Variants</h2>

                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Variant</TableHead>
                                <TableHead>Price</TableHead>
                                <TableHead>Stock</TableHead>
                                <TableHead>SKU</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow
                                v-for="variant in product.variants"
                                :key="variant.id"
                            >
                                <TableCell>
                                    {{
                                        Object.values(
                                            variant.options || {},
                                        ).join(' / ')
                                    }}
                                </TableCell>
                                <TableCell
                                    >৳{{
                                        variant.price || product.price
                                    }}</TableCell
                                >
                                <TableCell>{{ variant.stock || 0 }}</TableCell>
                                <TableCell class="font-mono text-sm">{{
                                    variant.sku
                                }}</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>

                <!-- All Images Gallery -->
                <div
                    v-if="product.images?.length > 1"
                    class="bg-white p-6 shadow-sm sm:rounded-lg"
                >
                    <h2 class="mb-4 text-xl font-bold">All Images</h2>
                    <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                        <img
                            v-for="img in product.images"
                            :key="img.id"
                            :src="`/storage/${img.image_path}`"
                            class="aspect-square w-full rounded-lg border object-cover"
                            :alt="product.name"
                        />
                    </div>
                </div>

                <!-- Meta Info -->
                <div
                    class="bg-white p-6 text-sm text-gray-500 shadow-sm sm:rounded-lg"
                >
                    <div class="flex flex-wrap gap-6">
                        <span
                            >Created:
                            {{
                                new Date(
                                    product.created_at,
                                ).toLocaleDateString()
                            }}</span
                        >
                        <span
                            >Updated:
                            {{
                                new Date(
                                    product.updated_at,
                                ).toLocaleDateString()
                            }}</span
                        >
                        <span v-if="product.created_by"
                            >Created by: {{ product.created_by?.name }}</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
