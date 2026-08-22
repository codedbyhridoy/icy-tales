<!-- StaticCategoryCard.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
import { Card, CardContent } from '@/Components/ui/card';
import { ArrowRight } from 'lucide-vue-next';

defineProps({
    category: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <Card
        class="group relative aspect-[4/5] w-full cursor-pointer overflow-hidden rounded-[20px] border-0 shadow-sm"
    >
        <!-- Background Image Container -->
        <CardContent class="h-full w-full p-0">
            <img
                :src="`/storage/${category.image}`"
                :alt="category.name"
                class="h-full w-full object-cover rounded-[20px] transition-transform duration-300 group-hover:scale-105"
            />
        </CardContent>

        <!-- Floating Bottom Overlay Bar -->
        <Link
            :href="route('frontend.categories.show', category.slug)"
            class="absolute right-2.5 bottom-3.5 left-2.5 flex items-center justify-between rounded-xl bg-white/95 p-3 shadow-md backdrop-blur-sm"
        >
            <span
                class="text-sm font-bold tracking-tight text-charcoal sm:text-base lg:text-[22px]"
            >
                {{ category.name }}
            </span>
            <button
                class="flex h-12 w-12 items-center justify-center rounded-full bg-rose-aura text-white transition-transform duration-200 group-hover:translate-x-0.5 hover:bg-[#e62274]"
            >
                <ArrowRight class="text-sm stroke-[3]" />
            </button>
        </Link>
    </Card>
</template>
