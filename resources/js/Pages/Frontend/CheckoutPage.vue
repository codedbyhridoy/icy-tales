<script setup>
import InputError from '@/Components/InputError.vue';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { formatPrice } from '@/lib/utils';
import { Link, usePage } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { ArrowRight } from 'lucide-vue-next';
import { computed } from 'vue';
import { toast } from 'vue-sonner';
defineOptions({ layout: FrontendLayout });

const props = defineProps({
    items: Object,
    total: Number,
    states: Array,
});

const form = useForm('post', route('checkout.store'), {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    country: 'Bangladesh',
    state_id: null,
    city_id: null,
    address: '',
    postal_code: '',
    payment_method: 'cod', // COD is the safe default when online gateway credentials are not configured locally.
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            toast.success()
        },
        onError: (e) => {
            console.log('Form error', e);
        },
    });
};

const months = Array.from({ length: 12 }, (_, index) => {
    const date = new Date(2000, index);

    return {
        value: String(index + 1).padStart(2, '0'),
        label: date.toLocaleString('en-US', {
            month: 'long',
        }),
    };
});

const currentYear = new Date().getFullYear();

const years = Array.from({ length: 15 }, (_, index) => {
    const year = String(currentYear + index);

    return {
        value: year,
        label: year,
    };
});

const cities = computed(() => {
    return (
        props.states.find((state) => state.id === form.state_id)?.cities ?? []
    );
});
</script>

<template>
    <section class="w-full px-[30px] py-[60px]">
        <div class="mx-auto flex w-full max-w-[1140px] items-start gap-16">
            <div class="w-full">
                <form @submit.prevent="submit">
                    <div>
                        <h3 class="text-oxblood font-archivo text-xl font-bold">
                            Billing Address:
                        </h3>
                        <div class="mt-7.5 space-y-7">
                            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                                <div>
                                    <div class="space-y-5">
                                        <label class="checkout-label">
                                            First Name
                                        </label>
                                        <input
                                            type="text"
                                            class="checkout-input mt-3"
                                            v-model="form.first_name"
                                        />
                                    </div>
                                    <InputError
                                        :message="form.errors.first_name"
                                        class="mt-1"
                                    />
                                </div>
                                <div>
                                    <div class="space-y-5">
                                        <label class="checkout-label">
                                            Last Name
                                        </label>
                                        <input
                                            type="text"
                                            class="checkout-input mt-3"
                                            v-model="form.last_name"
                                        />
                                    </div>
                                    <InputError
                                        :message="form.errors.last_name"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                                <div>
                                    <div class="space-y-5">
                                        <label class="checkout-label">
                                            Email Address
                                        </label>
                                        <input
                                            type="email"
                                            class="checkout-input mt-3"
                                            v-model="form.email"
                                        />
                                    </div>
                                    <InputError :message="form.errors.email" />
                                </div>
                                <div>
                                    <div class="space-y-5">
                                        <label class="checkout-label">
                                            Phone
                                        </label>
                                        <input
                                            type="text"
                                            class="checkout-input mt-3"
                                            v-model="form.phone"
                                        />
                                    </div>
                                    <InputError :message="form.errors.phone" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                                <div>
                                    <label class="checkout-label">
                                        State
                                    </label>
                                    <Select v-model="form.state_id">
                                        <SelectTrigger
                                            class="border-gray-soft focus-visible:border-rose-aura/40 mt-3 h-12.5 w-full rounded-full border px-5 transition-colors duration-200 focus:ring-0 focus:outline-none focus-visible:ring-0"
                                        >
                                            <SelectValue
                                                class="text-gray-medium text-sm"
                                                placeholder="Select State"
                                            />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-[20px]">
                                            <SelectItem
                                                v-for="state in states"
                                                :key="state.id"
                                                :value="state.id"
                                            >
                                                {{ state.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        :message="form.errors.state_id"
                                        class="mt-1"
                                    />
                                </div>
                                <div>
                                    <label class="checkout-label"> City </label>
                                    <Select
                                        v-model="form.city_id"
                                        :disabled="!form.state_id"
                                    >
                                        <SelectTrigger
                                            class="border-gray-soft focus-visible:border-rose-aura/40 mt-3 h-12.5 w-full rounded-full border px-5 transition-colors duration-200 focus:ring-0 focus:outline-none focus-visible:ring-0"
                                        >
                                            <SelectValue
                                                class="text-gray-medium text-sm"
                                                placeholder="Select City"
                                            />
                                        </SelectTrigger>
                                        <SelectContent class="rounded-[20px]">
                                            <SelectItem
                                                v-for="city in cities"
                                                :key="city.id"
                                                :value="city.id"
                                            >
                                                {{ city.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError
                                        :message="form.errors.city_id"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-10 lg:grid-cols-2">
                                <div>
                                    <div>
                                        <label class="checkout-label">
                                            Address
                                        </label>
                                        <input
                                            type="text"
                                            class="checkout-input mt-3"
                                            v-model="form.address"
                                        />
                                    </div>
                                    <InputError
                                        :message="form.errors.address"
                                        class="mt-1"
                                    />
                                </div>
                                <div>
                                    <div>
                                        <label class="checkout-label">
                                            Zip/Postal Code
                                        </label>
                                        <input
                                            type="text"
                                            inputmode="numeric"
                                            autocomplete="postal-code"
                                            class="checkout-input mt-3"
                                            v-model="form.postal_code"
                                        />
                                    </div>
                                    <InputError
                                        :message="form.errors.postal_code"
                                        class="mt-1"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[30px]">
                        <h3 class="text-oxblood font-archivo text-xl font-bold">
                            Payment Method:
                        </h3>
                        <div class="mt-7.5 space-y-7">
                            <div>
                                <RadioGroup
                                    v-model="form.payment_method"
                                    class="space-y-5"
                                >
                                    <!-- Stripe -->
                                    <label
                                        for="stripe"
                                        class="border-gray-soft has-[[data-state=checked]]:border-rose-aura has-[[data-state=checked]]:bg-rose-aura/5 flex h-12.5 cursor-pointer items-center justify-between rounded-full border px-5 has-[[data-state=checked]]:shadow-[0_8px_24px_rgba(248,61,142,0.12)]"
                                    >
                                        <div class="flex items-center gap-[15px]">
                                            <RadioGroupItem
                                                id="stripe"
                                                value="stripe"
                                                class="border-gray-soft data-[state=checked]:border-rose-aura h-7 w-7 border text-white transition-all duration-400 data-[state=checked]:border-7"
                                            />

                                            <span class="text-oxblood text-base font-medium">
                                                Stripe
                                            </span>
                                        </div>

                                        <div class="h-auto max-w-[122px]">
                                            <img
                                                src="/assets/images/checkout-cardimage.png"
                                                alt="Stripe payment"
                                                class="w-full"
                                            />
                                        </div>
                                    </label>

                                    <!-- SSLCommerz -->
                                    <label
                                        for="sslcommerz"
                                        class="border-gray-soft has-[[data-state=checked]]:border-rose-aura has-[[data-state=checked]]:bg-rose-aura/5 flex h-12.5 cursor-pointer items-center justify-between rounded-full border px-5 has-[[data-state=checked]]:shadow-[0_8px_24px_rgba(248,61,142,0.12)]"
                                    >
                                        <div
                                            class="flex items-center gap-[15px]"
                                        >
                                            <RadioGroupItem
                                                id="sslcommerz"
                                                value="sslcommerz"
                                                class="border-gray-soft data-[state=checked]:border-rose-aura h-7 w-7 border text-white transition-all duration-400 data-[state=checked]:border-7"
                                            />

                                            <span class="text-oxblood text-base font-medium">
                                                SSLCommerz
                                            </span>
                                        </div>

                                        <div class="h-auto max-w-[122px]">
                                            <img
                                                src="/assets/images/checkout-cardimage.png"
                                                alt="SSLCommerz payment"
                                                class="w-full"
                                            />
                                        </div>
                                    </label>

                                    <!-- Cash on Delivery -->
                                    <label
                                        for="cod"
                                        class="border-gray-soft has-[[data-state=checked]]:border-rose-aura has-[[data-state=checked]]:bg-rose-aura/5 flex h-12.5 cursor-pointer items-center justify-between rounded-full border px-5 has-[[data-state=checked]]:shadow-[0_8px_24px_rgba(248,61,142,0.12)]"
                                    >
                                        <div class="flex items-center gap-4">
                                            <RadioGroupItem
                                                id="cod"
                                                value="cod"
                                                class="border-gray-soft data-[state=checked]:border-rose-aura h-7 w-7 border text-white transition-all duration-400 data-[state=checked]:border-7"
                                            />

                                            <span class="font-medium">
                                                Cash on Delivery
                                            </span>
                                        </div>

                                        <img
                                            src="/assets/images/cash_icon.svg"
                                            class="h-5"
                                        />
                                    </label>
                                </RadioGroup>
                            </div>
                            <InputError
                                :message="form.errors.payment_method"
                                class="mt-1"
                            />
                        </div>
                    </div>
                    <div class="mt-9.5">
                        <p class="text-gray-medium text-center text-sm">
                            By clicking the button, you agree to the
                            <Link
                                href=""
                                class="text-rose-aura transiton duration-300 hover:underline"
                                >Terms & Conditions</Link
                            >
                        </p>
                        <button
                            class="bg-rose-aura mt-5 flex h-[50px] w-full cursor-pointer items-center justify-center gap-4.5 rounded-full text-white transition duration-300 hover:shadow-[0_8px_24px_rgba(248,61,142,0.35)]"
                        >
                            Place Order Now
                            <ArrowRight class="size-4" />
                        </button>
                    </div>
                </form>
            </div>

            <!-- Right Part -->
            <div class="sticky top-10 w-full md:w-[320px] md:min-w-[320px]">
                <div
                    class="border-purple-rich transiton w-full rounded-[20px] border-t-4 shadow-sm duration-300 hover:shadow-md"
                >
                    <!-- Header -->
                    <div
                        class="border-gray-soft grid grid-cols-[1fr_auto] border-b px-[30px] py-4"
                    >
                        <h2 class="text-oxblood text-sm font-bold">Items</h2>
                        <h2 class="text-oxblood text-sm font-bold">Price</h2>
                    </div>

                    <!-- Row -->
                    <div class="h-[350px] overflow-y-auto">
                        <div
                            v-for="item in items"
                            :key="item.key"
                            class="border-gray-soft grid grid-cols-[1fr_auto] gap-4 border-b px-[30px] py-5"
                        >
                            <div>
                                <h3
                                    class="text-oxblood font-archivo text-base font-bold"
                                >
                                    {{ item.quantity }} ×
                                    {{ item.name_snapshot }}
                                </h3>

                                <p class="text-gray-medium mt-2.5 text-sm">
                                    {{ item.product?.short_description }}
                                </p>
                            </div>

                            <div
                                class="text-oxblood self-start text-sm font-bold"
                            >
                                {{ formatPrice(item.price_snapshot) }}
                            </div>
                        </div>
                        <div
                            v-if="items.length === 0"
                            class="flex h-full w-full items-center justify-center"
                        >
                            <span>You have no cart</span>
                        </div>
                    </div>
                    <div
                        class="flex items-center justify-between gap-5 px-[30px] py-[30px] shadow-[0_-1px_3px_rgba(0,0,0,0.08)]"
                    >
                        <h3
                            class="text-oxblood font-archivo text-base font-bold"
                        >
                            Grand Total:
                        </h3>
                        <span class="text-rose-aura text-sm font-bold">
                            {{ formatPrice(total) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
