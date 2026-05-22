<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    code: '',
    name: '',
    description: '',
    is_active: true,
})

const submit = () => {
    form.post(route('features.store'))
}
</script>

<template>
    <Head title="Tambah Feature" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-2 min-w-0">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-indigo-500 shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Tambah Feature
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Tambah Feature
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Tambahkan fitur SaaS baru yang nantinya dapat dipilih pada subscription plan
                    </p>
                </div>

                <Link
                    :href="route('features.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors shrink-0"
                >
                    Kembali
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Data Feature
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Isi kode, nama, deskripsi, dan status aktif feature
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Code
                            </label>

                            <input
                                v-model="form.code"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="contoh: student_payment"
                            />

                            <p class="mt-1.5 text-xs text-gray-400">
                                Gunakan huruf kecil, angka, underscore, atau dash. Code ini harus sama dengan kode fitur di aplikasi.
                            </p>

                            <p v-if="form.errors.code" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.code }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Feature
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="contoh: Student Payment"
                            />

                            <p v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.name }}
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Deskripsi
                        </label>

                        <textarea
                            v-model="form.description"
                            rows="4"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Jelaskan fungsi fitur ini..."
                        />

                        <p v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800">
                                    Status Feature
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Aktifkan feature agar dapat dipilih pada subscription plan.
                                </p>
                            </div>

                            <input
                                id="is_active"
                                v-model="form.is_active"
                                type="checkbox"
                                class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                        </div>

                        <p v-if="form.errors.is_active" class="mt-2 text-xs font-medium text-red-600">
                            {{ form.errors.is_active }}
                        </p>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('features.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Feature' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>