<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
defineOptions({ layout: FrontendLayout });

const policies = [
    {   
        id: 1,
        title: "1. Information We Collect:",
        content: "We collect personal information such as your name, email address, and payment details when you create an account or make a purchase. We also collect usage data such as IP address, browser type, and pages visited.",
    },
    {
        id: 2,
        title: "2. How We Use Your Information:",
        content: "We use your personal information to provide and improve our services. Your information may also be used for communication purposes, such as sending newsletters or updates.",
    },
    {
        id: 3,
        title: "3. Information Sharing:",
        content: "We do not sell, trade, or otherwise transfer your personal information to third parties without your consent. We may share your information with trusted third-party service providers who assist us in operating our website.",
    },
    {
        id: 4,
        title: "4. Security:",
        content: "We implement security measures to protect your personal information against unauthorized access or alteration. However, no method of transmission over the Internet or electronic storage is 100% secure.",
    },
    {
        id:5,
        title: "5. Your Choices:",
        content: "You have the right to access, update, or delete your personal information at any time. You can opt out of receiving promotional emails by following the instructions provided in the email. By using our website, you consent to the terms of this Privacy Policy. If you have any questions or concerns, please contact us.<br />By using our website, you consent to the terms of this Privacy Policy. If you have any questions or concerns, please contact us.",
    },
];
</script>

<template>
  <section
            class="bg-linear-rainbow px-[30px] py-20"
        >
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-berkshire text-5xl text-charcoal">
                    Privacy Policy
                </h1>

                <div
                    class="mx-auto mt-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm shadow-sm"
                >
                    <span class="text-rose-aura">Home</span>
                    <span>/</span>
                    <span>Privacy Policy</span>
                </div>
            </div>
        </section>

        <section class="w-full px-[30px] py-12.5 md:py-[75px] lg:py-25">
            <div class="w-full max-w-[1140px] mx-auto">
                <div class="space-y-2">
                    <h3 class="text-3xl font-archivo text-charcoal">
                        Privacy Policy:
                    </h3>
                    <p class="text-base text-gray-neutral leading-6">
                        Protecting your privacy is important to us. This Privacy Policy outlines how we collect, use, and disclose personal information when you use our website.
                    </p>
                </div>
                <div class="space-y-2.5">
                    <div class="space-2" v-for="policy in policies" :key="policy.id">
                        <h4 class="text-[22px] text-charcoal font-archivo font-bold">
                            {{ policy.title }}
                        </h4>
                        <p class="text-base text-gray-neutral leading-6">
                            {{ policy.content }}
                        </p>
                    </div>
                </div>
            </div>
        </section>
</template>