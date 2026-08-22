<script setup>
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';
import { ChevronDown } from 'lucide-vue-next';

const model = defineModel({
    default: 'latest'
});
</script>

<template>
    <Select v-model="model">
        <SelectTrigger
            class="border-gray-soft hover:border-rose-aura/40 focus-visible:border-rose-aura/40 h-10 w-auto gap-10 rounded-full border bg-white px-4 transition-colors duration-200 focus:ring-0 focus:outline-none focus-visible:ring-0"
        >
            <SelectValue
                placeholder="Default Sorting"
                class="text-oxblood text-sm leading-4"
            />

            <template #icon>
                <ChevronDown
                    class="text-gray-medium h-4 w-4 transition-transform duration-200 data-[state=open]:rotate-180"
                />
            </template>
        </SelectTrigger>

        <SelectContent
            class="border-gray-soft w-[181px] rounded-xl border bg-white p-1 shadow-lg"
        >
            <SelectItem
                value="latest"
                class="text-oxblood focus:bg-rose-aura/10 focus:text-rose-aura cursor-pointer rounded-lg px-3 py-2 text-sm"
            >
                Newest
            </SelectItem>

            <SelectItem
                value="price_low"
                class="text-oxblood focus:bg-rose-aura/10 focus:text-rose-aura cursor-pointer rounded-lg px-3 py-2 text-sm"
            >
                Price: Low to High
            </SelectItem>

            <SelectItem
                value="price_high"
                class="text-oxblood focus:bg-rose-aura/10 focus:text-rose-aura cursor-pointer rounded-lg px-3 py-2 text-sm"
            >
                Price: High to Low
            </SelectItem>
        </SelectContent>
    </Select>
</template>
