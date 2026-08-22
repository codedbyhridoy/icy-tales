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

defineProps({
    countryCode: {
        type: String,
    },
});

const emit = defineEmits(['update:countryCode']);

const updateCountryCode = (value) => {
    emit('update:countryCode', value);
};
</script>

<template>
    <div class="flex gap-2">
        <Select
            :defaultValue="countryCode"
            @update:modelValue="updateCountryCode"
        >
            <SelectTrigger class="mt-1">
                <SelectValue placeholder="Select a Country" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Country Code</SelectLabel>
                    <SelectItem value="de"> Germany </SelectItem>
                    <SelectItem value="ch"> Switzerland</SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
    </div>
</template>
