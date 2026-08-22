<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('admin.password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <form @submit.prevent="submit">
            <div>
                <Label for="email">{{ $page.props.lang['Email'] }}</Label>

                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.email] ?? form.errors.email
                    "
                />
            </div>

            <div class="mt-4">
                <Label for="password">{{ $page.props.lang['Password'] }}</Label>

                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.password] ??
                        form.errors.password
                    "
                />
            </div>

            <div class="mt-4">
                <Label for="password_confirmation">{{
                    $page.props.lang['Confirm Password']
                }}</Label>

                <Input
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.password_confirmation] ??
                        form.errors.password_confirmation
                    "
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ $page.props.lang['Reset Password'] }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
