<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
defineOptions({ layout: FrontendLayout });

const termsAndConditions = [
  {
    id: 1,
    title: "1. Use of Website",
    content: "Your use of our website is subject to these Terms and Conditions. You must be at least 18 years old to use our services."
  },
  {
    id: 2,
    title: "2. User Account",
    content: "You are responsible for maintaining the confidentiality of your account and password. You agree to provide accurate and complete information when creating an account."
  },
  {
    id: 3,
    title: "3. Intellectual Property",
    content: "All content on this website, including text, graphics, logos, and images, is the property of [Your Online Education Platform] and protected by copyright laws. You may not reproduce, distribute, or transmit any content without prior written consent."
  },
  {
    id: 4,
    title: "4. Payment and Billing",
    content: "Payment for our services is required in advance. All fees are non-refundable."
  },
  {
    id: 5,
    title: "5. Termination",
    content: "We reserve the right to suspend or terminate your account at any time for violation of these Terms and Conditions. Please review our full Terms and Conditions for more detailed information. You have the right to access, update, or delete your personal information at any time. You can opt out of receiving promotional emails by following the instructions provided in the email. By using our website, you consent to the terms of this Privacy Policy. If you have any questions or concerns, please contact us."
  },
];
</script>

<template>
  <section
            class="bg-linear-rainbow px-[30px] py-20"
        >
            <div class="container mx-auto px-6 text-center">
                <h1 class="font-berkshire text-5xl text-charcoal">
                    Terms & Conditions
                </h1>

                <div
                    class="mx-auto mt-4 inline-flex items-center gap-2 rounded-full bg-white px-4 py-2 text-sm shadow-sm"
                >
                    <span class="text-rose-aura">Home</span>
                    <span>/</span>
                    <span>Terms & Conditions</span>
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
                    <div class="space-2" v-for="condition in termsAndConditions" :key="condition.id">
                        <h4 class="text-[22px] text-charcoal font-archivo font-bold">
                            {{ condition.title }}
                        </h4>
                        <p class="text-base text-gray-neutral leading-6">
                            {{ condition.content }}
                        </p>
                    </div>
                    <p class="text-base text-gray-neutral leading-6">
                        Please review our full terms & conditions for more information.
                    </p>
                </div>
            </div>
        </section>
</template>