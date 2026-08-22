<script setup>
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { ref, watch } from 'vue';

const props = defineProps({
    modelValue: {
        type: String,
        required: true,
    },
    perPage: {
        type: [Number, String],
    },
    searchText: {
        type: String,
        default: 'Search',
    },
    perPageText: {
        type: String,
        default: 'Per Page',
    },
});

const emit = defineEmits(['update:modelValue', 'reset', 'updatePerPage']);
const perPageRef = ref(String(props.perPage));

const handlePerPageChange = (newValue) => {
    perPageRef.value = newValue;
    emit('updatePerPage', newValue);
};

watch(
    () => props.perPage,
    (newValue) => {
        perPageRef.value = newValue;
    },
);
</script>

<template>
    <div class="flex items-center justify-between py-4">
        <input
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex h-10 w-full max-w-sm rounded-md border px-3 py-2 text-sm file:border-0 file:bg-transparent file:text-sm file:font-medium focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-hidden disabled:cursor-not-allowed disabled:opacity-50"
            :placeholder="searchText + '...'"
        />
        <div class="w-28">
            <Select
                v-model="perPageRef"
                @update:modelValue="handlePerPageChange"
            >
                <SelectTrigger class="ml-auto">
                    <SelectValue placeholder="Select per page" />
                </SelectTrigger>
                <SelectContent>
                    <SelectGroup>
                        <SelectLabel>{{ perPageText }}</SelectLabel>
                        <SelectItem value="10"> 10 </SelectItem>
                        <SelectItem value="20"> 20 </SelectItem>
                        <SelectItem value="30"> 30 </SelectItem>
                    </SelectGroup>
                </SelectContent>
            </Select>
        </div>
    </div>
</template>
