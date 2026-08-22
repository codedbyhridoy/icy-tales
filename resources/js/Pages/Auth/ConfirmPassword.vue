<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import GuestLayout from '@/Layouts/GuestLayout.vue';

import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ $page.props.lang['confirm-password-msg'] }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <Label for="password">{{ $page.props.lang['Password'] }}</Label>
                <Input
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                />
                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.password] ??
                        form.errors.password
                    "
                />
            </div>

            <div class="mt-4 flex justify-end">
                <Button
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ $page.props.lang['Confirm'] }}
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
