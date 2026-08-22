<script setup>
import { computed, ref, onMounted, onUnmounted, watch } from 'vue';

const props = defineProps({
    items: {
        type: Array,
        required: true,
        default: () => [],
    },
    itemsPerView: {
        type: Object,
        default: () => ({
            desktop: 4,
            tablet: 3,
            mobile: 1,
        }),
    },
});

const currentSlide = ref(0);
const currentItemsPerView = ref(getItemsPerView());
const trackRef = ref(null);

// Pointer event variables
let pointerStartX = 0;
let pointerStartY = 0;
let pointerCurrentX = 0;
let isPointerDown = false;
let isHorizontalSwipe = false;

// Get items per view based on window width
function getItemsPerView() {
    const width = window.innerWidth;
    if (width >= 1024) return props.itemsPerView.desktop;
    if (width >= 768) return props.itemsPerView.tablet;
    return props.itemsPerView.mobile;
}

// Split items into slides
const slides = computed(() => {
    const perView = currentItemsPerView.value;
    const result = [];
    for (let i = 0; i < props.items.length; i += perView) {
        result.push(props.items.slice(i, i + perView));
    }
    return result;
});

// Grid classes for the slide container
const gridClasses = computed(() => {
    const perView = currentItemsPerView.value;
    if (perView === 4) return 'grid grid-cols-4 items-start gap-2';
    if (perView === 3) return 'grid grid-cols-3 items-start gap-2';
    if (perView === 2) return 'grid grid-cols-2 items-start gap-2';
    return 'grid grid-cols-1 items-start gap-2';
});

// Navigation methods
const nextSlide = () => {
    if (slides.value.length === 0) return;
    if (currentSlide.value < slides.value.length - 1) {
        currentSlide.value++;
    }
};

const prevSlide = () => {
    if (slides.value.length === 0) return;
    if (currentSlide.value > 0) {
        currentSlide.value--;
    }
};

const goToSlide = (index) => {
    if (index >= 0 && index < slides.value.length) {
        currentSlide.value = index;
    }
};

// Pointer event handlers (works for both touch and mouse)
const handlePointerDown = (e) => {
    pointerStartX = e.clientX;
    pointerStartY = e.clientY;
    pointerCurrentX = pointerStartX;
    isPointerDown = true;
    isHorizontalSwipe = false;
};

const handlePointerMove = (e) => {
    if (!isPointerDown) return;
    
    pointerCurrentX = e.clientX;
    const deltaX = pointerCurrentX - pointerStartX;
    const deltaY = e.clientY - pointerStartY;
    
    if (!isHorizontalSwipe) {
        if (Math.abs(deltaX) > Math.abs(deltaY) && Math.abs(deltaX) > 10) {
            isHorizontalSwipe = true;
            // Prevent default only when we're sure it's a horizontal swipe
            e.preventDefault();
        } else if (Math.abs(deltaY) > Math.abs(deltaX) && Math.abs(deltaY) > 10) {
            // It's a vertical scroll, stop swiping
            isPointerDown = false;
            return;
        }
    }
    
    if (isHorizontalSwipe) {
        e.preventDefault();
    }
};

const handlePointerUp = (e) => {
    if (!isPointerDown) {
        isPointerDown = false;
        isHorizontalSwipe = false;
        return;
    }
    
    const deltaX = pointerCurrentX - pointerStartX;
    const swipeThreshold = 50; // Minimum distance for a swipe
    
    if (isHorizontalSwipe && Math.abs(deltaX) > swipeThreshold) {
        if (deltaX < 0) {
            // Swipe left - next slide
            nextSlide();
        } else {
            // Swipe right - previous slide
            prevSlide();
        }
    }
    
    isPointerDown = false;
    isHorizontalSwipe = false;
};

// Handle resize
const handleResize = () => {
    const newItemsPerView = getItemsPerView();
    if (newItemsPerView !== currentItemsPerView.value) {
        currentItemsPerView.value = newItemsPerView;
        // Adjust current slide to prevent out-of-bounds
        const maxSlide = Math.max(0, slides.value.length - 1);
        if (currentSlide.value > maxSlide) {
            currentSlide.value = maxSlide;
        }
    }
};

// Reset to first slide when items change
watch(() => props.items, () => {
    currentSlide.value = 0;
});

// Reset to first slide when itemsPerView changes
watch(() => props.itemsPerView, () => {
    currentSlide.value = 0;
    currentItemsPerView.value = getItemsPerView();
}, { deep: true });

onMounted(() => {
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
});
</script>

<template>
    <div class="relative w-full">
        <!-- Carousel Container with padding for buttons -->
        <div class="relative px-8 sm:px-10 md:px-12">
            <!-- Carousel Track -->
            <div 
                ref="trackRef"
                class="relative overflow-hidden touch-pan-y select-none"
                @pointerdown="handlePointerDown"
                @pointermove="handlePointerMove"
                @pointerup="handlePointerUp"
                @pointercancel="handlePointerUp"
            >
                <div
                    class="flex transition-transform duration-300 ease-in-out"
                    :style="{ transform: `translateX(-${currentSlide * 100}%)` }"
                >
                    <div
                        v-for="(slide, slideIndex) in slides"
                        :key="slideIndex"
                        class="w-full flex-shrink-0"
                    >
                        <div :class="gridClasses">
                            <div
                                v-for="(item, itemIndex) in slide"
                                :key="itemIndex"
                                class="p-2"
                            >
                                <slot name="item" :item="item" :index="itemIndex" :slide-index="slideIndex">
                                    <div class="bg-gray-100 rounded-lg p-4 text-center">
                                        <p class="text-gray-500">No item template provided</p>
                                        <p class="text-sm text-gray-400">{{ item }}</p>
                                    </div>
                                </slot>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons - Hidden on mobile, visible on tablet and desktop -->
            <button
                v-if="slides.length > 1"
                @click="prevSlide"
                class="hidden sm:flex absolute left-0 top-1/2 -translate-y-1/2 bg-white hover:bg-gray-50 rounded-full p-2 shadow-lg transition-all z-10 disabled:opacity-40 disabled:cursor-not-allowed border border-gray-200 cursor-pointer"
                :disabled="currentSlide === 0"
                aria-label="Previous"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button
                v-if="slides.length > 1"
                @click="nextSlide"
                class="hidden sm:flex absolute right-0 top-1/2 -translate-y-1/2 bg-white hover:bg-gray-50 rounded-full p-2 shadow-lg transition-all z-10 disabled:opacity-40 disabled:cursor-not-allowed border border-gray-200 cursor-pointer"
                :disabled="currentSlide === slides.length - 1"
                aria-label="Next"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 sm:h-6 sm:w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        <!-- Dots Pagination -->
        <div v-if="slides.length > 1" class="flex justify-center mt-6 space-x-2">
            <button
                v-for="(dot, dotIndex) in slides"
                :key="dotIndex"
                @click="goToSlide(dotIndex)"
                class="w-3 h-3 rounded-full transition-colors duration-200"
                :class="dotIndex === currentSlide ? 'bg-rose-aura' : 'bg-gray-300'"
                :aria-label="`Go to slide ${dotIndex + 1}`"
            ></button>
        </div>
    </div>
</template>