<script setup>
import { Link } from '@inertiajs/vue3'
import { ChevronLeft, ChevronRight } from 'lucide-vue-next'

defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links.length > 3" class="mt-8 flex items-center gap-2">

        <!-- Previous -->
        <component
            :is="links[0].url ? Link : 'button'"
            :href="links[0].url"
            class="flex h-[45px] w-[45px] items-center justify-center text-gray-medium rounded-full border border-gray-sort transition-colors"
            :class="{
                'hover:bg-gray-50': links[0].url,
                'cursor-not-allowed opacity-40': !links[0].url,
            }"
        >
            <ChevronLeft class="h-4 w-4" />
        </component>

        <!-- Page Numbers -->
        <template
            v-for="(link, index) in links.slice(1, -1)"
            :key="index"
        >
            <Link
                v-if="link.url"
                :href="link.url"
                class="flex h-[45px] w-[45px] items-center justify-center text-gray-medium rounded-full border border-gray-soft transition-colors"
                :class="{
                    'bg-rose-aura border-rose-aura text-white': link.active,
                    'hover:bg-gray-50': !link.active,
                }"
            >
                {{ link.label }}
            </Link>

            <span
                v-else
                class="flex h-10 w-10 items-center justify-center"
            >
                ...
            </span>
        </template>

        <!-- Next -->
        <component
            :is="links[links.length - 1].url ? Link : 'button'"
            :href="links[links.length - 1].url"
            class="flex h-[45px] w-[45px] items-center justify-center text-gray-medium rounded-full border border-gray-soft transition-colors"
            :class="{
                'hover:bg-gray-50': links[links.length - 1].url,
                'cursor-not-allowed opacity-40': !links[links.length - 1].url,
            }"
        >
            <ChevronRight class="h-4 w-4" />
        </component>

    </div>
</template>