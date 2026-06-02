<script setup>
import PublicLayout from '../../Layouts/PublicLayout.vue';
import AppButton from '../../Components/AppButton.vue';
import AppCard from '../../Components/AppCard.vue';
import EmptyState from '../../Components/EmptyState.vue';

defineOptions({ layout: PublicLayout });

defineProps({
    demografi: Object,
    layanan: Object,
    berita: Array,
    kategoriSurat: Array,
});
</script>

<template>
    <section class="relative overflow-hidden bg-teal-900 text-white">
        <div class="absolute inset-0 bg-[linear-gradient(rgba(15,118,110,.86),rgba(15,118,110,.86)),url('/favicon.ico')] bg-cover bg-center" />
        <div class="relative mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 md:grid-cols-[1.2fr_.8fr] lg:px-8 lg:py-24">
            <div>
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-200">Portal Gampong Digital</p>
                <h1 class="mt-3 max-w-3xl text-4xl font-bold leading-tight md:text-5xl">Layanan desa lebih dekat, jelas, dan mudah dipantau.</h1>
                <p class="mt-5 max-w-2xl text-lg text-teal-50">Akses informasi desa, ajukan surat online, dan pantau status administrasi tanpa harus bolak-balik ke kantor desa.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <AppButton href="/login" variant="secondary">Ajukan Surat Online</AppButton>
                    <AppButton href="/profil" variant="outline" class="border-white text-white hover:bg-white/10">Pelajari Profil Desa</AppButton>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <AppCard class="bg-white/95 text-slate-800">
                    <p class="text-sm text-slate-500">Penduduk</p>
                    <p class="mt-2 text-3xl font-bold">{{ demografi?.total_penduduk ?? 0 }}</p>
                </AppCard>
                <AppCard class="bg-white/95 text-slate-800">
                    <p class="text-sm text-slate-500">Keluarga</p>
                    <p class="mt-2 text-3xl font-bold">{{ demografi?.total_keluarga ?? 0 }}</p>
                </AppCard>
                <AppCard class="bg-white/95 text-slate-800">
                    <p class="text-sm text-slate-500">Pengajuan</p>
                    <p class="mt-2 text-3xl font-bold">{{ layanan?.pengajuan_surat?.total ?? 0 }}</p>
                </AppCard>
                <AppCard class="bg-white/95 text-slate-800">
                    <p class="text-sm text-slate-500">Layanan Aktif</p>
                    <p class="mt-2 text-3xl font-bold">{{ kategoriSurat?.length ?? 0 }}</p>
                </AppCard>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-4">
            <div>
                <h2 class="text-2xl font-semibold text-slate-800 md:text-3xl">Layanan Surat</h2>
                <p class="mt-2 text-slate-500">Pilih jenis surat yang tersedia dan ajukan dari portal warga.</p>
            </div>
            <AppButton href="/login" variant="outline">Masuk Warga</AppButton>
        </div>
        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <AppCard v-for="kategori in kategoriSurat" :key="kategori.id">
                <p class="text-sm font-semibold text-amber-700">{{ kategori.kode_surat }}</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-800">{{ kategori.nama_surat }}</h3>
            </AppCard>
        </div>
    </section>

    <section class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold text-slate-800 md:text-3xl">Berita Terbaru</h2>
        <div v-if="berita?.length" class="mt-6 grid gap-4 md:grid-cols-3">
            <AppCard v-for="item in berita" :key="item.id">
                <p class="text-sm font-semibold text-teal-700">{{ item.kategori }}</p>
                <h3 class="mt-2 text-lg font-semibold text-slate-800">{{ item.judul }}</h3>
                <p class="mt-3 line-clamp-3 text-sm text-slate-500" v-html="item.konten" />
                <AppButton :href="`/informasi/${item.slug}`" variant="ghost" class="mt-4 px-0">Baca selengkapnya</AppButton>
            </AppCard>
        </div>
        <EmptyState v-else class="mt-6" title="Belum ada informasi" message="Berita desa akan tampil di sini setelah dipublikasikan." />
    </section>
</template>
