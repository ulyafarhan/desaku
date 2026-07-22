<script setup>
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/AppButton.vue';
import { MapPin, ArrowLeft, Clock, ChevronRight } from 'lucide-vue-next';

defineOptions({ layout: PublicLayout });
const props = defineProps({
    fasilitas: Object,
    fasilitasTerbaru: Array,
});

const stripHtml = (html) => {
    if (!html) return '';
    return html.replace(/<[^>]*>/g, ' ').replace(/\s+/g, ' ').trim();
};
</script>

<template>
    <Head>
        <title>{{ fasilitas.nama_fasilitas }} - Fasilitas Gampong Udeung</title>
        <meta name="description" :content="stripHtml(fasilitas.deskripsi).substring(0, 160)" />
        <meta name="keywords" :content="`Fasilitas Desa, ${fasilitas.kategori}, ${fasilitas.nama_fasilitas}, Gampong Udeung, Pidie Jaya`" />
        <meta property="og:title" :content="fasilitas.nama_fasilitas" />
        <meta property="og:description" :content="stripHtml(fasilitas.deskripsi).substring(0, 160)" />
        <meta v-if="fasilitas.foto_url" property="og:image" :content="fasilitas.foto_url" />
    </Head>

    <main class="bg-white min-h-screen py-12">
        <article class="mx-auto max-w-3xl px-6 sm:px-8">
            <AppButton href="/fasilitas" variant="ghost" class="mb-8 gap-2 px-0 text-blue-600 hover:text-blue-700 transition-colors font-medium text-xs">
                <ArrowLeft class="size-4" />
                Kembali ke fasilitas
            </AppButton>

            <div v-if="fasilitas.foto_url" class="mb-8 overflow-hidden rounded-2xl border border-gray-200">
                <img :src="fasilitas.foto_url" :alt="fasilitas.nama_fasilitas" class="aspect-video w-full object-cover" loading="lazy" />
            </div>

            <div class="space-y-4">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="text-[10px] font-bold text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg uppercase tracking-wider block w-fit">
                        {{ fasilitas.kategori }}
                    </span>
                    <span
                        class="text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wider"
                        :class="{
                            'text-green-800 bg-green-50': fasilitas.status === 'Aktif',
                            'text-yellow-800 bg-yellow-50': fasilitas.status === 'Rusak Ringan',
                            'text-red-800 bg-red-50': fasilitas.status === 'Rusak Berat',
                            'text-gray-800 bg-gray-100': fasilitas.status === 'Tidak Aktif',
                        }"
                    >
                        {{ fasilitas.status }}
                    </span>
                </div>

                <h1 class="text-3xl sm:text-4xl font-normal text-gray-900 tracking-tight leading-tight">
                    {{ fasilitas.nama_fasilitas }}
                </h1>

                <div class="flex items-center gap-1.5 text-xs text-gray-500 font-medium">
                    <MapPin class="size-3.5 shrink-0" />
                    {{ fasilitas.lokasi || 'Lokasi tidak tercatat' }}
                </div>

                <div class="flex items-center gap-4 text-xs text-gray-500 font-bold border-y border-gray-100 py-3.5">
                    <span class="inline-flex items-center gap-1.5">
                        <Clock class="size-4" />
                        {{ new Date(fasilitas.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </span>
                </div>
            </div>

            <div class="mt-8 text-slate-700 text-sm sm:text-base leading-relaxed space-y-6 font-medium" v-html="fasilitas.deskripsi" />
        </article>

        <aside v-if="fasilitasTerbaru?.length" class="mx-auto max-w-5xl px-6 sm:px-8 mt-20">
            <h2 class="text-2xl font-normal text-gray-900 tracking-tight mb-6">
                Fasilitas Lainnya
            </h2>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <Link
                    v-for="item in fasilitasTerbaru"
                    :key="item.id"
                    :href="'/fasilitas/' + item.id"
                    class="group block bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition duration-200"
                >
                    <div class="h-36 overflow-hidden bg-gradient-to-br from-blue-500/20 via-cyan-500/10 to-blue-600/20 flex items-center justify-center">
                        <img
                            v-if="item.foto_url"
                            :src="item.foto_url"
                            :alt="item.nama_fasilitas"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        />
                        <MapPin v-else class="size-8 text-blue-800/60 stroke-[1.5]" />
                    </div>
                    <div class="p-4 space-y-1.5">
                        <h3 class="text-sm font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-1">
                            {{ item.nama_fasilitas }}
                        </h3>
                        <p v-if="item.lokasi" class="text-xs text-gray-500 line-clamp-1">
                            {{ item.lokasi }}
                        </p>
                    </div>
                </Link>
            </div>

            <div class="mt-8 text-center">
                <AppButton href="/fasilitas" variant="outline" class="rounded-full gap-1.5 text-xs">
                    Lihat Semua Fasilitas <ChevronRight class="size-3.5" />
                </AppButton>
            </div>
        </aside>
    </main>
</template>
