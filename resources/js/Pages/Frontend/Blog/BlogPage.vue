<script setup>
import BlogSidebar from '@/Components/Frontend/Blog/BlogSidebar.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { dateFormat } from '@/lib/date-eu';
import { Link } from '@inertiajs/vue3';
import { Calendar, Tag, User } from 'lucide-vue-next';
import { computed, ref } from 'vue';
defineOptions({ layout: FrontendLayout });

const props = defineProps({
    posts: {
        type: Object,
        required: true,
    },
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
        required: true,
    },
});

console.log(props.categories);

const posts = computed(() => props.posts.data);
</script>

<template>
    <section class="w-full px-[30px] py-20">
        <div class="mx-auto grid max-w-[1140px] grid-cols-12 gap-[30px]">
            <div class="col-span-9 grid grid-cols-3 gap-[30px]">
                <div
                    v-for="post in posts"
                    :key="post.id"
                    class="relative max-h-[380px] w-full rounded-[20px] shadow-[inset_0px_2px_73px_2px_rgba(0,0,0,0.05)] transition-all duration-300 hover:scale-105 hover:shadow-md"
                >
                    <img
                        :src="`/storage/${post.featured_image}`"
                        :alt="post.category.name"
                        class="aspect-245/172 h-auto w-full rounded-tl-[20px] rounded-tr-[20px] object-cover"
                    />
                    <div class="px-2.5 py-5">
                        <div
                            class="mb-4 flex items-center justify-start gap-2.5"
                        >
                            <div class="flex items-center gap-1">
                                <User
                                    class="text-rose-aura fill-rose-aura -mt-0.5 size-3.5"
                                />
                                <p
                                    class="text-gray-neutral text-xs leading-tight"
                                >
                                    By:{{ post.author?.first_name }}
                                    {{ post.author?.last_name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <Tag
                                    class="text-rose-aura fill-rose-aura size-3.5"
                                />
                                <p class="text-gray-neutral text-xs">
                                    {{ post.tags[0]?.name ?? 'No Tag' }}
                                </p>
                            </div>
                        </div>
                        <p
                            class="text-gray-neutral line-clamp-3 h-[55px] text-sm"
                        >
                            {{ post.excerpt }}
                        </p>
                    </div>
                    <div
                        class="flex justify-between gap-4 border-t border-black/15 px-2.5 py-4"
                    >
                        <div class="flex items-center gap-1">
                            <Calendar class="text-rose-aura -mt-0.5 size-3.5" />
                            <p class="text-gray-neutral text-xs leading-tight">
                                {{ dateFormat(post.published_at) }}
                            </p>
                        </div>
                        <Link
                            :href="route('blog.show', post.slug)"
                            class="bg-rose-aura rounded-full px-3 py-2.5 text-sm text-white transition duration-300 hover:opacity-90"
                        >
                            Read More
                        </Link>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="col-span-3 w-full p-5">
                <BlogSidebar 
                    :categories="categories" 
                    :featuredPosts="featuredPosts" 
                    :latestPosts="latestPosts" 
                    :filters="filters" 
                />
            </div>
        </div>
    </section>
</template>
