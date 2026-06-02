<script setup>
import { inject } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppButton from '../Components/AppButton.vue';
import Toast from '../Components/Toast.vue';

const progress = inject('pageProgress');
const logout = () => router.post('/logout');
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <div v-if="progress?.value" class="fixed left-0 top-0 z-50 h-1 w-full bg-teal-700" />
        <Toast />
        <header class="border-b border-slate-200 bg-white">
            <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                <Link href="/warga/dashboard" class="text-lg font-bold text-teal-800">Layanan Warga</Link>
                <div class="flex items-center gap-2">
                    <AppButton href="/" variant="ghost">Portal</AppButton>
                    <AppButton variant="outline" @click="logout">Keluar</AppButton>
                </div>
            </nav>
        </header>
        <main class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <slot />
        </main>
    </div>
</template>
