<script setup>
import { ArrowLeft } from "@lucide/vue"
import { cn } from "@/lib/utils"
import { Button } from "@/Components/ui/button";
import { useCarousel } from "./useCarousel"

const props = defineProps({
  variant: {
    type: String,
    default: "outline",
  },
  size: {
    type: String,
    default: "icon",
  },
  class: {
    type: String,
    default: "",
  },
})

const { orientation, canScrollPrev, scrollPrev } = useCarousel()
</script>

<template>
  <Button
    data-slot="carousel-previous"
    :disabled="!canScrollPrev"
    :class="
      cn(
        'absolute size-8 rounded-full',
        orientation === 'horizontal'
          ? 'top-1/2 -left-12 -translate-y-1/2'
          : '-top-12 left-1/2 -translate-x-1/2 rotate-90',
        props.class
      )
    "
    :variant="props.variant"
    :size="props.size"
    @click="scrollPrev"
  >
    <slot>
      <ArrowLeft />
      <span class="sr-only">Previous Slide</span>
    </slot>
  </Button>
</template>