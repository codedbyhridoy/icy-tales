<script setup>
import { Slider } from '@/Components/ui/slider';
</script>

<template>
    <div
        class="
            [&_.bg-primary]:!bg-rose-aura
            [&_.border-primary]:!border-rose-aura

            [&_[role=slider]]:transition-transform
            [&_[role=slider]]:duration-150

            [&_[role=slider]]:focus-visible:scale-125
            [&_[role=slider]]:focus-visible:ring-4
            [&_[role=slider]]:focus-visible:ring-rose-aura/20
            [&_[role=slider]]:focus-visible:ring-offset-0
        "
    >
        <Slider v-bind="$attrs" />
    </div>
</template>
