<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { reactive } from 'vue';

const props = defineProps({
    categories: Object,
    filters: Object,
    dojangs: Array,
});

const page = usePage();

const form = reactive({
    search: props.filters.search || '',
    dojang_id: props.filters.dojang_id || '',
    status: props.filters.status || '',
});

const applyFilter = () => {
    router.get(route('payment-categories.index'), form, {
        preserveState: true,
        preserveScroll: true,
    });
};

const resetFilter = () => {
    router.get(route('payment-categories.index'));
};

const destroyCategory = (category) => {
    if (!confirm(`Hapus kategori "${category.name}"?`)) return;

    router.delete(route('payment-categories.destroy', category.id), {
        preserveScroll: true,
    });
};

const statusLabel = (value) => value ? 'Aktif' : 'Nonaktif';

const statusClass = (value) => {
    return value
        ? 'bg-green-50 text-green-700 border-green-100'
        : 'bg-gray-50 text-gray-600 border-gray-100';
};

const statusDotClass = (value) => {
    return value ? 'bg-green-500' : 'bg-gray-400';
};
</script>

<template>
    <Head title="Kategori Pembayaran" />

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
                        d="M9 14.25l6-6m-5.5.5h.01m4.99 4.99h.01M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"
                    />
                </svg>

                <h2 class="font-semibold text-gray-800 text-base truncate">
                    Kategori Pembayaran
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        Data Kategori Pembayaran
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        Kelola jenis pembayaran siswa berdasarkan dojang dan status kategori
                    </p>
                </div>

                <Link
                    :href="route('payment-categories.create')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all shrink-0"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-4 h-4"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2.5"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>

                    Tambah Kategori
                </Link>
            </div>

            <div
                v-if="page.props.flash?.success"
                class="bg-green-50 border border-green-100 text-green-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.success }}
            </div>

            <div
                v-if="page.props.flash?.error"
                class="bg-red-50 border border-red-100 text-red-700 text-sm font-medium px-4 py-3 rounded-xl shadow-sm"
            >
                {{ page.props.flash.error }}
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100">
                    <h3 class="text-sm font-semibold text-gray-800">
                        Filter Kategori
                    </h3>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Cari kategori berdasarkan nama, dojang, atau status aktif
                    </p>
                </div>

                <div class="p-5">
                    <div class="grid gap-4 md:grid-cols-4">
                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Cari
                            </label>

                            <input
                                v-model="form.search"
                                type="text"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Nama kategori"
                                @keyup.enter="applyFilter"
                            />
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dojang
                            </label>

                            <select
                                v-model="form.dojang_id"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Dojang</option>
                                <option
                                    v-for="dojang in dojangs"
                                    :key="dojang.id"
                                    :value="dojang.id"
                                >
                                    {{ dojang.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="mb-1.5 block text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Status
                            </label>

                            <select
                                v-model="form.status"
                                class="w-full rounded-lg border-gray-200 text-sm text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Semua Status</option>
                                <option value="active">Aktif</option>
                                <option value="inactive">Nonaktif</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <button
                                type="button"
                                @click="applyFilter"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg bg-gray-900 px-4 py-2.5 text-sm font-medium text-white shadow-sm transition-all hover:bg-gray-800 active:scale-95 md:w-auto"
                            >
                                Filter
                            </button>

                            <button
                                type="button"
                                @click="resetFilter"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-lg border border-gray-200 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 md:w-auto"
                            >
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Daftar Kategori
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Menampilkan kategori pembayaran yang terdaftar di sistem
                        </p>
                    </div>

                    <span class="text-xs text-gray-400 shrink-0">
                        {{ categories.data.length }} item
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50">
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Kategori
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Dojang
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400">
                                    Status
                                </th>
                                <th class="px-5 py-3.5 text-xs font-semibold uppercase tracking-widest text-gray-400 text-right">
                                    Action
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            <tr
                                v-for="category in categories.data"
                                :key="category.id"
                                class="hover:bg-gray-50/70 transition-colors"
                            >
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3 min-w-[220px]">
                                        <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm uppercase shrink-0">
                                            {{ category.name?.charAt(0) ?? 'K' }}
                                        </div>

                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 truncate">
                                                {{ category.name }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5 truncate">
                                                {{ category.description || 'Tidak ada deskripsi' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2 min-w-[160px]">
                                        <div class="w-7 h-7 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                            {{ category.dojang?.name?.charAt(0) ?? '-' }}
                                        </div>

                                        <span class="font-medium text-gray-700 truncate">
                                            {{ category.dojang?.name || '-' }}
                                        </span>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium"
                                        :class="statusClass(category.is_active)"
                                    >
                                        <span
                                            class="w-1.5 h-1.5 rounded-full"
                                            :class="statusDotClass(category.is_active)"
                                        ></span>

                                        {{ statusLabel(category.is_active) }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link
                                            :href="route('payment-categories.show', category.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-lg hover:bg-indigo-100 transition-colors"
                                        >
                                            Detail
                                        </Link>

                                        <Link
                                            :href="route('payment-categories.edit', category.id)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-gray-600 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
                                        >
                                            Edit
                                        </Link>

                                        <button
                                            type="button"
                                            @click="destroyCategory(category)"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-colors"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <tr v-if="categories.data.length === 0">
                                <td colspan="4" class="py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="w-10 h-10 text-gray-300"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                            stroke-width="1.5"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M9 14.25l6-6m-5.5.5h.01m4.99 4.99h.01M6 3h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2z"
                                            />
                                        </svg>

                                        <p class="text-sm font-medium">
                                            Belum ada kategori pembayaran
                                        </p>

                                        <Link
                                            :href="route('payment-categories.create')"
                                            class="text-indigo-600 text-sm hover:underline"
                                        >
                                            Tambah kategori pertama
                                        </Link>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="categories.links?.length > 3"
                    class="border-t border-gray-100 px-5 py-3 flex items-center justify-between gap-4 flex-wrap"
                >
                    <p class="text-xs text-gray-400">
                        Menampilkan {{ categories.from }}–{{ categories.to }} dari {{ categories.total }} data
                    </p>

                    <div class="inline-flex items-center gap-1">
                        <template v-for="link in categories.links" :key="link.label">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    'px-3 py-1.5 text-xs rounded-lg border transition-colors',
                                    link.active
                                        ? 'bg-indigo-600 text-white border-indigo-600'
                                        : 'bg-white text-gray-600 border-gray-200 hover:bg-gray-50'
                                ]"
                            />

                            <span
                                v-else
                                v-html="link.label"
                                class="px-3 py-1.5 text-xs rounded-lg border border-gray-100 text-gray-300 bg-gray-50 cursor-not-allowed"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>