<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { Badge } from '@/Components/ui/badge';
import { Button } from '@/Components/ui/button';
import { formatDate, formatPrice } from '@/lib/utils';
import { useForm } from 'laravel-precognition-vue-inertia';
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    order: Object,
});

const form = useForm('patch', route('super_admin.orders.update.status', props.order.id), {
    status: props.order.status
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true
    });
};
</script>

<template>
    <Head title="Order Details" />

    <AuthenticatedLayout>
        <div class="space-y-6 p-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        Order #{{ order.order_number }}
                    </h1>

                    <p class="text-muted-foreground">
                        {{ formatDate(order.created_at) }}
                    </p>
                </div>

                <Link :href="route('super_admin.orders.index')">
                    <Button variant="outline"> Back </Button>
                </Link>
            </div>

            <!-- Customer + Shipping -->
            <div class="grid gap-6 lg:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle> Customer Information </CardTitle>
                    </CardHeader>

                    <CardContent class="space-y-3">
                        <div>
                            <p class="text-muted-foreground text-sm">Name</p>

                            <p>
                                {{ order.first_name }}
                                {{ order.last_name }}
                            </p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Email</p>

                            <p>{{ order.email }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Phone</p>

                            <p>{{ order.phone }}</p>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle> Shipping Address </CardTitle>
                    </CardHeader>

                    <CardContent class="space-y-3">
                        <div>
                            <p class="text-muted-foreground text-sm">Country</p>

                            <p>{{ order.country }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">State</p>

                            <p>{{ order.city?.state?.name }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">City</p>

                            <p>{{ order.city?.name }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">Address</p>

                            <p>{{ order.address }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground text-sm">
                                Postal Code
                            </p>

                            <p>{{ order.postal_code }}</p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Order Summary -->
            <Card>
                <CardHeader>
                    <CardTitle> Order Summary </CardTitle>
                </CardHeader>

                <CardContent>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <p class="text-muted-foreground">Payment Method</p>

                            <p>{{ order.latest_payment?.gateway ?? 'n/a' }}</p>
                        </div>

                        <div>
                            <p class="text-muted-foreground">Payment Status</p>

                            <Badge>
                                {{ order.latest_payment?.status ?? 'pending' }}
                            </Badge>
                        </div>

                        <div class="space-y-2">
                           <div>
                             <p class="text-muted-foreground">Order Status</p>
                              <Badge>
                                {{ order.status }}
                            </Badge>
                           </div>

                            <div class="flex items-center gap-3">
                                <Select v-model="form.status">
                                    <SelectTrigger class="w-52">
                                        <SelectValue placeholder="Select Status" />
                                    </SelectTrigger>

                                    <SelectContent>
                                        <SelectItem value="pending">
                                            Pending
                                        </SelectItem>

                                        <SelectItem value="processing">
                                            Processing
                                        </SelectItem>

                                        <SelectItem value="shipped">
                                            Shipped
                                        </SelectItem>

                                        <SelectItem value="delivered">
                                            Delivered
                                        </SelectItem>

                                        <SelectItem value="cancelled">
                                            Cancelled
                                        </SelectItem>
                                    </SelectContent>
                                </Select>

                                <Button
                                    @click="submit"
                                    :disabled="
                                        form.processing ||
                                        form.status === order.status
                                    "
                                >
                                    Update
                                </Button>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted-foreground">Total</p>

                            <p class="font-bold">
                                {{ formatPrice(order.total) }}
                            </p>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <!-- Ordered Products -->

            <Card>
                <CardHeader>
                    <CardTitle> Ordered Products </CardTitle>
                </CardHeader>

                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Image</TableHead>

                                <TableHead>Product</TableHead>

                                <TableHead>Variant</TableHead>

                                <TableHead>Price</TableHead>

                                <TableHead>Qty</TableHead>

                                <TableHead>Total</TableHead>
                            </TableRow>
                        </TableHeader>

                        <TableBody>
                            <TableRow
                                v-for="item in order.order_items"
                                :key="item.id"
                            >
                                <TableCell>
                                    <img
                                        :src="`/storage/${item.product.images[0].image_path}`"
                                        class="h-16 w-16 rounded object-cover"
                                    />
                                </TableCell>

                                <TableCell>
                                    {{ item.product_name }}
                                </TableCell>

                                <TableCell>
                                    {{ item.variant_name }}
                                </TableCell>

                                <TableCell>
                                    {{ formatPrice(item.price) }}
                                </TableCell>

                                <TableCell>
                                    {{ item.quantity }}
                                </TableCell>

                                <TableCell>
                                    {{ formatPrice(item.subtotal) }}
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AuthenticatedLayout>
</template>
