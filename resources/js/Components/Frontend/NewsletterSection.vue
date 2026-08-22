<script setup>
import { usePage, useForm } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';

const page = usePage();

const props = defineProps({
    source: String,
});

const form = useForm({
    email: '',
    source: props.source,
    privacy_policy: false
});

const submit = () => {
    form.post(route('newsletter.store'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success(page.props.flash.success);
        },
    });
};
</script>

<template>
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
            <p class="mx-auto mt-5 max-w-xl text-sm text-gray-500 sm:text-base">
                Get 10% off your next order and stay updated with our latest
                offers.
            </p>

            <!-- Form -->
            <form
                @submit.prevent="submit"
            >
                <div
                    class="mx-auto mt-8 flex max-w-2xl flex-col gap-4 sm:flex-row sm:items-center"
                >
                    <input
                        type="email"
                        placeholder="Enter Your Email Address"
                        class="h-14 w-full rounded-full border border-gray-200 bg-white px-6 text-sm transition outline-none focus:border-pink-400 focus:ring-2 focus:ring-pink-100"
                        v-model="form.email"
                    />

                    <button
                        type="submit"
                        class="h-14 min-w-[180px] rounded-full bg-pink-500 px-8 font-medium text-white shadow-lg shadow-pink-200 transition hover:bg-pink-600"
                    >
                        Subscribe →
                    </button>
                </div>

                <!-- Privacy -->
                <div
                    class="mt-5 flex items-center justify-center gap-2 text-xs text-gray-500 sm:text-sm"
                >
                    <input type="checkbox" class="h-4 w-4 rounded" v-model="form.privacy_policy" />

                    <span>
                        I agree to the
                        <a href="#" class="text-pink-500 hover:underline">
                            Privacy Policy
                        </a>
                    </span>
                </div>
            </form>
        </div>
    </div>
</template>
