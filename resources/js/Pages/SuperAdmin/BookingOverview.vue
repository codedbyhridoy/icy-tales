<script setup>
import Pagination from '@/Components/Pagination.vue';
import SearchInput from '@/Components/SearchInput.vue';
import useSearchAndSort from '@/Composables/useSearchAndSort';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { formatRangeFormat } from '@/lib/utils';
import { Head, usePage } from '@inertiajs/vue3';
import { ArrowDownUp } from 'lucide-vue-next';
import { computed } from 'vue';

import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/Components/ui/table';

const {
    search,
    sort_by,
    sort_type,
    per_page,
    updateSearch,
    updateSort,
    updatePerPage,
} = useSearchAndSort(route('super_admin.booking-overview'));

const props = defineProps({
    bookings: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        required: false,
    },
});

const page = usePage();
const can = computed(() => page.props.auth.can);
</script>

<template>
    <Head
        title="{{ $page.props.lang['Booking Overview'] ??
                                'Booking Overview' }}"
    />

    <AuthenticatedLayout>
        <div class="py-8">
            <div class="max-w-8xl mx-auto space-y-6 sm:px-6 lg:px-8">
                <div
                    v-if="can['manage booking-overview'] ?? false"
                    class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    <div class="flex items-center justify-between pb-[20px]">
                        <h2
                            class="text-1xl mb-4 leading-none font-extrabold tracking-tight text-gray-900 md:text-2xl dark:text-white"
                        >
                            {{
                                $page.props.lang['Booking Overview'] ??
                                'Booking Overview'
                            }}
                        </h2>
                    </div>

                    <SearchInput
                        v-model="search"
                        :per-page="per_page"
                        :search-text="$page.props.lang['Search']"
                        :per-page-text="$page.props.lang['Per Page']"
                        @update:modelValue="updateSearch"
                        @updatePerPage="updatePerPage"
                    />
                    <div class="w-full overflow-x-auto scrollbar-hide rounded-md border">
                        <div class="w-full max-w-[1500px]">
                            <Table>
                                <TableHeader>
                                    <TableRow>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Job Type'
                                                    ] ?? 'Job Type'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Hourly Rate'
                                                    ] ?? 'Hourly Rate'
                                                }}</span>
                                                <ArrowDownUp
                                                    @click="
                                                        updateSort(
                                                            'hourly_rate',
                                                        )
                                                    "
                                                    height="16"
                                                    class="cursor-pointer"
                                                    :class="
                                                        sort_type === 'desc' &&
                                                        sort_by ===
                                                            'hourly_rate'
                                                            ? 'text-slate-900'
                                                            : ''
                                                    "
                                                />
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Schedule Time'
                                                    ] ?? 'Schedule Time'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Company Name'
                                                    ] ?? 'Company Name'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Practice Email'
                                                    ] ?? 'Practice Email'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Practice Telephone'
                                                    ] ?? 'Practice Telephone'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Practice Address'
                                                    ] ?? 'Practice Address'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Specialist Name'
                                                    ] ?? 'Specialist Name'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Specialist Email'
                                                    ] ?? 'Specialist Email'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Specialist Telephone'
                                                    ] ?? 'Specialist Telephone'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2 text-nowrap"
                                            >
                                                <span>{{
                                                    $page.props.lang[
                                                        'Specialist Address'
                                                    ] ?? 'Specialist Address'
                                                }}</span>
                                            </div>
                                        </TableHead>
                                        <TableHead>
                                            <div
                                                class="inline-flex items-center justify-center gap-2"
                                            >
                                                <span>{{
                                                    $page.props.lang['Status']
                                                }}</span>
                                            </div>
                                        </TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    <TableRow
                                        v-for="booking in bookings.data"
                                        :key="booking.id"
                                    >
                                        <TableCell>
                                            {{ booking.job_post.job_type }}
                                        </TableCell>
                                        <TableCell>
                                            {{ booking.job_post.hourly_rate }} ₣
                                        </TableCell>
                                        <TableCell>
                                            <span class="text-nowrap">
                                                {{
                                                    formatRangeFormat(
                                                        booking.job_post
                                                            .start_date,
                                                        booking.job_post
                                                            .end_date,
                                                    )
                                                }}
                                            </span>
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.job_post
                                                    .practice_profile
                                                    .company_name
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.job_post
                                                    .practice_profile.user.email
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.job_post
                                                    .practice_profile.user
                                                    .telephone
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.job_post
                                                    .practice_profile.address
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.specialist_profile.user
                                                    .first_name
                                            }}
                                            {{
                                                booking.specialist_profile.user
                                                    .last_name
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.specialist_profile.user
                                                    .email
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.specialist_profile.user
                                                    .telephone
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            {{
                                                booking.specialist_profile
                                                    .address
                                            }}
                                        </TableCell>
                                        <TableCell>
                                            <span class="capitalize">{{
                                                booking.status
                                            }}</span>
                                        </TableCell>
                                    </TableRow>
                                    <TableRow v-if="bookings.data.length === 0">
                                        <TableCell
                                            colspan="12"
                                            class="h-24 text-center"
                                        >
                                            {{ $page.props.lang['No data.'] }}
                                        </TableCell>
                                    </TableRow>
                                </TableBody>
                            </Table>
                        </div>
                    </div>

                    <div class="flex items-center justify-end space-x-2 py-4">
                        <div class="space-x-2">
                            <Pagination :links="props.bookings.links" />
                        </div>
                    </div>
                </div>
                <div
                    v-else
                    class="bg-white p-4 text-center shadow-sm sm:rounded-lg sm:p-8 dark:bg-gray-800"
                >
                    {{ $page.props.lang["You don't have permission"] }}
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.scrollbar-hide {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;     /* Firefox */
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;    
  scrollbar-width: 0;
}
</style>