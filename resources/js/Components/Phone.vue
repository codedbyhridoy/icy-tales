<script setup>
import { Input } from '@/Components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { ref } from 'vue';

const props = defineProps({
    countryPhoneCode: {
        type: String,
    },
    phone: {
        type: [String, Number],
    },
});

const emit = defineEmits(['update:countryPhoneCode', 'update:phone']);

const updateCountryCode = (value) => {
    emit('update:countryPhoneCode', value);
};

const phoneNumber = ref(props.phone);
</script>

<template>
    <div class="flex gap-2">
        <Select
            :defaultValue="countryPhoneCode"
            @update:modelValue="updateCountryCode"
        >
            <SelectTrigger class="mt-1 w-24">
                <SelectValue placeholder="Select a Country Code" />
            </SelectTrigger>
            <SelectContent>
                <SelectGroup>
                    <SelectLabel>Country Code</SelectLabel>
                    <SelectItem value="49"> Germany(+49) </SelectItem>
                    <SelectItem value="41"> Switzerland(+41) </SelectItem>
                </SelectGroup>
            </SelectContent>
        </Select>
        <Input
            type="number"
            v-model="phoneNumber"
            placeholder="1625557751"
            @update:modelValue="(value) => emit('update:phone', value)"
            class="mt-1"
        />
    </div>
</template>
