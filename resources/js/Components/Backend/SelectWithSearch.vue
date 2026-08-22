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
        default: 'Gesuchte Funktion',
    },
    crrValue: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update']);

const openGesuchte = ref(false);
const currentValue = ref(props.crrValue);

watch(
    () => props.crrValue,
    (v) => {
        currentValue.value = v ?? '';
    },
);

const setCurrentValue = (v) => (currentValue.value = v);
defineExpose({ setCurrentValue, currentValue });

const onSelectGesuchte = (value) => {
    if (props.disabled) return;
    currentValue.value = value;
    emit('update', value);
    openGesuchte.value = false;
};

const gesuchteLabel = computed(() => {
    const found = props.options.find((o) => o.value === currentValue.value);
    return found ? found.label : '';
});
</script>

<template>
    <div v-if="disabled">
        <button
            type="button"
            disabled
            class="flex h-10 w-full cursor-not-allowed items-center justify-between rounded-md border bg-transparent p-4 text-sm opacity-70"
        >
            <div class="flex items-center gap-1">
                <span class="">
                    {{ gesuchteLabel || label }}
                </span>
                >
            </div>
            <ChevronsUpDown class="h-4 w-4 opacity-50" />
        </button>
    </div>

    <Popover v-else v-model:open="openGesuchte">
        <PopoverTrigger as-child>
            <button
                type="button"
                class="focus-visible:ring-ring placeholder:text-muted-foreground mt-[3px] flex h-10 w-full items-center justify-between rounded-md border bg-transparent px-3 py-2 text-sm focus-visible:ring-2 focus-visible:outline-hidden"
            >
                <div class="flex items-center gap-1">
                    <span class="">
                        {{ gesuchteLabel || label }}
                    </span>
                </div>
                <ChevronsUpDown class="h-4 w-4 opacity-50" />
            </button>
        </PopoverTrigger>

        <PopoverContent
            align="start"
            :side-offset="6"
            class="max-w-[var(--radix-popover-trigger-width)] min-w-[220px] p-0"
        >
            <div class="overflow-x-auto">
                <div class="min-w-max">
                    <Command>
                        <CommandInput :placeholder="''" />
                        <CommandEmpty>{{
                            $page.props.lang['No data.']
                        }}</CommandEmpty>

                        <CommandGroup class="max-h-[300px] overflow-y-auto">
                            <CommandItem
                                v-for="fn in options"
                                :key="fn.value"
                                :value="fn.label"
                                @select="() => onSelectGesuchte(fn.value)"
                                class="whitespace-nowrap"
                            >
                                <Check
                                    class="mr-2 h-4 w-4"
                                    :class="
                                        fn.value === currentValue
                                            ? 'opacity-100'
                                            : 'opacity-0'
                                    "
                                />
                                {{ fn.label }}
                            </CommandItem>
                        </CommandGroup>
                    </Command>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
