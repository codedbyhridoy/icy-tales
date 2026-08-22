<script setup>
import { Link } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';
defineOptions({ layout: FrontendLayout });
</script>

<template>
    <div
        class="bg-linear-aurora h-auto w-full px-[30px] py-[100px] md:py-[150px] lg:py-[200px]"
    >
        <div
            class="mx-auto flex max-w-[750px] flex-col items-center justify-center"
        >
            <div class="h-auto max-w-[165px] mb-5 md:mb-8 lg:mb-10.5">
                <img
                    src="/assets/images/thankyou-emoji.png"
                    alt="Thank You Emoji"
                    class="h-auto w-full object-cover"
                />
            </div>
            <h1>Thank You</h1>
            <p class="paragraph-secondary text-center mt-6 md:mt-8 lg:mt-11">
                We're delighted you've decided to treat yourself to our
                delectable ice creams. Your order has been received and is now
                being prepared with care.
            </p>
            <Link :href="route('home')" class="btn-aura-lg mt-6 md:mt-10 lg:mt-12.5">
                <ArrowLeft />
                Back to Home
            </Link>
        </div>
    </div>
</template>
