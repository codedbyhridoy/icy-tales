<script setup>
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Switch } from '@/Components/ui/switch';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';

const props = defineProps({
    member: {
        type: Object,
        required: true
    }
});

const form = useForm('post', route('super_admin.team-members.update', props.member), {
    _method: 'PUT',
    first_name: props.member.first_name,
    last_name: props.member.last_name,
    designation: props.member.designation,
    bio: props.member.bio,
    image: null,
    facebook_url: props.member.facebook,
    instagram_url: props.member.instagram,
    youtube_url: props.member.youtube,
    is_active: Boolean(props.member.is_active),
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        preserveState: true,
        onError: (e) => {
            console.log(e);
        },
    });
};

const handleImage = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <Head title="Edit Member" />

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
                            Edit Member
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
                        <div class="space-y-6">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Basic Information
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Member details displayed on the website.
                                </p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label>First Name</Label>

                                    <Input v-model="form.first_name" />

                                    <p
                                        v-if="form.errors.first_name"
                                        class="text-sm text-red-500"
                                    >
                                        {{ form.errors.first_name }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <Label>Last Name</Label>

                                    <Input v-model="form.last_name" />

                                    <p
                                        v-if="form.errors.last_name"
                                        class="text-sm text-red-500"
                                    >
                                        {{ form.errors.last_name }}
                                    </p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <Label>Designation</Label>

                                <Input v-model="form.designation" />

                                <p
                                    v-if="form.errors.designation"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.designation }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <Label>Bio</Label>

                                <Input v-model="form.designation" />

                                <p
                                    v-if="form.errors.designation"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.designation }}
                                </p>
                            </div>
                        </div>

                        <!-- Image -->
                        <div class="space-y-5 border-t pt-8">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Profile Image
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Upload a professional square photo.
                                </p>
                            </div>

                            <div class="w-[120px] aspect-square rounded-full border border-soft-light">
                                <img 
                                    :src="`/storage/${member.image}`" 
                                    :alt="`${member.first_name} ${member.last_name}`" 
                                    class="w-full h-full rounded-full object-cover" 
                                />
                            </div>

                            <div class="space-y-2">
                                <Label>Image</Label>

                                <Input type="file" @change="handleImage" />

                                <p
                                    v-if="form.errors.image"
                                    class="text-sm text-red-500"
                                >
                                    {{ form.errors.image }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-6 border-t pt-8">
                            <div>
                                <h3 class="text-lg font-semibold">
                                    Social Media
                                </h3>

                                <p class="text-sm text-gray-500">
                                    Leave blank if unavailable.
                                </p>
                            </div>

                            <div class="grid gap-6 md:grid-cols-2">
                                <div class="space-y-2">
                                    <Label>Facebook</Label>
                                    <Input
                                        v-model="form.facebook_url"
                                        placeholder="https://facebook.com/..."
                                    />
                                </div>

                                <div class="space-y-2">
                                    <Label>Instagram</Label>
                                    <Input
                                        v-model="form.instagram_url"
                                        placeholder="https://instagram.com/..."
                                    />
                                </div>

                                <div class="space-y-2">
                                    <Label>YouTube</Label>
                                    <Input
                                        v-model="form.youtube_url"
                                        placeholder="https://youtube.com/..."
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="border-t pt-8">
                            <div
                                class="flex items-center justify-between rounded-xl border p-4"
                            >
                                <div>
                                    <h4 class="text-xl font-medium">Active Member</h4>

                                    <p class="text-sm text-gray-500">
                                        Show this member on the website.
                                    </p>
                                </div>

                                <Switch v-model="form.is_active" />
                            </div>
                        </div>
                        <!-- Submit -->
                        <div class="flex justify-end border-t pt-8">
                            <Button type="submit" :disabled="form.processing">
                                {{
                                    form.processing
                                        ? 'Creating...'
                                        : 'Create Member'
                                }}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
