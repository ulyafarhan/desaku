<script setup>
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import AppButton from '../../Components/AppButton.vue';
import AppCard from '../../Components/AppCard.vue';
import EmptyState from '../../Components/EmptyState.vue';
import { 
    Users, 
    Home as HomeIcon, 
    FileText, 
    Briefcase, 
    Newspaper, 
    PhoneCall, 
    Fingerprint, 
    Clock, 
    CheckCircle2, 
    ShieldCheck, 
    Send,
    UserCheck,
    User,
    ArrowUpRight,
    MapPin,
    Shield,
    Sparkles,
    BookOpen,
    Activity,
    ArrowRight
} from '@lucide/vue';

defineOptions({ layout: PublicLayout });

const stripHtml = (html) => {
    if (!html) return '';
    return html.replace(/<[^>]*>/g, '');
};

const props = defineProps({
    demografi: Object,
    layanan: Object,
    berita: Array,
    kategoriSurat: Array,
});

// Counter animation
const animatedStats = ref({ penduduk: 0, keluarga: 0, pengajuan: 0, layanan: 0 });

const animateCount = (key, target) => {
    if (!target) return;
    const duration = 1200;
    const step = Math.max(1, Math.ceil(target / (duration / 16)));
    const interval = setInterval(() => {
        animatedStats.value[key] = Math.min(animatedStats.value[key] + step, target);
        if (animatedStats.value[key] >= target) clearInterval(interval);
    }, 16);
};

onMounted(() => {
    animateCount('penduduk', props.demografi?.total_penduduk ?? 0);
    animateCount('keluarga', props.demografi?.total_keluarga ?? 0);
    animateCount('pengajuan', props.layanan?.pengajuan_surat?.total ?? 0);
    animateCount('layanan', props.kategoriSurat?.length ?? 0);
});

// Featured cards list with dynamic routing
const featureItems = [
    {
        title: "Layanan Mandiri",
        desc: "Warga dapat mengajukan berbagai jenis surat keterangan secara mandiri online.",
        icon: FileText,
        gradient: "from-blue-500/10 to-indigo-500/10 text-blue-600 border-blue-500/10",
        href: "/login"
    },
    {
        title: "Transparansi Data",
        desc: "Penyajian statistik demografi kependudukan gampong secara real-time dan terbuka.",
        icon: Users,
        gradient: "from-teal-500/10 to-emerald-500/10 text-teal-600 border-teal-500/10",
        href: "/statistik"
    },
    {
        title: "Kabar Informasi",
        desc: "Dapatkan pengumuman kegiatan, berita terbaru, dan sosialisasi penting gampong.",
        icon: Newspaper,
        gradient: "from-amber-500/10 to-orange-500/10 text-amber-600 border-amber-500/10",
        href: "/informasi"
    },
    {
        title: "Dukungan Cepat",
        desc: "Hubungi operator pelayanan langsung melalui WhatsApp untuk bantuan berkas.",
        icon: PhoneCall,
        gradient: "from-sky-500/10 to-cyan-500/10 text-sky-600 border-sky-500/10",
        href: "https://wa.me/6281234567890",
        external: true
    }
];

// Aparatur Gampong list
const aparaturList = [
    {
        name: "H. Syarifuddin",
        role: "Keuchik (Kepala Desa)",
        photo: "https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=600&auto=format&fit=crop"
    },
    {
        name: "Cut Rahmawati, S.Sos",
        role: "Sekretaris Desa",
        photo: "https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=600&auto=format&fit=crop"
    },
    {
        name: "Ahmad Faisal",
        role: "Kaur Keuangan",
        photo: "https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=600&auto=format&fit=crop"
    }
];
</script>

<template>
    <!-- HERO SECTION WITH WORLD-CLASS EDITORIAL GRADIENTS & GLOWS -->
    <section class="relative min-h-[90vh] flex items-center justify-center bg-[#07090e] text-white overflow-hidden py-24 px-6">
        <!-- Abstract gradient mesh & blur overlays -->
        <div class="absolute inset-0 z-0">
            <div class="absolute top-[-10%] left-[-10%] w-[50%] h-[50%] rounded-full bg-blue-500/10 blur-[120px]" />
            <div class="absolute bottom-[-10%] right-[-10%] w-[50%] h-[50%] rounded-full bg-teal-500/10 blur-[120px]" />
            <img 
                src="https://images.unsplash.com/photo-1604999333679-b86d54738315?q=80&w=1920&auto=format&fit=crop" 
                alt="Landscape Desa" 
                class="size-full object-cover opacity-[0.08] scale-100 animate-subtle-zoom"
            />
            <!-- Visual grid pattern overlay -->
            <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:32px_32px]" />
        </div>

        <div class="relative z-10 mx-auto max-w-5xl text-center space-y-8">
            <div class="flex flex-col items-center gap-3">
                <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-semibold bg-white/[0.04] border border-white/[0.08] backdrop-blur-md text-teal-400">
                    <Sparkles class="size-3.5 text-teal-400 animate-pulse" />
                    KBN Pilot Project Gampong Udeung
                </span>
            </div>
            
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-extrabold tracking-tight text-white leading-[1.1] max-w-4xl mx-auto">
                Sistem Layanan Mandiri <br class="hidden sm:inline" />
                <span class="bg-gradient-to-r from-teal-300 via-blue-300 to-indigo-300 bg-clip-text text-transparent">Digital Gampong</span>
            </h1>
            
            <p class="text-base sm:text-xl text-slate-200 max-w-2xl mx-auto font-medium leading-relaxed">
                Platform administrasi desa modern untuk mempermudah permohonan surat keterangan warga secara daring, transparan, dan terintegrasi penuh.
            </p>
            
            <div class="pt-8 flex flex-col sm:flex-row gap-4 justify-center items-center">
                <Link href="/login" class="inline-flex items-center justify-center rounded-full bg-[#1A73E8] text-white hover:bg-[#155fa8] transition-all duration-300 px-8 py-4 text-sm font-semibold shadow-lg shadow-blue-500/20">
                    Ajukan Surat Online <ArrowUpRight class="size-4 ml-1.5" />
                </Link>
                <Link href="/profil" class="inline-flex items-center justify-center rounded-full bg-white/10 border border-white/20 backdrop-blur-sm px-8 py-4 text-sm font-semibold text-white hover:bg-white/25 transition-all duration-300">
                    Pelajari Profil Desa
                </Link>
            </div>
        </div>
    </section>

    <!-- BENTO GRID FEATURES (INTERACTIVE, FLAT, GOOGLE STYLED) -->
    <section class="relative z-20 -mt-16 mx-auto max-w-7xl px-6 lg:px-8">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
            <template v-for="item in featureItems" :key="item.title">
                <!-- External Link -->
                <a 
                    v-if="item.external"
                    :href="item.href"
                    target="_blank"
                    class="group bg-white border border-slate-200 p-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.02)] flex flex-col justify-between hover:border-slate-350 transition duration-200"
                >
                    <div class="space-y-4">
                        <div class="size-11 rounded-xl flex items-center justify-center border" :class="item.gradient">
                            <component :is="item.icon" class="size-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-1">
                                {{ item.title }} <ArrowUpRight class="size-3.5 text-slate-400 group-hover:text-slate-600 transition" />
                            </h3>
                            <p class="mt-2 text-xs text-slate-650 leading-relaxed font-medium">{{ item.desc }}</p>
                        </div>
                    </div>
                    <span class="mt-4 text-xs font-bold text-teal-700 inline-flex items-center gap-1">Hubungi Operator <ArrowRight class="size-3" /></span>
                </a>
                
                <!-- Inertia Link -->
                <Link 
                    v-else
                    :href="item.href"
                    class="group bg-white border border-slate-200 p-6 rounded-2xl shadow-[0_4px_12px_rgba(0,0,0,0.02)] flex flex-col justify-between hover:border-slate-350 transition duration-200"
                >
                    <div class="space-y-4">
                        <div class="size-11 rounded-xl flex items-center justify-center border" :class="item.gradient">
                            <component :is="item.icon" class="size-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-900 tracking-tight flex items-center gap-1">
                                {{ item.title }} <ArrowUpRight class="size-3.5 text-slate-400 group-hover:text-slate-600 transition" />
                            </h3>
                            <p class="mt-2 text-xs text-slate-650 leading-relaxed font-medium">{{ item.desc }}</p>
                        </div>
                    </div>
                    <span class="mt-4 text-xs font-bold text-teal-700 inline-flex items-center gap-1">Buka Halaman <ArrowRight class="size-3" /></span>
                </Link>
            </template>
        </div>
    </section>

    <!-- STATISTICS SUMMARY SECTION (INTEGRATED & HIGH CONTRAST) -->
    <section class="bg-white border-b border-slate-200 py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12 items-start">
                <div class="lg:col-span-4 space-y-4">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#1A73E8]">
                        Data Kependudukan
                    </span>
                    <h2 class="text-3xl font-normal text-slate-900 tracking-tight leading-tight">Udeung Dalam Angka</h2>
                    <p class="text-slate-700 text-sm leading-relaxed font-medium">
                        Kami menyajikan ringkasan data demografi dan layanan administrasi desa secara transparan untuk memantau perkembangan pembangunan gampong.
                    </p>
                    <div class="pt-2">
                        <Link href="/statistik" class="inline-flex items-center justify-center rounded-full border border-slate-300 text-slate-800 hover:border-slate-400 bg-white transition duration-200 px-5 py-3 text-xs font-semibold">
                            Lihat Statistik Detail <ArrowRight class="size-3.5 ml-1.5" />
                        </Link>
                    </div>
                </div>

                <div class="lg:col-span-8 grid gap-6 sm:grid-cols-3">
                    <Link href="/statistik" class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 hover:bg-slate-50 transition duration-200 space-y-2 block">
                        <span class="text-3xl font-normal text-[#202124] block">{{ animatedStats.penduduk }}</span>
                        <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1">
                            Total Warga Aktif <ArrowUpRight class="size-3 text-slate-400" />
                        </h4>
                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">Penduduk terdata dan memiliki hak pelayanan digital mandiri.</p>
                    </Link>
                    <Link href="/statistik" class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 hover:bg-slate-50 transition duration-200 space-y-2 block">
                        <span class="text-3xl font-normal text-[#202124] block">{{ animatedStats.keluarga }}</span>
                        <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1">
                            Kepala Keluarga <ArrowUpRight class="size-3 text-slate-400" />
                        </h4>
                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">Jumlah kartu keluarga terdaftar di data administrasi desa.</p>
                    </Link>
                    <Link href="/statistik" class="p-6 rounded-2xl border border-slate-200 bg-slate-50/50 hover:border-slate-300 hover:bg-slate-50 transition duration-200 space-y-2 block">
                        <span class="text-3xl font-normal text-[#202124] block">{{ animatedStats.pengajuan }}</span>
                        <h4 class="text-xs font-bold text-slate-900 flex items-center gap-1">
                            Surat Keterangan Terbit <ArrowUpRight class="size-3 text-slate-400" />
                        </h4>
                        <p class="text-[11px] text-slate-700 leading-relaxed font-medium">Total pengajuan persuratan mandiri warga yang selesai diproses.</p>
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <!-- SERVICES OFFERED SECTION (INTERACTIVE SERVICES LINK TO PORTAL) -->
    <section class="bg-white border-b border-slate-200 py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12 items-start">
                <!-- Left side title & description -->
                <div class="lg:col-span-4 space-y-5 lg:sticky lg:top-24">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#1A73E8]">
                        Pelayanan Publik
                    </span>
                    <h2 class="text-3xl font-normal text-slate-900 tracking-tight leading-tight">Jenis Layanan Surat Mandiri</h2>
                    <p class="text-slate-700 text-sm leading-relaxed font-medium">
                        Kami merangkum birokrasi konvensional menjadi beberapa klik praktis. Pilih berkas surat yang diperlukan dan lengkapi datanya langsung dari beranda warga Anda.
                    </p>
                    <div class="pt-2">
                        <Link href="/login" class="inline-flex items-center justify-center rounded-full bg-[#1A73E8] text-white hover:bg-[#155fa8] transition-colors duration-200 px-6 py-3.5 text-xs font-semibold shadow-sm">
                            Masuk Portal Warga <ArrowUpRight class="size-3.5 ml-1" />
                        </Link>
                    </div>
                </div>

                <!-- Right side grid of available categories (Clean flat layout, interactive click to login) -->
                <div class="lg:col-span-8 grid gap-px bg-slate-200 border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    <Link 
                        v-for="kategori in kategoriSurat" 
                        :key="kategori.id"
                        href="/login"
                        class="bg-white p-6 flex flex-col justify-between hover:bg-slate-50/50 transition duration-150"
                    >
                        <div class="space-y-4">
                            <div class="flex justify-between items-start">
                                <span class="text-[10px] font-bold text-[#1A73E8] bg-[#E8F0FE] px-2.5 py-1 rounded-md uppercase tracking-wider block w-fit">
                                    {{ kategori.kode_surat }}
                                </span>
                                <span class="text-xs text-teal-700 font-semibold inline-flex items-center gap-1 opacity-0 group-hover:opacity-100 transition duration-150">
                                    Ajukan <ArrowRight class="size-3" />
                                </span>
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-sm font-bold text-[#202124] leading-snug">{{ kategori.nama_surat }}</h3>
                                <p class="text-xs text-slate-650 leading-relaxed font-medium">Pengajuan berkas secara online dengan QR Code tanda tangan elektronik sah.</p>
                            </div>
                        </div>
                    </Link>

                    <!-- Placeholder if empty -->
                    <div v-if="!kategoriSurat?.length" class="col-span-2 bg-white text-center py-12 text-slate-500 text-sm font-semibold">
                        Belum ada data kategori surat aktif.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CONSULTATION & STEPS SECTION (FLAT & MINIMALIST) -->
    <section class="mx-auto max-w-7xl px-6 py-28 lg:px-8 bg-white">
        <div class="grid gap-12 lg:grid-cols-12 items-center">
            <!-- Left Side Image -->
            <div class="lg:col-span-5 relative order-2 lg:order-1">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-500/20 to-indigo-500/20 rounded-2xl filter blur-xl opacity-70 -z-10" />
                <div class="overflow-hidden rounded-2xl border border-slate-200">
                    <img 
                        src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?q=80&w=1000&auto=format&fit=crop" 
                        alt="Citizen using portal" 
                        class="size-full object-cover aspect-[4/3]"
                    />
                </div>
            </div>

            <!-- Right Side Step indicators -->
            <div class="lg:col-span-7 space-y-6 order-1 lg:order-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-850">
                    <Sparkles class="size-3.5" /> Alur Pengajuan
                </span>
                <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Proses Praktis 4 Langkah</h2>
                <p class="text-slate-700 leading-relaxed text-sm font-medium">
                    Kami memprioritaskan efisiensi penuh tanpa mengharuskan Anda mengantre lama di kantor balai desa.
                </p>

                <!-- Steps List -->
                <div class="grid gap-4 sm:grid-cols-2 pt-4">
                    <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-2">
                        <span class="text-2xl font-bold text-teal-700 block">01</span>
                        <h4 class="text-sm font-bold text-slate-900">Autentikasi NIK</h4>
                        <p class="text-[11px] text-slate-650 leading-relaxed font-semibold">Masuk ke portal dengan NIK warga dan kata sandi Anda.</p>
                    </div>
                    <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-2">
                        <span class="text-2xl font-bold text-blue-700 block">02</span>
                        <h4 class="text-sm font-bold text-slate-900">Input Data Isian</h4>
                        <p class="text-[11px] text-slate-650 leading-relaxed font-semibold">Isi formulir digital serta unggah berkas kelengkapan.</p>
                    </div>
                    <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-2">
                        <span class="text-2xl font-bold text-indigo-700 block">03</span>
                        <h4 class="text-sm font-bold text-slate-900">Verifikasi Berkas</h4>
                        <p class="text-[11px] text-slate-650 leading-relaxed font-semibold">Operator memproses data isian dan validasi berkas.</p>
                    </div>
                    <div class="p-5 bg-white border border-slate-200 rounded-2xl space-y-2">
                        <span class="text-2xl font-bold text-emerald-700 block">04</span>
                        <h4 class="text-sm font-bold text-slate-900">Unduh PDF Resmi</h4>
                        <p class="text-[11px] text-slate-650 leading-relaxed font-semibold">Surat yang terbit dilengkapi QR Code TTE sah.</p>
                    </div>
                </div>

                <!-- Operator contact box -->
                <div class="mt-8 rounded-2xl bg-slate-50 border border-slate-200 p-5 flex flex-col sm:flex-row items-center gap-4">
                    <img 
                        src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" 
                        alt="Operator Foto" 
                        class="size-12 rounded-full object-cover shrink-0"
                    />
                    <div class="grow text-center sm:text-left space-y-0.5">
                        <p class="text-[10px] font-bold text-slate-450 uppercase tracking-wider">Layanan WhatsApp Mandiri</p>
                        <p class="text-sm font-bold text-slate-800">Nadia Safira (Operator Gampong)</p>
                        <p class="text-xs text-teal-800 font-bold">0812-3456-7890</p>
                    </div>
                    <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center justify-center rounded-full bg-emerald-700 hover:bg-emerald-800 text-white font-bold text-xs py-2.5 px-5 transition-all shadow-sm">
                        Hubungi Operator
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 2: KBN PILOT INITIATIVES (BENTO-STYLE ACTION HUB, MINIMALIST & INTERCONNECTED) -->
    <section class="bg-slate-50 border-t border-slate-200 py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="text-center space-y-3 mb-16">
                <span class="text-xs font-bold tracking-widest text-[#1A73E8] uppercase">Kampung Bebas Narkoba (KBN)</span>
                <h2 class="text-3xl font-normal text-slate-900 tracking-tight leading-tight">Komitmen Lingkungan Udeung</h2>
                <p class="text-slate-700 max-w-xl mx-auto text-sm leading-relaxed font-medium">
                    Sebagai wilayah percontohan anti-narkoba di Kabupaten Pidie Jaya, kami bersinergi aktif bersama warga mempertahankan KBN.
                </p>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <Link href="/profil" class="bg-white p-6 rounded-2xl border border-slate-200 hover:border-slate-350 transition duration-200 flex flex-col justify-between min-h-[180px]">
                    <div class="space-y-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#D93025] bg-red-50 px-2.5 py-1 rounded-md uppercase tracking-wider">
                            Pencegahan
                        </span>
                        <h3 class="text-sm font-bold text-[#202124] flex items-center gap-1">
                            Sosialisasi Pemuda <ArrowUpRight class="size-3 text-slate-400" />
                        </h3>
                        <p class="text-xs text-slate-650 leading-relaxed font-medium">Penyuluhan berkala mengenai bahaya zat terlarang yang menyasar kelompok pemuda gampong.</p>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 mt-4 flex items-center gap-1">Detail di Profil Desa <ArrowRight class="size-3" /></span>
                </Link>
                <Link href="/profil" class="bg-white p-6 rounded-2xl border border-slate-200 hover:border-slate-350 transition duration-200 flex flex-col justify-between min-h-[180px]">
                    <div class="space-y-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#1E8E3E] bg-green-50 px-2.5 py-1 rounded-md uppercase tracking-wider">
                            Partisipasi
                        </span>
                        <h3 class="text-sm font-bold text-[#202124] flex items-center gap-1">
                            Pengawasan Warga <ArrowUpRight class="size-3 text-slate-400" />
                        </h3>
                        <p class="text-xs text-slate-650 leading-relaxed font-medium">Masyarakat aktif berperan sebagai lini pengaman utama dalam melaporkan peredaran narkoba.</p>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 mt-4 flex items-center gap-1">Detail di Profil Desa <ArrowRight class="size-3" /></span>
                </Link>
                <Link href="/profil" class="bg-white p-6 rounded-2xl border border-slate-200 hover:border-slate-350 transition duration-200 flex flex-col justify-between min-h-[180px]">
                    <div class="space-y-3">
                        <span class="inline-flex items-center gap-1 text-[10px] font-bold text-[#1A73E8] bg-[#E8F0FE] px-2.5 py-1 rounded-md uppercase tracking-wider">
                            Sinergitas
                        </span>
                        <h3 class="text-sm font-bold text-[#202124] flex items-center gap-1">
                            Mitra BNK & Polres <ArrowUpRight class="size-3 text-slate-400" />
                        </h3>
                        <p class="text-xs text-slate-650 leading-relaxed font-medium">Kolaborasi terarah bersama jajaran Kepolisian Resor dan Badan Narkotika Kabupaten.</p>
                    </div>
                    <span class="text-[11px] font-bold text-slate-700 mt-4 flex items-center gap-1">Detail di Profil Desa <ArrowRight class="size-3" /></span>
                </Link>
            </div>
        </div>
    </section>

    <!-- APARATUR GAMPONG SECTION -->
    <section class="mx-auto max-w-7xl px-6 py-28 lg:px-8 border-t border-slate-200 bg-white">
        <div class="text-center space-y-3 mb-16">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-[#E8F0FE] text-[#1A73E8]">
                <Users class="size-3.5" /> Struktur Pemerintahan
            </span>
            <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Pemerintahan Gampong</h2>
            <p class="text-slate-755 max-w-xl mx-auto text-sm leading-relaxed font-bold">
                Jajaran aparatur desa Gampong Udeung yang berfokus melayani kepentingan administrasi dan pembangunan desa.
            </p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div 
                v-for="aparat in aparaturList" 
                :key="aparat.name"
                class="bg-slate-50/50 rounded-2xl border border-slate-200 overflow-hidden shadow-sm transition-all duration-300"
            >
                <div class="aspect-[4/3] w-full overflow-hidden bg-slate-50">
                    <img 
                        :src="aparat.photo" 
                        :alt="aparat.name"
                        class="size-full object-cover"
                    />
                </div>
                <div class="p-6 text-center space-y-2 bg-white border-t border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">{{ aparat.name }}</h3>
                    <span class="inline-block text-[10px] font-bold text-teal-900 bg-teal-50 px-3 py-1 rounded-full uppercase tracking-wider">{{ aparat.role }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- SECTION 3: GEOGRAPHIC & INFRASTRUCTURE MAP (CLEAN EDITORIAL LAYOUT) -->
    <section class="bg-slate-50 border-t border-slate-200 py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-12 items-center">
                <div class="lg:col-span-5 space-y-5">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-[#1A73E8]">
                        Orbitasi Wilayah
                    </span>
                    <h2 class="text-3xl font-normal text-slate-900 tracking-tight leading-tight">Letak & Akses Gampong</h2>
                    <p class="text-slate-700 text-sm leading-relaxed font-medium">
                        Gampong Udeung terletak di Kecamatan Bandar Baru, berjarak sekitar 3.5 km dari simpang pusat kecamatan Lueng Putu. Berbatasan langsung dengan Gampong Ara di bagian utara melalui jembatan gantung Garuda.
                    </p>
                    <div class="space-y-2 pt-2 text-xs text-slate-800 font-bold">
                        <p>• Koordinat Sektor: 5.277732, 96.102347</p>
                        <p>• Sektor Komoditas: Padi Sawah, Jagung, Kakao</p>
                    </div>
                </div>

                <div class="lg:col-span-7 rounded-2xl border border-slate-200 overflow-hidden relative h-80 shadow-sm bg-white p-2">
                    <iframe 
                        src="https://www.openstreetmap.org/export/embed.html?bbox=96.0900%2C5.2650%2C96.1150%2C5.2900&amp;layer=mapnik&amp;marker=5.2777%2C96.1023" 
                        class="size-full border-0 absolute inset-0 rounded-xl"
                        allowfullscreen="" 
                        loading="lazy"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>

    <!-- BERITA TERBARU SECTION -->
    <section class="bg-white border-t border-slate-200 py-24">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
                <div class="space-y-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-850">
                        <Newspaper class="size-3.5" /> Kabar Desa
                    </span>
                    <h2 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">Berita & Informasi Terbaru</h2>
                </div>
                <AppButton href="/informasi" variant="outline" class="rounded-full self-start shrink-0">
                    Lihat Semua Berita
                </AppButton>
            </div>

            <div v-if="berita?.length" class="grid gap-8 md:grid-cols-3">
                <article 
                    v-for="item in berita" 
                    :key="item.id"
                    class="group flex flex-col justify-between bg-slate-50/50 rounded-2xl border border-slate-200 overflow-hidden hover:border-slate-350 transition duration-200"
                >
                    <div>
                        <!-- Image / Cover Section -->
                        <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-teal-500/20 via-emerald-500/10 to-teal-600/20 flex items-center justify-center border-b border-slate-150">
                            <img 
                                v-if="item.cover_image" 
                                :src="item.cover_image" 
                                :alt="item.judul"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="flex flex-col items-center justify-center text-teal-800/60 p-4 text-center">
                                <Newspaper class="size-10 mb-2 stroke-[1.5]" />
                                <span class="text-xs font-semibold tracking-wide uppercase opacity-70">Desaku Kabar</span>
                            </div>
                            
                            <!-- Category Badge overlaid on card image bottom-left -->
                            <div class="absolute bottom-3 left-3">
                                <span class="text-[10px] font-bold tracking-wider text-teal-850 uppercase bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm">
                                    {{ item.kategori }}
                                </span>
                            </div>
                        </div>

                        <!-- Info Content Section -->
                        <div class="p-6 space-y-3">
                            <div class="flex items-center gap-3 text-[10px] text-slate-650 font-bold">
                                <span class="flex items-center gap-1">
                                    <Clock class="size-3" />
                                    {{ new Date(item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </span>
                                <span class="size-1 rounded-full bg-slate-400"></span>
                                <span class="flex items-center gap-1">
                                    <User class="size-3" />
                                    Admin Desa
                                </span>
                            </div>
                            
                            <h3 class="text-base font-bold text-slate-900 line-clamp-2 leading-snug group-hover:text-[#1A73E8] transition-colors duration-300">
                                <Link :href="`/informasi/${item.slug}`">{{ item.judul }}</Link>
                            </h3>
                            
                            <p class="text-xs text-slate-700 line-clamp-3 leading-relaxed font-medium">
                                {{ stripHtml(item.konten) }}
                            </p>
                        </div>
                    </div>
                    
                    <div class="px-6 pb-6 pt-2">
                        <Link 
                            :href="`/informasi/${item.slug}`" 
                            class="text-teal-700 font-bold flex items-center gap-1 text-xs hover:text-teal-850 transition duration-150"
                        >
                            Baca Selengkapnya <span class="transition-transform duration-300 group-hover:translate-x-1">→</span>
                        </Link>
                    </div>
                </article>
            </div>
            <EmptyState v-else class="mt-6 bg-white" title="Belum ada informasi" message="Berita desa akan tampil di sini setelah dipublikasikan." :icon="Newspaper" />
        </div>
    </section>
</template>

<style>
.animate-subtle-zoom {
    animation: subtleZoom 25s infinite alternate ease-in-out;
}

@keyframes subtleZoom {
    0% { transform: scale(1.02) rotate(0.1deg); }
    100% { transform: scale(1.06) rotate(-0.1deg); }
}
</style>
