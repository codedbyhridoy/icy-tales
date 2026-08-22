<script setup>
import { Link } from '@inertiajs/vue3';
import { ArrowLeft } from 'lucide-vue-next';
</script>

<template>
    <section
        class="bg-linear-aurora flex h-screen w-full items-center justify-center"
    >
        <div class="text-center mx-auto max-w-[740px] p-10">
            <h1 class="text-oxblood font-archivo text-[140px]">404</h1>
            <h3 class="font-archivo md:mt-8 lg:mt-11">
                Sorry! The Page Not Found.
            </h3>
            <p class="paragraph-secondary mt-4">
                Lorem ipsum dolor sit amet, consec tse tur adipiscing elit, sed
                do eiusmod tempor incididunt ut labore
            </p>
            <Link
                :href="route('home')"
                class="btn-aura-lg mt-6 md:mt-10 lg:mt-12.5"
            >
                <ArrowLeft />
                Back to Home
            </Link>
        </div>
    </section>
</template>
