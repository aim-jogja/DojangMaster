<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    category: Object,
    dojangs: Array,
});

const form = useForm({
    dojang_id: props.category.dojang_id,
    name: props.category.name,
    description: props.category.description || '',
    is_active: Boolean(props.category.is_active),
});

const submit = () => {
    form.put(route('payment-categories.update', props.category.id));
};
</script>

<template>
    <Head title="Edit Kategori Pembayaran" />

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
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M19.5 7.125L16.875 4.5M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Edit Kategori Pembayaran
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        Edit {{ category.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Perbarui kategori pembayaran, dojang, deskripsi, dan status aktif
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="form.is_active
                        ? 'bg-green-50 text-green-700 border-green-100'
                        : 'bg-gray-50 text-gray-600 border-gray-100'"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="form.is_active ? 'bg-green-500' : 'bg-gray-400'"
                    ></span>

                    {{ form.is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Ringkasan Kategori
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Data kategori saat ini sebelum diperbarui
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ category.name?.charAt(0) ?? 'K' }}
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nama
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900 truncate">
                            {{ category.name ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Dojang
                        </p>

                        <div class="mt-2 flex items-center gap-2 min-w-0">
                            <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                {{ category.dojang?.name?.charAt(0) ?? '-' }}
                            </div>

                            <p class="text-sm font-semibold text-gray-900 truncate">
                                {{ category.dojang?.name || '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <p class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Status
                        </p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">
                            {{ category.is_active ? 'Aktif' : 'Nonaktif' }}
                        </p>
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden"
            >
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Form Edit Kategori
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Sesuaikan data kategori pembayaran yang perlu diperbarui
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
                            <option value="">Pilih Dojang</option>
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

                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                            Nama Kategori
                        </label>

                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />

                        <div v-if="form.errors.name" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.name }}
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
                        />

                        <div v-if="form.errors.description" class="mt-1.5 text-xs font-medium text-red-600">
                            {{ form.errors.description }}
                        </div>
                    </div>

                    <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <label for="is_active" class="text-sm font-semibold text-gray-800">
                                    Status Kategori
                                </label>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    Ubah status aktif atau nonaktif kategori pembayaran
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

                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50/50 flex items-center justify-end gap-2">
                    <Link
                        :href="route('payment-categories.index')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                    >
                        Batal
                    </Link>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        {{ form.processing ? 'Mengupdate...' : 'Update Kategori' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>