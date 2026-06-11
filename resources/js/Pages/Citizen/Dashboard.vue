<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import CitizenLayout from '../../Layouts/CitizenLayout.vue';
import AppButton from '../../Components/AppButton.vue';
import AppCard from '../../Components/AppCard.vue';
import EmptyState from '../../Components/EmptyState.vue';
import StatusBadge from '../../Components/StatusBadge.vue';
import Pagination from '../../Components/Pagination.vue';

defineOptions({ layout: CitizenLayout });

const props = defineProps({
    warga: Object,
    kategoriSurat: Array,
    pengajuan: Object,
    summary: Object,
    biodataComplete: Boolean,
    biodataCompleteness: Number,
    isKepalaKeluarga: Boolean,
    anggotaKeluarga: Array,
});

const activeTab = ref('beranda');
const tabs = [
    { id: 'beranda', label: 'Beranda', icon: 'home' },
    { id: 'pengajuan', label: 'Pengajuan Surat', icon: 'file' },
    { id: 'keluarga', label: 'Keluarga Saya', icon: 'users' },
    { id: 'biodata', label: 'Biodata', icon: 'user' },
];

const setActiveTab = (tabId) => {
    activeTab.value = tabId;
    try {
        const url = new URL(window.location.href);
        url.searchParams.set('tab', tabId);
        window.history.pushState({}, '', url.toString());
    } catch (e) {}
};

const syncTabWithUrl = () => {
    try {
        const params = new URLSearchParams(window.location.search);
        const tab = params.get('tab');
        if (tab && ['beranda', 'pengajuan', 'keluarga', 'biodata'].includes(tab)) {
            activeTab.value = tab;
        }
    } catch (e) {}
};

let pollingTimeout = null;

const startPolling = () => {
    const delay = Math.floor(Math.random() * (45000 - 30000 + 1)) + 30000;
    pollingTimeout = setTimeout(() => {
        router.reload({
            only: ['pengajuan', 'summary'],
            onFinish: () => {
                startPolling();
            }
        });
    }, delay);
};

onMounted(() => {
    syncTabWithUrl();
    startPolling();
});

onUnmounted(() => {
    if (pollingTimeout) {
        clearTimeout(pollingTimeout);
    }
});

const page = usePage();
watch(() => page.url, () => {
    syncTabWithUrl();
});

const recentPengajuan = computed(() => (props.pengajuan?.data || []).slice(0, 3));
const totalAnggota = computed(() => (props.anggotaKeluarga || []).length);

const formatDate = (d) => {
    if (!d) return '-';
    return new Date(d).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const getGenderLabel = (g) => g === 'L' ? 'Laki-laki' : g === 'P' ? 'Perempuan' : '-';

const getStatusColor = (s) => {
    const map = { 'Kepala Keluarga': 'bg-teal-100 text-teal-800', 'Istri': 'bg-purple-100 text-purple-800', 'Anak': 'bg-sky-100 text-sky-800' };
    return map[s] || 'bg-slate-100 text-slate-700';
};

const maskNik = (nik) => {
    if (!nik || nik.length < 8) return nik;
    return nik.slice(0, 4) + '****' + nik.slice(-4);
};

const getInitials = (name) => {
    if (!name) return '?';
    return name.split(' ').slice(0, 2).map(w => w[0]).join('').toUpperCase();
};

const getFileUrl = (path) => {
    if (!path) return '#';
    
    if (path.startsWith('http://') || path.startsWith('https://')) {
        return path;
    }

    if (path.startsWith('/warga/') || path.startsWith('/admin/') || path.startsWith('warga/') || path.startsWith('admin/')) {
        return path.startsWith('/') ? path : '/' + path;
    }
    
    // If it contains /storage/, extract the relative path starting from /storage/
    const storageIdx = path.indexOf('/storage/');
    if (storageIdx !== -1) {
        return path.substring(storageIdx);
    }
    
    return path.startsWith('/') ? '/storage' + path : '/storage/' + path;
};

const requiredFields = ['nama_lengkap', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'agama', 'pendidikan', 'pekerjaan', 'status_perkawinan'];
const fieldLabels = {
    nama_lengkap: 'Nama Lengkap', tempat_lahir: 'Tempat Lahir', tanggal_lahir: 'Tanggal Lahir',
    jenis_kelamin: 'Jenis Kelamin', agama: 'Agama', pendidikan: 'Pendidikan',
    pekerjaan: 'Pekerjaan', status_perkawinan: 'Status Perkawinan',
};
</script>

<template>
    <div class="google-editorial min-h-screen py-8 px-4">
        <!-- Floating Action Button for Mobile -->
        <div class="fixed bottom-20 right-4 z-40 md:hidden">
            <button
                @click="setActiveTab('pengajuan')"
                class="flex size-14 items-center justify-center rounded-full bg-primary text-white shadow-lg active:scale-95 transition-all border border-blue-500/10"
            >
                <svg class="size-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg></button>
        </div>

        <div class="max-w-4xl mx-auto space-y-8">
            <!-- Tab Navigation (Desktop only) -->
            <div class="hidden md:flex gap-1 overflow-x-auto rounded-full border border-slate-200 bg-white p-1.5 shadow-sm max-w-max mx-auto">
                <button
                    v-for="tab in tabs" :key="tab.id"
                    @click="setActiveTab(tab.id)"
                    class="flex items-center gap-2 whitespace-nowrap rounded-full px-5 py-2 text-sm font-medium transition-all"
                    :class="activeTab === tab.id
                        ? 'bg-primary text-white shadow-sm'
                        : 'text-secondary hover:bg-slate-50'"
                >
                    <svg v-if="tab.icon === 'home'" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                    <svg v-else-if="tab.icon === 'file'" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                    <svg v-else-if="tab.icon === 'users'" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/></svg>
                    <svg v-else-if="tab.icon === 'user'" class="size-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>{{ tab.label }}</span>
                </button>
            </div>

            <!-- ==================== TAB: BERANDA ==================== -->
            <div v-if="activeTab === 'beranda'" class="space-y-6">
                <!-- Welcome card -->
                <div class="editorial-card welcome-card">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <span class="overline-label">Layanan Mandiri Warga</span>
                            <h1 class="headline-lg mt-1">Selamat datang kembali,</h1>
                            <p class="headline-md text-primary mt-1 font-medium">{{ warga.nama_lengkap }}</p>
                            <div class="mt-4 flex items-center gap-2 text-sm text-secondary">
                                <svg class="size-4 text-primary shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                <span>{{ warga.keluarga?.alamat || 'Alamat belum tersedia' }}, {{ warga.keluarga?.dusun }} {{ warga.keluarga?.rt_rw }}</span>
                            </div>
                        </div>
                        <div class="avatar-circle-lg overflow-hidden shrink-0">
                            <img v-if="warga.foto_profil" :src="warga.foto_profil" alt="Profil" class="size-full object-cover" />
                            <span v-else>{{ getInitials(warga.nama_lengkap) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Biodata alert -->
                <div v-if="!biodataComplete" class="alert-box warning-alert">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1">
                        <h4 class="label-md text-warning-dark">Biodata Belum Lengkap ({{ biodataCompleteness }}%)</h4>
                        <p class="body-sm text-secondary mt-0.5">Lengkapi biodata dan berkas Anda terlebih dahulu agar dapat mengajukan surat administrasi desa.</p>
                    </div>
                    <button @click="setActiveTab('biodata')" class="btn-primary py-1.5 px-4 h-auto text-xs shrink-0">Lengkapi</button>
                </div>

                <!-- Summary stats -->
                <div class="grid grid-cols-3 gap-4">
                    <div class="editorial-card stat-card">
                        <div class="icon-wrapper bg-warning-soft text-warning mb-2">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <span class="overline-label">Menunggu</span>
                        <p class="headline-md text-neutral mt-1 font-semibold">{{ summary.pending }}</p>
                    </div>
                    <div class="editorial-card stat-card">
                        <div class="icon-wrapper bg-primary-soft text-primary mb-2">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path d="M12 2v4"/><path d="M12 18v4"/></svg>
                        </div>
                        <span class="overline-label">Diproses</span>
                        <p class="headline-md text-neutral mt-1 font-semibold">{{ summary.diproses }}</p>
                    </div>
                    <div class="editorial-card stat-card">
                        <div class="icon-wrapper bg-success-soft text-success mb-2">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <span class="overline-label">Selesai</span>
                        <p class="headline-md text-neutral mt-1 font-semibold">{{ summary.selesai }}</p>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="grid grid-cols-3 gap-4">
                    <button @click="setActiveTab('pengajuan')" class="editorial-card action-card">
                        <div class="action-icon bg-primary-soft text-primary mb-3">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        </div>
                        <span class="label-sm text-neutral font-medium">Ajukan Surat</span>
                    </button>
                    <button @click="setActiveTab('keluarga')" class="editorial-card action-card">
                        <div class="action-icon bg-accent-soft text-primary mb-3">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                        </div>
                        <span class="label-sm text-neutral font-medium">Keluarga Saya</span>
                    </button>
                    <button @click="setActiveTab('biodata')" class="editorial-card action-card">
                        <div class="action-icon bg-warning-soft text-warning mb-3">
                            <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <span class="label-sm text-neutral font-medium">Biodata Saya</span>
                    </button>
                </div>

                <!-- Recent submissions -->
                <div class="space-y-4">
                    <h2 class="headline-sm">Pengajuan Terakhir</h2>
                    <div v-if="recentPengajuan.length" class="space-y-3">
                        <div
                            v-for="item in recentPengajuan" :key="item.id"
                            class="editorial-card list-item-card"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <Link :href="`/warga/pengajuan/${item.id}`" class="headline-sm font-medium hover:text-primary transition-colors block leading-snug">
                                        {{ item.kategori?.nama_surat }}
                                    </Link>
                                    <p class="body-sm text-secondary font-mono mt-1">
                                        {{ item.nomor_registrasi }} · {{ item.pemohon?.nama_lengkap }}
                                    </p>
                                </div>
                                <StatusBadge :status="item.status" class="shrink-0" />
                            </div>
                            
                            <div v-if="(item.status === 'Selesai' || item.status === 'Disetujui') && item.file_pdf_url" class="attachment-footer mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="success-label flex items-center gap-1.5">
                                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Surat siap diunduh
                                </span>
                                <a :href="getFileUrl(item.file_pdf_url)" target="_blank" class="btn-primary py-1.5 px-4 h-auto text-xs">
                                    Unduh PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <div v-else class="editorial-card empty-card text-center py-10">
                        <svg class="mx-auto size-12 text-secondary mb-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                        <p class="body-md text-secondary font-medium">Belum ada pengajuan surat.</p>
                        <button @click="setActiveTab('pengajuan')" class="btn-secondary mt-4">Ajukan sekarang</button>
                    </div>
                </div>
            </div>

            <!-- ==================== TAB: PENGAJUAN SURAT ==================== -->
            <div v-if="activeTab === 'pengajuan'" class="space-y-6">
                <div>
                    <h2 class="headline-lg">Layanan & Pengajuan Surat</h2>
                    <p class="body-md text-secondary mt-1">Pilih jenis surat keterangan atau administrasi desa yang ingin Anda ajukan.</p>
                </div>

                <!-- Biodata gate -->
                <div v-if="!biodataComplete" class="alert-box error-alert">
                    <svg class="size-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div class="flex-1">
                        <h4 class="label-md text-error">Biodata & Berkas Belum Lengkap</h4>
                        <p class="body-sm text-secondary mt-0.5">Harap lengkapi biodata Anda pada tab Biodata sebelum dapat mengakses formulir pengajuan surat.</p>
                    </div>
                    <button @click="setActiveTab('biodata')" class="btn-primary py-1.5 px-4 h-auto text-xs shrink-0">Lengkapi Biodata</button>
                </div>

                <!-- Services Grid -->
                <div class="grid gap-4 sm:grid-cols-2">
                    <div v-for="kategori in kategoriSurat" :key="kategori.id" class="editorial-card service-card flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between">
                                <span class="category-label text-primary">{{ kategori.kode_surat }}</span>
                                <span class="badge tertiary-badge font-semibold">{{ (kategori.syarat_dokumen || []).length }} Syarat</span>
                            </div>
                            <h3 class="headline-sm mt-3 leading-snug">{{ kategori.nama_surat }}</h3>
                        </div>
                        <AppButton
                            :href="biodataComplete ? `/warga/surat/ajukan/${kategori.id}` : undefined"
                            class="btn-primary mt-6 w-full justify-center"
                            :class="{ 'opacity-50 cursor-not-allowed pointer-events-none': !biodataComplete }"
                        >
                            Ajukan Surat
                        </AppButton>
                    </div>
                </div>

                <!-- Riwayat Pengajuan -->
                <div class="pt-4 space-y-4">
                    <div>
                        <h2 class="headline-sm">Riwayat Pengajuan Surat</h2>
                        <p class="body-sm text-secondary mt-0.5">
                            {{ isKepalaKeluarga ? 'Daftar pengajuan surat dari seluruh anggota keluarga Anda.' : 'Daftar pengajuan surat administrasi Anda.' }}
                        </p>
                    </div>

                    <div v-if="pengajuan.data?.length" class="space-y-3">
                        <div
                            v-for="item in pengajuan.data" :key="item.id"
                            class="editorial-card list-item-card"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0 flex-1">
                                    <Link :href="`/warga/pengajuan/${item.id}`" class="headline-sm font-medium hover:text-primary transition-colors block leading-snug">
                                        {{ item.kategori?.nama_surat }}
                                    </Link>
                                    <div class="mt-2 flex flex-wrap items-center gap-2 text-xs text-secondary">
                                        <span class="font-mono">{{ item.nomor_registrasi }}</span>
                                        <span>·</span>
                                        <span v-if="isKepalaKeluarga && item.pemohon" class="success-chip">{{ item.pemohon.nama_lengkap }}</span>
                                    </div>
                                </div>
                                <StatusBadge :status="item.status" class="shrink-0" />
                            </div>
                            
                            <div v-if="(item.status === 'Selesai' || item.status === 'Disetujui') && item.file_pdf_url" class="attachment-footer mt-4 pt-4 border-t border-slate-100 flex items-center justify-between">
                                <span class="success-label flex items-center gap-1.5">
                                    <svg class="size-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                                    Surat siap diunduh
                                </span>
                                <a :href="getFileUrl(item.file_pdf_url)" target="_blank" class="btn-primary py-1.5 px-4 h-auto text-xs">
                                    Unduh PDF
                                </a>
                            </div>
                        </div>
                    </div>
                    <EmptyState v-else title="Belum ada pengajuan" message="Mulai dari katalog pengajuan untuk membuat surat pertama Anda." />

                    <Pagination v-if="pengajuan.meta" :links="pengajuan.links" :meta="pengajuan.meta" class="mt-6" />
                </div>
            </div>

            <!-- ==================== TAB: KELUARGA SAYA ==================== -->
            <div v-if="activeTab === 'keluarga'" class="space-y-6">
                <div>
                    <h2 class="headline-lg">Keluarga Saya</h2>
                    <p class="body-md text-secondary mt-1">Informasi KK dan daftar anggota keluarga yang terdaftar dalam satu KK.</p>
                </div>

                <!-- KK Info card -->
                <div class="editorial-card kk-card p-0 overflow-hidden">
                    <div class="bg-neutral p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div class="icon-wrapper bg-white/10 text-white shrink-0">
                                <svg class="size-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="18" rx="2"/><line x1="2" y1="8" x2="22" y2="8"/></svg>
                            </div>
                            <div>
                                <span class="overline-label text-slate-350">Nomor Kartu Keluarga</span>
                                <p class="headline-sm tracking-wider mt-0.5 text-white">{{ warga.keluarga?.no_kk || '-' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-6 p-6 grid-cols-3 text-xs bg-white border-t border-slate-200">
                        <div>
                            <span class="overline-label">Alamat KK</span>
                            <p class="body-md text-neutral font-medium mt-1">{{ warga.keluarga?.alamat || '-' }}</p>
                        </div>
                        <div>
                            <span class="overline-label">Dusun</span>
                            <p class="body-md text-neutral font-medium mt-1">{{ warga.keluarga?.dusun || '-' }}</p>
                        </div>
                        <div>
                            <span class="overline-label">RT/RW</span>
                            <p class="body-md text-neutral font-medium mt-1">{{ warga.keluarga?.rt_rw || '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Family members list -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h3 class="headline-sm">Anggota Keluarga ({{ totalAnggota }})</h3>
                        <span v-if="isKepalaKeluarga" class="success-chip">Kepala Keluarga</span>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div v-for="anggota in anggotaKeluarga" :key="anggota.nik" class="editorial-card member-card flex flex-col justify-between">
                            <div>
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="avatar-circle-sm shrink-0 font-bold"
                                            :class="anggota.jenis_kelamin === 'L' ? 'bg-sky-50 text-sky-700' : 'bg-pink-50 text-pink-700'"
                                        >
                                            {{ getInitials(anggota.nama_lengkap) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="label-md text-neutral font-medium truncate">{{ anggota.nama_lengkap }}</p>
                                            <p class="body-sm text-secondary font-mono mt-0.5">NIK: {{ maskNik(anggota.nik) }}</p>
                                        </div>
                                    </div>
                                    <span class="badge px-2.5 py-0.5 shrink-0" :class="getStatusColor(anggota.status_keluarga)">
                                        {{ anggota.status_keluarga || 'Anggota' }}
                                    </span>
                                </div>
                                <div class="mt-4 grid grid-cols-2 gap-4 text-xs border-t border-slate-100 pt-3">
                                    <div><span class="text-secondary font-medium">Umur:</span> <span class="font-medium text-neutral ml-1">{{ anggota.umur }} tahun</span></div>
                                    <div><span class="text-secondary font-medium">Gender:</span> <span class="font-medium text-neutral ml-1">{{ anggota.jenis_kelamin }}</span></div>
                                </div>
                            </div>
                            
                            <!-- Submit on behalf action (only KK) -->
                            <div v-if="isKepalaKeluarga && biodataComplete" class="mt-4 pt-3 border-t border-slate-100">
                                <AppButton :href="`/warga/surat/ajukan/${kategoriSurat[0]?.id || 1}`" variant="outline" class="btn-secondary w-full text-xs py-2 h-auto justify-center">
                                    Ajukan Surat
                                </AppButton>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Manage link -->
                <div class="text-center pt-2">
                    <AppButton href="/warga/keluarga" variant="outline" class="btn-secondary w-full sm:w-auto">
                        Kelola Anggota Keluarga
                    </AppButton>
                </div>
            </div>

            <!-- ==================== TAB: BIODATA ==================== -->
            <div v-if="activeTab === 'biodata'" class="space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="headline-lg">Biodata Saya</h2>
                        <p class="body-md text-secondary mt-1">Periksa data profil dan kelengkapan administrasi Anda.</p>
                    </div>
                    <AppButton href="/warga/profil" class="btn-primary">
                        Edit Biodata
                    </AppButton>
                </div>

                <!-- Profile header card -->
                <div class="editorial-card flex items-center gap-5">
                    <div class="avatar-circle-lg overflow-hidden shrink-0">
                        <img v-if="warga.foto_profil" :src="warga.foto_profil" alt="Foto Profil" class="size-full object-cover" />
                        <span v-else>{{ getInitials(warga.nama_lengkap) }}</span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="headline-sm font-medium">{{ warga.nama_lengkap }}</h3>
                        <p class="body-sm text-secondary font-mono mt-0.5">NIK: {{ warga.nik }}</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <span v-if="warga.foto_ktp" class="badge success-badge">KTP OK</span>
                            <span v-else class="badge error-badge">KTP Kosong</span>

                            <span v-if="warga.foto_kk" class="badge success-badge">KK OK</span>
                            <span v-else class="badge error-badge">KK Kosong</span>
                        </div>
                    </div>
                </div>

                <!-- Completeness bar -->
                <div class="editorial-card">
                    <div class="flex items-center justify-between text-sm font-medium mb-2">
                        <span class="text-secondary">Kelengkapan Biodata</span>
                        <span :class="biodataComplete ? 'text-primary' : 'text-warning-dark'">{{ biodataCompleteness }}%</span>
                    </div>
                    <div class="h-2 overflow-hidden rounded-full bg-slate-100">
                        <div
                            class="h-full rounded-full transition-all duration-500"
                            :class="biodataComplete ? 'bg-primary' : 'bg-warning'"
                            :style="{ width: biodataCompleteness + '%' }"
                        />
                    </div>
                </div>

                <!-- Details Grid -->
                <div class="grid gap-4 grid-cols-2">
                    <div v-for="field in requiredFields" :key="field" class="editorial-card biodata-field-card">
                        <span class="overline-label">{{ fieldLabels[field] }}</span>
                        <p class="body-lg mt-1 font-medium leading-tight" :class="warga[field] ? 'text-neutral' : 'text-error'">
                            <template v-if="field === 'tanggal_lahir'">{{ warga[field] ? formatDate(warga[field]) : 'Belum diisi' }}</template>
                            <template v-else-if="field === 'jenis_kelamin'">{{ getGenderLabel(warga[field]) || 'Belum diisi' }}</template>
                            <template v-else>{{ warga[field] || 'Belum diisi' }}</template>
                        </p>
                    </div>
                </div>

                <div class="grid gap-4 grid-cols-2">
                    <div class="editorial-card biodata-field-card">
                        <span class="overline-label">Nomor Induk Kependudukan (NIK)</span>
                        <p class="body-lg mt-1 font-mono text-neutral font-medium">{{ warga.nik }}</p>
                    </div>
                    <div class="editorial-card biodata-field-card">
                        <span class="overline-label">Status Hubungan</span>
                        <p class="body-lg mt-1 text-neutral font-medium">{{ warga.status_keluarga || '-' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&display=swap');

.google-editorial {
    font-family: 'Instrument Sans', 'Google Sans', sans-serif;
    color: #202124;
    background-color: #F8F9FA;
}

/* Typography styles based on spec */
.headline-lg {
    font-size: 32px;
    font-weight: 400;
    line-height: 40px;
    letter-spacing: 0px;
    color: #202124;
}

.headline-md {
    font-size: 22px;
    font-weight: 400;
    line-height: 30px;
    letter-spacing: 0px;
    color: #202124;
}

.headline-sm {
    font-size: 20px;
    font-weight: 400;
    line-height: 28px;
    letter-spacing: 0px;
    color: #202124;
}

.body-lg {
    font-size: 16px;
    font-weight: 400;
    line-height: 24px;
    letter-spacing: 0.25px;
}

.body-md {
    font-size: 14px;
    font-weight: 400;
    line-height: 22px;
    letter-spacing: 0.15px;
}

.body-sm {
    font-size: 12px;
    font-weight: 400;
    line-height: 18px;
    letter-spacing: 0.2px;
}

.label-lg {
    font-size: 16px;
    font-weight: 500;
    line-height: 24px;
    letter-spacing: 0px;
}

.label-md {
    font-size: 14px;
    font-weight: 500;
    line-height: 20px;
    letter-spacing: 0px;
}

.label-sm {
    font-size: 12px;
    font-weight: 500;
    line-height: 16px;
    letter-spacing: 0.04em;
}

.overline-label {
    font-size: 11px;
    font-weight: 500;
    line-height: 16px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: #5F6368;
}

.category-label {
    font-size: 12px;
    font-weight: 500;
    line-height: 16px;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

/* Colors styling mapping */
.text-primary { color: #1A73E8; }
.text-secondary { color: #5F6368; }
.text-neutral { color: #202124; }
.text-error { color: #D93025; }
.text-warning-dark { color: #B06000; }

.bg-primary { background-color: #1A73E8; }
.bg-warning { background-color: #F9AB00; }

/* Custom components */
.editorial-card {
    background: #FFFFFF;
    border: 1px solid #E5E7EB;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 1px 2px 0 rgba(60,64,67,0.1), 0 1px 3px 1px rgba(60,64,67,0.05);
}

.welcome-card {
    border-top: 4px solid #1A73E8;
}

.stat-card {
    padding: 20px 16px;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.action-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 24px 16px;
    cursor: pointer;
    transition: all 0.2s;
}
.action-card:hover {
    background-color: #F8F9FA;
    border-color: #D2E3FC;
}

.list-item-card {
    padding: 20px;
    transition: all 0.2s;
}
.list-item-card:hover {
    border-color: #D2E3FC;
    box-shadow: 0 4px 12px 0 rgba(60,64,67,0.08);
}

.service-card {
    padding: 24px;
}

.member-card {
    padding: 20px;
}

.biodata-field-card {
    padding: 16px 20px;
}

.avatar-circle-lg {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 64px;
    height: 64px;
    border-radius: 9999px;
    background-color: #E8F0FE;
    color: #1A73E8;
    font-weight: 600;
    font-size: 22px;
}

.avatar-circle-sm {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 9999px;
}

/* Alert styles */
.alert-box {
    display: flex;
    gap: 14px;
    padding: 16px;
    border-radius: 12px;
    align-items: center;
}

.warning-alert {
    background-color: #FEF7E0;
    border: 1px solid #FEEFC3;
    color: #B06000;
}

.error-alert {
    background-color: #FCE8E6;
    border: 1px solid #FAD2CF;
    color: #D93025;
}

/* Soft accent circles */
.icon-wrapper, .action-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 9999px;
}

.bg-warning-soft { background-color: #FEF7E0; color: #B06000; }
.bg-primary-soft { background-color: #E8F0FE; color: #1A73E8; }
.bg-success-soft { background-color: #E6F4EA; color: #137333; }
.bg-accent-soft { background-color: #D2E3FC; color: #1A73E8; }

/* Buttons */
.btn-primary {
    background-color: #1A73E8;
    color: #FFFFFF;
    font-size: 14px;
    font-weight: 500;
    border-radius: 9999px;
    padding: 11px 24px;
    height: 48px;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-primary:hover {
    background-color: #1557B0;
}

.btn-secondary {
    background-color: #FFFFFF;
    color: #1A73E8;
    font-size: 14px;
    font-weight: 500;
    border-radius: 9999px;
    padding: 11px 24px;
    height: 48px;
    border: 1px solid #D2E3FC;
    transition: background-color 0.2s;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.btn-secondary:hover {
    background-color: #F4F8FF;
}

/* Labels & badges */
.success-label {
    font-size: 12px;
    font-weight: 600;
    color: #137333;
}

.badge {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 9999px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.tertiary-badge {
    background-color: #E8F0FE;
    color: #1A73E8;
}

.success-badge {
    background-color: #E6F4EA;
    color: #137333;
}

.error-badge {
    background-color: #FCE8E6;
    color: #C5221F;
}

.success-chip {
    background-color: #E6F4EA;
    color: #137333;
    font-size: 11px;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 9999px;
}
</style>
