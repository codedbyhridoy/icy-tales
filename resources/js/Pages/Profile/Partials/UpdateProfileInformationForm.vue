<script setup>
import InputError from '@/Components/InputError.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Link, useForm, usePage } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $page.props.lang['Profile Information'] }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{
                    $page.props.lang[
                        "Update your account's profile information and email address."
                    ]
                }}
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('admin.profile.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <Label for="first_name">{{
                    $page.props.lang['First Name']
                }}</Label>

                <Input
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="first_name"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.first_name] ??
                        form.errors.first_name
                    "
                />
            </div>
            <div>
                <Label for="last_name">{{
                    $page.props.lang['Last Name']
                }}</Label>

                <Input
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    autofocus
                    autocomplete="last_name"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.last_name] ??
                        form.errors.last_name
                    "
                />
            </div>

            <div>
                <Label for="email">{{ $page.props.lang['Email'] }}</Label>
                <Input
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError
                    class="mt-2"
                    :message="
                        $page.props.lang[form.errors.email] ?? form.errors.email
                    "
                />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    {{ $page.props.lang['Your email address is unverified.'] }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        {{
                            $page.props.lang[
                                'Click here to re-send the verification email.'
                            ]
                        }}
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    {{
                        $page.props.lang[
                            'A new verification link has been sent to your email address.'
                        ]
                    }}
                </div>
            </div>

            <div class="flex items-center gap-4">
                <Button :disabled="form.processing">{{
                    $page.props.lang['Save']
                }}</Button>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        {{ $page.props.lang['Saved.'] }}
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
