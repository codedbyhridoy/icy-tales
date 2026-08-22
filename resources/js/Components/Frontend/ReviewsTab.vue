<script setup>
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { formatDate } from '@/lib/utils';
import { usePage } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { Star } from 'lucide-vue-next';
import { computed, ref } from 'vue';

import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';

import { EllipsisVertical, Pencil, Trash2 } from 'lucide-vue-next';

const page = usePage();

const user = computed(() => page.props.auth.user);

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    canReview: Boolean,
});

console.log(props.canReview);

const rating = ref(0);

const form = useForm('post', route('reviews.store', props.product), {
    product_id: props.product.id,
    title: '',
    comment: '',
    rating: 0,
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onFinish: () => {
            form.reset();
        },
        onError: (e) => {
            console.log('Error', e);
        },
    });
};

const selectRating = (star) => {
    if (rating.value === star) {
        rating.value = star - 1;
        form.rating = star - 1;
        return;
    }

    if (star === rating.value + 1) {
        rating.value = star;
        form.rating = star;
    } else if (rating.value === 0 && star === 1) {
        rating.value = star;
        form.rating = star;
    } else if (star > rating.value + 1) {
        console.log(`Please select ${rating.value + 1} star first`);
    }
};
</script>

<template>
    <section class="space-y-12">
        <!-- Review Summary -->

        <div class="border-gray-soft rounded-[10px] border px-8 py-4">
            <div>
                <p>Ratings & Reviews of {{ product.name }}</p>
                <div
                    class="mt mt-10 grid w-full max-w-[500px] grid-cols-2 gap-10"
                >
                    <div calss="">
                        <h2 class="text-oxblood text-5xl">
                            {{ product.average_rating }}/5
                        </h2>
                        <div class="mt-2 flex gap-1">
                            <Star
                                v-for="star in 5"
                                :key="star"
                                class="text-amber-gold fill-amber-gold size-10"
                            />
                        </div>
                        <p>
                            {{
                                product.total_ratings > 1
                                    ? `${product.total_ratings} ratings`
                                    : `${product.total_ratings} rating`
                            }}
                        </p>
                    </div>
                    <div>
                        <div
                            v-for="rating in [5, 4, 3, 2, 1]"
                            :key="rating"
                            class="mt-2 flex h-4 items-center gap-4"
                        >
                            <div class="flex gap-1">
                                <Star
                                    v-for="star in 5"
                                    :key="star"
                                    :class="[
                                        'size-4',
                                        star <= rating
                                            ? 'fill-amber-gold text-amber-gold'
                                            : 'text-gray-300',
                                    ]"
                                />
                            </div>

                            <span class="text-gray-medium text-sm">
                                {{ product.rating_counts[rating] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reviews -->

        <div class="space-y-6">
            <div
                v-for="review in product.reviews"
                class="border-gray-soft relative rounded-2xl border p-6"
            >
                <DropdownMenu v-if="user && user.id === review.user_id">
                    <DropdownMenuTrigger as-child class="absolute top-1 right-1">
                        <Button
                            variant="ghost"
                            size="icon"
                            class="h-8 w-8 rounded-full"
                        >
                            <EllipsisVertical class="h-4 w-4" />
                        </Button>
                    </DropdownMenuTrigger>

                    <DropdownMenuContent align="end">
                        <DropdownMenuItem>
                            <Pencil class="mr-2 h-4 w-4" />
                            Edit Review
                        </DropdownMenuItem>

                        <DropdownMenuItem
                            class="text-red-500 focus:text-red-500"
                        >
                            <Trash2 class="mr-2 h-4 w-4" />
                            Delete Review
                        </DropdownMenuItem>
                    </DropdownMenuContent>
                </DropdownMenu>
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-oxblood font-archivo text-lg font-bold">
                            {{ review.user?.first_name }}
                            {{ review.user?.last_name }}
                        </h4>

                        <div class="mt-2 flex gap-1">
                            <Star
                                v-for="star in 5"
                                :key="star"
                                :class="[
                                    'h-4 w-4',
                                    star <= review.rating
                                        ? 'fill-amber-gold text-amber-gold'
                                        : 'text-gray-300',
                                ]"
                            />
                        </div>
                    </div>

                    <span class="text-gray-medium text-sm">
                        {{ formatDate(review.created_at) }}
                    </span>
                </div>

                <h5 class="text-oxblood mt-5 font-semibold">
                    {{ review.title }}
                </h5>

                <p class="text-gray-medium mt-3 leading-7">
                    {{ review.comment }}
                </p>
            </div>
        </div>

        <!-- Review Form -->

        <div class="border-gray-soft rounded-2xl border p-8">
            <h3 class="text-oxblood font-archivo text-2xl font-bold">
                Write a Review
            </h3>

            <p class="text-gray-medium mt-2">
                Share your experience with this product.
            </p>

            <!-- Rating -->

            <div class="mt-8">
                <Label> Rating </Label>

                <div class="mt-3 flex gap-2">
                    <button
                        v-for="star in 5"
                        :key="star"
                        type="button"
                        @click="selectRating(star)"
                    >
                        <Star
                            :class="[
                                'h-8 w-8 transition-all duration-200',
                                star <= rating
                                    ? 'fill-amber-gold text-amber-gold'
                                    : 'hover:text-amber-warm text-gray-300',
                            ]"
                        />
                    </button>
                </div>
            </div>

            <!-- Title -->

            <form @submit.prevent="submit">
                <div class="mt-8">
                    <Label> Review Title </Label>

                    <Input
                        class="mt-3"
                        placeholder="Drop a though"
                        v-model="form.title"
                    />
                </div>

                <!-- Review -->

                <div class="mt-8">
                    <Label> Your Review </Label>

                    <Textarea
                        rows="6"
                        class="mt-3"
                        placeholder="Write your review..."
                        v-model="form.comment"
                    />
                </div>

                <Button type="submit" class="mt-8"> Submit Review </Button>
            </form>
        </div>
    </section>
</template>
