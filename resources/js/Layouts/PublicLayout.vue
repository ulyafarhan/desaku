<script setup>
import { inject } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import AppButton from '../Components/AppButton.vue';
import Toast from '../Components/Toast.vue';

const page = usePage();
const progress = inject('pageProgress');
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div v-if="progress?.value" class="fixed left-0 top-0 z-50 h-1 w-full bg-teal-700" />
        <Toast />
        <header class="sticky top-0 z-40 border-b border-slate-200 bg-white/95 backdrop-blur">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <Link href="/" class="text-xl font-bold text-teal-800">Desaku</Link>
                <div class="hidden items-center gap-5 text-sm font-medium text-slate-600 sm:flex">
                    <Link href="/profil" class="hover:text-teal-700">Profil</Link>
                    <Link href="/informasi" class="hover:text-teal-700">Informasi</Link>
                    <AppButton :href="page.props.auth?.warga ? '/warga/dashboard' : '/login'" variant="primary">Ajukan Surat</AppButton>
                </div>
                <AppButton :href="page.props.auth?.warga ? '/warga/dashboard' : '/login'" class="sm:hidden">Layanan</AppButton>
            </nav>
        </header>
        <main>
            <slot />
        </main>
        <footer class="mt-16 border-t border-slate-200 bg-white">
            <div class="mx-auto grid max-w-7xl gap-4 px-4 py-8 text-sm text-slate-500 sm:px-6 lg:px-8">
                <strong class="text-slate-800">Desaku</strong>
                <p>Portal informasi dan layanan administrasi desa yang mudah diakses warga.</p>
            </div>
        </footer>
    </div>
</template>
