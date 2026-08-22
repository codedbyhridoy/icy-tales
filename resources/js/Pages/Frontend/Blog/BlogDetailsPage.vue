<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
import { formatDate } from '@/lib/utils';
import { Link } from '@inertiajs/vue3';
import {
    Calendar,
    Facebook,
    Instagram,
    Linkedin,
    Quote,
    Search,
    User,
    Youtube,
} from 'lucide-vue-next';
import { computed } from 'vue';
import BlogDetailsSidebar from '@/Components/Frontend/Blog/BlogDetailsSidebar.vue';
defineOptions({ layout: FrontendLayout });

const page = usePage();

const user = computed(() => page.props.auth.user);

const props = defineProps({
    blog: {
        type: Object,
        required: true,
    },
    previous: Object,
    next: Object,
});

const form = useForm({
    content: ''
});

const submit = () => {
    form.post(
        route('blog.comments.store', props.blog),
        {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            },
            onError: (e) => {
                console.log(e);
            }
        }
    )
}

const countComments = computed(() => {
    const total = props.blog.comments?.length ?? 0;

    if (total === 0) {
        return 'No comments on this blog';
    }

    if (total === 1) {
        return '1 Comment';
    }

    return `${total} Comments`;
});

const parsedContent = computed(() => {
    if (!props.blog.content) return [{ type: 'html', content: '' }];

    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = props.blog.content;

    const blocks = [];
    const children = Array.from(tempDiv.childNodes);
    let currentHtml = '';
    let isInQuote = false;

    children.forEach((node) => {
        // Check if it's a horizontal rule (separator)
        if (node.nodeType === 1 && node.tagName === 'HR') {
            // If we have accumulated HTML, push it
            if (currentHtml.trim()) {
                blocks.push({ type: 'html', content: currentHtml });
                currentHtml = '';
            }
            // Add a separator marker
            blocks.push({ type: 'separator' });
            return;
        }

        // Check for blockquote
        if (node.nodeType === 1 && node.tagName === 'BLOCKQUOTE') {
            if (currentHtml.trim()) {
                blocks.push({ type: 'html', content: currentHtml });
                currentHtml = '';
            }

            // Extract quote content
            const quoteClone = node.cloneNode(true);
            const citeElement = quoteClone.querySelector('cite');
            let author = null;

            if (citeElement) {
                author = citeElement.textContent.trim();
                citeElement.remove();
            }

            blocks.push({
                type: 'quote',
                content: quoteClone.innerHTML,
                author: author,
            });
            return;
        }

        // Accumulate regular content
        if (node.nodeType === 1) {
            currentHtml += node.outerHTML;
        } else if (node.nodeType === 3 && node.textContent.trim()) {
            currentHtml += node.textContent;
        }
    });

    // Push remaining content
    if (currentHtml.trim()) {
        blocks.push({ type: 'html', content: currentHtml });
    }

    return blocks;
});
</script>

<template>
    <section class="w-full px-[30px] py-20">
        <div class="mx-auto grid max-w-[1140px] grid-cols-12 gap-[30px]">
            <div class="col-span-8 px-3 py-[15px] shadow-lg">
                <div>
                    <div class="aspect-735/509 overflow-hidden rounded-[20px]">
                        <img
                            :src="`/storage/${blog.featured_image}`"
                            :alt="blog.category?.name"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <div class="mt-4">
                        <h3
                            class="font-archivo text-charcoal text-2xl font-semibold"
                        >
                            {{ blog.title }}
                        </h3>
                        <div
                            class="mt-6 mb-4 flex items-center justify-start gap-2.5"
                        >
                            <div class="flex items-center gap-1">
                                <User
                                    class="text-rose-aura fill-rose-aura -mt-0.5 size-5"
                                />
                                <p
                                    class="text-gray-neutral text-xs leading-tight"
                                >
                                    By: {{ blog.author.first_name }} {{ blog.author?.last_name }}
                                </p>
                            </div>
                            <div class="flex items-center gap-1">
                                <Calendar
                                    class="text-rose-aura fill-rose-aura size-4"
                                />
                                <p class="text-gray-neutral text-xs">
                                    {{ formatDate(blog.published_at) }}
                                </p>
                            </div>
                        </div>
                        <!-- Render parsed content with styled quotes -->
                        <div
                            v-for="(block, index) in parsedContent"
                            :key="index"
                        >
                            <!-- Regular HTML content -->
                            <div
                                v-if="block.type === 'html'"
                                v-html="block.content"
                                class="text-gray-neutral"
                            ></div>

                            <!-- Styled Quote Block - Matches your design -->
                            <div v-else-if="block.type === 'quote'">
                                <blockquote
                                    class="bg-rose-aura h-auto w-full space-y-6 rounded-[20px] p-[30px]"
                                >
                                    <Quote
                                        class="size-9 fill-white text-white"
                                    />
                                    <p class="text-lg text-white italic">
                                        “{{
                                            block.content.replace(
                                                /<[^>]*>/g,
                                                '',
                                            )
                                        }}”
                                    </p>
                                    <cite
                                        v-if="block.author"
                                        class="mt-4 block text-sm text-gray-500 not-italic"
                                    >
                                        — {{ block.author }}
                                    </cite>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tags Related -->
                <div class="mt-8.5">
                    <div>
                        <div class="flex items-center justify-between gap-10">
                            <div>
                                <h2
                                    class="font-archivo text-charcoal text-2xl font-semibold"
                                >
                                    Related Tags
                                </h2>
                                <div
                                    class="mt-3.5 flex w-full max-w-[400px] items-center gap-5"
                                >
                                    <div
                                        v-for="tag in blog.tags"
                                        :key="tag.id"
                                        class="bg-rose-aura rounded-full px-4.5 py-2.5 text-white"
                                    >
                                        {{ tag.name }}
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h2
                                    class="font-archivo text-charcoal text-right text-2xl font-semibold"
                                >
                                    Social Share
                                </h2>
                                <div
                                    class="mt-3.5 flex items-center justify-center gap-5"
                                >
                                    <div
                                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                                    >
                                        <Linkedin
                                            class="fill-white text-lg text-white"
                                        />
                                    </div>
                                    <div
                                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                                    >
                                        <Facebook
                                            class="fill-white text-lg text-white"
                                        />
                                    </div>
                                    <div
                                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                                    >
                                        <Instagram class="text-lg text-white" />
                                    </div>
                                    <div
                                        class="bg-rose-aura flex h-[45px] w-[45px] items-center justify-center rounded-full"
                                    >
                                        <Youtube
                                            class="fill-white text-lg text-white"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-[30px]">
                        <div class="flex items-center justify-between">
                            <Link
                                :href="
                                    previous
                                        ? route('blog.show', previous.slug)
                                        : '#'
                                "
                                :class="[
                                    'border-b-4 pb-2',
                                    previous
                                        ? 'border-rose-aura hover:text-rose-aura'
                                        : 'pointer-events-none border-gray-300 text-gray-400',
                                ]"
                            >
                                Previous
                            </Link>

                            <Link
                                :href="
                                    next ? route('blog.show', next.slug) : '#'
                                "
                                :class="[
                                    'border-b-4 pb-2 text-right',
                                    next
                                        ? 'border-rose-aura hover:text-rose-aura'
                                        : 'pointer-events-none border-gray-300 text-gray-400',
                                ]"
                            >
                                Next
                            </Link>
                        </div>
                        <div
                            class="bg-rose-aura mt-[75px] flex flex-col items-center justify-center rounded-[20px] px-[78px] pb-10 text-white"
                        >
                            <div
                                class="-mt-18 mb-4 flex flex-col items-center justify-center"
                            >
                                <div
                                    class="aspect-square w-[132px] rounded-full bg-red-600"
                                ></div>
                                <div class="mt-4 text-center">
                                    <h3
                                        class="font-archivo text-2xl font-bold text-white"
                                    >
                                        {{ blog.author?.first_name }}
                                        {{ blog.author?.last_name }}
                                    </h3>
                                    <p class="mt-1 text-base text-white">
                                        Senior Director
                                    </p>
                                </div>
                            </div>
                            <p class="text-center text-base leading-6">
                                Lorem ipsum dolor sit amet, consectetur
                                adipisicing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Ut
                                enim ad minim veniam, quis nostrud exercitation
                                ullamco laboris nisi ut ali,
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Comment Part -->
                <div class="mt-11">
                    <div>
                        <h2 class="text-charcoal text-2xl font-semibold">
                            {{ countComments  }}
                        </h2>
                        <div>
                            <div
                                v-for="comment in blog.comments"
                                :key="comment.id"
                                class="relative flex items-start gap-5 py-8.5"
                            >
                                <div class="aspect-square w-full max-w-25">
                                    <img
                                        src="https://wallpaperaccess.com/full/9404569.jpg"
                                        alt=""
                                        class="h-full w-full rounded-full object-cover"
                                    />
                                </div>
                                <div class="">
                                    <h3
                                        class="font-archivo text-oxblood text-lg font-bold"
                                    >
                                        {{ comment.user.first_name }} {{ comment.user?.last_name }}
                                    </h3>
                                    <p class="text-gray-neutal mt-1.5 text-sm">
                                        {{ formatDate(comment.created_at) }}
                                    </p>
                                    <p
                                        class="text-gray-neutral max-w-[560px] text-base"
                                    >
                                        {{ comment.content }}
                                    </p>
                                </div>
                                <button
                                    v-if="user"
                                    class="bg-rose-aura absolute top-3 right-3 h-11.5 rounded-full px-7 text-center text-lg text-white"
                                >
                                    Reply
                                </button>
                            </div>
                        </div>
                    </div>
                    <div v-if="user" class="border-b border-black/20 py-10">
                        <h2 class="text-charcoal text-2xl font-semibold">
                            Leave a comment
                        </h2>
                        <form @submit.prevent="submit" class="mt-7">
                            <div>
                                <textarea
                                    placeholder="Enter your comment here..."
                                    class="h-30 w-full rounded-[25px] border border-black/15 px-4 py-3 transition-all duration-200 ease-out focus:border-black/20 focus:ring-4 focus:ring-black/5 focus:outline-none"
                                    v-model="form.content"
                                />
                            </div>
                            <button
                                class="bg-rose-aura mt-6.5 rounded-full px-7.5 py-[15px] text-white transition-all duration-300 ease-out hover:-translate-y-0.5 hover:shadow-lg active:translate-y-0 active:scale-[0.98]"
                            >
                                Comment
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <BlogDetailsSidebar />
        </div>
    </section>
</template>
