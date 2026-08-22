<script setup>
import Carousel from '@/Components/Frontend/Carousel/Carousel.vue';
import TeamMemberCard from '@/Components/Frontend/TeamMemberCard.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
defineOptions({ layout: FrontendLayout });

import { computed } from 'vue';

const props = defineProps({
    members: {
        type: Array,
        required: true,
    },
    statistics: {
        type: Array,
        required: true,
    },
});

const hasMembers = computed(() => props.members?.length > 0);
</script>

<template>
    <div class="bg-white">
        <!-- Hero -->

        <!-- Story -->
        <section class="py-24">
            <div
                class="mx-auto grid max-w-[1240px] items-center gap-16 px-4 lg:grid-cols-2"
            >
                <div class="aspect-square w-full">
                    <img
                        src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1000"
                        class="h-full w-full rounded-full object-cover"
                    />
                </div>

                <div>
                    <span
                        class="rounded-full bg-pink-100 px-5 py-2 text-sm font-semibold text-pink-600"
                    >
                        OUR STORY
                    </span>

                    <h2
                        class="mt-6 font-berkshire text-[60px] leading-tight font-bold text-gray-900"
                    >
                        Our Journey Began With a Simple Dream
                    </h2>

                    <div class="mt-6 space-y-3">
                        <p class="leading-8 text-gray-600">
                            Our goal is to make the best ice cream using only
                            the finest, natural ingredients. From rich, creamy
                            classics to adventurous new creations, every flavor
                            is meticulously crafted in-house to ensure the
                            highest quality and freshness.
                        </p>
                        <p class="leading-8 text-gray-600">
                            We take pride in offering a diverse range of
                            options, including dairy-free, vegan, and
                            gluten-free choices, so everyone can find their
                            perfect scoop.
                        </p>
                    </div>

                    <button
                        class="mt-8 rounded-full bg-pink-500 px-8 py-3 font-semibold text-white transition hover:bg-pink-600"
                    >
                        Learn More
                    </button>
                </div>
            </div>
        </section>

        <!-- Mission -->
        <section class="bg-purple-700 py-24 text-white">
            <div
                class="mx-auto grid max-w-7xl items-center gap-16 px-4 lg:grid-cols-2"
            >
                <div>
                    <span
                        class="rounded-full bg-white/20 px-5 py-2 text-sm font-semibold"
                    >
                        OUR MISSION
                    </span>

                    <h2 class="mt-6 text-4xl font-bold">
                        Our Mission Is To Create Moments
                    </h2>

                    <p class="mt-6 leading-8 text-purple-100">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Eaque inventore corporis voluptatem placeat consequatur
                        asperiores aliquam odio perferendis.
                    </p>

                    <button
                        class="mt-8 rounded-full bg-pink-500 px-8 py-3 font-semibold text-white"
                    >
                        Learn More
                    </button>
                </div>

                <div>
                    <img
                        src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?w=1000"
                        class="h-[450px] w-full rounded-[40px] object-cover"
                    />
                </div>
            </div>
        </section>

        <!-- Stats -->
        <section v-if="statistics.length > 0" class="py-24">
            <div class="mx-auto max-w-7xl px-4">
                <div class="text-center">
                    <h2 class="text-4xl font-bold">Our Statistics</h2>
                </div>

                <div class="mt-16 grid gap-6 md:grid-cols-4">
                    <div
                        v-for="stat in statistics"
                        :key="stat.id"
                        class="hover:border-rose-aura rounded-3xl border-b-5 border-transparent p-8 text-center shadow-sm transition duration-300 hover:scale-105 hover:border-b-5 hover:shadow-lg"
                    >
                        <h3 class="text-4xl font-bold text-pink-500">
                            {{ stat.value }}{{ stat.suffix }}
                        </h3>
                        <p class="mt-3 text-gray-600">
                            {{ stat.title }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Team -->
        <section
            class="bg-gradient-to-r from-pink-50 via-purple-50 to-cyan-50 py-24"
        >
            <div class="mx-auto max-w-[1140px] px-4">
                <div class="text-center">
                    <h2 class="text-4xl font-bold">Our Team Members</h2>
                </div>

                <div v-if="hasMembers" class="mt-12">
                    <Carousel
                        :items="members"
                        :items-per-view="{
                            desktop: 3,
                            tablet: 3,
                            mobile: 1,
                        }"
                    >
                        <template #item="{ item }">
                            <TeamMemberCard :member="item" />
                        </template>
                    </Carousel>
                </div>
                <div v-else class="mt-12 py-12 text-center">
                    <p class="text-lg text-gray-500">
                        No team members available yet.
                    </p>
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="py-24">
            <div class="mx-auto max-w-3xl px-4 text-center">
                <h2 class="text-4xl font-bold">Sign Up For Exclusive Deals</h2>

                <p class="mt-4 text-gray-500">
                    Get updates and promotions directly in your inbox.
                </p>

                <div
                    class="mx-auto mt-10 flex max-w-xl flex-col gap-4 sm:flex-row"
                >
                    <input
                        type="email"
                        placeholder="Enter your email"
                        class="flex-1 rounded-full border px-6 py-4 outline-none"
                    />

                    <button
                        class="rounded-full bg-pink-500 px-8 py-4 font-semibold text-white"
                    >
                        Subscribe
                    </button>
                </div>
            </div>
        </section>
    </div>
</template>
