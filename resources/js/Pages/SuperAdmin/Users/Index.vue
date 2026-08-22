<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
</script>

<template>
    <AuthenticatedLayout>
        <div class="py-8">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                {{
                    $page.props.lang['Welcome to User page!'] ??
                    'Welcome to User page!'
                }}
            </div>
        </div>
    </AuthenticatedLayout>
</template>
