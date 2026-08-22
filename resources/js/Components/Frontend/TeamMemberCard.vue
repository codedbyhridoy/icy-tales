<script setup>
defineProps({
    member: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="flex flex-col items-center text-center">
        <div class="h-48 w-48 overflow-hidden rounded-full bg-gray-100">
            <img
                :src="`/storage/${member.image}`"
                :alt="`${member.first_name} ${member.last_name}`"
                class="h-full w-full object-cover transition duration-300 hover:scale-105"
            />
        </div>

        <h3 class="font-archivo mt-5 text-lg font-semibold text-gray-900">
            {{ member.first_name }} {{ member.last_name }}
        </h3>

        <p class="mt-1 text-sm text-gray-400">
            {{ member.designation }}
        </p>

        <div class="mt-4 flex items-center gap-2">
            <a
                :href="member.facebook"
                target="_blank"
                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-500 text-white transition hover:bg-pink-600"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    class="w-3.5 text-white"
                >
                    <path
                        d="M13.5 22v-8h2.7l.4-3h-3.1V9.1c0-.9.3-1.6 1.6-1.6H17V4.8c-.3 0-1.2-.1-2.3-.1-2.3 0-3.9 1.4-3.9 4V11H8v3h2.8v8h2.7z"
                    />
                </svg>
            </a>

            <a
                :href="member.instagram"
                target="_blank"
                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-500 text-white transition hover:bg-pink-600"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-3.5"
                >
                    <rect
                        x="3"
                        y="3"
                        width="18"
                        height="18"
                        rx="5"
                        ry="5"
                    ></rect>
                    <circle cx="12" cy="12" r="4"></circle>
                    <circle cx="17.5" cy="6.5" r="1"></circle>
                </svg>
            </a>

            <a
                :href="member.youtube"
                target="_blank"
                class="flex h-8 w-8 items-center justify-center rounded-full bg-pink-500 text-white transition hover:bg-pink-600"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="w-3.5"
                >
                    <path
                        d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46A2.78 2.78 0 0 0 1.46 6.42 29.94 29.94 0 0 0 1 12a29.94 29.94 0 0 0 .46 5.58 2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29.94 29.94 0 0 0 23 12a29.94 29.94 0 0 0-.46-5.58z"
                    />
                    <polygon
                        points="10 9 16 12 10 15 10 9"
                        fill="currentColor"
                        stroke="none"
                    />
                </svg>
            </a>
        </div>
    </div>
</template>
