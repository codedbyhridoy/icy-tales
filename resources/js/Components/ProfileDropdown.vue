<template>
    <div class="flex items-center gap-10">
        <Link v-if="is_admin_login" href="/admin/dashboard">
            <LayoutDashboard class="text-primary-900 h-5 w-5" />
        </Link>
        <DropdownMenu>
            <DropdownMenuTrigger as-child class="cursor-pointer">
                <button
                    type="button"
                    class="hover:bg-secondary-100 inline-flex items-center justify-center rounded-full p-1.5 transition focus:outline-none"
                    aria-label="Open user menu"
                >
                    <UserCircle class="text-primary-900 h-5 w-5" />
                </button>
            </DropdownMenuTrigger>

            <DropdownMenuContent align="end" class="w-50">
                <!-- Dashboard -->
                <DropdownMenuItem as-child>
                    <Link
                        v-if="role"
                        :href="dashboardUrl"
                        class="flex w-full items-center gap-2"
                    >
                        <LayoutDashboard class="h-4 w-4" />
                        <span>{{ $page.props.lang['Dashboard'] }}</span>
                    </Link>
                </DropdownMenuItem>

                <!-- Logout -->
                <DropdownMenuItem as-child>
                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="flex w-full items-center gap-2"
                    >
                        <LogOut class="h-4 w-4" />
                        <span>{{ $page.props.lang['Logout'] }}</span>
                    </Link>
                </DropdownMenuItem>
            </DropdownMenuContent>
        </DropdownMenu>
    </div>
</template>
<script setup>
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu';
import { Link } from '@inertiajs/vue3';
import { LayoutDashboard, LogOut, UserCircle } from 'lucide-vue-next';
defineProps({
    is_admin_login: {
        type: Boolean,
        default: false,
    },
    role: {
        type: String,
    },
    dashboardUrl: {
        type: String,
    },
});
</script>
