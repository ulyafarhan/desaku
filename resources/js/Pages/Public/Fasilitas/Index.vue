<script setup>
import { ref } from 'vue';
import { router, Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/AppButton.vue';
import EmptyState from '@/Components/EmptyState.vue';
import Pagination from '@/Components/Pagination.vue';
import { MapPin, Search, Clock, X } from 'lucide-vue-next';

defineOptions({ layout: PublicLayout });

const props = defineProps({
    fasilitas: Object,
    kategoriList: Array,
    selectedKategori: String,
    filters: Object,
});

const activeKategori = ref(props.selectedKategori || '');
const searchQuery = ref(props.filters?.search || '');

function triggerFilter() {
    router.get('/fasilitas', {
        kategori: activeKategori.value || undefined,
        search: searchQuery.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
}

function filterByKategori(kategori) {
    activeKategori.value = kategori;
    triggerFilter();
}

function clearSearch() {
    searchQuery.value = '';
    triggerFilter();
}
</script>

<template>
    <Head>
        <title>Fasilitas Desa - Gampong Udeung, Pidie Jaya, Aceh</title>
        <meta name="description" content="Daftar fasilitas umum yang tersedia di Gampong Udeung, Pidie Jaya. Informasi sarana ibadah, pendidikan, kesehatan, dan fasilitas umum lainnya." />
        <meta name="keywords" content="Fasilitas Desa, Sarana Umum, Gampong Udeung, Pidie Jaya, Fasilitas Gampong" />
        <meta property="og:title" content="Fasilitas Desa - Gampong Udeung, Pidie Jaya, Aceh" />
        <meta property="og:description" content="Daftar fasilitas umum yang tersedia di Gampong Udeung, Pidie Jaya." />
    </Head>

    <header class="bg-white border-b border-gray-200 py-16">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="max-w-3xl space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 uppercase tracking-wider">
                    <MapPin class="size-3.5" /> Sarana & Prasarana
                </span>

                <h1 class="text-4xl sm:text-5xl font-normal text-gray-900 tracking-tight leading-tight">
                    Fasilitas Desa
                </h1>

                <p class="text-base sm:text-lg text-gray-500 font-normal leading-relaxed">
                    Daftar fasilitas umum, sarana ibadah, pendidikan, kesehatan, dan prasarana lain yang tersedia di Gampong Udeung.
                </p>

                <div class="max-w-md pt-4">
                    <form @submit.prevent="triggerFilter" class="flex items-center bg-gray-50 rounded-lg p-1.5 border border-gray-300 focus-within:border-blue-600 focus-within:bg-white transition duration-200">
                        <div class="pl-2.5 text-gray-400 shrink-0">
                            <Search class="size-4" />
                        </div>
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Cari fasilitas..."
                            class="bg-transparent text-gray-900 text-sm grow px-2 py-2"
                            style="outline: none !important; box-shadow: none !important; border: none !important;"
                        />
                        <button
                            v-if="searchQuery"
                            type="button"
                            @click="clearSearch"
                            class="p-1.5 text-gray-400 hover:text-gray-700 shrink-0 rounded"
                        >
                            <X class="size-4" />
                        </button>
                        <AppButton
                            type="submit"
                            class="rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 text-xs transition shrink-0"
                        >
                            Cari
                        </AppButton>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <section class="mx-auto max-w-7xl px-6 py-12 lg:px-8 bg-gray-50 min-h-[500px]">
        <div class="space-y-3">
            <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block">Kategori Fasilitas:</span>
            <div class="flex flex-wrap gap-2">
                <button
                    class="rounded-full border px-4 py-1.5 text-xs font-semibold transition"
                    :class="!activeKategori ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-300 bg-white text-gray-500 hover:border-gray-400'"
                    @click="filterByKategori('')"
                >
                    Semua
                </button>
                <button
                    v-for="k in kategoriList"
                    :key="k"
                    class="rounded-full border px-4 py-1.5 text-xs font-semibold transition"
                    :class="activeKategori === k ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-300 bg-white text-gray-500 hover:border-gray-400'"
                    @click="filterByKategori(k)"
                >
                    {{ k }}
                </button>
            </div>
        </div>

        <div v-if="fasilitas.data?.length" class="mt-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <Link
                v-for="item in fasilitas.data"
                :key="item.id"
                :href="'/fasilitas/' + item.id"
                class="group block bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg transition duration-200"
            >
                <div class="relative h-48 w-full overflow-hidden bg-gradient-to-br from-blue-500/20 via-cyan-500/10 to-blue-600/20 flex items-center justify-center">
                    <img
                        v-if="item.foto_url"
                        :src="item.foto_url"
                        :alt="item.nama_fasilitas"
                        class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                    />
                    <div v-else class="flex flex-col items-center justify-center text-blue-800/60 p-4 text-center">
                        <MapPin class="size-10 mb-2 stroke-[1.5]" />
                        <span class="text-xs font-semibold tracking-wide uppercase opacity-70">{{ item.kategori || 'Fasilitas' }}</span>
                    </div>

                    <div class="absolute bottom-3 left-3">
                        <span
                            class="text-[10px] font-bold tracking-wider uppercase bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-lg shadow-sm"
                            :class="{
                                'text-green-800': item.status === 'Aktif',
                                'text-yellow-800': item.status === 'Rusak Ringan',
                                'text-red-800': item.status === 'Rusak Berat',
                                'text-gray-800': item.status === 'Tidak Aktif',
                            }"
                        >
                            {{ item.status }}
                        </span>
                    </div>
                </div>

                <div class="p-6 space-y-3">
                    <div class="flex items-center gap-3 text-[10px] text-gray-400 font-bold">
                        <span class="flex items-center gap-1">
                            <Clock class="size-3" />
                            {{ new Date(item.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </span>
                    </div>

                    <h2 class="text-base font-bold text-gray-900 leading-snug group-hover:text-blue-600 transition duration-200 line-clamp-2">
                        {{ item.nama_fasilitas }}
                    </h2>

                    <div class="flex items-center gap-1 text-xs text-gray-500">
                        <MapPin class="w-3.5 h-3.5 shrink-0" />
                        <span class="line-clamp-1">{{ item.lokasi || '-' }}</span>
                    </div>

                    <p v-if="item.deskripsi" class="text-xs text-gray-600 line-clamp-3 leading-relaxed font-medium">
                        {{ item.deskripsi }}
                    </p>
                </div>
            </Link>
        </div>

        <EmptyState
            v-else
            class="mt-10 bg-white"
            title="Tidak ditemukan fasilitas"
            message="Kami tidak menemukan fasilitas dengan kata kunci atau kategori pencarian tersebut."
            :icon="MapPin"
        >
            <AppButton @click="clearSearch" variant="outline" class="rounded-full">
                Reset Pencarian
            </AppButton>
        </EmptyState>

        <div class="mt-12">
            <Pagination v-if="fasilitas.meta" :links="fasilitas.links" :meta="fasilitas.meta" />
        </div>
    </section>
</template>
