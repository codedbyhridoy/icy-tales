<script setup>
import { ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import { Textarea } from '@/Components/ui/textarea';
import { useForm } from 'laravel-precognition-vue-inertia';

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

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    categories: Array,
});

const form = useForm(
    'post',
    route('super_admin.products.update', props.product.id),
    {
        _method: 'PUT',
        name: props.product.name,
        slug: props.product.slug,
        category_id: String(props.product.category_id), 
        short_description: props.product.short_description,
        description: props.product.description,
        is_featured: Boolean(props.product.is_featured),
        is_active: Boolean(props.product.is_active),

        images: [],

        options: props.product.options.map((option) => ({
            id: option.id,
            name: option.name,
            type: option.type,
            values: option.values.map((value) => value.value),
        })),

        variants: props.product.variants
            ? props.product.variants.map((v) => ({
                  id: v.id,
                  options: v.optionValues
                      ? v.optionValues.reduce((acc, ov) => {
                            if (ov.option) {
                                acc[ov.option.type] = ov.value;
                            }
                            return acc;
                        }, {})
                      : {},
                  price: v.price,
                  stock: v.stock,
                  sku: v.sku,
              }))
            : [],
    },
);

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
    } 
);

const onSlugInput = () => {
    slugEdited.value = true;
};

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

const submit = () => {
    form.submit({
        forceFormData: true,

        onSuccess: () => {
            form.reset();
        },

        onError: (errors) => {
            console.log(errors);
        },
    });
};
</script>

<template>
    <Head title="Edit Product" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="rounded-lg bg-white p-8 shadow">
                    <h1 class="mb-6 text-2xl font-bold">Edit Product</h1>

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
                        <div class="flex flex-col gap-4 border-t pt-6">
                            <Label class="flex items-center gap-2">
                                <Switch v-model="form.is_featured" />
                                {{
                                    $page.props.lang['Featured Product'] ??
                                    'Featured Product'
                                }}
                            </Label>

                            <Label class="flex items-center gap-2">
                                <Switch v-model="form.is_active" />
                                {{
                                    $page.props.lang['Active Product'] ??
                                    'Active Product'
                                }}
                            </Label>
                        </div>

                        <!-- SUBMIT -->
                        <div class="pt-6">
                            <Button type="submit">
                                {{
                                    $page.props.lang['Edit Product'] ??
                                    'Edit Product'
                                }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
