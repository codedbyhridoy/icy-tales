<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="mt-8 flex flex-wrap p-3">
            <template v-for="(link, key) in links" :key="key">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 rounded border px-4 py-3 text-sm leading-4 text-gray-400"
                    v-html="link.label"
                ></div>

                <Link
                    v-else
                    class="focus:border-primary focus:text-primary mr-1 mb-1 rounded border px-4 py-3 text-sm leading-4 hover:bg-white"
                    :class="{
                        'pointer-events-none border-gray-200 bg-gray-100 text-gray-600':
                            link.active,
                    }"
                    :href="link.url"
                >
                    <span v-html="link.label"></span>
                </Link>
            </template>
        </div>
    </div>
</template>
