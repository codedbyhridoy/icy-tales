<script setup>
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { Textarea } from '@/Components/ui/textarea';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ref, watch } from 'vue';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    categories: Array,
});

const form = useForm('post', route('super_admin.products.store'), {
    name: '',
    slug: '',
    category_id: '',
    short_description: '',
    description: '',
    is_featured: false,
    is_active: true,

    images: [],

    options: [
        {
            name: '',
            type: '',
            values: [''],
        },
    ],

    variants: [],
});

const slugEdited = ref(false);

const slugify = (text) =>
    (text || '')
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');

watch(
    () => form.name,
    (n) => {
        if (!slugEdited.value) form.slug = slugify(n);
    },
    { immediate: true },
);

const onSlugInput = () => {
    slugEdited.value = true;
};

// Image Handler
const handleImages = (event) => {
    form.images = Array.from(event.target.files);
};

// Generate Variants
function generateVariants() {
    const cleanOptions = form.options.filter(
        (o) => o.name.trim() && o.values.some((v) => v.trim()),
    );

    if (!cleanOptions.length) {
        form.variants = [];
        return;
    }

    const lists = cleanOptions.map((o) =>
        o.values
            .filter((v) => v.trim())
            .map((v) => ({
                option: o.name,
                value: v,
            })),
    );

    const cartesian = (...arrays) =>
        arrays.reduce(
            (a, b) => a.flatMap((x) => b.map((y) => [...x, y])),
            [[]],
        );

    const combos = cartesian(...lists);

    form.variants = combos.map((combo) => {
        const obj = {};

        combo.forEach((c) => {
            obj[c.option] = c.value;
        });

        return {
            id: Date.now() + Math.random(),

            options: obj,

            price: 0,
            stock: 0,
            sku: generateSKU(obj),
        };
    });
}

// Generate SKU
function generateSKU(options) {
    return (
        Object.values(options)
            .map((v) => v.substring(0, 3).toUpperCase())
            .join('-') +
        '-' +
        Math.floor(Math.random() * 999)
    );
}

function sanitizeOptions(options) {
    return options
        .map((option) => ({
            name: option.name?.trim() ?? '',
            type: option.type?.trim() ?? '',
            values: (option.values ?? [])
                .map((value) => value?.trim() ?? '')
                .filter((value) => value),
        }))
        .filter((option) => option.name && option.type && option.values.length);
}

function sanitizeVariants(variants) {
    return variants.map((variant) => ({
        ...variant,
        price: Math.max(0, Number(variant.price) || 0),
        stock: Math.max(0, Number(variant.stock) || 0),
    }));
}

const submit = () => {
    form.options = sanitizeOptions(form.options);
    form.variants = sanitizeVariants(form.variants);

    form.submit({
        forceFormData: true,

        onSuccess: () => {
            form.reset();
        },
        onError: (e) => {
            console.log('Error', e);
        },
    });
};
</script>

<template>
    <Head title="Create Product" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-8 shadow">
                    <h1 class="mb-6 text-2xl font-bold">Create Product</h1>

                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- BASIC INFO -->
                        <div class="grid gap-6">
                            <div class="space-y-2">
                                <Label>
                                    {{
                                        $page.props.lang['Product Name'] ??
                                        'Product Name'
                                    }}
                                </Label>
                                <Input v-model="form.name" />
                            </div>

                            <div class="space-y-2">
                                <Label>
                                    {{ $page.props.lang['Slug'] ?? 'Slug' }}
                                </Label>
                                <Input
                                    v-model="form.slug"
                                    autocomplete="slug"
                                    @input="onSlugInput"
                                />
                            </div>

                            <div class="space-y-2">
                                <Label>
                                    {{
                                        $page.props.lang['Category'] ??
                                        'Category'
                                    }}
                                </Label>

                                <Select v-model="form.category_id">
                                    <SelectTrigger>
                                        <SelectValue
                                            placeholder="Select category"
                                        />
                                    </SelectTrigger>

                                    <SelectContent>
                                        <SelectItem
                                            v-for="category in categories"
                                            :key="category.id"
                                            :value="String(category.id)"
                                        >
                                            {{ category.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>

                            <div class="space-y-2">
                                <Label>
                                    {{
                                        $page.props.lang['Short Description'] ??
                                        'Short Description'
                                    }}
                                </Label>
                                <Textarea v-model="form.short_description" />
                            </div>

                            <div class="space-y-2">
                                <Label>
                                    {{
                                        $page.props.lang['Description'] ??
                                        'Description'
                                    }}
                                </Label>
                                <Textarea v-model="form.description" />
                            </div>
                        </div>

                        <!-- OPTIONS -->
                        <div class="space-y-4 border-t pt-6">
                            <Label>
                                {{
                                    $page.props.lang['Product Options'] ??
                                    'Product Options'
                                }}
                            </Label>

                            <div
                                v-for="(option, i) in form.options"
                                :key="i"
                                class="space-y-3 rounded-md border p-4"
                            >
                                <Input
                                    v-model="option.name"
                                    placeholder="Option name (e.g. Color)"
                                />

                                <Select v-model="option.type">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Type" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="color">
                                            Color
                                        </SelectItem>
                                        <SelectItem value="size">
                                            Size
                                        </SelectItem>
                                    </SelectContent>
                                </Select>

                                <div class="space-y-2">
                                    <div
                                        v-for="(val, j) in option.values"
                                        :key="j"
                                        class="flex gap-2"
                                    >
                                        <Input v-model="option.values[j]" />

                                        <Button
                                            type="button"
                                            variant="destructive"
                                            @click="option.values.splice(j, 1)"
                                        >
                                            X
                                        </Button>
                                    </div>

                                    <Button
                                        type="button"
                                        variant="outline"
                                        @click="option.values.push('')"
                                    >
                                        + Add Value
                                    </Button>
                                </div>
                            </div>

                            <Button
                                type="button"
                                variant="outline"
                                @click="
                                    form.options.push({
                                        name: '',
                                        values: [''],
                                    })
                                "
                                :disabled="form.options.length >= 2"
                            >
                                {{
                                    form.options.length >= 2
                                        ? 'Disabled'
                                        : '+ Add Option'
                                }}
                            </Button>
                        </div>

                        <!-- VARIANTS -->
                        <div class="space-y-4 border-t pt-6">
                            <Button type="button" @click="generateVariants">
                                Generate Variants
                            </Button>

                            <div v-if="form.variants.length">
                                <h2 class="mb-4 text-lg font-bold">
                                    {{ $page.props.lang['Variants'] }}
                                </h2>

                                <Table>
                                    <TableHeader>
                                        <TableRow>
                                            <TableHead>
                                                {{
                                                    $page.props.lang[
                                                        'Variant'
                                                    ] ?? 'Variant'
                                                }}
                                            </TableHead>
                                            <TableHead>
                                                {{
                                                    $page.props.lang['Price'] ??
                                                    'Price'
                                                }}
                                            </TableHead>
                                            <TableHead>
                                                {{
                                                    $page.props.lang['Stock'] ??
                                                    'Stock'
                                                }}
                                            </TableHead>
                                            <TableHead>
                                                {{
                                                    $page.props.lang['SKU'] ??
                                                    'SKU'
                                                }}
                                            </TableHead>
                                        </TableRow>
                                    </TableHeader>

                                    <TableBody>
                                        <TableRow
                                            v-for="v in form.variants"
                                            :key="v.id"
                                        >
                                            <TableCell>
                                                {{
                                                    Object.values(
                                                        v.options,
                                                    ).join(' / ')
                                                }}
                                            </TableCell>

                                            <TableCell>
                                                <Input
                                                    v-model="v.price"
                                                    type="number"
                                                />
                                            </TableCell>

                                            <TableCell>
                                                <Input
                                                    v-model="v.stock"
                                                    type="number"
                                                />
                                            </TableCell>

                                            <TableCell>
                                                <Input v-model="v.sku" />
                                            </TableCell>
                                        </TableRow>
                                    </TableBody>
                                </Table>
                            </div>
                        </div>

                        <!-- IMAGES -->
                        <div class="space-y-2 border-t pt-6">
                            <Label>
                                {{
                                    $page.props.lang['Product Images'] ??
                                    'Product Images'
                                }}
                            </Label>

                            <Input
                                type="file"
                                multiple
                                @change="handleImages"
                            />
                        </div>

                        <!-- SWITCHES -->
                        <div class="border-t pt-6">
                            <h3
                                class="mb-1 text-lg font-semibold text-gray-900"
                            >
                                {{
                                    $page.props.lang['Product Settings'] ??
                                    'Product Settings'
                                }}
                            </h3>

                            <p class="mb-6 text-sm text-gray-500">
                                {{
                                    $page.props.lang[
                                        'Control how this product appears in your store.'
                                    ] ??
                                    'Control how this product appears in your store.'
                                }}
                            </p>

                            <div class="space-y-4">
                                <!-- Featured Product -->
                                <div
                                    class="flex items-center justify-between rounded-lg border p-4"
                                >
                                    <div>
                                        <Label class="font-medium">
                                            {{
                                                $page.props.lang[
                                                    'Featured Product'
                                                ] ?? 'Featured Product'
                                            }}
                                        </Label>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{
                                                $page.props.lang[
                                                    'Display this product on the homepage and featured sections.'
                                                ] ??
                                                'Display this product on the homepage and featured sections.'
                                            }}
                                        </p>
                                    </div>

                                    <Switch v-model="form.is_featured" />
                                </div>

                                <!-- Active Product -->
                                <div
                                    class="flex items-center justify-between rounded-lg border p-4"
                                >
                                    <div>
                                        <Label class="font-medium">
                                            {{
                                                $page.props.lang[
                                                    'Active Product'
                                                ] ?? 'Active Product'
                                            }}
                                        </Label>

                                        <p class="mt-1 text-sm text-gray-500">
                                            {{
                                                $page.props.lang[
                                                    'Make this product available for customers to purchase.'
                                                ] ??
                                                'Make this product available for customers to purchase.'
                                            }}
                                        </p>
                                    </div>

                                    <Switch v-model="form.is_active" />
                                </div>
                            </div>
                        </div>

                        <!-- SUBMIT -->
                        <div class="pt-6">
                            <Button type="submit">
                                {{
                                    $page.props.lang['Create Product'] ??
                                    'Create Product'
                                }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
