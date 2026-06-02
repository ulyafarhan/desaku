<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const flash = computed(() => page.props.flash || {});
</script>

<template>
    <div class="fixed right-4 top-4 z-50 space-y-2">
        <div v-if="flash.success" class="rounded-md bg-emerald-600 px-4 py-3 text-sm font-medium text-white shadow-lg">{{ flash.success }}</div>
        <div v-if="flash.error" class="rounded-md bg-red-600 px-4 py-3 text-sm font-medium text-white shadow-lg">{{ flash.error }}</div>
    </div>
</template>
