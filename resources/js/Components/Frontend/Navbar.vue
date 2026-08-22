<script setup>
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/Components/ui/sheet';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import ProfileDropdown from '../ProfileDropdown.vue';

import {
    ArrowRight,
    ChevronDown,
    LayoutDashboard,
    Menu,
    Search,
    ShoppingBag,
} from 'lucide-vue-next';

defineProps({
    is_home: {
        type: Boolean,
        required: true,
    },
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const is_admin_login = computed(() => page.props.auth.is_admin_login);

const cartCount = computed(() => page.props.cart?.count ?? 0);
console.log('Cart_count', cartCount.value);
const navigationItems = [
    {
        id: 1,
        label: 'Home',
        link: 'home',
    },
    {
        id: 2,
        label: 'About',
        link: 'about.index',
    },
    {
        id: 3,
        label: 'Pages',
        children: [
            { id: 1, label: 'Team', link: 'team-members' },
            { id: 2, label: 'Review', link: 'review' },
            { id: 7, label: 'Special Offers', link: 'special-offers' },
            { id: 8, label: 'Privacy Policy', link: 'privacy-policy' },
            { id: 9, label: 'Terms & Conditions', link: 'terms-conditions' },
        ],
    },
    {
        id: 4,
        label: 'Blog',
        link: 'blog.index',
    },
    {
        id: 5,
        label: 'FAQs',
        link: 'faqs',
    },
];

const isDropdownActive = (item) => {
    return item.children?.some((child) => route().current(child.link));
};

const isLinkActive = (item) => {
    return route().current(item.link);
};
</script>

<template>
    <nav
        :class="[
            'w-full px-4 py-5.5 sm:px-6 lg:px-[30px]',
            is_home ? 'bg-linear-aurora' : 'bg-white',
        ]"
    >
        <div class="mx-auto flex max-w-[1400px] items-center justify-between">
            <!-- Logo -->
            <div class="max-w-[130px] sm:max-w-[150px] lg:max-w-[172px]">
                <img
                    src="/assets/images/IcyTales.png"
                    alt="IcyTales Logo"
                    class="h-auto w-full object-cover"
                />
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden items-center gap-[60px] lg:flex">
                <ul class="flex items-center gap-10">
                    <li
                        v-for="navigationItem in navigationItems"
                        :key="navigationItem.id"
                    >
                        <!-- Dropdown -->
                        <DropdownMenu v-if="navigationItem.children">
                            <DropdownMenuTrigger as-child>
                                <button
                                    class="flex items-center gap-2 text-base font-normal transition-colors"
                                    :class="
                                        isDropdownActive(navigationItem)
                                            ? 'text-rose-aura'
                                            : 'text-oxblood'
                                    "
                                >
                                    {{ navigationItem.label }}
                                    <ChevronDown class="h-3 w-3" />
                                </button>
                            </DropdownMenuTrigger>

                            <DropdownMenuContent align="center">
                                <DropdownMenuItem
                                    v-for="childItem in navigationItem.children"
                                    :key="childItem.id"
                                    as-child
                                >
                                    <Link :href="route(childItem.link)">
                                        {{ childItem.label }}
                                    </Link>
                                </DropdownMenuItem>
                            </DropdownMenuContent>
                        </DropdownMenu>

                        <!-- Normal Link -->
                        <Link
                            v-else
                            :href="route(navigationItem.link)"
                            class="text-base font-normal transition-colors"
                            :class="
                                isLinkActive(navigationItem)
                                    ? 'text-rose-aura'
                                    : 'text-oxblood'
                            "
                        >
                            {{ navigationItem.label }}
                        </Link>
                    </li>
                </ul>

                <div class="flex items-center gap-8">
                    <button>
                        <Search class="h-5 w-5" />
                    </button>

                    <Link :href="route('cart.index')" class="relative">
                        <ShoppingBag class="h-5 w-5" />
                        <span
                            class="bg-purple-rich absolute -right-1 -bottom-1 flex h-[13px] w-[13px] items-center justify-center rounded-full text-center text-[7px] text-white"
                        >
                            {{ cartCount }}
                        </span>
                    </Link>
                </div>
                <Link v-if="!user && is_admin_login" href="/admin/dashboard">
                    <LayoutDashboard class="text-primary-900 h-5 w-5" />
                </Link>
                <ProfileDropdown
                    v-if="user && !is_admin_login"
                    :is_admin_login="is_admin_login"
                    :dashboardUrl="dashboardUrl"
                    :role="role"
                />

                <div class="flex items-center gap-5">
                    <Link
                        v-if="!user && !is_admin_login"
                        :href="route('login')"
                        class="btn-aura-md border-rose-aura text-oxblood border bg-transparent px-10"
                    >
                        Login
                    </Link>

                    <button class="btn-aura-md">
                        <span>Contact Us</span>

                        <ArrowRight class="h-3 w-3" />
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div class="flex items-center gap-4 lg:hidden">
                <button>
                    <Search class="h-5 w-5" />
                </button>

                <button class="relative">
                    <ShoppingBag class="h-5 w-5" />
                    <span
                        class="bg-purple-rich absolute right-0 bottom-0 flex h-[13px] w-[13px] items-center justify-center rounded-full text-white"
                    >
                        <!-- {{ cartCount }} -->
                        2
                    </span>
                </button>

                <Sheet>
                    <SheetTrigger as-child>
                        <button
                            class="flex h-10 w-10 items-center justify-center rounded-lg border"
                        >
                            <Menu class="h-5 w-5" />
                        </button>
                    </SheetTrigger>

                    <SheetContent side="right" class="w-[320px]">
                        <SheetHeader>
                            <SheetTitle> Menu </SheetTitle>
                        </SheetHeader>

                        <div class="mt-8 flex flex-col gap-6">
                            <div
                                v-for="navigationItem in navigationItems"
                                :key="navigationItem.id"
                            >
                                <!-- Mobile Dropdown -->
                                <div v-if="navigationItem.children">
                                    <p class="text-oxblood mb-3 font-semibold">
                                        {{ navigationItem.label }}
                                    </p>

                                    <div class="ml-4 flex flex-col gap-3">
                                        <Link
                                            v-for="child in navigationItem.children"
                                            :key="child.id"
                                            :href="route(child.link)"
                                            class="text-muted-foreground text-sm"
                                        >
                                            {{ child.label }}
                                        </Link>
                                    </div>
                                </div>

                                <!-- Mobile Link -->
                                <Link
                                    v-else
                                    :href="route(navigationItem.link)"
                                    class="text-oxblood block font-medium"
                                >
                                    {{ navigationItem.label }}
                                </Link>
                            </div>

                            <div class="border-t pt-6">
                                <button
                                    class="btn-aura-md w-full justify-center"
                                >
                                    <span> Contact Us </span>

                                    <ArrowRight class="h-3 w-3" />
                                </button>
                            </div>
                        </div>
                    </SheetContent>
                </Sheet>
            </div>
        </div>
    </nav>
</template>
