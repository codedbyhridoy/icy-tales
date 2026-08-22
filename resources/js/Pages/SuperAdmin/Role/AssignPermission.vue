<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';

import { Button } from '@/Components/ui/button';
import { Checkbox } from '@/Components/ui/checkbox';
import { Label } from '@/Components/ui/label';
import { computed } from 'vue';

const props = defineProps({
    role: Object,
    permission_data: Array,
    role_has_permissions: Object,
});

const page = usePage();
const can = computed(() => page.props.auth.can);

const form = useForm({
    permissions: props.role_has_permissions,
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
                                    $page.props.lang['Assign Permission'] ??
                                    'Assign Permission'
                                }}
                            </h2>
                        </header>

                        <form
                            @submit.prevent="
                                form.patch(
                                    route(
                                        'super_admin.role.assign-permission-update',
                                        role.id,
                                    ),
                                )
                            "
                            class="mt-6 space-y-6"
                        >
                            <div
                                v-for="(permission, index) in permission_data"
                                :key="index"
                            >
                                <Checkbox
                                    :id="'permission-' + permission.id"
                                    :checked="form.permissions[permission.name]"
                                    @update:checked="
                                        (value) =>
                                            (form.permissions[permission.name] =
                                                value)
                                    "
                                />
                                <Label
                                    :for="'permission-' + permission.id"
                                    :class="'pl-[5px] capitalize'"
                                >
                                    {{ permission.name }}
                                </Label>
                            </div>

                            <div
                                class="flex items-center gap-4"
                                v-if="can['manage role-permission']"
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
                                        {{
                                            $page.props.lang['Updated.'] ??
                                            'Updated.'
                                        }}
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
