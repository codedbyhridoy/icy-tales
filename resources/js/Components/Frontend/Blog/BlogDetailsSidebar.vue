<script>
import {
    Search,
    Facebook,
    Linkedin,
    Instagram,
    Youtube
} from 'lucide-vue-next';

const categories = [
    {
        id: 1,
        label: 'Ice Cream',
    },
    {
        id: 1,
        label: 'Vanila Ice-Cream',
    },
    {
        id: 1,
        label: 'Coconut Ice-Cream',
    },
    {
        id: 1,
        label: 'Vanila Milkshake',
    },
];
</script>

<template>
    <div class="col-span-4 w-full p-5">
        <!-- Searchbar for news -->
        <div
            class="sidebar-scroll sticky top-10 max-h-[calc(100vh-40px)] space-y-7.5 overflow-y-auto"
        >
            <div
                class="space-y-5.5 px-6 py-8.5 shadow-[0_0_12px_rgba(0,0,0,0.08)]"
            >
                <h2
                    class="font-archivo text-charcoal text-center text-2xl font-semibold"
                >
                    Search News
                </h2>
                <form>
                    <div
                        class="flex items-center border-t border-black/20 pt-[30px]"
                    >
                        <input
                            type="text"
                            class="h-[41px] w-full rounded-tl-full rounded-bl-full border-t border-b border-l border-black/15 px-5 outline-0"
                        />
                        <button
                            class="bg-rose-aura flex h-10.5 w-12.5 items-center justify-center rounded-tr-full rounded-br-full text-white"
                        >
                            <Search class="size-5" />
                        </button>
                    </div>
                </form>
            </div>
            <div
                class="space-y-5.5 px-6 py-8.5 shadow-[0_0_12px_rgba(0,0,0,0.08)]"
            >
                <h2
                    class="font-archivo text-charcoal text-center text-2xl font-semibold"
                >
                    Popular Categories
                </h2>
                <div
                    class="flex items-center border-t border-black/20 pt-[30px]"
                >
                    <ul class="w-full space-y-4.5 text-center">
                        <li v-for="category in categories" :key="category.id">
                            {{ category.label }}
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Follow Us -->
            <div
                class="space-y-5.5 px-6 py-8.5 shadow-[0_0_12px_rgba(0,0,0,0.08)]"
            >
                <h2
                    class="font-archivo text-charcoal text-center text-2xl font-semibold"
                >
                    Follow Us
                </h2>
                <div
                    class="flex items-center justify-center gap-5 border-t border-black/20 pt-[30px]"
                >
                    <div
                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                    >
                        <Linkedin class="fill-white text-lg text-white" />
                    </div>
                    <div
                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                    >
                        <Facebook class="fill-white text-lg text-white" />
                    </div>
                    <div
                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                    >
                        <Instagram class="text-lg text-white" />
                    </div>
                    <div
                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                    >
                        <Youtube class="fill-white text-lg text-white" />
                    </div>
                </div>
            </div>

            <!-- Feeds -->
            <div
                class="space-y-5.5 px-6 py-8.5 shadow-[0_0_12px_rgba(0,0,0,0.08)]"
            >
                <h2
                    class="font-archivo text-charcoal text-center text-2xl font-semibold"
                >
                    Feeds
                </h2>
                <div class="border-t border-black/20 pt-[30px]">
                    <div
                        v-for="i in 10"
                        :key="i"
                        class="flex items-center gap-2.5 border-b border-black/20 pb-[30px]"
                    >
                        <div calss="max-w-[108px] h-[93px]">
                            <img
                                src="https://www.foodandwine.com/thmb/QnTrAIt3aY1g4ToQEk-jULmKMsQ=/1500x0/filters:no_upscale():max_bytes(150000):strip_icc()/vanilla-ice-cream-FT-RECIPE0324-cebca493f53c4431a0049ea65bfb4796.jpg"
                                alt=""
                                class="h-[93px] max-w-[108px] rounded-[10px] object-cover"
                            />
                        </div>
                        <h3 class="text-oxblood font-archivo text-base">
                            Why You Need Virtual Assistant for Your Company
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
