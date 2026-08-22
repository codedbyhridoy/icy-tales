<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Head, Link } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';

const form = useForm('post', route('user.registration'), {
    first_name: '',
    last_name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.submit({
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

    <section class="flex min-h-[90vh] w-full items-center justify-center">
        <form @submit.prevent="submit" class="w-full max-w-[500px]">
            <div>
                <Label for="first_name">First Name</Label>

                <Input
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="first_name"
                    v-model="form.first_name"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4">
                <Label for="last_name">Last Name</Label>

                <Input
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="last_name"
                    v-model="form.last_name"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4">
                <Label for="email">Email</Label>

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autocomplete="username"
                    v-model="form.email"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4">
                <Label for="password">Password</Label>

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    v-model="form.password"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4">
                <Label for="password_confirmation">Confirm Password</Label>

                <Input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="new-password"
                    v-model="form.password_confirmation"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <p class="">Already have an account? 
                    <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    Log In Now
                </Link></p>

                <Button class="ms-4"> Register </Button>
            </div>
        </form>
    </section>
</template>
