<script setup>
import FrontendLayout from '@/Layouts/FrontendLayout.vue';
defineOptions({ layout: FrontendLayout });
import { Quote, Star } from 'lucide-vue-next';
</script>

<template>
    <section
        class="flex h-[calc(100vh-100px)] items-center justify-center py-24"
    >
        <div class="mx-auto w-full max-w-[1140px] px-5">
            <div class="text-center">
                <h2
                    class="font-berkshire tlg:ext-[60px] text-oxblood font-bold md:text-5xl"
                >
                    Hear From Our
                    <span class="font-berkshire text-rose-aura">Gelato</span>
                    Enthusiasts
                </h2>

                <p class="text-gray-neutral mt-5 text-xl">
                    Read testimonials from those who have enjoyed our artisan
                    gelato.
                </p>
                <div class="mt-12">
                    <!-- <Carousel
                        :items="members"
                        :items-per-view="{
                            desktop: 3,
                            tablet: 3,
                            mobile: 1,
                        }"
                    >
                        <div class="w-full h-[300px] rounded-[20px] bg-red-600 px-[30px] py-10">
                            <img src="/aseets/images/figure-testimonials.svg" alt="" class="w-[150px]" />
                        </div>
                    </Carousel> -->
                </div>
                <!-- <div v-else class="mt-12 py-12 text-center">
                    <p class="text-lg text-gray-500">
                        No team members available yet.
                    </p>
                </div> -->
            </div>
            <div class="grid grid-cols-3 gap-8">
                <div
                    v-for="testimonial in 3"
                    :key="testimonial"
                    class="h-[300px] w-full rounded-[20px] px-[30px] py-10 shadow-[inset_0px_2px_73px_2px_rgba(0,0,0,0.05)] border-b-5 border-transparent hover:border-rose-aura hover:scale-105 transition-all duration-300"
                >
                    <Quote class="size-10 text-rose-aura rotate-180" />
                    <p class="mt-5.5 text-base text-gray-neutral line-clamp-3">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Non molestias corrupti commodi voluptatibus quis odio incidunt odit iusto temporibus illum ad dicta magni maiores, repellendus, minima deleniti necessitatibus architecto provident.
                    </p>
                    <div class="flex items-center gap-3 mt-7">
                        <div class="w-13.5 h-13.5">
                            <img 
                                src="https://images.unsplash.com/photo-1628563694622-5a76957fd09c?fm=jpg&q=60&w=3000&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW5zdGFncmFtJTIwcHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D" 
                                alt="User Name" 
                                class="w-full aspect-square rounded-full object-cover" 
                            />
                        </div>
                        <div>
                            <div class="flex items-center gap-0.5">
                                <Star v-for="star in 5" :key="star" class="size-3 text-amber-gold fill-amber-gold" />
                            </div>
                            <h3 class="font-archivo text-lg font-bold text-rose-aura mt-1.5">
                                Kylian Mbappe
                            </h3>
                            <p class="text-sm text-gray-neutral mt-2">
                                Happy Client
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
