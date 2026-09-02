<script setup>
import { ref } from 'vue';

import {
    Accordion,
    AccordionContent,
    AccordionItem,
    AccordionTrigger,
} from '@/Components/ui/accordion';
import { ArrowRight } from 'lucide-vue-next';

import NewsletterSection from '@/Components/Frontend/NewsletterSection.vue';
import FrontendLayout from '@/Layouts/FrontendLayout.vue';

defineOptions({
    layout: FrontendLayout,
});

const email = ref('');

const faqs = [
    {
        id: 'item-1',
        question: 'Do you offer dairy-free ice cream options?',
        answer: 'Yes. We offer a selection of dairy-free ice cream made with plant-based ingredients. Please check the product details for specific ingredients and allergens.',
    },
    {
        id: 'item-2',
        question: 'Can I order ice cream online?',
        answer: 'Yes! You can browse our available flavors, add your favorite products to your cart, and place your order directly through our website.',
    },
    {
        id: 'item-3',
        question: 'What ingredients do you use in your ice cream?',
        answer: 'We use carefully selected ingredients including fresh dairy or plant-based alternatives, natural flavors, and quality toppings depending on the flavor.',
    },
    {
        id: 'item-4',
        question: 'Do you have nutrition information available?',
        answer: 'Yes. Nutrition and ingredient information is available on individual product pages where applicable.',
    },
    {
        id: 'item-5',
        question: 'Do you have gluten-free ice cream?',
        answer: 'Some of our flavors are made without gluten-containing ingredients. Please check each product description for detailed allergen information.',
    },
    {
        id: 'item-6',
        question: 'Can I book your store for private events?',
        answer: 'Absolutely. Contact our team with your event details and we will be happy to discuss private event options.',
    },
];

/*
|--------------------------------------------------------------------------
| Split FAQs into two independent desktop columns
|--------------------------------------------------------------------------
*/

const leftFaqs = [faqs[0], faqs[2], faqs[4]];

const rightFaqs = [faqs[1], faqs[3], faqs[5]];

const submitNewsletter = () => {
    if (!email.value) return;

    console.log('Newsletter email:', email.value);

    email.value = '';
};
</script>

<template>
    <!-- =========================================
        FAQ SECTION
    ========================================== -->
    <section class="w-full px-5 py-24">
        <div class="mx-auto max-w-[1140px]">
            <!-- Heading -->
            <div class="mb-12.5 text-center">
                <h2
                    class="font-berkshire text-oxblood text-4xl font-bold md:text-5xl lg:text-[60px]"
                >
                    Frequently Asked
                    <span class="font-berkshire text-rose-aura">
                        Questions
                    </span>
                </h2>

                <p class="text-gray-neutral mt-5 text-xl">
                    Some of the queries you want to know about us.
                </p>
            </div>

            <!-- =========================================
                MOBILE FAQ
                Keeps normal order: 1, 2, 3, 4, 5, 6
            ========================================== -->

            <div class="md:hidden">
                <Accordion
                    type="single"
                    collapsible
                    class="flex flex-col gap-5"
                >
                    <AccordionItem
                        v-for="faq in faqs"
                        :key="faq.id"
                        :value="String(faq.id)"
                        class="px-7 py-5 shadow-sm"
                    >
                        <AccordionTrigger
                            class="text-oxblood font-archivo py-0 text-left text-lg font-bold hover:no-underline [&>svg]:hidden"
                        >
                            {{ faq.question }}
                        </AccordionTrigger>

                        <AccordionContent
                            class="font-archivo text-gray-medium mt-4 text-base"
                        >
                            {{ faq.answer }}
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </div>

            <!-- =========================================
                DESKTOP FAQ
                Two completely independent columns
            ========================================== -->

            <div class="hidden gap-x-5 md:grid md:grid-cols-2">
                <!-- LEFT COLUMN -->
                <Accordion
                    type="single"
                    collapsible
                    class="flex flex-col gap-5"
                >
                    <AccordionItem
                        v-for="faq in leftFaqs"
                        :key="faq.id"
                        :value="String(faq.id)"
                        class="px-7 py-5 shadow-sm"
                    >
                        <AccordionTrigger
                            class="text-oxblood font-archivo py-0 text-left text-lg font-bold hover:no-underline [&>svg]:hidden"
                        >
                            {{ faq.question }}
                        </AccordionTrigger>

                        <AccordionContent
                            class="font-archivo text-gray-medium mt-4 text-base"
                        >
                            {{ faq.answer }}
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>

                <!-- RIGHT COLUMN -->
                <Accordion
                    type="single"
                    collapsible
                    class="flex flex-col gap-5"
                >
                    <AccordionItem
                        v-for="faq in rightFaqs"
                        :key="faq.id"
                        :value="String(faq.id)"
                        class="px-7 py-5 shadow-sm"
                    >
                        <AccordionTrigger
                            class="text-oxblood font-archivo py-0 text-left text-lg font-bold hover:no-underline [&>svg]:hidden"
                        >
                            {{ faq.question }}
                        </AccordionTrigger>

                        <AccordionContent
                            class="font-archivo text-gray-medium mt-4 text-base"
                        >
                            {{ faq.answer }}
                        </AccordionContent>
                    </AccordionItem>
                </Accordion>
            </div>
        </div>
    </section>

    <!-- =========================================
        OFFER BANNER
    ========================================== -->

    <section class="w-full max-w-[1920px] mx-auto">
        <div class="relative aspect-[2/1] overflow-hidden">
            <!-- Banner Image -->
            <img
                src="/assets/images/special-offer-banner.png"
                alt=""
                class="absolute inset-0 h-full w-full object-contain"
            />

            <!-- Dynamic Content -->
            <div
                class="absolute top-1/2 left-1/2 flex flex-col justify-center items-center w-full max-w-[600px] -translate-x-1/2 -translate-y-1/2 px-5 text-center"
            >
                <h1
                    class="text-white"
                >
                    Limited Time Offer!
                </h1>

                <p class="mt-3 text-7.5 text-white md:text-base mt-10">
                    Get <span class="text-[#FFD800]">20% Off</span> All Vegan Ice Creams!
                </p>
                <button class="btn-rich-lg mt-7.5">
                    Get offer
                    <ArrowRight class="size-4" />
                </button>
                <p class="text-base text-white mt-6">Use code: VEGAN20 at checkout.</p>
            </div>
        </div>
    </section>

    <section class="relative overflow-hidden bg-white py-16 md:py-24">
        <NewsletterSection source="faqs" />
    </section>
</template>
