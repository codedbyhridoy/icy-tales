import { router, usePage } from '@inertiajs/vue3';
import throttle from 'lodash/throttle';
import { ref } from 'vue';

export default function useSearchAndSort(url) {
    const inertiaPage = usePage();
    const search = ref(inertiaPage.props.filters.search || '');
    const sort_by = ref(inertiaPage.props.filters.sort_by || '');
    const sort_type = ref(inertiaPage.props.filters.sort_type || 'asc');
    const per_page = ref(inertiaPage.props.filters.per_page || 10);
    const page = ref(inertiaPage.props.filters.page || 1);

    const throttledUpdateSearch = throttle((newSearch) => {
        search.value = newSearch;
        page.value = 1;
        filterResults();
    }, 200);

    function updateSearch(newSearch) {
        throttledUpdateSearch(newSearch);
    }

    function updateSort(newSortKey) {
        if (sort_by.value === newSortKey) {
            sort_type.value = sort_type.value === 'asc' ? 'desc' : 'asc';
        } else {
            sort_by.value = newSortKey;
            sort_type.value = 'asc';
        }
        filterResults();
    }

    function updatePerPage(newPerPage) {
        per_page.value = newPerPage;
        page.value = 1;
        filterResults();
    }

    function updatePage(newPage) {
        page.value = newPage;
        filterResults();
    }

    function filterResults() {
        router.get(
            url,
            {
                search: search.value,
                sort_by: sort_by.value,
                sort_type: sort_type.value,
                per_page: per_page.value,
                page: page.value,
            },
            {
                preserveState: true,
                replace: true,
            },
        );
    }
    return {
        search,
        sort_by,
        sort_type,
        per_page,
        page,
        updateSearch,
        updateSort,
        updatePerPage,
        updatePage,
    };
}
