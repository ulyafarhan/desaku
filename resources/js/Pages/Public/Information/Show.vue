<script setup>
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import AppButton from '../../../Components/AppButton.vue';

defineOptions({ layout: PublicLayout });
defineProps({ informasi: Object });
</script>

<template>
    <article class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <AppButton href="/informasi" variant="ghost" class="mb-6 px-0">Kembali ke informasi</AppButton>
        <p class="text-sm font-semibold text-teal-700">{{ informasi.kategori }}</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-800 md:text-4xl">{{ informasi.judul }}</h1>
        <div class="prose prose-slate mt-8 max-w-none text-base leading-7 text-slate-700" v-html="informasi.konten" />
    </article>
</template>
