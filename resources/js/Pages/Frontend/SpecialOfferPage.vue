<script setup>
import CarouselPagination from '@/Components/Frontend/CarouselPagination.vue';
import ProductCard from '@/Components/Frontend/ProductCard.vue';
import {
    Carousel,
    CarouselContent,
    CarouselItem,
} from '@/Components/ui/carousel';
import { useCarouselDots } from '@/Composables/useCarouselDots';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { ArrowRight } from 'lucide-vue-next';

const { api, selectedIndex, scrollSnaps, cardsPerPage, setApi } =
    useCarouselDots();

defineOptions({ layout: FrontendLayout });
</script>

<template>
    <!-- Hero Section -->
    <section class="bg-linear-rainbow w-full px-5 pt-8">
        <div
            class="mx-auto flex h-[300px] max-w-7xl flex-col items-center justify-center"
        >
            <h1
                class="font-serif text-5xl font-bold text-[#2D1E1E] md:text-6xl"
            >
                Special Offers
            </h1>

            <div
                class="mt-5 flex items-center gap-2 rounded-full bg-white px-4 py-2 shadow-sm"
            >
                <span class="text-xs text-pink-500">Home</span>
                <span class="text-xs text-gray-400">/</span>
                <span class="text-xs text-gray-700">Special Offers</span>
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

            <!-- Main Carousel -->
            <Carousel
                class="mt-12 w-full cursor-grab active:cursor-grabbing"
                :opts="{
                    align: 'start',
                    loop: false,
                    slidesToScroll: 1 /* Changed to 1 for smoother pagination tracking */,
                    watchDrag: true,
                }"
                @init-api="setApi"
            >
                <CarouselContent>
                    <CarouselItem
                        v-for="n in 16"
                        :key="n"
                        class="basis-full select-none sm:basis-1/2 md:basis-1/3 lg:basis-1/4"
                    >
                        <ProductCard class="mx-auto max-w-[255px]" />
                    </CarouselItem>
                </CarouselContent>
            </Carousel>

            <!-- Connected Pagination Component -->
            <CarouselPagination
                :api="api"
                :scroll-snaps="scrollSnaps"
                :selected-index="selectedIndex"
                :cards-per-page="cardsPerPage"
            />
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

    <section
        class="overflow-hidden bg-linear-rainbow py-20 lg:py-28"
    >
        <div class="container mx-auto max-w-[1140px] px-6">
            <!-- Heading -->
            <div class="text-center">
                <h2 class="font-berkshire text-charcoal text-4xl lg:text-5xl">
                    Our
                    <span class="text-rose-aura"> Commitments </span>
                    to You
                </h2>

                <p class="paragraph-secondary mx-auto mt-3 max-w-md">
                    Exceeding expectations with every promise we make.
                </p>
            </div>

            <!-- Content -->
            <div
                class="mt-16 grid items-center gap-10 lg:grid-cols-[240px_1fr_240px]"
            >
                <!-- Left -->
                <div class="space-y-6">
                    <div class="commitment-card">
                        <div class="commitment-icon bg-rose-aura">
                            <Truck class="size-5 text-white" />
                        </div>

                        <h4>Free Shipping</h4>

                        <p>
                            Enjoy free shipping on all orders within our
                            country.
                        </p>
                    </div>

                    <div class="commitment-card">
                        <div class="commitment-icon bg-purple-rich">
                            <ShieldCheck class="size-5 text-white" />
                        </div>

                        <h4>Unique Packaging</h4>

                        <p>
                            Experience our commitment to uniqueness with every
                            order.
                        </p>
                    </div>
                </div>

                <!-- Center Image -->
                <div class="relative flex justify-center">
                    <div
                        class="absolute inset-0 m-auto h-[320px] w-[320px] rounded-full bg-[#F3E8DF]"
                    />

                    <img
                        src="/assets/images/icecream.png"
                        alt="Ice Cream"
                        class="relative z-10 w-[280px] lg:w-[360px]"
                    />
                </div>

                <!-- Right -->
                <div class="space-y-6">
                    <div class="commitment-card">
                        <div class="commitment-icon bg-purple-rich">
                            <BadgeDollarSign class="size-5 text-white" />
                        </div>

                        <h4>100% Money Back</h4>

                        <p>
                            Rest assured with our money back guarantee at any
                            time.
                        </p>
                    </div>

                    <div class="commitment-card">
                        <div class="commitment-icon bg-rose-aura">
                            <PackageCheck class="size-5 text-white" />
                        </div>

                        <h4>Fast Delivery</h4>

                        <p>
                            Experience swift and efficiently reliable delivery.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-white py-16 md:py-24">
        <div class="container mx-auto px-4">
            <!-- Decorative Shapes -->
            <div class="absolute top-[55%] left-[15%] hidden md:block">
                <div class="relative">
                    <div
                        class="h-7 w-7 rounded-full border-2 border-purple-500"
                    ></div>
                    <div
                        class="absolute top-2 left-4 h-7 w-7 rounded-full bg-pink-500 opacity-90"
                    ></div>
                </div>
            </div>

            <div class="absolute top-[35%] right-[20%] hidden md:block">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none">
                    <path d="M3 21L21 3L17 21L12 15L3 21Z" fill="#ec4899" />
                    <path d="M3 21L21 3" stroke="#7c3aed" stroke-width="1.5" />
                </svg>
            </div>

            <!-- Content -->
            <div class="mx-auto max-w-4xl text-center">
                <!-- Heading -->
                <h2
                    class="font-berkshire text-3xl leading-tight font-bold text-gray-900 sm:text-4xl lg:text-5xl"
                >
                    Sign up For
                    <span class="text-rose-aura font-berkshire">
                        Exclusive Deals
                    </span>
                    <br />
                    and Updates
                </h2>

                <!-- Description -->
                <p
                    class="mx-auto mt-5 max-w-xl text-sm text-gray-500 sm:text-base"
                >
                    Get 10% off your next order and stay updated with our latest
                    offers.
                </p>

                <!-- Form -->
                <form
                    class="mx-auto mt-8 flex max-w-2xl flex-col gap-4 sm:flex-row sm:items-center"
                >
                    <input
                        type="email"
                        placeholder="Enter Your Email Address"
                        class="h-14 w-full rounded-full border border-gray-200 bg-white px-6 text-sm transition outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100"
                    />

                    <button
                        type="submit"
                        class="h-14 min-w-[180px] rounded-full bg-pink-500 px-8 font-medium text-white shadow-lg shadow-pink-200 transition hover:bg-pink-600"
                    >
                        Subscribe →
                    </button>
                </form>

                <!-- Privacy -->
                <div
                    class="mt-5 flex items-center justify-center gap-2 text-xs text-gray-500 sm:text-sm"
                >
                    <input type="checkbox" class="h-4 w-4 rounded" />

                    <span>
                        I agree to the
                        <a href="#" class="text-pink-500 hover:underline">
                            Privacy Policy
                        </a>
                    </span>
                </div>
            </div>
        </div>
    </section>
    w
</template>
