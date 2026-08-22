<script setup>
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/Components/ui/alert-dialog';
import { defineExpose, ref } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'Are you sure?',
    },
    description: {
        type: String,
        default: 'Are you sure you want to delete this data?',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
});

const emit = defineEmits(['confirm']);

const isOpen = ref(false);

// Method to open the dialog
const open = () => {
    isOpen.value = true;
};

// Method to confirm action
const confirm = () => {
    isOpen.value = false;
    emit('confirm');
};

// Expose the `open` method to be triggered programmatically
defineExpose({ open });
</script>

<template>
    <AlertDialog v-model:open="isOpen">
        <AlertDialogContent>
            <AlertDialogHeader>
                <AlertDialogTitle>{{ title }}</AlertDialogTitle>
                <AlertDialogDescription>{{
                    description
                }}</AlertDialogDescription>
            </AlertDialogHeader>
            <AlertDialogFooter>
                <AlertDialogCancel>{{ cancelText }}</AlertDialogCancel>
                <AlertDialogAction @click="confirm">{{
                    confirmText
                }}</AlertDialogAction>
            </AlertDialogFooter>
        </AlertDialogContent>
    </AlertDialog>
</template>
