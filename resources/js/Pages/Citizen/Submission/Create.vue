<script setup>
import { computed, reactive, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import CitizenLayout from '../../../Layouts/CitizenLayout.vue';
import AppButton from '../../../Components/AppButton.vue';
import AppCard from '../../../Components/AppCard.vue';
import FormInput from '../../../Components/FormInput.vue';
import FormSelect from '../../../Components/FormSelect.vue';
import StepIndicator from '../../../Components/StepIndicator.vue';

defineOptions({ layout: CitizenLayout });

const props = defineProps({ kategori: Object });
const current = ref(0);
const steps = ['Data Isian', 'Dokumen', 'Tinjauan'];
const fields = computed(() => props.kategori.schema_isian || []);
const documents = computed(() => props.kategori.syarat_dokumen || []);

const initialData = {};
fields.value.forEach((field) => {
    initialData[field.field] = '';
});

const initialFiles = {};
documents.value.forEach((document) => {
    initialFiles[document.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '')] = '';
});

const form = useForm({
    kategori_surat_id: props.kategori.id,
    data_isian: reactive(initialData),
    file_syarat: reactive(initialFiles),
});

const next = () => {
    current.value = Math.min(current.value + 1, steps.length - 1);
};

const previous = () => {
    current.value = Math.max(current.value - 1, 0);
};

const submit = () => form.post('/warga/surat/pengajuan');
</script>

<template>
    <div class="mx-auto max-w-3xl space-y-6">
        <div>
            <p class="text-sm font-semibold text-teal-700">{{ kategori.kode_surat }}</p>
            <h1 class="mt-1 text-3xl font-bold text-slate-800">{{ kategori.nama_surat }}</h1>
        </div>
        <StepIndicator :steps="steps" :current="current" />

        <AppCard>
            <form class="space-y-5" @submit.prevent="submit">
                <div v-if="current === 0" class="space-y-4">
                    <template v-for="field in fields" :key="field.field">
                        <FormSelect
                            v-if="field.type === 'select'"
                            :id="field.field"
                            v-model="form.data_isian[field.field]"
                            :label="field.label"
                            :options="field.options || []"
                            :required="field.required"
                            :error="form.errors[`data_isian.${field.field}`]"
                        />
                        <label v-else-if="field.type === 'textarea'" :for="field.field" class="block">
                            <span class="mb-1.5 block text-sm font-semibold text-slate-700">{{ field.label }} <span v-if="field.required" class="text-red-600">*</span></span>
                            <textarea :id="field.field" v-model="form.data_isian[field.field]" rows="4" class="block w-full rounded-md border border-slate-200 px-3 py-2.5 text-base focus:border-teal-500 focus:ring-teal-500" />
                        </label>
                        <FormInput
                            v-else
                            :id="field.field"
                            v-model="form.data_isian[field.field]"
                            :type="field.type === 'number' ? 'number' : field.type === 'date' ? 'date' : 'text'"
                            :label="field.label"
                            :required="field.required"
                            :error="form.errors[`data_isian.${field.field}`]"
                        />
                    </template>
                </div>

                <div v-if="current === 1" class="space-y-4">
                    <p class="text-sm text-slate-500">Masukkan tautan dokumen hasil unggah atau lokasi berkas yang dapat diverifikasi petugas.</p>
                    <FormInput
                        v-for="document in documents"
                        :id="document"
                        :key="document"
                        v-model="form.file_syarat[document.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '')]"
                        :label="document"
                        placeholder="https://..."
                        required
                        :error="form.errors[`file_syarat.${document.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '')}`]"
                    />
                </div>

                <div v-if="current === 2" class="space-y-4 text-sm">
                    <h2 class="text-lg font-semibold text-slate-800">Tinjau Pengajuan</h2>
                    <dl class="grid gap-3">
                        <div v-for="field in fields" :key="field.field" class="rounded-md bg-slate-50 p-3">
                            <dt class="font-semibold text-slate-700">{{ field.label }}</dt>
                            <dd class="text-slate-600">{{ form.data_isian[field.field] || '-' }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="flex flex-wrap justify-between gap-3 border-t border-slate-200 pt-5">
                    <AppButton v-if="current > 0" type="button" variant="outline" @click="previous">Kembali</AppButton>
                    <span v-else />
                    <AppButton v-if="current < 2" type="button" @click="next">Lanjut</AppButton>
                    <AppButton v-else type="submit" :loading="form.processing">Kirim Pengajuan</AppButton>
                </div>
            </form>
        </AppCard>
    </div>
</template>
