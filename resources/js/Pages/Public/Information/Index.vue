<script setup>
import PublicLayout from '../../../Layouts/PublicLayout.vue';
import AppCard from '../../../Components/AppCard.vue';
import AppButton from '../../../Components/AppButton.vue';
import EmptyState from '../../../Components/EmptyState.vue';

defineOptions({ layout: PublicLayout });
defineProps({ informasi: Object, kategori: Array });
</script>

<template>
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-800 md:text-4xl">Informasi Publik</h1>
        <p class="mt-3 text-slate-500">Berita, agenda, dan pengumuman resmi desa.</p>
        <div v-if="informasi.data?.length" class="mt-8 grid gap-4 md:grid-cols-3">
            <AppCard v-for="item in informasi.data" :key="item.id">
                <p class="text-sm font-semibold text-teal-700">{{ item.kategori }}</p>
                <h2 class="mt-2 text-lg font-semibold text-slate-800">{{ item.judul }}</h2>
                <p class="mt-3 line-clamp-4 text-sm text-slate-500" v-html="item.konten" />
                <AppButton :href="`/informasi/${item.slug}`" variant="ghost" class="mt-4 px-0">Baca</AppButton>
            </AppCard>
        </div>
        <EmptyState v-else class="mt-8" title="Belum ada informasi" message="Informasi publik akan tampil setelah dipublikasikan admin." />
    </section>
</template>
