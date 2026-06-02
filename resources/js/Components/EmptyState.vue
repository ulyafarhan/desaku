<script setup>
defineProps({
    title: { type: String, required: true },
    message: { type: String, required: true },
});
</script>

<template>
    <div class="rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center">
        <div class="mx-auto mb-4 flex size-14 items-center justify-center rounded-full bg-teal-50 text-2xl text-teal-700">i</div>
        <h3 class="text-lg font-semibold text-slate-800">{{ title }}</h3>
        <p class="mt-1 text-sm text-slate-500">{{ message }}</p>
        <div v-if="$slots.default" class="mt-5">
            <slot />
        </div>
    </div>
</template>
