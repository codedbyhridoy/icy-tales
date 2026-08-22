<script setup>
import ToastListItem from '@/Components/ToastListItem.vue';
import { router, usePage } from '@inertiajs/vue3';
import { onUnmounted } from 'vue';

import toast from '@/Composables/useToast';

const page = usePage();

let removeFinishEventListener = router.on('finish', () => {
    if (page.props.flash.success) {
        toast.add({
            message: page.props.flash.success,
            type: 'success',
        });
    } else if (page.props.flash.error) {
        toast.add({
            message: page.props.flash.error,
            type: 'error',
        });
    }
});
onUnmounted(() => removeFinishEventListener());

const remove = (index) => {
    toast.remove(index);
};
</script>
<template>
    <TransitionGroup
        tag="div"
        enter-from-class="translate-x-full opacity-0"
        enter-active-class="duration-500"
        leave-active-class="duration-500"
        leave-to-class="translate-x-full opacity-0"
        class="fixed top-4 right-4 z-50 w-full max-w-xs space-y-4"
    >
        <ToastListItem
            v-for="(item, index) in toast.items"
            :key="item.key"
            :message="item.message"
            :type="item.type"
            :duration="1000"
            @remove="remove(index)"
        />
    </TransitionGroup>
</template>
