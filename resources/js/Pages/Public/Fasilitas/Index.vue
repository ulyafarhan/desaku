<script setup>
import { Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppCard from '@/Components/AppCard.vue';
import Pagination from '@/Components/Pagination.vue';
import EmptyState from '@/Components/EmptyState.vue';
import { MapPin } from 'lucide-vue-next';

const props = defineProps({
    fasilitas: Object,
    kategoriList: Array,
    selectedKategori: String,
});
</script>

<template>
    <PublicLayout title="Fasilitas Desa">
        <section class="py-12">
            <div class="max-w-7xl mx-auto px-4">
                <h1 class="text-3xl font-bold text-center mb-4">Fasilitas Desa</h1>
                <p class="text-gray-600 text-center mb-8 max-w-2xl mx-auto">
                    Daftar fasilitas umum yang tersedia di Gampong Udeung
                </p>

                <!-- Filter Kategori -->
                <div class="flex flex-wrap gap-2 justify-center mb-8">
                    <Link href="/fasilitas"
                          class="px-4 py-2 rounded-full text-sm font-medium transition"
                          :class="!selectedKategori
                              ? 'bg-blue-600 text-white'
                              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                        Semua
                    </Link>
                    <Link v-for="k in kategoriList" :key="k"
                          :href="`/fasilitas?kategori=${encodeURIComponent(k)}`"
                          class="px-4 py-2 rounded-full text-sm font-medium transition"
                          :class="selectedKategori === k
                              ? 'bg-blue-600 text-white'
                              : 'bg-gray-100 text-gray-700 hover:bg-gray-200'">
                        {{ k }}
                    </Link>
                </div>

                <!-- Grid Fasilitas -->
                <div v-if="fasilitas.data.length" class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <AppCard v-for="item in fasilitas.data" :key="item.id"
                             class="overflow-hidden">
                        <template #image>
                            <div v-if="item.foto_url" class="w-full h-48 overflow-hidden">
                                <img :src="item.foto_url" :alt="item.nama_fasilitas"
                                     class="w-full h-full object-cover">
                            </div>
                            <div v-else class="w-full h-48 bg-slate-100 flex items-center justify-center">
                                <MapPin class="w-10 h-10 text-slate-300" />
                            </div>
                        </template>
                        <template #content>
                            <h3 class="text-lg font-bold mb-1">{{ item.nama_fasilitas }}</h3>
                            <div class="flex items-center gap-1 text-sm text-gray-500 mb-2">
                                <MapPin class="w-4 h-4 shrink-0" />
                                <span>{{ item.lokasi || '-' }}</span>
                            </div>
                            <span class="inline-block px-2 py-1 text-xs font-medium rounded-full"
                                  :class="{
                                      'bg-green-100 text-green-800': item.status === 'Aktif',
                                      'bg-yellow-100 text-yellow-800': item.status === 'Rusak Ringan',
                                      'bg-red-100 text-red-800': item.status === 'Rusak Berat',
                                      'bg-gray-100 text-gray-800': item.status === 'Tidak Aktif',
                                  }">
                                {{ item.status }}
                            </span>
                            <p v-if="item.deskripsi" class="mt-2 text-sm text-gray-600 line-clamp-3">
                                {{ item.deskripsi }}
                            </p>
                        </template>
                    </AppCard>
                </div>

                <EmptyState v-else
                    title="Belum ada fasilitas"
                    description="Belum ada data fasilitas desa yang tersedia." />

                <Pagination v-if="fasilitas.hasPages" :links="fasilitas.links" class="mt-8" />
            </div>
        </section>
    </PublicLayout>
</template>