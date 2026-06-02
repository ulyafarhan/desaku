<script setup>
import PublicLayout from '../../Layouts/PublicLayout.vue';
import AppCard from '../../Components/AppCard.vue';
import StatusBadge from '../../Components/StatusBadge.vue';

defineOptions({ layout: PublicLayout });
defineProps({ result: Object });
</script>

<template>
    <section class="mx-auto max-w-3xl px-4 py-12 sm:px-6 lg:px-8">
        <AppCard>
            <StatusBadge :status="result.valid ? 'Selesai' : 'Ditolak'" />
            <h1 class="mt-4 text-2xl font-bold text-slate-800">Verifikasi Dokumen</h1>
            <p class="mt-2 text-slate-500">{{ result.message }}</p>
            <dl v-if="result.valid" class="mt-6 grid gap-3 text-sm">
                <div><dt class="font-semibold text-slate-700">Nomor Registrasi</dt><dd>{{ result.nomor_registrasi }}</dd></div>
                <div><dt class="font-semibold text-slate-700">Jenis Surat</dt><dd>{{ result.jenis_surat }}</dd></div>
                <div><dt class="font-semibold text-slate-700">Pemohon</dt><dd>{{ result.nama_pemohon }} ({{ result.nik_pemohon }})</dd></div>
                <div><dt class="font-semibold text-slate-700">Tanggal Terbit</dt><dd>{{ result.tanggal_terbit }}</dd></div>
            </dl>
        </AppCard>
    </section>
</template>
