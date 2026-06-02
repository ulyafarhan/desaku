<script setup>
import CitizenLayout from '../../../Layouts/CitizenLayout.vue';
import AppButton from '../../../Components/AppButton.vue';
import AppCard from '../../../Components/AppCard.vue';
import StatusBadge from '../../../Components/StatusBadge.vue';

defineOptions({ layout: CitizenLayout });
defineProps({ pengajuan: Object });
</script>

<template>
    <div class="space-y-6">
        <AppButton href="/warga/dashboard" variant="ghost" class="px-0">Kembali ke dashboard</AppButton>
        <section class="grid gap-4 lg:grid-cols-[1fr_.8fr]">
            <AppCard>
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <p class="text-sm text-slate-500">{{ pengajuan.nomor_registrasi }}</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-800">{{ pengajuan.kategori?.nama_surat }}</h1>
                    </div>
                    <StatusBadge :status="pengajuan.status" />
                </div>
                <p v-if="pengajuan.catatan_penolakan" class="mt-4 rounded-md bg-red-50 p-3 text-sm text-red-700">{{ pengajuan.catatan_penolakan }}</p>
                <a v-if="pengajuan.file_pdf_url" :href="pengajuan.file_pdf_url" class="mt-4 inline-flex text-sm font-semibold text-teal-700">Unduh PDF Surat</a>
            </AppCard>
            <AppCard>
                <h2 class="font-semibold text-slate-800">Pemohon</h2>
                <p class="mt-2 text-slate-700">{{ pengajuan.pemohon?.nama_lengkap }}</p>
                <p class="text-sm text-slate-500">{{ pengajuan.nik_pemohon }}</p>
            </AppCard>
        </section>

        <section class="grid gap-4 lg:grid-cols-2">
            <AppCard>
                <h2 class="font-semibold text-slate-800">Data Isian</h2>
                <dl class="mt-4 grid gap-3 text-sm">
                    <div v-for="(value, key) in pengajuan.data_isian" :key="key" class="rounded-md bg-slate-50 p-3">
                        <dt class="font-semibold capitalize text-slate-700">{{ String(key).replaceAll('_', ' ') }}</dt>
                        <dd class="text-slate-600">{{ value }}</dd>
                    </div>
                </dl>
            </AppCard>
            <AppCard>
                <h2 class="font-semibold text-slate-800">Dokumen Syarat</h2>
                <dl class="mt-4 grid gap-3 text-sm">
                    <div v-for="(value, key) in pengajuan.file_syarat" :key="key" class="rounded-md bg-slate-50 p-3">
                        <dt class="font-semibold capitalize text-slate-700">{{ String(key).replaceAll('_', ' ') }}</dt>
                        <dd><a :href="value" class="text-teal-700 underline" target="_blank">{{ value }}</a></dd>
                    </div>
                </dl>
            </AppCard>
        </section>

        <AppCard>
            <h2 class="font-semibold text-slate-800">Timeline Status</h2>
            <ol class="mt-4 space-y-3">
                <li v-for="track in pengajuan.tracking" :key="track.id" class="border-l-2 border-teal-600 pl-4">
                    <StatusBadge :status="track.status_baru" />
                    <p class="mt-1 text-sm text-slate-600">{{ track.keterangan_update }}</p>
                    <p class="mt-1 text-xs text-slate-400">{{ track.created_at }}</p>
                </li>
            </ol>
        </AppCard>
    </div>
</template>
