<script setup>
import PublicLayout from '../../Layouts/PublicLayout.vue';
import AppCard from '../../Components/AppCard.vue';

defineOptions({ layout: PublicLayout });
defineProps({ perangkat: Array });
</script>

<template>
    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-slate-800 md:text-4xl">Profil dan Lembaga Desa</h1>
        <p class="mt-3 max-w-3xl text-slate-500">Informasi ringkas struktur pemerintahan, layanan, dan wilayah kerja desa.</p>

        <div class="mt-8 grid gap-4 md:grid-cols-3">
            <AppCard v-for="orang in perangkat" :key="orang.jabatan">
                <p class="text-sm font-semibold text-teal-700">{{ orang.jabatan }}</p>
                <h2 class="mt-2 text-xl font-semibold text-slate-800">{{ orang.nama }}</h2>
            </AppCard>
        </div>

        <div class="mt-10 rounded-lg border border-slate-200 bg-white p-6">
            <h2 class="text-2xl font-semibold text-slate-800">Peta Wilayah</h2>
            <div class="mt-4 flex min-h-72 items-center justify-center rounded-md border border-dashed border-slate-300 bg-slate-50 text-center text-slate-500">
                Peta OpenStreetMap/Leaflet dapat dimuat lazy di sini saat koordinat desa sudah tersedia.
            </div>
        </div>
    </section>
</template>
