<template>
    <div class="h-25 w-full bg-white fill-white/50 drop-shadow-xl">
        <div
            class="mx-auto flex h-full max-w-[1440px] items-center justify-between gap-5 px-5 md:px-10"
        >
            <Link
                :href="route('home')"
                class="max-w-[200px] min-w-[100px] transition-all duration-300 md:max-w-[220px] lg:max-w-[290px]"
            >
                <img
                    src="/assets/images\dentalmatch-logo.png"
                    alt="Dental Logo"
                    class="h-auto w-full object-contain"
                />
            </Link>
            <NavigationMenu class="hidden lg:block">
                <NavigationMenuList class="flex items-center gap-10">
                    <NavigationMenuItem
                        v-for="(item, index) in headerItems"
                        :key="index"
                    >
                        <NavigationMenuLink as-child>
                            <Link
                                v-if="item.name == 'Feedback'"
                                @click="isFeedbackOpen = true"
                                class="font-outfit text-secondary-600 text-lg font-normal whitespace-nowrap hover:bg-transparent"
                                :class="
                                    page.url === route(item.path, {}, false)
                                        ? 'font-semibold'
                                        : 'font-normal'
                                "
                            >
                                {{ item.name }}
                            </Link>
                            <Link
                                v-else-if="item.path"
                                :href="route(item.path)"
                                class="font-outfit text-secondary-600 text-base leading-[100%] font-normal whitespace-nowrap hover:bg-transparent"
                                :class="
                                    page.url === route(item.path, {}, false)
                                        ? 'font-semibold'
                                        : 'font-normal'
                                "
                            >
                                {{ item.name }}
                            </Link>
                            <a
                                v-else
                                href="#"
                                class="font-outfit text-secondary-600 text-base leading-[100%] font-normal whitespace-nowrap hover:bg-transparent"
                                >{{ item.name }}</a
                            >
                        </NavigationMenuLink>
                    </NavigationMenuItem>
                    <Link
                        v-if="!user && is_admin_login"
                        href="/admin/dashboard"
                    >
                        <LayoutDashboard class="text-primary-900 h-5 w-5" />
                    </Link>
                    <Link
                        v-if="!user"
                        :href="route('login')"
                        class="bg-accent-500 text-primary-900 rounded-[20px] px-6 py-3.5 transition-colors duration-200 hover:bg-[#99d6c3]"
                    >
                        {{ $page.props.lang['Login'] }}
                    </Link>
                    <ProfileDropdown
                        v-else
                        :is_admin_login="is_admin_login"
                        :dashboardUrl="dashboardUrl"
                        :role="role"
                    />
                </NavigationMenuList>
            </NavigationMenu>

            <Sheet>
                <SheetTrigger class="block lg:hidden" as-child>
                    <button>
                        <Menu class="stroke-secondary-600 size-6" />
                    </button>
                </SheetTrigger>
                <SheetContent side="left">
                    <SheetHeader>
                        <SheetClose
                            class="absolute top-5 right-5 cursor-pointer"
                        >
                            <X class="h-5 w-5" />
                        </SheetClose>
                    </SheetHeader>
                    <ul
                        class="mt-20 flex flex-col items-center justify-center gap-5"
                    >
                        <li v-for="(item, index) in headerItems" :key="index">
                            <SheetClose as-child>
                                <Link
                                    v-if="item.name == 'Feedback'"
                                    @click="isFeedbackOpen = true"
                                    class="font-outfit text-secondary-600 text-lg leading-[100%] font-normal whitespace-nowrap hover:bg-transparent"
                                    :class="
                                        page.url === route(item.path, {}, false)
                                            ? 'font-semibold'
                                            : 'font-normal'
                                    "
                                >
                                    {{ item.name }}
                                </Link>
                                <Link
                                    v-else-if="item.path"
                                    :href="route(item.path)"
                                    class="font-outfit text-secondary-600 text-lg leading-[100%] font-normal whitespace-nowrap hover:bg-transparent"
                                    :class="
                                        page.url === route(item.path, {}, false)
                                            ? 'font-semibold'
                                            : 'font-normal'
                                    "
                                >
                                    {{ item.name }}
                                </Link>
                                <a
                                    v-else
                                    href="font-outfit text-secondary-600 hover:bg-transparent font-normal text-lg leading-[100%] whitespace-nowrap"
                                    >{{ item.name }}</a
                                >
                            </SheetClose>
                        </li>

                        <li class="w-full" v-if="user && isPublicRoute">
                            <div class="grid gap-2 px-2">
                                <SheetClose as-child>
                                    <Link
                                        :href="dashboardUrl"
                                        class="text-secondary-600 hover:bg-secondary-50 flex w-full items-center justify-center gap-2 rounded-md px-3"
                                    >
                                        <LayoutDashboard class="h-4 w-4" />
                                        <span>{{
                                            $page.props.lang['Dashboard']
                                        }}</span>
                                    </Link>
                                </SheetClose>
                            </div>
                        </li>
                        <div class="mt-5" v-if="!user">
                            <SheetClose as-child>
                                <Link
                                    :href="route('login')"
                                    class="bg-accent-500 text-primary-900 rounded-[20px] px-6 py-3.5 transition-colors duration-200 hover:bg-[#99d6c3]"
                                >
                                    {{ $page.props.lang['Login'] }}
                                </Link>
                            </SheetClose>
                        </div>
                        <li class="w-full" v-if="user">
                            <div class="grid gap-2 px-2">
                                <SheetClose as-child>
                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        class="text-secondary-600 hover:bg-secondary-50 flex w-full items-center justify-center gap-2 rounded-md px-3"
                                    >
                                        <LogOut class="h-4 w-4" />
                                        <span>{{
                                            $page.props.lang['Logout']
                                        }}</span>
                                    </Link>
                                </SheetClose>
                            </div>
                        </li>
                    </ul>
                </SheetContent>
            </Sheet>
        </div>
        <FeedbackSheet v-model:open="isFeedbackOpen" />
    </div>
</template>

<script setup>
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuLink,
    NavigationMenuList,
} from '@/Components/ui/navigation-menu';

import {
    Sheet,
    SheetClose,
    SheetContent,
    SheetHeader,
    SheetTrigger,
} from '@/Components/ui/sheet';
import { Link } from '@inertiajs/vue3';

import FeedbackSheet from '@/Components/DashboardComponents/FeedbackModal.vue';
import ProfileDropdown from '@/Components/ProfileDropdown.vue';
import { usePage } from '@inertiajs/vue3';
import { LayoutDashboard, LogOut, Menu, X } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const page = usePage();
const user = computed(() => page.props.auth.user);
const role = computed(() => page.props.auth.role);
const is_admin_login = computed(() => page.props.auth.is_admin_login);

const isFeedbackOpen = ref(false);

const openFeedback = () => {
    isFeedbackOpen.value = true;
};
const handler = () => openFeedback();
onMounted(() => window.addEventListener('open-menubar-modal', handler));
onBeforeUnmount(() =>
    window.removeEventListener('open-menubar-modal', handler),
);

const publicNames = ['practice', 'professional', 'news', 'contact', 'about'];
const isPublicRoute = computed(() => {
    const url = (page.url || '/').split('?')[0];
    if (url === '/') return true;
    return publicNames.some((p) => url === `/${p}` || url.startsWith(`/${p}/`));
});

const navigationItems = [
    { name: 'Für Praxen', path: 'practice' },
    { name: 'Für Fachpersonal', path: 'professional' },
    { name: 'News', path: 'news' },
    { name: 'Kontakt', path: 'contact' },
    { name: 'Über uns', path: 'about' },
];
const PracticeNavigationItems = [
    { name: 'Fachpersonal suchen', path: 'directory' },
    { name: 'Meine Inserate', path: 'advertisements' },
    { name: 'Profil', path: 'practice.userprofile' },
    { name: 'Benachrichtigungen', path: 'dentnotifications' },
    { name: 'Feedback', path: 'jobfeedback', trigger: 'yes' },
];
const SpecialistNavigationItems = [
    { name: 'Entdecken', path: 'discoverjob' },
    { name: 'Einsätze', path: 'assignments' },
    { name: 'Profil', path: 'userprofile' },
    { name: 'Benachrichtigungen', path: 'notifications' },
    { name: 'Feedback', path: 'jobfeedback', trigger: 'yes' },
];

const dashboardUrl = computed(() => {
    if (role.value === 'specialist') return '/specialists/findjobs';
    if (role.value === 'practice') return '/practices/find-specialist';
    return '/admin/dashboard';
});

const headerItems = computed(() => {
    if (isPublicRoute.value) return navigationItems;

    if (role.value === 'practice') return PracticeNavigationItems;
    if (role.value === 'specialist') return SpecialistNavigationItems;

    return navigationItems;
});
</script>
