<script setup>
import { cn } from '@/lib/utils';
import { formatPrice } from '@/lib/utils';
import { Link } from '@inertiajs/vue3';
import { Heart, Star } from 'lucide-vue-next';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div
        class="w-full h-full rounded-[20px] shadow-[inset_0px_2px_73px_2px_rgba(0,0,0,0.05)] transition duration-300 hover:scale-105"
    >
        <div class="p-[11px]">
            <Link
                :href="route('explore-products.show', product)"
                class="relative flex aspect-233/244 items-center justify-center rounded-[20px]"
            >
                    <img
                        :src="
                            product.images?.[0]?.image_path
                                ? `/storage/${product.images[0].image_path}`
                                : '/placeholder.jpg'
                        "
                        class="h-full w-full object-cover rounded-[20px]"
                        :alt="product.name"
                    />
                <button
                    type="button"
                    @click.stop=""
                    class="absolute top-2.5 left-2.5 flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white transition duration-300 hover:shadow-md"
                >
                    <Heart class="h-5 w-5" />
                </button>
            </Link>
        </div>
        <div class="flex h-full flex-col px-[23px] py-[10px_18px]">
            <div class="flex items-start justify-between h-12">
                <Link :href="route('explore-products.show', product)">
                    <h5
                        class="hover:text-rose-aura transiton-all line-clamp-2 duration-200 hover:underline"
                    >
                        {{ product.name }}
                    </h5>
                </Link>
                <Link class="mt-1 flex items-center justify-center gap-1">
                    <Star
                        class="text-amber-warm fill-amber-warm -mt-1 h-[13px] w-[13px]"
                    />
                    <span class="text-oxblood text-sm leading-4.5 font-semibold"
                        >4.9/5</span
                    >
                </Link>
            </div>
            <div class="mt-[13px] h-12">
                <Link :href="route('explore-products.show', product)">
                <p
                    class="text-gray-neutral line-clamp-2 text-base leading-6 hover:text-rose-aura hover:underline transition-all duration-200"
                >
                    {{ product?.short_description }}
                </p>
            </Link>
            </div>
            <div class="mt-3 flex items-center justify-between">
                <Link :href="route('explore-products.show', product)">
                    <h6 class="text-rose-aura text-[22px] font-semibold hover:underline transition-all duration-200">
                        {{ formatPrice(product.min_price) }}
                    </h6>
                </Link>
                <button
                    class="bg-purple-rich transiton flex h-12 w-12 cursor-pointer items-center justify-center rounded-full duration-300 hover:shadow-md"
                >
                    <img
                        src="/assets/images/cart.svg"
                        alt="Cart Image"
                        class="h-auto w-auto"
                    />
                </button>
            </div>
        </div>
    </div>
</template>
