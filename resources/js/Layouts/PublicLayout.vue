<script setup>
import { inject, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppButton from '../Components/AppButton.vue';
import Toast from '../Components/Toast.vue';
import { Menu, X, Phone, Mail, MapPin, Landmark, Clock, ArrowRight, Shield, Send, CheckCircle2, Globe, Heart } from '@lucide/vue';

const page = usePage();
const progress = inject('pageProgress');
const mobileMenuOpen = ref(false);

const aspirasiInput = ref('');
const suggestionSent = ref(false);
const sendAspirasi = () => {
    if (aspirasiInput.value.trim() === '') return;
    suggestionSent.value = true;
    setTimeout(() => {
        aspirasiInput.value = '';
        suggestionSent.value = false;
    }, 4000);
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 flex flex-col font-sans w-full overflow-x-hidden">
        <!-- Progress Bar for page loads -->
        <div v-if="progress?.value" class="fixed left-0 top-0 z-50 h-[3px] w-full overflow-hidden bg-teal-150">
            <div class="h-full w-1/3 rounded-full bg-gradient-to-r from-teal-500 to-emerald-600 animate-progress" />
        </div>
        <Toast />

        <!-- Header bar and Nav container -->
        <header class="sticky top-0 z-40 border-b border-slate-100/80 bg-white/85 backdrop-blur-md transition-all duration-300">
            <!-- Top bar / desktop only info -->
            <div class="hidden border-b border-slate-100/50 bg-slate-50/70 py-2.5 text-xs text-slate-600 sm:block">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-6 lg:px-8">
                    <div class="flex items-center gap-5">
                        <span class="flex items-center gap-1.5"><MapPin class="size-3.5 text-teal-600 shrink-0" /> Kantor Keuchik Gampong Udeung, Pidie Jaya</span>
                        <span class="h-3 w-px bg-slate-200" />
                        <span class="flex items-center gap-1.5"><Clock class="size-3.5 text-teal-600 shrink-0" /> Pelayanan: Sen - Jum (08:00 - 16:30)</span>
                    </div>
                    <div class="flex items-center gap-5">
                        <a href="mailto:info@udeung.desa.id" class="flex items-center gap-1.5 hover:text-teal-700 transition duration-150"><Mail class="size-3.5 text-teal-600 shrink-0" /> info@udeung.desa.id</a>
                        <span class="h-3 w-px bg-slate-200" />
                        <span class="flex items-center gap-1.5 font-semibold text-slate-800"><Phone class="size-3.5 text-teal-600 shrink-0" /> Operator: 0812-3456-7890</span>
                    </div>
                </div>
            </div>

            <!-- Main Nav -->
            <nav class="mx-auto flex max-w-7xl w-full items-center justify-between px-6 py-4 lg:px-8">
                <!-- Logo -->
                <Link href="/" class="flex items-center gap-2.5 group">
                    <div class="flex size-10 items-center justify-center rounded-xl bg-teal-700 text-white shadow-[0_4px_12px_rgba(15,118,110,0.15)] group-hover:bg-teal-800 transition duration-300">
                        <Landmark class="size-5" />
                    </div>
                    <div>
                        <span class="text-xl font-bold tracking-tight text-slate-900 group-hover:text-teal-800 transition duration-300">Desaku</span>
                        <p class="text-[9px] font-bold tracking-widest text-teal-600 uppercase -mt-0.5">Gampong Udeung</p>
                    </div>
                </Link>

                <!-- Desktop Menu -->
                <div class="hidden items-center gap-1 text-sm font-semibold text-slate-650 sm:flex">
                    <Link href="/" class="px-4 py-2 rounded-full transition duration-200 hover:bg-slate-50" :class="activeClass = $page.url === '/' ? 'text-teal-700 bg-teal-50/50' : 'hover:text-slate-900'">Beranda</Link>
                    <Link href="/profil" class="px-4 py-2 rounded-full transition duration-200 hover:bg-slate-50" :class="activeClass = $page.url.startsWith('/profil') ? 'text-teal-700 bg-teal-50/50' : 'hover:text-slate-900'">Profil Desa</Link>
                    <Link href="/informasi" class="px-4 py-2 rounded-full transition duration-200 hover:bg-slate-50" :class="activeClass = $page.url.startsWith('/informasi') ? 'text-teal-700 bg-teal-50/50' : 'hover:text-slate-900'">Informasi</Link>
                    <Link href="/statistik" class="px-4 py-2 rounded-full transition duration-200 hover:bg-slate-50" :class="activeClass = $page.url.startsWith('/statistik') ? 'text-teal-700 bg-teal-50/50' : 'hover:text-slate-900'">Statistik</Link>
                    <Link href="/verifikasi" class="px-4 py-2 rounded-full transition duration-200 hover:bg-slate-50" :class="activeClass = $page.url.startsWith('/verifikasi') ? 'text-teal-700 bg-teal-50/50' : 'hover:text-slate-900'">Verifikasi Surat</Link>
                </div>

                <!-- Desktop Call-to-action -->
                <div class="hidden items-center gap-4 sm:flex">
                    <AppButton :href="page.props.auth?.warga ? '/warga/dashboard' : '/login'" variant="primary" class="rounded-full shadow-sm hover:shadow transition-all duration-300">
                        {{ page.props.auth?.warga ? 'Dashboard Warga' : 'Masuk Portal' }}
                    </AppButton>
                </div>

                <!-- Mobile menu toggle -->
                <button class="inline-flex items-center justify-center rounded-xl p-2 text-slate-700 hover:bg-slate-50 sm:hidden transition" @click="mobileMenuOpen = !mobileMenuOpen">
                    <Menu v-if="!mobileMenuOpen" class="size-6" />
                    <X v-else class="size-6" />
                </button>
            </nav>

            <!-- Mobile Menu panel -->
            <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="-translate-y-2 opacity-0"
                enter-to-class="translate-y-0 opacity-100"
                leave-active-class="transition duration-150 ease-in"
                leave-from-class="translate-y-0 opacity-100"
                leave-to-class="-translate-y-2 opacity-0"
            >
                <div v-if="mobileMenuOpen" class="border-t border-slate-100 bg-white px-6 py-6 shadow-xl sm:hidden">
                    <div class="grid gap-2">
                        <Link href="/" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-750 hover:bg-teal-50/60 hover:text-teal-700 transition" @click="mobileMenuOpen = false">Beranda</Link>
                        <Link href="/profil" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-750 hover:bg-teal-50/60 hover:text-teal-700 transition" @click="mobileMenuOpen = false">Profil Desa</Link>
                        <Link href="/informasi" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-750 hover:bg-teal-50/60 hover:text-teal-700 transition" @click="mobileMenuOpen = false">Informasi</Link>
                        <Link href="/statistik" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-750 hover:bg-teal-50/60 hover:text-teal-700 transition" @click="mobileMenuOpen = false">Statistik</Link>
                        <Link href="/verifikasi" class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-750 hover:bg-teal-50/60 hover:text-teal-700 transition" @click="mobileMenuOpen = false">Verifikasi Surat</Link>
                        
                        <hr class="border-slate-100 my-2">
                        
                        <div class="flex flex-col gap-2 pt-1 text-xs text-slate-600 px-4">
                            <span class="flex items-center gap-2"><Phone class="size-4 text-teal-650" /> 0812-3456-7890</span>
                            <span class="flex items-center gap-2"><Mail class="size-4 text-teal-650" /> info@udeung.desa.id</span>
                        </div>
                        
                        <AppButton :href="page.props.auth?.warga ? '/warga/dashboard' : '/login'" class="mt-4 w-full rounded-full" @click="mobileMenuOpen = false">
                            {{ page.props.auth?.warga ? 'Dashboard Warga' : 'Masuk Portal' }}
                        </AppButton>
                    </div>
                </div>
            </Transition>
        </header>

        <!-- Main slot -->
        <main class="flex-grow">
            <slot />
        </main>

        <!-- KOTAK ASPIRASI / NEWSLETTER SECTION (PREMIUM BENTO CARD) -->
        <section id="aspirasi" class="relative z-30 -mb-16 mx-auto max-w-5xl px-6 w-full">
            <div class="bg-slate-900 rounded-3xl shadow-[0_20px_50px_rgba(15,23,42,0.15)] p-8 sm:p-12 flex flex-col lg:flex-row items-center justify-between gap-8 text-white border border-slate-800 relative overflow-hidden">
                <!-- Decorative subtle glow in background -->
                <div class="absolute -right-20 -bottom-20 w-80 h-80 rounded-full bg-teal-500/10 blur-[100px] pointer-events-none" />
                <div class="absolute -left-20 -top-20 w-80 h-80 rounded-full bg-blue-500/10 blur-[100px] pointer-events-none" />

                <div class="space-y-3 text-center lg:text-left flex-1 max-w-xl relative z-10">
                    <span class="text-[10px] font-bold text-teal-400 bg-teal-950/80 border border-teal-800/60 px-3 py-1 rounded-full uppercase tracking-widest inline-block">Layanan Pengaduan</span>
                    <h3 class="text-2xl sm:text-3xl font-extrabold tracking-tight leading-tight">Kotak Aspirasi & Layanan Warga</h3>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed">
                        Kirim saran, masukan, atau kendala pelayanan untuk pembangunan Gampong Udeung yang lebih maju dan transparan.
                    </p>
                </div>
                
                <div class="w-full lg:w-[400px] shrink-0 relative z-10">
                    <form v-if="!suggestionSent" @submit.prevent="sendAspirasi" class="w-full flex gap-2 bg-slate-800/80 p-2 rounded-full border border-slate-700/80 focus-within:border-teal-500 focus-within:ring-2 focus-within:ring-teal-500/25 transition-all duration-300 min-w-0">
                        <input 
                            type="text" 
                            v-model="aspirasiInput"
                            placeholder="Tulis aspirasi atau email Anda..." 
                            class="bg-transparent border-0 focus:ring-0 text-white placeholder-slate-500 grow min-w-0 px-4 py-2 text-sm focus:outline-none"
                            required
                        />
                        <button 
                            type="submit" 
                            class="rounded-full bg-white text-slate-900 hover:bg-teal-50 font-bold px-6 py-2.5 text-xs flex items-center gap-1.5 shrink-0 transition-all duration-200"
                        >
                            Kirim <Send class="size-3" />
                        </button>
                    </form>
                    <div v-else class="flex items-center gap-3 text-teal-200 text-sm font-medium bg-teal-950/50 border border-teal-900/40 py-4 px-6 rounded-3xl animate-fade-in justify-center">
                        <CheckCircle2 class="size-5 text-teal-400 shrink-0" />
                        <span class="leading-relaxed">Aspirasi terkirim. Terima kasih atas partisipasi Anda!</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer dark theme section (Minimalist & Editorial) -->
        <footer class="bg-[#0B0F19] text-slate-400 border-t border-slate-900">
            <div class="mx-auto max-w-7xl px-6 pt-32 pb-16 lg:px-8">
                <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
                    <!-- Column 1: Brand & Info -->
                    <div class="space-y-5">
                        <Link href="/" class="flex items-center gap-2.5">
                            <div class="flex size-10 items-center justify-center rounded-xl bg-teal-550 text-slate-950 shadow-md shadow-teal-500/10">
                                <Landmark class="size-5 text-white" />
                            </div>
                            <div>
                                <span class="text-xl font-bold tracking-tight text-white">Desaku</span>
                                <p class="text-[9px] font-bold tracking-widest text-teal-400 uppercase -mt-0.5">Gampong Udeung</p>
                            </div>
                        </Link>
                        <p class="text-sm leading-relaxed text-slate-400 font-medium">
                            Portal resmi pelayanan publik digital dan keterbukaan informasi Gampong Udeung, Kecamatan Bandar Baru, Pidie Jaya. Layanan mandiri transparan bagi seluruh warga.
                        </p>
                        <div class="space-y-3 pt-2 text-sm text-slate-350">
                            <div class="flex items-start gap-3">
                                <MapPin class="size-4 shrink-0 text-teal-500 mt-1" />
                                <span class="leading-relaxed">Jl. Phoroh, Gampong Udeung, Kec. Bandar Baru, Pidie Jaya, 24184</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <Phone class="size-4 shrink-0 text-teal-500" />
                                <span>0812-3456-7890</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <Mail class="size-4 shrink-0 text-teal-500" />
                                <span>info@udeung.desa.id</span>
                            </div>
                        </div>
                    </div>

                    <!-- Column 2: Navigation Links -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold tracking-widest text-white uppercase border-l-2 border-teal-500 pl-3">Menu Utama</h4>
                        <ul class="mt-4 space-y-2.5 text-sm font-medium">
                            <li><Link href="/" class="hover:text-white transition duration-150">Beranda Utama</Link></li>
                            <li><Link href="/profil" class="hover:text-white transition duration-150">Profil & Lembaga</Link></li>
                            <li><Link href="/informasi" class="hover:text-white transition duration-150">Berita & Informasi</Link></li>
                            <li><Link href="/statistik" class="hover:text-white transition duration-150">Statistik Kependudukan</Link></li>
                            <li><Link href="/verifikasi" class="hover:text-white transition duration-150">Verifikasi Surat / Dokumen</Link></li>
                            <li><Link href="/login" class="hover:text-white transition duration-150">Portal Layanan Warga</Link></li>
                        </ul>
                    </div>

                    <!-- Column 3: Institutional Links -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold tracking-widest text-white uppercase border-l-2 border-teal-500 pl-3">Tautan Resmi</h4>
                        <ul class="mt-4 space-y-2.5 text-sm font-medium">
                            <li><a href="https://kemendesa.go.id" target="_blank" class="hover:text-white transition duration-150 inline-flex items-center gap-1">Kementerian Desa <ArrowRight class="size-3" /></a></li>
                            <li><a href="https://pidiejayakab.go.id" target="_blank" class="hover:text-white transition duration-150 inline-flex items-center gap-1">Pemerintah Kabupaten <ArrowRight class="size-3" /></a></li>
                            <li><a href="https://bps.go.id" target="_blank" class="hover:text-white transition duration-150 inline-flex items-center gap-1">BPS Nasional <ArrowRight class="size-3" /></a></li>
                            <li><a href="#" class="hover:text-white transition duration-150">Kebijakan Privasi</a></li>
                            <li><a href="#" class="hover:text-white transition duration-150">Syarat & Ketentuan</a></li>
                        </ul>
                    </div>

                    <!-- Column 4: Operational & Map CTA -->
                    <div class="space-y-4">
                        <h4 class="text-xs font-bold tracking-widest text-white uppercase border-l-2 border-teal-500 pl-3">Jam Kerja Pelayanan</h4>
                        <div class="text-sm space-y-2.5 font-medium">
                            <p>Senin – Kamis : <span class="text-white">08.00 – 16.30 WIB</span></p>
                            <p>Jumat : <span class="text-white">08.00 – 16.00 WIB</span></p>
                            <p>Sabtu – Minggu : <span class="text-slate-600">Tutup (Hari Libur)</span></p>
                        </div>
                        <div class="pt-2">
                            <a href="https://www.google.com/maps/place/5%C2%B016'39.8%22N+96%C2%B006'08.4%22E/@5.2777317,96.1023468,17z" target="_blank" class="inline-flex items-center justify-center gap-2 rounded-full border border-slate-800 px-5 py-3 text-xs font-semibold text-slate-300 hover:bg-slate-800 hover:text-white transition-all duration-300 w-full">
                                <Globe class="size-3.5 text-teal-400" /> Lihat Peta Lokasi Kantor
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright bar -->
                <div class="mt-16 border-t border-slate-900 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
                    <p>© 2026 Pemerintah Gampong Udeung. Seluruh Hak Cipta Dilindungi.</p>
                    <p class="flex items-center gap-1">Made with <Heart class="size-3 text-red-500 animate-pulse fill-red-500" /> &middot; Didukung oleh <span class="text-teal-400 font-semibold">Desaku Digital Platform</span></p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
.animate-progress {
    animation: progress-indeterminate 1.5s ease-in-out infinite;
}

@keyframes progress-indeterminate {
    0% { transform: translateX(-100%) scaleX(0.5); }
    50% { transform: translateX(100%) scaleX(1); }
    100% { transform: translateX(300%) scaleX(0.5); }
}

.animate-fade-in {
    animation: fadeIn 0.2s ease-out forwards;
}

@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.97); }
    to { opacity: 1; transform: scale(1); }
}
</style>
