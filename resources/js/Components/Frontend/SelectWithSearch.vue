<script setup>
import {
    Command,
    CommandEmpty,
    CommandGroup,
    CommandInput,
    CommandItem,
} from '@/Components/ui/command';

import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/Components/ui/popover';

import { Check, ChevronsUpDown } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },

    label: {
        type: String,
        default: 'Select Items',
    },

    modelValue: {
        type: Array,
        default: () => [],
    },

    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const open = ref(false);

const selected = ref([...props.modelValue]);

watch(
    () => props.modelValue,
    (value) => {
        selected.value = [...value];
    },
);

const toggleItem = (value) => {
    const index = selected.value.indexOf(value);

    if (index === -1) {
        selected.value.push(value);
    } else {
        selected.value.splice(index, 1);
    }

    emit('update:modelValue', [...selected.value]);
};

const displayLabel = computed(() => {
    const labels = props.options
        .filter(option => selected.value.includes(option.value))
        .map(option => option.label);

    if (!labels.length) {
        return props.label;
    }

    if (labels.length <= 2) {
        return labels.join(', ');
    }

    return `${labels.slice(0, 2).join(', ')} +${labels.length - 2} more`;
});
</script>

<template>
    <Popover v-model:open="open">
        <PopoverTrigger as-child>
            <button
                type="button"
                :disabled="disabled"
                class="focus-visible:ring-ring flex h-10 w-full items-center justify-between rounded-md border bg-transparent px-3 py-2 text-sm focus-visible:ring-2 disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span class="truncate">
                    {{ displayLabel }}
                </span>

                <ChevronsUpDown class="h-4 w-4 opacity-50" />
            </button>
        </PopoverTrigger>

        <PopoverContent
            class="w-[var(--radix-popover-trigger-width)] p-0"
            align="start"
        >
            <Command>
                <CommandInput placeholder="Search..." />

                <CommandEmpty>
                    No results found.
                </CommandEmpty>

                <CommandGroup class="max-h-72 overflow-y-auto">
                    <CommandItem
                        v-for="option in options"
                        :key="option.value"
                        :value="option.label"
                        @select.prevent="toggleItem(option.value)"
                    >
                        <Check
                            class="mr-2 h-4 w-4"
                            :class="
                                selected.includes(option.value)
                                    ? 'opacity-100'
                                    : 'opacity-0'
                            "
                        />

                        {{ option.label }}
                    </CommandItem>
                </CommandGroup>
            </Command>
        </PopoverContent>
    </Popover>
</template>