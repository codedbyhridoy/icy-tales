<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Label } from '@/Components/ui/label';
import { computed } from 'vue';

const props = defineProps({
    permission: Object,
    role_data: Array,
    permission_has_roles: Object,
});

const page = usePage();
const can = computed(() => page.props.auth.can);

const form = useForm({
    roles: props.permission_has_roles,
});
</script>

<template>
    <Head title="Assign Permssion" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <section>
                        <header>
                            <h2
                                class="text-lg font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{
                                    $page.props.lang['Assign Role'] ??
                                    'Assign Role'
                                }}
                            </h2>
                        </header>

                        <form
                            @submit.prevent="
                                form.patch(
                                    route(
                                        'super_admin.permission.assign-role-update',
                                        permission.id,
                                    ),
                                )
                            "
                            class="mt-6 space-y-6"
                        >
                            <div
                                v-for="(role, index) in role_data"
                                :key="index"
                            >
                                <Checkbox
                                    :checked="form.roles[role.name]"
                                    @update:checked="
                                        (value) =>
                                            (form.roles[role.name] = value)
                                    "
                                />
                                <Label
                                    :for="'role-' + role.id"
                                    :class="'pl-[5px] capitalize'"
                                >
                                    {{ role.name }}
                                </Label>
                            </div>

                            <div
                                v-if="can['manage role-permission']"
                                class="flex items-center gap-4"
                            >
                                <Button :disabled="form.processing">{{
                                    $page.props.lang['Update'] ?? 'Update'
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
                                        {{ $page.props.lang['Updated.'] }}
                                    </p>
                                </Transition>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
