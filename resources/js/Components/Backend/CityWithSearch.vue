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
import axios from 'axios';
import { Check, ChevronsUpDown } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

const props = defineProps({
    url: { type: String, required: true },

    label: { type: String, default: 'Stadt' },
    crrValue: { type: String, default: '' },
    disabled: { type: Boolean, default: false },

    limit: { type: Number, default: 100 },
});

const emit = defineEmits(['update']);

const openGesuchte = ref(false);
const currentValue = ref(props.crrValue);

const labelMap = ref(new Map()); // value -> label

// remote list (only current results)
const options = ref([]);

const searchText = ref('');
const loading = ref(false);

// keep in sync when backend updates prop value
watch(
    () => props.crrValue,
    (v) => {
        currentValue.value = v ?? '';
    },
);

// expose same
const setCurrentValue = (v) => (currentValue.value = v);
defineExpose({ setCurrentValue, currentValue });

const onSelectGesuchte = (value) => {
    if (props.disabled) return;
    currentValue.value = value;
    emit('update', value);
    openGesuchte.value = false;
};

const gesuchteLabel = computed(() => {
    const found = props.options?.find?.((o) => o.value === currentValue.value);
    return found?.label || currentValue.value || '';
});

// ------- fetch (no stale response issue) -------
let debounceTimer = null;
let reqId = 0;

const rememberLabels = (list) => {
    (list || []).forEach((o) => {
        if (o?.value != null && o?.label != null) {
            labelMap.value.set(o.value, o.label);
        }
    });
};

const fetchCities = async () => {
    const myId = ++reqId;
    loading.value = true;
    try {
        const res = await axios.get(props.url, {
            params: { q: searchText.value, limit: props.limit },
        });

        if (myId !== reqId) return; // ignore stale

        const list = res.data?.data ?? [];
        options.value = list;
        rememberLabels(list);
    } catch (e) {
        if (myId !== reqId) return;
        options.value = [];
    } finally {
        if (myId === reqId) loading.value = false;
    }
};

const debounceFetch = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(fetchCities, 300);
};

// open -> initial 20
watch(openGesuchte, (v) => {
    if (!v) return;
    searchText.value = '';
    options.value = [];
    fetchCities();
});

// search -> remote fetch
watch(searchText, () => {
    if (!openGesuchte.value) return;
    debounceFetch();
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
                        <CommandInput
                            v-model="searchText"
                            placeholder="Stadt suchen..."
                        />

                        <CommandEmpty v-if="!loading && options.length === 0">
                            {{ $page.props.lang['No data.'] }}
                        </CommandEmpty>

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

                            <div
                                v-if="loading"
                                class="text-muted-foreground px-3 py-2 text-sm"
                            >
                                {{ $page.props.lang['Loading...'] }}
                            </div>
                        </CommandGroup>
                    </Command>
                </div>
            </div>
        </PopoverContent>
    </Popover>
</template>
