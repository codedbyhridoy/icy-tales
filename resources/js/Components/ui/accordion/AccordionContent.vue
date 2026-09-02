<script setup>
import { cn } from '@/lib/utils';
import { reactiveOmit } from '@vueuse/core';
import { AccordionContent } from 'reka-ui';

const props = defineProps({
    forceMount: { type: Boolean, required: false },
    asChild: { type: Boolean, required: false },
    as: { type: null, required: false },
    class: { type: null, required: false },
});

const delegatedProps = reactiveOmit(props, 'class');
</script>

<template>
    <AccordionContent
        v-bind="delegatedProps"
        class="data-[state=closed]:animate-accordion-up data-[state=open]:animate-accordion-down overflow-hidden text-sm transition-all"
    >
        <div :class="cn('pt-0 pb-4', props.class)">
            <slot />
        </div>
    </AccordionContent>
</template>
