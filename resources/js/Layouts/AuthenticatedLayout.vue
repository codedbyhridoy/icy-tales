<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Button } from '@/Components/ui/button';
import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/Components/ui/collapsible';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuLabel,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Separator } from '@/Components/ui/separator';
import {
    Sidebar,
    SidebarContent,
    SidebarGroup,
    SidebarGroupLabel,
    SidebarHeader,
    SidebarInset,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    SidebarMenuSub,
    SidebarMenuSubButton,
    SidebarMenuSubItem,
    SidebarProvider,
    SidebarRail,
    SidebarTrigger,
} from '@/Components/ui/sidebar';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BadgeCheck,
    BookOpen,
    ChartLine,
    ChevronRight,
    CircleUser,
    Command,
    LayoutDashboard,
    LogOut,
    Package,
    PieChart,
    Settings,
    Settings2,
    ShoppingBag,
    SquareTerminal,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Toaster } from 'vue-sonner';
import 'vue-sonner/style.css';

const page = usePage();
const permissions = computed(() => page.props.auth.can);

const data = {
    user: {
        name: 'shadcn',
        email: 'm@example.com',
        avatar: '/avatars/shadcn.jpg',
    },
    teams: [
        {
            name: 'Evil Corp.',
            logo: Command,
            plan: 'Free',
        },
    ],
    navMain: [
        {
            title: page.props.lang['Dashboard'],
            url: route('super_admin.dashboard'),
            icon: LayoutDashboard,
            hasPermission: true,
            isActive: route().current('super_admin.dashboard'),
        },
        {
            title: page.props.lang['Users'] ?? 'Users',
            url: route('super_admin.users.index'),
            icon: SquareTerminal,
            isActive: route().current('super_admin.users.index'),
            hasPermission: permissions.value['manage role-permission'] ?? false,
        },
        {
            title: 'Store',
            icon: Package,
            isActive:
                route().current('super_admin.products.*') ||
                route().current('super_admin.categories.*') ||
                route().current('super_admin.reviews.*'),
            hasPermission:
                permissions.value['manage products'] ||
                permissions.value['manage categories'] ||
                permissions.value['manage reviews'],
            items: [
                {
                    title: 'Categories',
                    url: route('super_admin.categories.index'),
                    hasPermission: permissions.value['manage categories'],
                },
                {
                    title: 'Products',
                    url: route('super_admin.products.index'),
                    hasPermission: permissions.value['manage products'],
                },
                {
                    title: 'Reviews',
                    url: route('super_admin.reviews.index'),
                    hasPermission: permissions.value['manage reviews'],
                },
            ],
        },
        {
            title: 'Posts',
            icon: BookOpen,
            isActive:
                route().current('super_admin.blogs.*') ||
                route().current('super_admin.blog-categories.*') ||
                route().current('super_admin.tags.*'),
            hasPermission:
                permissions.value['manage blogs'] ||
                permissions.value['manage blog-categories'] ||
                permissions.value['manage tags'],
            items: [
                {
                    title: 'Blogs',
                    url: route('super_admin.blogs.index'),
                    hasPermission: permissions.value['manage blogs'],
                },
                {
                    title: 'Categories',
                    url: route('super_admin.blog-categories.index'),
                    hasPermission: permissions.value['manage blog-categories'],
                },
                {
                    title: 'Tags',
                    url: route('super_admin.tags.index'),
                    hasPermission: permissions.value['manage tags'],
                },
                // {
                //     title: 'Comments',
                //     url: route('super_admin.comments.index'),
                // },
            ],
        },
        {
            title: page.props.lang['Orders'] ?? 'Orders',
            url: route('super_admin.orders.index'),
            icon: ShoppingBag,
            isActive: route().current('super_admin.orders.index'),
            hasPermission: permissions.value['manage orders'] ?? false,
        },
        {
            title: page.props.lang['Members'] ?? 'Members',
            url: route('super_admin.team-members.index'),
            icon: Users,
            isActive:
                route().current('super_admin.team-members.index') ||
                route().current('super_admin.team-members.create') ||
                route().current('super_admin.team-members.show') ||
                route().current('super_admin.team-members.edit'),
            hasPermission: permissions.value['manage members'] ?? false,
        },
        {
            title: page.props.lang['Statistics'] ?? 'Statistics',
            url: route('super_admin.statistics.index'),
            icon: ChartLine,
            isActive:
                route().current('super_admin.statistics.index') ||
                route().current('super_admin.statistics.create'),
            hasPermission: permissions.value['manage members'] ?? false,
        },
        {
            title:
                page.props.lang['Roles & Permissions'] ?? 'Roles & Permissions',
            url: '#',
            icon: SquareTerminal,
            hasPermission: permissions.value['manage role-permission'] ?? false,
            isActive:
                route().current('super_admin.permissions.index') ||
                route().current('super_admin.roles.index') ||
                route().current('super_admin.role.assign-permission') ||
                route().current('super_admin.permission.assign-role'),
            items: [
                {
                    title: 'Roles',
                    url: route('super_admin.roles.index'),
                },
                {
                    title: 'Permissions',
                    url: route('super_admin.permissions.index'),
                },
            ],
        },
        {
            title: page.props.lang['Settings'],
            url: '#',
            icon: Settings2,
            items: [
                {
                    title: 'General',
                    url: '#',
                },
                {
                    title: 'Billing',
                    url: '#',
                },
            ],
        },
    ],
    pharmacies: [
        {
            name: 'Sales & Marketing',
            url: '#',
            icon: PieChart,
        },
    ],
    settings: [
        {
            name: page.props.lang['Settings'],
            url: route('super_admin.settings.index'),
            icon: Settings,
            hasPermission: permissions.value['manage setting'] ?? false,
            isActive: route().current('super_admin.settings.index'),
        },
    ],
};
</script>

<template>
    <div class="h-full w-full">
        <Toaster
            position="top-right"
            closeButton="true"
            closeButtonPosition="top-left"
            richColors
        />
        <SidebarProvider>
            <Sidebar collapsible="icon">
                <SidebarHeader>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <DropdownMenu>
                                <DropdownMenuTrigger as-child>
                                    <SidebarMenuButton
                                        size="lg"
                                        class="data-[state=open]:bg-sidebar-accent data-[state=open]:text-sidebar-accent-foreground"
                                    >
                                        <div
                                            class="bg-0wh bg-sidebar-primary text-sidebar-primary-foreground flex aspect-square size-8 w-100 items-center rounded-lg"
                                        >
                                            <Link
                                                :href="
                                                    route(
                                                        'super_admin.dashboard',
                                                    )
                                                "
                                            >
                                                <ApplicationLogo
                                                    class="h-auto w-[120px]"
                                                />
                                            </Link>
                                        </div>
                                        <!-- <div
                                            class="grid flex-1 text-left text-sm leading-tight"
                                        >
                                            <span class="truncate font-semibold"
                                                >Dental Match</span
                                            >
                                        </div> -->
                                    </SidebarMenuButton>
                                </DropdownMenuTrigger>
                            </DropdownMenu>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarHeader>
                <SidebarContent>
                    <SidebarGroup>
                        <SidebarGroupLabel>{{
                            $page.props.lang.Platform
                        }}</SidebarGroupLabel>
                        <SidebarMenu>
                            <div v-for="item in data.navMain" :key="item.title">
                                <template v-if="item.hasPermission">
                                    <template v-if="item.items?.length">
                                        <Collapsible
                                            :default-open="item.isActive"
                                            class="group/collapsible"
                                        >
                                            <SidebarMenuItem>
                                                <CollapsibleTrigger as-child>
                                                    <SidebarMenuButton
                                                        :tooltip="item.title"
                                                    >
                                                        <component
                                                            :is="item.icon"
                                                        />
                                                        <span>{{
                                                            item.title
                                                        }}</span>
                                                        <ChevronRight
                                                            class="ml-auto transition-transform duration-200 group-data-[state=open]/collapsible:rotate-90"
                                                        />
                                                    </SidebarMenuButton>
                                                </CollapsibleTrigger>
                                                <CollapsibleContent>
                                                    <SidebarMenuSub>
                                                        <CollapsibleContent>
                                                            <SidebarMenuSub>
                                                                <SidebarMenuSubItem
                                                                    v-for="subItem in item.items.filter(
                                                                        (i) =>
                                                                            i.hasPermission,
                                                                    )"
                                                                    :key="
                                                                        subItem.title
                                                                    "
                                                                >
                                                                    <SidebarMenuSubButton
                                                                        as-child
                                                                    >
                                                                        <Link
                                                                            :href="
                                                                                subItem.url
                                                                            "
                                                                        >
                                                                            <span
                                                                                >{{
                                                                                    subItem.title
                                                                                }}</span
                                                                            >
                                                                        </Link>
                                                                    </SidebarMenuSubButton>
                                                                </SidebarMenuSubItem>
                                                            </SidebarMenuSub>
                                                        </CollapsibleContent>
                                                    </SidebarMenuSub>
                                                </CollapsibleContent>
                                            </SidebarMenuItem>
                                        </Collapsible>
                                    </template>

                                    <template v-else>
                                        <SidebarMenuItem>
                                            <SidebarMenuButton
                                                :class="{ 'is-active': true }"
                                                :tooltip="item.title"
                                                as-child
                                            >
                                                <Link :href="item.url">
                                                    <component
                                                        :is="item.icon"
                                                    />
                                                    <span>{{
                                                        item.title
                                                    }}</span>
                                                </Link>
                                            </SidebarMenuButton>
                                        </SidebarMenuItem>
                                    </template>
                                </template>
                            </div>
                        </SidebarMenu>
                    </SidebarGroup>
                    <SidebarGroup class="group-data-[collapsible=icon]:hidden">
                        <SidebarGroupLabel>{{
                            $page.props.lang['Settings']
                        }}</SidebarGroupLabel>
                        <SidebarMenu>
                            <SidebarMenuItem
                                v-for="item in data.settings"
                                :key="item.name"
                            >
                                <SidebarMenuButton as-child>
                                    <a :href="item.url">
                                        <component :is="item.icon" />
                                        <span>{{ item.name }}</span>
                                    </a>
                                </SidebarMenuButton>
                            </SidebarMenuItem>
                        </SidebarMenu>
                    </SidebarGroup>
                </SidebarContent>
                <SidebarRail />
            </Sidebar>
            <SidebarInset>
                <header
                    class="bg-background relative top-2 z-30 flex h-14 items-center gap-2 border-b px-4 sm:h-auto sm:border-0 sm:bg-transparent"
                >
                    <SidebarTrigger class="-ml-1" />
                    <Separator orientation="vertical" class="mr-2 h-4" />
                    <div class="relative ml-auto flex-1 md:grow-0"></div>
                    <DropdownMenu>
                        <DropdownMenuTrigger as-child>
                            <Button
                                variant="secondary"
                                size="icon"
                                class="rounded-full"
                            >
                                <CircleUser class="h-5 w-5" />
                                <span class="sr-only">{{
                                    $page.props.lang['Toggle user menu']
                                }}</span>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <DropdownMenuLabel>
                                {{ $page.props.auth.user.name }}
                                <div class="text-muted-foreground text-xs">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>
                                <Link
                                    :href="route('admin.profile.edit')"
                                    class="flex items-center"
                                >
                                    <BadgeCheck class="mr-2 h-4 w-4" />
                                    {{ $page.props.lang['Profile'] }}
                                </Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem>
                                <Link
                                    :href="route('admin.logout')"
                                    method="post"
                                    class="flex items-center"
                                >
                                    <LogOut class="mr-2 h-4 w-4" />
                                    {{ $page.props.lang['Logout'] }}
                                </Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </header>
                <div class="main-container">
                    <slot />
                </div>
            </SidebarInset>
        </SidebarProvider>
    </div>
</template>
