<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

defineProps({});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                {{
                    $page.props.lang['Welcome to dental Match!'] ??
                    'Welcome to dental Match!'
                }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
