<script setup>
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/Components/ui/tabs';
import { timeAgo } from '@/lib/date-eu';
import { router, Link } from '@inertiajs/vue3';
import { Clock, Search, Tag } from 'lucide-vue-next';
import { ref, reactive } from 'vue';

const props = defineProps({
    categories: {
        type: Array,
        required: true
    },
    featuredPosts: {
        type: Object,
        required: true,
    },
    latestPosts: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: true
    }
});

const filters = reactive({
    search: props.filters.search ?? '',
    category: props.filters.category_id ?? ''
})



const filterPosts = () => {
    router.get(
        route('blog.index'),
        filters,
        {
            preserveScroll: true,
            replace: true,
        },
    );
};

const handleCategory = (id) => {
    filters.category = id;
    filterPosts();
}
</script>

<template>
    <div class="w-full">
        <form @submit.prevent="filterPosts">
            <div class="flex items-center border-b border-black/30 pb-[30px]">
                <input
                    type="text"
                    class="border-gray-neutral h-[41px] w-full rounded-tl-full rounded-bl-full border-t border-b border-l px-5 outline-0"
                    v-model="filters.search"
                />
                <button
                    type="submit"
                    class="bg-rose-aura flex h-10.5 w-12.5 items-center justify-center rounded-tr-full rounded-br-full text-white"
                >
                    <Search class="size-5" />
                </button>
            </div>
        </form>
        <div class="mt-[30px]">
            <Tabs default-value="featured" class="w-full">
                <!-- Header -->
                <TabsList
                    class="mb-[30px] h-auto w-full justify-start rounded-none border-b bg-transparent p-0"
                >
                    <TabsTrigger
                        value="featured"
                        class="text-charcoal data-[state=active]:border-rose-aura data-[state=active]:text-rose-aura rounded-none border-b-[5px] border-transparent px-1.5 pt-2 pb-2.5 text-sm font-semibold data-[state=active]:shadow-none"
                    >
                        Featured
                    </TabsTrigger>

                    <TabsTrigger
                        value="recent"
                        class="text-charcoal data-[state=active]:border-rose-aura data-[state=active]:text-rose-aura rounded-none border-b-[5px] border-transparent px-1.5 pt-2 pb-2.5 text-sm font-semibold data-[state=active]:shadow-none"
                    >
                        Recent
                    </TabsTrigger>
                </TabsList>

                <TabsContent value="featured">
                    <div v-for="post in featuredPosts" :key="post.id">
                        <Link
                            class="mb-2.5 flex items-center gap-2.5 border-b border-black/30 pb-2.5"
                        >
                            <div class="aspect-64/46 w-full max-w-16">
                                <img
                                    :src="`storage/${post.featured_image}`"
                                    :alt="post.category?.name"
                                    class="h-full w-full rounded-[8px] object-cover"
                                />
                            </div>
                            <div>
                                <h4
                                    class="text-oxblood font-archivo line-clamp-2 text-sm font-medium"
                                >
                                    {{ post.title }}
                                </h4>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <Clock class="size-2.5" />
                                        <p
                                            class="text-gray-neutral text-[10px]"
                                        >
                                            {{ timeAgo(post.published_at) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <Tag class="size-2.5" />
                                        <p
                                            class="text-gray-neutral text-[10px]"
                                        >
                                            {{ post.category?.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </TabsContent>

                <TabsContent value="recent">
                    <div v-for="post in latestPosts" :key="post.id">
                        <Link
                            class="mb-2.5 flex items-center gap-2.5 border-b border-black/30 pb-2.5"
                        >
                            <div class="aspect-64/46 w-full max-w-16">
                                <img
                                    :src="`storage/${post.featured_image}`"
                                    :alt="post.category?.name"
                                    class="h-full w-full rounded-[8px] object-cover"
                                />
                            </div>
                            <div>
                                <h4
                                    class="text-oxblood font-archivo line-clamp-2 text-sm font-medium"
                                >
                                    {{ post.title }}
                                </h4>
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <Clock class="size-2.5" />
                                        <p
                                            class="text-gray-neutral text-[10px]"
                                        >
                                            {{ timeAgo(post.published_at) }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <Tag class="size-2.5" />
                                        <p
                                            class="text-gray-neutral text-[10px]"
                                        >
                                            {{ post.category?.name }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </Link>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
        <div class="mt-10 pt-5 border-t border-black/15">
            <h2 class="text-lg font-medium leading-6 font-archivo uppercase">
                Categories
            </h2>
            <div class="mt-5">
                <div v-for="category in categories" :key="category.id" class="pl-7 pt-0.5 pb-3.5 border-b border-black/15">
                    <button 
                        @click="handleCategory(category.id)" 
                        class="w-full flex justify-between hover:underline underline-offset-2 transition-all duration-300 cursor-pointer"
                    >
                        <span class="text-oxblood text-sm font-medium">
                            {{ category.name }}
                        </span>
                        <span class="text-rose-aura text-sm font-medium">
                            ({{ category.posts_count }})
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
