<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    owners: Array,
});

const form = useForm({
    user_id: '',
    name: '',
    address: '',
    domain: '',
    phone: '',
    is_active: true,
});

const submit = () => {
    form.post(route('dojangs.store'));
};
</script>

<template>
    <Head title="Tambah Dojang" />

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
                    Tambah Dojang
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Tambah Dojang
                </h1>

                <p class="text-sm text-gray-500 mt-0.5">
                    Buat data dojang baru dan hubungkan dengan owner
                </p>
            </div>

            <form @submit.prevent="submit" class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Data Dojang
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Lengkapi informasi dasar dojang
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Owner
                        </label>

                        <select
                            v-model="form.user_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih owner</option>
                            <option
                                v-for="owner in owners"
                                :key="owner.id"
                                :value="owner.id"
                            >
                                {{ owner.name }} - {{ owner.email }}
                            </option>
                        </select>

                        <div v-if="form.errors.user_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.user_id }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Dojang
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: Dojang Bantul"
                            />

                            <div v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Domain
                            </label>

                            <input
                                v-model="form.domain"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="contoh: dojang-bantul"
                            />

                            <div v-if="form.errors.domain" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.domain }}
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Telepon
                            </label>

                            <input
                                v-model="form.phone"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: 08123456789"
                            />

                            <div v-if="form.errors.phone" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.phone }}
                            </div>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <label for="is_active" class="text-sm font-semibold text-gray-800">
                                        Status Dojang
                                    </label>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Aktifkan dojang agar dapat digunakan
                                    </p>
                                </div>

                                <input
                                    id="is_active"
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="mt-1 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                />
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Alamat
                        </label>

                        <textarea
                            v-model="form.address"
                            rows="4"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Masukkan alamat lengkap dojang"
                        />

                        <div v-if="form.errors.address" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.address }}
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('dojangs.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>