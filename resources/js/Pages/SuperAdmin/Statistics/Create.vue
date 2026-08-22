<script setup>
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';

import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/Components/ui/select';

const form = useForm('post', route('super_admin.statistics.store'), {
    title: '',
    value: '',
    suffix: '',
    sort_order: 0,
    status: true,
});

const submit = () => {
    if (form.suffix === 'none') {
        form.suffix === null;
    }
    
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onError: (e) => {
            console.log(e);
        },
    });
};
</script>

<template>
    <Head title="Create Satistic" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-4xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2
                            class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white"
                        >
                            Create Statistics
                        </h2>

                        <!-- <Button variant="outline" as-child>
                            <a :href="route('categories.index')">
                                <ArrowLeft class="mr-2 h-4 w-4" />
                                Back
                            </a>
                        </Button> -->
                    </div>

                    <!-- Form -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Name -->
                        <div class="space-y-2">
                            <Label>Title</Label>
                            <Input v-model="form.title" />
                            <p
                                v-if="form.errors.title"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.title }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <Label>Value</Label>
                            <Input v-model="form.value" />
                            <p
                                v-if="form.errors.value"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.value }}
                            </p>
                        </div>

                        <!-- Image -->
                        <div class="space-y-2">
                            <Label>Suffix</Label>
                            <Select v-model="form.suffix">
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder="Select a Suffix"
                                    />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="none">None</SelectItem>
                                    <SelectItem value="+">+</SelectItem>
                                    <SelectItem value="%">%</SelectItem>
                                    <SelectItem value="*">*</SelectItem>
                                </SelectContent>
                            </Select>
                            <p
                                v-if="form.errors.image"
                                class="text-sm text-red-500"
                            >
                                {{ form.errors.image }}
                            </p>
                        </div>

                        <!-- Status -->
                        <div class="space-y-2">
                            <Label>Sort Order</Label>
                            <Input type="number" v-model="form.sort_order" />
                        </div>

                        <div
                            class="flex items-center justify-between rounded-lg border p-4"
                        >
                            <div>
                                <Label class="font-medium">
                                    {{
                                        $page.props.lang['Active'] ??
                                        'Active'
                                    }}
                                </Label>

                                <p class="mt-1 text-sm text-gray-500">
                                    {{
                                        $page.props.lang[
                                            'Display this this statistic in the about page section.'
                                        ] ??
                                        'Display this this statistic in the about page section.'
                                    }}
                                </p>
                            </div>

                            <Switch v-model="form.status" />
                        </div>

                        <Button :disabled="form.processing" type="submit">
                            Create Statistic
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
