<script setup>
import Carousel from '@/Components/Frontend/Carousel/Carousel.vue';
import ProductCard from '@/Components/Frontend/ProductCard.vue';
import ReviewsTab from '@/Components/Frontend/ReviewsTab.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { router } from '@inertiajs/vue3';
import { ArrowRight, Heart, Star } from 'lucide-vue-next';
import { computed, onMounted, ref, watch } from 'vue';

import { Button } from '@/Components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import { formatPrice } from '@/lib/utils';

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    related_products: {
        type: Object,
        required: true,
    },
    canReview: Boolean,
});

const relatedProducts = computed(() => props.related_products.data);

console.log(props.product);
console.log('Related Products', props.related_products);
console.log('can review', props.canReview);

// Images
const images = computed(() => props.product.images ?? []);

const activeImage = ref(
    images.value.find((img) => img.is_main)?.image_path ||
        images.value[0]?.image_path ||
        '',
);

// Change image manually
const setImage = (imagePath) => {
    activeImage.value = imagePath;
};

// Options
const options = computed(() => props.product.options ?? []);

const selectedOptions = ref({});

// Quantity
const quantity = ref(1);

const inc = () => {
    // Don't exceed available stock
    if (quantity.value < displayStock.value) {
        quantity.value++;
    }
};

const dec = () => {
    if (quantity.value > 1) quantity.value--;
};

// Select first value of each option by default
onMounted(() => {
    const variant = props.product.variants[0];
    if (!variant) return;
    variant.option_values.forEach((value) => {
        selectedOptions.value[value.product_option_id] = value.id;
    });
});

// Find current variant from selected option values (FIXED)
const currentVariant = computed(() => {
    const selectedIds = Object.values(selectedOptions.value).map(Number);

    // If no options selected yet, return null
    if (!selectedIds.length) return null;

    return (
        props.product.variants.find((variant) => {
            const variantIds = variant.option_values.map((value) => value.id);

            // Check if all selected IDs exist in variant IDs
            const allSelectedExist = selectedIds.every((id) =>
                variantIds.includes(id),
            );
            // Check if all variant IDs exist in selected IDs (no extra values)
            const allVariantExist = variantIds.every((id) =>
                selectedIds.includes(id),
            );

            return allSelectedExist && allVariantExist;
        }) || null
    );
});

// Price
const displayPrice = computed(() => {
    return currentVariant.value?.price ?? props.product.price;
});

// Stock (FIXED - handle null/undefined)
const displayStock = computed(() => {
    return currentVariant.value?.stock ?? 0;
});

// Out of stock
const outOfStock = computed(() => displayStock.value <= 0);

// Watch for variant changes to reset quantity if needed
watch(currentVariant, (newVariant) => {
    if (newVariant && quantity.value > newVariant.stock) {
        quantity.value = Math.min(quantity.value, newVariant.stock);
    }
});

const canAddToCart = computed(() => {
    return currentVariant.value && displayStock.value > 0;
});

const addingToCart = ref(false);

const addToCart = () => {
    if (!currentVariant.value) return;

    router.post(
        route('cart.store'),
        {
            product_variant_id: currentVariant.value.id,
            quantity: quantity.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onStart: () => {
                addingToCart.value = true;
            },
            onFinish: () => {
                addingToCart.value = false;
            },
            onSuccess: () => {
                quantity.value = 1;
            },
        },
    );
};
</script>

<template>
    <section class="w-full px-[30px] pt-[60px]">
        <div
            class="mx-auto grid w-full max-w-[1140px] grid-cols-1 items-start gap-10 px-6 lg:grid-cols-2"
        >
            <!-- Images -->
            <div class="flex items-center gap-5">
                <!-- Images -->
                <div class="flex flex-col gap-3">
                    <button
                        v-for="(img, index) in images"
                        :key="index"
                        @click="setImage(img.image_path)"
                        class="aspect-square w-full max-w-[90px] overflow-hidden rounded-[10px] border-5 transition duration-300"
                        :class="
                            activeImage === img.image_path
                                ? 'border-rose-aura/50'
                                : 'border-transparent'
                        "
                    >
                        <img
                            :src="`/storage/${img.image_path}`"
                            class="h-full w-full object-cover"
                        />
                    </button>
                </div>

                <!-- Main Image -->
                <div class="relative flex-1">
                    <div
                        class="bg-rose-aura/10 aspect-480/570 w-full max-w-[480px] overflow-hidden rounded-[10px]"
                    >
                        <img
                            :src="`/storage/${activeImage}`"
                            class="rouded-[10px] h-full w-full object-cover"
                        />
                    </div>

                    <Button
                        variant="ghost"
                        size="icon"
                        class="absolute top-4 left-4 rounded-full bg-white shadow hover:shadow-md"
                    >
                        <Heart class="h-5 w-5" />
                    </Button>
                </div>
            </div>

            <!-- Product Information -->
            <div class="w-full max-w-[480px] px-5">
                <div class="mb-5 flex items-center gap-2">
                    <div class="flex items-center justify-center">
                        <div class="flex items-center justify-center">
                            <Star
                                v-for="star in 5"
                                :key="star"
                                :class="[
                                    'h-5 w-5',
                                    star <= Math.round(product.average_rating)
                                        ? 'fill-amber-warm text-amber-warm'
                                        : 'text-gray-300',
                                ]"
                            />
                        </div>

                        <p class="text-amber-warm">
                            ({{ product.average_rating }}/5)
                        </p>
                    </div>
                </div>
                <h3 class="font-archivo text-charcoal text-3xl font-bold">
                    {{ product.name }}
                </h3>
                <h3
                    class="text-purple-rich font-archivo mt-7 text-[30px] font-bold"
                >
                    {{ formatPrice(displayPrice) }}
                </h3>
                <p class="text-gray-medium mt-5.5 text-base">
                    {{ product?.short_description }}
                </p>
                <div
                    v-for="option in options"
                    :key="option.id"
                    class="mt-[26px]"
                >
                    <h6 class="text-charcoal font-archivo text-base font-bold">
                        {{ option.name }}
                    </h6>

                    <!-- Color option -->
                    <div
                        v-if="option.type === 'color'"
                        class="mt-4 flex items-center gap-2.5"
                    >
                        <label
                            v-for="value in option.values"
                            :key="value.id"
                            class="cursor-pointer"
                        >
                            <input
                                type="radio"
                                class="sr-only"
                                v-model="selectedOptions[option.id]"
                                :value="value.id"
                            />

                            <div
                                class="flex h-8 w-8 items-center justify-center rounded-full border-2 transition duration-300"
                                :class="
                                    selectedOptions[option.id] === value.id
                                        ? 'border-rose-aura'
                                        : 'border-gray-soft'
                                "
                            >
                                <div
                                    class="h-4.5 w-4.5 rounded-full"
                                    :style="{ backgroundColor: value.value }"
                                ></div>
                            </div>
                        </label>
                    </div>

                    <!-- Size option -->
                    <div
                        v-else-if="option.type === 'size'"
                        class="mt-4 flex gap-3"
                    >
                        <label
                            v-for="value in option.values"
                            :key="value.id"
                            class="cursor-pointer"
                        >
                            <input
                                type="radio"
                                class="sr-only"
                                v-model="selectedOptions[option.id]"
                                :value="value.id"
                            />

                            <div
                                class="text-charcoal flex h-[43px] w-[43px] items-center justify-center rounded-full border text-lg transition-all duration-200"
                                :class="
                                    selectedOptions[option.id] === value.id
                                        ? 'border-black bg-black text-white'
                                        : 'border-gray-soft text-black'
                                "
                            >
                                {{ value.value }}
                            </div>
                        </label>
                    </div>

                    <!-- Default -->
                    <div v-else class="mt-4 flex gap-3">
                        <button
                            v-for="value in option.values"
                            :key="value.id"
                            class="rounded-md border px-4 py-2"
                        >
                            {{ value.value }}
                        </button>
                    </div>
                </div>
                <div class="border-gray-soft mt-[30px] border-t">
                    <div class="mt-[30px] flex items-center gap-3">
                        <div
                            class="border-gray-soft grid h-[53px] w-full max-w-[181px] grid-cols-3 rounded-full border"
                        >
                            <button
                                @click="dec"
                                class="border-gray-soft hover:bg-frost-light m-0 h-full w-full cursor-pointer rounded-tl-full rounded-bl-full border-r p-0 text-xl font-bold text-black transition duration-300"
                            >
                                -
                            </button>
                            <div
                                class="flex items-center justify-center text-lg text-black"
                            >
                                {{ quantity }}
                            </div>
                            <button
                                @click="inc"
                                class="border-gray-soft hover:bg-frost-light m-0 h-full w-full cursor-pointer rounded-tr-full rounded-br-full border-l p-0 text-center text-lg font-bold text-black transition duration-300"
                            >
                                +
                            </button>
                        </div>
                        <button
                            @click="addToCart"
                            :disabled="!canAddToCart"
                            class="bg-rose-aura flex cursor-pointer items-center gap-4 rounded-full px-7 py-3.5 text-sm leading-4 font-semibold text-white transition-all duration-300 hover:opacity-95 hover:shadow-[0_8px_24px_rgba(248,61,142,0.35)] sm:text-base"
                        >
                            {{
                                addingToCart
                                    ? 'Adding...'
                                    : outOfStock
                                      ? 'Out of Stock'
                                      : 'Add to Cart'
                            }}
                            <ArrowRight class="text-xs" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="mx-auto mt-[70px] w-full max-w-[1140px] px-5">
            <Tabs default-value="description" class="w-full">
                <!-- Header -->
                <TabsList
                    class="h-auto w-full justify-start gap-10 rounded-none border-b bg-transparent p-0"
                >
                    <TabsTrigger
                        value="description"
                        class="text-charcoal data-[state=active]:border-rose-aura data-[state=active]:text-rose-aura rounded-none border-b-[5px] border-transparent px-0 pb-6 text-lg font-semibold data-[state=active]:shadow-none"
                    >
                        Description
                    </TabsTrigger>

                    <TabsTrigger
                        value="info"
                        class="text-charcoal data-[state=active]:border-rose-aura data-[state=active]:text-rose-aura rounded-none border-b-[5px] border-transparent px-0 pb-6 text-lg font-semibold data-[state=active]:shadow-none"
                    >
                        Additional Information
                    </TabsTrigger>

                    <TabsTrigger
                        value="reviews"
                        class="text-charcoal data-[state=active]:border-rose-aura data-[state=active]:text-rose-aura rounded-none border-b-[5px] border-transparent px-0 pb-6 text-lg font-semibold data-[state=active]:shadow-none"
                    >
                        Reviews
                    </TabsTrigger>
                </TabsList>

                <!-- Contents -->
                <TabsContent
                    value="description"
                    class="py-[35px_47px] text-2xl leading-[1.9] text-gray-500"
                >
                    <p class="text-gray-medium text-base leading-7">
                        Ratione voluptatem serui nesciunt neaque porro quisquam
                        est, qui dolorem ipsum quia dolor sit amet...
                        list-group-item list-group-item list-group-item Lorem
                        ipsum dolor sit amet consectetur adipisicing elit. Saepe
                        deleniti, quo assumenda temporibus eos natus? Aliquam
                        voluptatum facere quidem libero molestias. Quos ea quod
                        excepturi possimus ut veritatis et officiis! Lorem ipsum
                        dolor sit amet, consectetur adipisicing elit. Recusandae
                        optio, nisi sapiente vel in quia laudantium, saepe
                        reiciendis dolor numquam modi voluptate quo eius
                        delectus temporibus molestias rerum. Rerum, quas?
                    </p>
                    <p class="text-gray-medium mt-5 text-base leading-7">
                        Lorem, ipsum dolor sit amet consectetur adipisicing
                        elit. Culpa praesentium minus quaerat doloribus facere
                        placeat, accusamus eveniet animi sunt asperiores.
                        Perspiciatis optio est culpa, excepturi earum quod
                        asperiores veritatis sint? Lorem ipsum dolor, sit amet
                        consectetur adipisicing elit. Libero, ex repudiandae.
                        Sit, culpa atque? Tempora cumque iste saepe quos porro
                        illo dolor ab sapiente, at eos quae non ducimus est.
                    </p>
                </TabsContent>

                <TabsContent
                    value="info"
                    class="pt-14 text-2xl leading-[1.9] text-gray-500"
                >
                    Additional information goes here.
                </TabsContent>

                <TabsContent
                    value="reviews"
                    class="pt-14 text-2xl leading-[1.9] text-gray-500"
                >
                    <ReviewsTab :product="product" :can-review="canReview" />
                </TabsContent>
            </Tabs>
        </div>
    </section>

    <!-- Explore Category Section -->
    <section class="w-full px-[30px] py-16 md:py-[84px] lg:py-[142px]">
        <div class="mx-auto max-w-[1140px]">
            <div class="space-y-4 text-center">
                <h3>
                    Related
                    <span class="text-rose-aura font-berkshire">Products</span>
                </h3>
                <p class="paragraph-secondary">
                    Discover the favorites that keep our customers coming back
                    for more.
                </p>
            </div>

            <div class="mt-12">
                <Carousel
                    :items="relatedProducts"
                    :items-per-view="{
                        desktop: 4,
                        tablet: 3,
                        mobile: 1,
                    }"
                >
                    <template #item="{ item }">
                        <ProductCard :product="item" />
                    </template>
                </Carousel>
            </div>

            <!-- <div v-else class="mt-12">
                <div
                    class="flex flex-col items-center justify-center rounded-2xl bg-gray-50 p-12"
                >
                    <ShoppingBag class="h-16 w-16 text-gray-400" />
                    <h4 class="mt-4 text-xl font-semibold text-gray-700">
                        No Best Sellers Yet
                    </h4>
                    <p class="mt-2 text-gray-500">
                        Our best sellers list is being updated. Check back soon!
                    </p>
                </div>
            </div> -->
        </div>
    </section>
</template>
