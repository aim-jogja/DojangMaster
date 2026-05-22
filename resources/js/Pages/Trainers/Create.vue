<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    dojangs: Array,
});

const form = useForm({
    dojang_id: props.dojangs.length === 1 ? props.dojangs[0].id : '',
    name: '',
    email: '',
    password: '',
});

const submit = () => {
    form.post(route('trainers.store'));
};
</script>

<template>
    <Head title="Tambah Trainer" />

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
                    Tambah Trainer
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">
                    Tambah Trainer
                </h1>

                <p class="text-sm text-gray-500 mt-0.5">
                    Buat akun pelatih baru dan hubungkan dengan dojang
                </p>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Data Trainer
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Lengkapi identitas trainer dan akses login
                    </p>
                </div>

                <div class="p-5 space-y-5">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </label>

                        <select
                            v-model="form.dojang_id"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="">Pilih dojang</option>
                            <option
                                v-for="dojang in dojangs"
                                :key="dojang.id"
                                :value="dojang.id"
                            >
                                {{ dojang.name }}
                            </option>
                        </select>

                        <div v-if="form.errors.dojang_id" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.dojang_id }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Trainer
                            </label>

                            <input
                                v-model="form.name"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Contoh: Ahmad Hakim"
                            />

                            <div v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.name }}
                            </div>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Email
                            </label>

                            <input
                                v-model="form.email"
                                type="email"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="trainer@email.com"
                            />

                            <div v-if="form.errors.email" class="mt-1.5 text-xs font-medium text-red-600">
                                {{ form.errors.email }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Password
                        </label>

                        <input
                            v-model="form.password"
                            type="password"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Masukkan password akun trainer"
                        />

                        <div v-if="form.errors.password" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4">
                        <div class="flex items-start gap-3">
                            <div class="w-9 h-9 rounded-xl bg-white border border-indigo-100 flex items-center justify-center text-indigo-600 shrink-0">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="w-4 h-4"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M13.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m.75 0h7.5A2.25 2.25 0 0115 12.75v6A2.25 2.25 0 0112.75 21h-7.5A2.25 2.25 0 013 18.75v-6a2.25 2.25 0 012.25-2.25z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-gray-900">
                                    Akun login trainer
                                </p>
                                <p class="text-sm text-gray-600 mt-0.5">
                                    Trainer akan menggunakan email dan password ini untuk masuk ke sistem.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('trainers.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg
                            v-if="form.processing"
                            class="w-4 h-4 animate-spin"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>

                        {{ form.processing ? 'Menyimpan...' : 'Simpan Trainer' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>