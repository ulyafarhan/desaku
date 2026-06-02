<script setup>
import { useForm } from '@inertiajs/vue3';
import PublicLayout from '../../Layouts/PublicLayout.vue';
import AppButton from '../../Components/AppButton.vue';
import AppCard from '../../Components/AppCard.vue';
import FormInput from '../../Components/FormInput.vue';

defineOptions({ layout: PublicLayout });

const form = useForm({
    nik: '',
    no_kk: '',
});

const submit = () => form.post('/login');
</script>

<template>
    <section class="mx-auto grid max-w-5xl gap-8 px-4 py-12 sm:px-6 md:grid-cols-[1fr_.9fr] lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase text-teal-700">Portal Layanan Mandiri</p>
            <h1 class="mt-3 text-3xl font-bold text-slate-800 md:text-4xl">Masuk dengan NIK dan Nomor KK.</h1>
            <p class="mt-4 text-slate-500">Data dipakai hanya untuk memverifikasi bahwa Anda warga aktif desa sebelum mengajukan layanan administrasi.</p>
        </div>
        <AppCard>
            <form class="space-y-4" @submit.prevent="submit">
                <FormInput id="nik" v-model="form.nik" label="NIK" required placeholder="16 digit NIK" :error="form.errors.nik" />
                <FormInput id="no_kk" v-model="form.no_kk" label="Nomor KK" required placeholder="16 digit Nomor KK" :error="form.errors.no_kk" />
                <AppButton type="submit" :loading="form.processing" class="w-full">Masuk</AppButton>
            </form>
        </AppCard>
    </section>
</template>
