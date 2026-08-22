<script setup>
import Navbar from '@/Components/Frontend/Navbar.vue';
import Footer from '@/Components/Frontend/Footer.vue';
import { Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const isHomePage = computed(() => page.url === '/');
</script>

<template>
    <div class="main-layout-wrapper">
        <Toaster
            position="top-right"
            closeButton="true"
            closeButtonPosition="top-left"
            richColors
        />
        <header>
            <Navbar :is_home="isHomePage" />
        </header>
        <main>
            <slot />
        </main>
        <footer>
            <Footer />
        </footer>
    </div>
</template>