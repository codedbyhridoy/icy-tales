<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { ArrowRight, Package, ShoppingBag } from 'lucide-vue-next';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import ProductCard from '@/Components/Frontend/ProductCard.vue';
import CategoryCard from '@/Components/Frontend/CategoryCard.vue';
import Carousel from '@/Components/Frontend/Carousel/Carousel.vue';
import NewsletterSection from '@/Components/Frontend/NewsletterSection.vue';

defineOptions({ layout: FrontendLayout });

const props = defineProps({
    categories: {
        type: Object,
        required: true,
    },
    featured_products: {
        type: Object,
        required: true,
    },
});

const featuredProducts = computed(() => props.featured_products.data);
const hasFeaturedProducts = computed(
    () => props.featured_products?.data?.length > 0,
);
</script>

<template>
    <!-- Hero Section -->
    <section class="bg-linear-aurora relative w-full px-[30px]">
        <div
            class="mx-auto flex max-w-[1440px] flex-col items-center justify-between gap-20 pt-10 pb-[215px] lg:flex-row lg:gap-10 lg:pt-0"
        >
            <div class="space-y-5">
                <div class="flex items-center gap-2">
                    <div class="bg-purple-rich h-[3px] w-16"></div>
                    <h4>Welcome to The</h4>
                </div>
                <h1>
                    Discover
                    <span class="text-rose-aura font-berkshire">Sweet</span>
                    Delights!
                </h1>
                <p
                    class="text-oxblood text-[16px] leading-relaxed sm:text-[18px] md:text-[20px] lg:text-[22px]"
                >
                    Relish the timeless taste of handcrafted ice cream, made
                    with passion and the finest ingredients.
                </p>
                <button class="btn-rich-lg">
                    Browse Our Classic Flavor
                    <ArrowRight class="h-3 w-3" />
                </button>
            </div>
            <div class="relative h-auto w-full max-w-[728px]">
                <div class="aspect-square rounded-full">
                    <img
                        src="/assets/images/gradient_circle.svg"
                        class="h-auto w-full"
                        alt="Hero Circle"
                    />
                </div>
                <div
                    class="absolute top-0 left-1/2 h-auto w-full -translate-x-1/2"
                >
                    <img
                        src="/assets/images/hero_figure.svg"
                        class="ml-[50px] h-auto w-full object-cover"
                        alt="Hero Figure"
                    />
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 z-50 h-auto w-full">
            <img
                src="/assets/images/wavy_banner.png"
                alt="Wavy Banner"
                class="h-auto w-full object-contain"
            />
        </div>
    </section>

    <!-- Explore Menu Section -->
    <section class="relative overflow-hidden px-[30px]">
        <div class="relative mx-auto mt-15 max-w-[1140px]">
            <div
                class="grid grid-cols-1 items-center gap-20 lg:grid-cols-2 lg:gap-12"
            >
                <!-- Left -->
                <div class="order-2 w-full lg:order-1">
                    <div
                        class="relative mx-auto aspect-square max-w-[578px] rounded-full bg-[#F6F3F9]"
                    >
                        <div
                            class="absolute top-1/2 left-1/2 h-auto w-full -translate-x-1/2 -translate-y-1/2"
                        >
                            <img
                                src="/assets/images/relive-girl.png"
                                alt=""
                                class="z-10 -mt-15 h-auto w-full"
                            />
                        </div>
                        <div class="absolute top-12 right-0 max-w-[84px]">
                            <img
                                src="/assets/images/04.png"
                                alt="Circle"
                                class="h-auto w-full"
                            />
                        </div>
                    </div>
                </div>

                <!-- Right -->
                <div class="order-1 w-full lg:order-2">
                    <h3>
                        Relive the Sweet Memories of Classic
                        <span class="text-rose-aura font-berkshire"
                            >Ice Creams</span
                        >
                    </h3>
                    <p class="paragraph-secondary mt-[27px]">
                        From rich chocolate fudge to creamy vanilla sundaes,
                        discover our menu of classic ice cream creations.
                    </p>
                    <Link
                        class="btn-aura-lg mt-9 inline-flex"
                    >
                        Explore Our Menu
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                </div>
            </div>
            <div
                class="absolute -right-[20px] bottom-0 w-[100px] max-w-[224px] md:right-[-10px] lg:w-[224px] xl:-right-56"
            >
                <img
                    src="/assets/images/02.png"
                    alt="Circle"
                    class="h-auto w-full"
                />
            </div>
            <div class="absolute top-[60%] left-0 max-w-[64px] xl:-left-[64px]">
                <img
                    src="/assets/images/03.png"
                    alt="Circle"
                    class="h-auto w-full"
                />
            </div>
            <div
                class="absolute top-[30%] right-0 max-w-[52px] xl:right-[-52px]"
            >
                <img
                    src="/assets/images/05.png"
                    alt="Circle"
                    class="h-auto w-full"
                />
            </div>
        </div>
    </section>

    <!-- Favorites Section -->
    <section
        class="bg-linear-rainbow relative z-20 px-[30px] py-16 md:py-[84px] lg:py-[142px]"
    >
        <div class="mx-auto max-w-[1140px]">
            <div class="space-y-4 text-center">
                <h3>
                    Our
                    <span class="text-rose-aura font-berkshire">Classic</span>
                    Favorites
                </h3>
                <p class="paragraph-secondary">
                    Check out our top products that our customers love.
                </p>
            </div>

            <div v-if="hasFeaturedProducts" class="mt-12">
                <Carousel
                    :items="featuredProducts"
                    :items-per-view="{
                        desktop: 4,
                        tablet: 3,
                        mobile: 1,
                    }"
                >
                    <template #item="{ item }">
                        <ProductCard :product="item" class="bg-white" />
                    </template>
                </Carousel>
            </div>

            <!-- Empty State Message -->
            <div v-else class="mt-12">
                <div
                    class="flex flex-col items-center justify-center rounded-2xl p-12"
                >
                    <Package class="h-16 w-16 text-gray-400" />
                    <h4 class="mt-4 text-xl font-semibold text-gray-700">
                        No Products Yet
                    </h4>
                    <p class="mt-2 text-gray-500">
                        Our classic favorites are coming soon. Stay tuned!
                    </p>
                </div>
            </div>
        </div>

        <!-- Background Images -->
        <div
            class="absolute top-[142px] left-0 -z-10 h-[100px] w-[60px] bg-[url('/assets/images/classic-leftimage.png')] bg-cover bg-center sm:h-[140px] sm:w-20 md:h-[220px] md:w-[120px] lg:h-[430px] lg:w-[280px]"
        ></div>

        <div
            class="absolute right-0 bottom-[90px] -z-10 h-[120px] w-[45px] bg-[url('/assets/images/classic-rightimage.png')] bg-cover bg-center sm:h-40 sm:w-[70px] md:h-[260px] md:w-[100px] lg:h-[500px] lg:w-[212px]"
        ></div>
    </section>

    <!-- Explore Category Section -->
    <section class="w-full px-[30px] py-16 md:py-[84px] lg:py-[142px]">
        <div class="mx-auto max-w-[1520px]">
            <div class="space-y-4 text-center">
                <h3>
                    Explore Our
                    <span class="text-rose-aura font-berkshire"
                        >Categories</span
                    >
                </h3>
                <p class="paragraph-secondary">
                    Browse through our different categories to find your
                    favorite ice cream treats.
                </p>
            </div>
            <div
                v-if="categories.length > 0"
                class="mt-12.5 grid grid-cols-1 gap-[30px] sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4"
            >
                <div v-for="category in categories" :key="category.id">
                    <CategoryCard :category="category" />
                </div>
            </div>
            <div v-else class="mt-12">
                <div
                    class="flex flex-col items-center justify-center rounded-2xl p-12"
                >
                    <Package class="h-16 w-16 text-gray-400" />
                    <h4 class="mt-4 text-xl font-semibold text-gray-700">
                        No Categories Yet
                    </h4>
                    <p class="mt-2 text-gray-500">
                        We're adding new categories soon. Check back later!
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section
        class="bg-rose-aura w-full px-[30px] py-16 md:py-[84px] lg:py-[142px]"
    >
        <div class="mx-auto max-w-[1140px]">
            <div class="relativew-full text-center">
                <img src="" alt="" />
                <div class="mx-auto max-w-[635px] text-center">
                    <h2
                        class="font-berkshire text-5xl leading-tight font-normal text-white sm:text-6xl md:text-7xl lg:text-8xl xl:text-[112px] xl:leading-[108px]"
                    >
                        Limited Time Offer
                    </h2>
                </div>
                <p class="mt-10 text-[30px] text-white">
                    Get
                    <span class="text-amber-warm font-archivo">20% Off</span>
                    All Vegan Ice Creams!
                </p>
                <button class="btn-rich-lg mx-auto mt-[35px]">
                    Get This Deal
                    <ArrowRight class="text-base" />
                </button>
                <p class="mt-6 text-base text-white">
                    Use code: VEGAN20 at checkout.
                </p>
            </div>
        </div>
    </section>

    <!-- Explore Category Section -->
    <section class="w-full px-[30px] py-16 md:py-[84px] lg:py-[142px]">
        <div class="mx-auto max-w-[1140px]">
            <div class="space-y-4 text-center">
                <h3>
                    Our
                    <span class="text-rose-aura font-berkshire">Best</span>
                    Sellers
                </h3>
                <p class="paragraph-secondary">
                    Discover the favorites that keep our customers coming back
                    for more.
                </p>
            </div>

            <div v-if="hasProducts" class="mt-12">
                <Carousel
                    :items="products"
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
            <div v-else class="mt-12">
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
            </div>
        </div>
    </section>

    <section
        class="relative overflow-hidden py-16 md:py-20 lg:py-24"
        style="
            background: linear-gradient(
                90deg,
                #f6eff8 0%,
                #f8f2f2 50%,
                #f0f7f8 100%
            );
        "
    >
        <div class="container mx-auto px-4 sm:px-6">
            <!-- Heading -->
            <div class="mb-12 text-center lg:mb-16">
                <h2
                    class="font-berkshire text-3xl leading-tight font-bold text-gray-900 sm:text-4xl lg:text-5xl"
                >
                    Hear from Our
                    <span class="text-rose-aura font-berkshire">Happy</span>
                    <br />
                    <span class="text-rose-aura font-berkshire">Ice Cream</span>
                    Lovers
                </h2>
            </div>

            <!-- Mobile Avatar -->
            <div class="mb-8 flex justify-center md:hidden">
                <img
                    src="https://i.pravatar.cc/120?img=1"
                    alt="Customer"
                    class="h-20 w-20 rounded-full object-cover shadow-lg"
                />
            </div>

            <!-- Floating Avatars (Desktop / Tablet Only) -->
            <div class="pointer-events-none absolute inset-0 hidden md:block">
                <!-- Left Side -->
                <img
                    src="https://i.pravatar.cc/100?img=1"
                    class="absolute top-[30%] left-[12%] h-14 w-14 rounded-full object-cover shadow-lg lg:h-16 lg:w-16"
                />

                <img
                    src="https://i.pravatar.cc/100?img=2"
                    class="absolute top-[48%] left-[18%] h-12 w-12 rounded-full object-cover shadow-lg"
                />

                <img
                    src="https://i.pravatar.cc/100?img=3"
                    class="absolute top-[60%] left-[10%] h-14 w-14 rounded-full object-cover shadow-lg"
                />

                <img
                    src="https://i.pravatar.cc/100?img=4"
                    class="absolute top-[70%] left-[20%] h-14 w-14 rounded-full object-cover shadow-lg"
                />

                <!-- Right Side -->
                <img
                    src="https://i.pravatar.cc/100?img=5"
                    class="absolute top-[28%] right-[18%] h-14 w-14 rounded-full object-cover shadow-lg lg:h-16 lg:w-16"
                />

                <img
                    src="https://i.pravatar.cc/100?img=6"
                    class="absolute top-[40%] right-[10%] h-14 w-14 rounded-full object-cover shadow-lg"
                />

                <img
                    src="https://i.pravatar.cc/100?img=7"
                    class="absolute top-[50%] right-[20%] h-12 w-12 rounded-full object-cover shadow-lg"
                />

                <img
                    src="https://i.pravatar.cc/100?img=8"
                    class="absolute top-[70%] right-[14%] h-14 w-14 rounded-full object-cover shadow-lg"
                />
            </div>

            <!-- Testimonial Content -->
            <div class="relative mx-auto max-w-3xl px-4 text-center">
                <!-- Decorative Quote -->
                <div
                    class="absolute top-0 left-1/2 -translate-x-1/2 text-[120px] leading-none font-bold text-pink-100 opacity-50 select-none sm:text-[160px] lg:text-[220px]"
                >
                    "
                </div>

                <!-- Testimonial -->
                <p
                    class="relative z-10 mx-auto max-w-2xl text-sm leading-7 text-gray-700 sm:text-base lg:text-lg lg:leading-8"
                >
                    Beatae vitae dicta sunt explicabo. Nemo enim ipsam
                    voluptatem quia voluptas aspernatur aut odit aut fugit, sed
                    quia consequuntur magni dolores eos qui ratione voluptatem
                    sequi nesciunt. Neque porro quisquam est qui dolorem ipsum
                    quia dolor sit amet.
                </p>

                <!-- Customer Info -->
                <div class="mt-10">
                    <h4 class="text-lg font-semibold text-pink-500">
                        Kevin Andrew
                    </h4>

                    <p class="mt-1 text-sm text-gray-500">Happy Customer</p>

                    <!-- Rating -->
                    <div class="mt-4 flex justify-center gap-1">
                        <span
                            v-for="i in 5"
                            :key="i"
                            class="text-lg text-yellow-400"
                        >
                            ★
                        </span>
                    </div>
                </div>

                <!-- Pagination Dots -->
                <div class="mt-8 flex justify-center gap-3">
                    <span class="h-3 w-3 rounded-full bg-pink-500"></span>

                    <span class="h-3 w-3 rounded-full bg-gray-300"></span>

                    <span class="h-3 w-3 rounded-full bg-gray-300"></span>
                </div>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-white py-16 md:py-24">
        <NewsletterSection source="home" />
    </section>

    <!-- Explore Category Section -->
    <section
        class="bg-linear-rainbow w-full px-[30px] py-16 md:py-[84px] lg:py-[142px]"
    >
        <div class="mx-auto max-w-[1520px]">
            <div class="space-y-4 text-center">
                <h3>
                    Follow Us
                    <span class="text-rose-aura font-berkshire"
                        >on Instagram</span
                    >
                </h3>
                <p class="paragraph-secondary">
                    Browse through our different categories to find your
                    favorite ice cream treats.
                </p>
            </div>
            <div
                class="mt-12.5 grid grid-cols-1 gap-[30px] sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5"
            >
                <!-- <ProductsCategoryCard />
                <ProductsCategoryCard />
                <ProductsCategoryCard />
                <ProductsCategoryCard />
                <ProductsCategoryCard /> -->
            </div>
        </div>
    </section>
</template>
