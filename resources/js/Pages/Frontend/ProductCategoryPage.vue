<script setup>
import { ref, computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Search } from 'lucide-vue-next';
import { formatPrice } from '@/lib/utils';
import { Tabs, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import { RadioGroup, RadioGroupItem } from '@/Components/ui/radio-group';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import PriceRanger from '@/Components/Frontend/PriceRanger.vue';
import ProductCard from '@/Components/Frontend/ProductCard.vue';
import ProductSorting from '@/Components/Frontend/ProductSorting.vue';
import ProductPagination from '@/Components/Frontend/ProductPagination.vue';


defineOptions({ layout: FrontendLayout });

const props = defineProps({
    products: {
        type: Object,
        required: true,
    },
    featured_products: {
        type: Object,
        required: true
    },
    categories: {
        type: Object,
        required: true,
    },
    selectedCategory: {
        type: String
    },
    filters: {
        type: Object,
        required: true
    }
});

const products = computed(() => props.products.data);
const totalProducts = computed(() => props.products);

const featuredProducts = computed(() => props.featured_products.data);

const search = ref(props.filters.search ?? '');
const selectedCategory = ref(props.selectedCategory ?? '');
const price = ref([
    props.filters.min_price ?? 0,
    props.filters.max_price ?? 100,
]);
const sort = ref(props.filters.sort ?? 'latest');
const viewMode = ref('grid');

const filterdProducts = () => {
    router.get(
        route('frontend.categories.show', selectedCategory.value),
        {
            search: search.value,
            min_price: price.value[0],
            max_price: price.value[1],
            sort: sort.value,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};

const changeCategory = (slug) => {
    selectedCategory.value = slug;
    filterdProducts();
};
</script>

<template>
    <section class="mx-auto max-w-7xl px-[30px] py-14">
        <div class="grid grid-cols-12 gap-2.5">
            <div class="col-span-12 px-5 lg:col-span-3">
                <!-- Search Bar -->
                <div
                    class="border-gray-soft focus-within:border-rose-aura focus-within:ring-rose-aura/10 flex h-11 w-full items-center justify-between gap-2 rounded-full border px-2 transition-all duration-200 focus-within:shadow-sm focus-within:ring-4"
                >
                    <Input
                        class="border-0 bg-transparent shadow-none focus-visible:ring-0 focus-visible:ring-offset-0"
                        v-model="search"
                        @input="filterdProducts"
                    />
                    <Button
                        variant="icon"
                        class="text-gray-neutral hover:text-rose-aura transiton cursor-pointer text-sm duration-200"
                    >
                        <Search />
                    </Button>
                </div>

                <!-- Category Box -->
                <div class="border-gray-soft mt-[60px] space-y-5 border-b pb-8">
                    <h3 class="text-oxblood text-[22px]">Categories</h3>

                    <RadioGroup
                        v-model="selectedCategory"
                        @update:modelValue="changeCategory"
                        class="mt-5 grid gap-0"
                    >
                        <Label
                            v-for="category in categories"
                            :key="category.id"
                            class="flex items-center justify-start gap-3 py-2.5"
                        >
                            <RadioGroupItem
                                :id="`category-${category.id}`"
                                :value="category.slug"
                                class="text-rose-aura data-[state=checked]:border-rose-aura border-gray-soft h-5 w-5 border-2 transition-all duration-200"
                            />
                            <span class="text-gray-medium text-sm">
                                {{ category.name }}
                            </span>
                        </Label>
                    </RadioGroup>
                </div>

                <!-- Filter Price Box -->
                <div class="border-gray-soft mt-[30px] border-b pb-8">
                    <h3 class="text-oxblood text-[22px]">Filter By Price</h3>
                    <div
                        class="mt-5 flex flex-row items-center gap-10 lg:flex-col lg:gap-3"
                    >
                        <PriceRanger
                            v-model="price"
                            @update:modelValue="filterdProducts"
                            :min="0"
                            :max="100"
                            class="w-full"
                        />
                        <div
                            class="text-rose-aura flex items-center gap-2 whitespace-nowrap"
                        >
                            <span class="text-gray-medium">Range:</span>
                            <span>{{ formatPrice(price[0]) }} - {{ formatPrice(price[1]) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Featured Products -->
                <div class="mt-[30px]">
                    <h3 class="text-oxblood font-archivo text-[22px]">
                        Featured Products
                    </h3>
                    <div class="mt-5 space-y-5">
                        <div 
                            v-for="product in featuredProducts"
                            :key="product.id"
                        >
                            <Link 
                                :href="route('explore-products.show', product)" 
                                class="w-full grid grid-cols-7 items-center gap-3"
                            >
                                <div class="col-span-2 aspect-66/62 rounded-[10px]">
                                    <img
                                        :src="`/storage/${product.images[0].image_path}`"
                                        alt="Featured Products"
                                        class="h-full w-full rounded-[10px]"
                                    />
                                </div>
                                <div class="spacey-y-3 col-span-5">
                                    <h4
                                        class="font-archivo text-oxblood text-base font-bold line-clamp-1"
                                    >
                                        {{ product.name }}
                                    </h4>
                                    <div
                                        class="flex items-center justify-start gap-2"
                                    >
                                        <span
                                            class="text-gray-medium text-base font-bold"
                                        >
                                            $48
                                        </span>
                                        <span
                                            class="text-rose-aura text-base font-bold"
                                        >
                                            {{ formatPrice(product.min_price) }}
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Part -->
            <div class="col-span-12 px-[15px] lg:col-span-9">
                <!-- Top Functionality -->
                <div class="flex items-center justify-between gap-10">
                    <!-- Layout Changing Tabs -->
                    <div class="flex items-center justify-start gap-[22px]">
                        <Tabs v-model="viewMode">
                            <TabsList class="flex gap-3 bg-transparent p-0">
                                <TabsTrigger
                                    value="grid"
                                    class="rounded-2 bg-frost-light data-[state=active]:bg-rose-aura text-gray-medium flex h-11 w-11 items-center justify-center transition-colors data-[state=active]:text-white"
                                >
                                    <svg
                                        class="h-5.5 w-5.5"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <rect
                                            x="3"
                                            y="3"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="10"
                                            y="3"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="17"
                                            y="3"
                                            width="4"
                                            height="4"
                                        />

                                        <rect
                                            x="3"
                                            y="10"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="10"
                                            y="10"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="17"
                                            y="10"
                                            width="4"
                                            height="4"
                                        />

                                        <rect
                                            x="3"
                                            y="17"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="10"
                                            y="17"
                                            width="4"
                                            height="4"
                                        />
                                        <rect
                                            x="17"
                                            y="17"
                                            width="4"
                                            height="4"
                                        />
                                    </svg>
                                </TabsTrigger>

                                <TabsTrigger
                                    value="list"
                                    class="rounded-2 bg-frost-light data-[state=active]:bg-rose-aura text-gray-medium flex h-11 w-11 items-center justify-center transition-colors data-[state=active]:text-white"
                                >
                                    <svg
                                        class="h-5.5 w-5.5"
                                        viewBox="0 0 24 24"
                                        fill="currentColor"
                                    >
                                        <rect
                                            x="3"
                                            y="4"
                                            width="4"
                                            height="4"
                                            rx="1"
                                        />
                                        <rect
                                            x="9"
                                            y="5"
                                            width="12"
                                            height="2"
                                            rx="1"
                                        />

                                        <rect
                                            x="3"
                                            y="10"
                                            width="4"
                                            height="4"
                                            rx="1"
                                        />
                                        <rect
                                            x="9"
                                            y="11"
                                            width="12"
                                            height="2"
                                            rx="1"
                                        />

                                        <rect
                                            x="3"
                                            y="16"
                                            width="4"
                                            height="4"
                                            rx="1"
                                        />
                                        <rect
                                            x="9"
                                            y="17"
                                            width="12"
                                            height="2"
                                            rx="1"
                                        />
                                    </svg>
                                </TabsTrigger>
                            </TabsList>
                        </Tabs>
                        <p class="text-gray-medium text-sm">
                            Showing {{ products.length }} of {{ totalProducts.total }} results
                        </p>
                    </div>

                    <!-- Product Sorting -->
                    <ProductSorting v-model="sort" @update:modelValue="filterdProducts" />
                </div>
                <!-- Products Container -->
                <div v-if="viewMode === 'grid'" class="mt-15">
                    <div
                        class="grid w-full grid-cols-1 gap-[30px] md:grid-cols-2 lg:grid-cols-3"
                    >
                        <ProductCard
                            v-for="product in products"
                            :key="product.id"
                            :product="product"
                        />
                    </div>
                    <div v-if="totalProducts?.links" class="flex justify-center">
                        <ProductPagination :links="totalProducts.links" />
                    </div>
                </div>
                <div v-else>
                    <div class="h-screen w-full bg-red-600"></div>
                </div>
            </div>
        </div>
    </section>
</template>
