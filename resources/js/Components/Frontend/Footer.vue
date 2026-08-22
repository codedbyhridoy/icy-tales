<template>
    <div class="relative overflow-hidden bg-[#7A3EB1] text-white">
        <!-- Decorative Ice Cream -->
        <div class="pointer-events-none absolute bottom-0 left-0 opacity-10">
            <img
                src="/images/footer-icecream.png"
                alt=""
                class="w-40 md:w-56 lg:w-72"
            />
        </div>

        <div class="relative z-10 container mx-auto px-6 py-12 md:py-16">
            <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4 lg:gap-8">
                <!-- Logo -->
                <div>
                    <div class="flex items-center gap-3">
                        <img src="/logo.png" alt="Logo" class="h-12" />

                        <h3 class="text-2xl font-bold text-pink-300">
                            IcyTales
                        </h3>
                    </div>

                    <p class="mt-4 max-w-xs text-sm leading-7 text-purple-100">
                        Creating delicious ice cream experiences and sweet
                        memories for everyone.
                    </p>
                </div>

                <!-- Navigation -->
                <div>
                    <h4 class="mb-5 text-lg font-semibold text-white">Navigation</h4>

                    <ul class="space-y-3 text-sm">
                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                Home
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                About
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                Shop
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                Products
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                Blog
                            </a>
                        </li>

                        <li>
                            <a
                                href="#"
                                class="text-purple-100 hover:text-pink-300"
                            >
                                Contact
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h4 class="mb-5 text-lg font-semibold text-white">Contact</h4>

                    <div class="space-y-5">
                        <div class="flex gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10"
                            >
                                📍
                            </div>

                            <div>
                                <p class="text-xs text-purple-200 uppercase">
                                    Address
                                </p>

                                <p class="mt-1 text-sm text-purple-100">
                                    121 King Street Melbourne, Australia
                                </p>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10"
                            >
                                ✉️
                            </div>

                            <div>
                                <p class="text-xs text-purple-200 uppercase">
                                    Email
                                </p>

                                <p class="mt-1 text-sm text-purple-100">
                                    info@example.com
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Phone + Social -->
                <div>
                    <div class="mb-8">
                        <div class="flex gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10"
                            >
                                ☎
                            </div>

                            <div>
                                <p class="text-xl font-semibold">
                                    +123456780123
                                </p>

                                <p class="text-sm text-purple-100">
                                    Got Questions? Call us 24/7
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div class="flex gap-3">
                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-pink-500"
                        >
                            f
                        </a>

                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-pink-500"
                        >
                            x
                        </a>

                        <a
                            href="#"
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-white/10 transition hover:bg-pink-500"
                        >
                            ig
                        </a>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="mt-10 border-t border-white/10"></div>

            <!-- Copyright -->
            <div
                class="mt-6 flex flex-col items-center justify-between gap-4 text-center text-xs text-purple-200 md:flex-row"
            >
                <p>Copyright © 2025 IcyTales. All rights reserved.</p>

                <div class="flex gap-4">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms</a>
                </div>
            </div>
        </div>

        <!-- Back To Top -->
        <button
            @click="scrollTop"
            class="fixed right-6 bottom-6 z-50 flex h-12 w-12 items-center justify-center rounded-lg bg-pink-500 text-white shadow-lg transition hover:bg-pink-600"
        >
            ↑
        </button>
    </div>
</template>

<script setup>
const scrollTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};
</script>
