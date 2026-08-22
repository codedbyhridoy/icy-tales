<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { useForm } from 'laravel-precognition-vue-inertia';
import { toast } from 'vue-sonner';

const page = usePage();

const form = useForm('post', route('customer.login'), {
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.submit({
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            toast.success(page.props.lang['Logged in successfully!'] ?? 'Logged in successfully!');
        }
    })
};
</script>

<template>
    <Head title="Log in" />

    <section class="flex min-h-[90vh] w-full items-center justify-center">
        <form @submit.prevent="submit" class="w-full max-w-[500px]">
            <div>
                <Label for="email">{{
                    $page.props.lang['Email'] ?? 'Email'
                }}</Label>

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                    v-model="form.email"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4">
                <Label for="password">{{
                    $page.props.lang['Password'] ?? 'Password'
                }}</Label>

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    required
                    autocomplete="current-password"
                    v-model="form.password"
                />

                <InputError class="mt-2" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span
                        class="ms-2 text-sm text-gray-600 dark:text-gray-400"
                        >{{
                            $page.props.lang['Remember me'] ?? 'Remember me'
                        }}</span
                    >
                </label>
            </div>

            <div class="mt-4 flex items-center justify-end">
                <!-- <Link
                    v-if="canResetPassword"
                    :href="route('admin.password.request')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                >
                    {{ $page.props.lang['Forgot your password?'] }}
                </Link> -->

                <Button class="ms-4">
                    {{ $page.props.lang['Log in'] ?? 'Login' }}
                </Button>
            </div>
            <p>Don't have an account? <Link :href="route('register')">Register</Link></p>
        </form>
    </section>
</template>
