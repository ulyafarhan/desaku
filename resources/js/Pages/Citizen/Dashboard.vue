<script setup>
import CitizenLayout from '../../Layouts/CitizenLayout.vue';
import AppButton from '../../Components/AppButton.vue';
import AppCard from '../../Components/AppCard.vue';
import EmptyState from '../../Components/EmptyState.vue';
import StatusBadge from '../../Components/StatusBadge.vue';

defineOptions({ layout: CitizenLayout });
defineProps({
    warga: Object,
    kategoriSurat: Array,
    pengajuan: Object,
    summary: Object,
});
</script>

<template>
    <div class="space-y-8">
        <section class="grid gap-4 md:grid-cols-[1.2fr_.8fr]">
            <div>
                <p class="text-sm font-semibold text-teal-700">Selamat datang</p>
                <h1 class="mt-1 text-3xl font-bold text-slate-800">{{ warga.nama_lengkap }}</h1>
                <p class="mt-2 text-slate-500">Pantau pengajuan surat dan mulai layanan administrasi baru dari dashboard ini.</p>
            </div>
            <AppCard>
                <p class="text-sm text-slate-500">Alamat keluarga</p>
                <p class="mt-2 font-semibold text-slate-800">{{ warga.keluarga?.alamat || 'Belum tersedia' }}</p>
                <p class="mt-1 text-sm text-slate-500">{{ warga.keluarga?.dusun }} {{ warga.keluarga?.rt_rw }}</p>
            </AppCard>
        </section>

        <section class="grid gap-4 sm:grid-cols-3">
            <AppCard><p class="text-sm text-slate-500">Menunggu</p><p class="mt-2 text-3xl font-bold text-amber-700">{{ summary.pending }}</p></AppCard>
            <AppCard><p class="text-sm text-slate-500">Diproses</p><p class="mt-2 text-3xl font-bold text-sky-700">{{ summary.diproses }}</p></AppCard>
            <AppCard><p class="text-sm text-slate-500">Selesai/Disetujui</p><p class="mt-2 text-3xl font-bold text-emerald-700">{{ summary.selesai }}</p></AppCard>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-slate-800">Katalog Pengajuan</h2>
            <div class="mt-4 grid gap-4 md:grid-cols-3">
                <AppCard v-for="kategori in kategoriSurat" :key="kategori.id">
                    <p class="text-sm font-semibold text-amber-700">{{ kategori.kode_surat }}</p>
                    <h3 class="mt-2 font-semibold text-slate-800">{{ kategori.nama_surat }}</h3>
                    <AppButton :href="`/warga/surat/ajukan/${kategori.id}`" class="mt-4 w-full">Ajukan</AppButton>
                </AppCard>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-semibold text-slate-800">Riwayat Pengajuan</h2>
            <div v-if="pengajuan.data?.length" class="mt-4 grid gap-3">
                <AppCard v-for="item in pengajuan.data" :key="item.id" class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="font-semibold text-slate-800">{{ item.kategori?.nama_surat }}</p>
                        <p class="text-sm text-slate-500">{{ item.nomor_registrasi }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <StatusBadge :status="item.status" />
                        <AppButton :href="`/warga/pengajuan/${item.id}`" variant="outline">Detail</AppButton>
                    </div>
                </AppCard>
            </div>
            <EmptyState v-else class="mt-4" title="Belum ada pengajuan" message="Mulai dari katalog pengajuan untuk membuat surat pertama Anda." />
        </section>
    </div>
</template>
