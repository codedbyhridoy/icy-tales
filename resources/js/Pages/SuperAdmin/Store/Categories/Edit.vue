<script setup>
import { Head } from '@inertiajs/vue3'
import { useForm } from 'laravel-precognition-vue-inertia'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Switch } from '@/Components/ui/switch'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    category: {
        type: Object,
        required: true
    }
});

const form = useForm('post', route('super_admin.categories.update', props.category), {
    _method: 'PUT',
    name: props.category.name,
    slug: props.category.slug,
    image: null,
    status: Boolean(props.category.status),
})

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onError: (e) => {
            console.log(e);
        }
    });
}

const handleImage = (e) => {
    form.image = e.target.files[0]
};

console.log(form)
</script>

<template>
    <Head title="Edit Category" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-4xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800">

                    <!-- Header -->
                    <div class="flex items-center justify-between pb-6">
                        <h2 class="text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                            Edit Category
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
                            <Label>Name</Label>
                            <Input v-model="form.name" placeholder="Enter category name" />
                            <p v-if="form.errors.name" class="text-sm text-red-500">
                                {{ form.errors.name }}
                            </p>
                        </div>

                        <!-- Slug -->
                        <div class="space-y-2">
                            <Label>Slug</Label>
                            <Input v-model="form.slug" placeholder="e.g. ice-cream" />
                            <p v-if="form.errors.slug" class="text-sm text-red-500">
                                {{ form.errors.slug }}
                            </p>
                        </div>

                        <!-- Image -->
                        <div class="space-y-2">
                            <Label>Image</Label>
                            <div class="max-w-[120px] aspect-square rounded-[10px]">
                                <img 
                                    :src="`/storage/${category.image}`" 
                                    :alt="category.name" 
                                    class="w-full h-full rounded-[10px]" 
                                />
                            </div>
                            <Input type="file" @change="handleImage" />
                             <p v-if="form.errors.image" class="text-sm text-red-500">
                                {{ form.errors.image }}
                            </p>
                        </div>

                        <!-- Status -->
                       <Switch v-model="form.status" />

                        <!-- Submit -->
                        <div class="flex justify-end pt-4">
                            <Button type="submit" :disabled="form.processing">
                                Create Category
                            </Button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>