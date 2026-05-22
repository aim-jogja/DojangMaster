<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    category: Object,
});

const statusLabel = (value) => value ? 'Aktif' : 'Nonaktif';
</script>

<template>
    <Head title="Detail Kategori Pembayaran" />

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
                    Detail Kategori Pembayaran
                </h2>
            </div>
        </template>

        <div class="space-y-5">
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 truncate">
                        {{ category.name }}
                    </h1>

                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ category.dojang?.name || '-' }}
                    </p>
                </div>

                <span
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium rounded-full border shrink-0"
                    :class="category.is_active
                        ? 'bg-green-50 text-green-700 border-green-100'
                        : 'bg-gray-50 text-gray-600 border-gray-100'"
                >
                    <span
                        class="w-1.5 h-1.5 rounded-full"
                        :class="category.is_active ? 'bg-green-500' : 'bg-gray-400'"
                    ></span>

                    {{ statusLabel(category.is_active) }}
                </span>
            </div>

            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between gap-4">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-800">
                            Informasi Kategori
                        </h3>
                        <p class="text-xs text-gray-400 mt-0.5">
                            Detail kategori pembayaran dan dojang terkait
                        </p>
                    </div>

                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-bold uppercase shrink-0">
                        {{ category.name?.charAt(0) ?? 'K' }}
                    </div>
                </div>

                <div class="p-5">
                    <dl class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Nama Kategori
                            </dt>
                            <dd class="mt-1 text-sm font-semibold text-gray-900">
                                {{ category.name ?? '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Dojang
                            </dt>

                            <dd class="mt-2 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-white border border-gray-200 flex items-center justify-center text-gray-500 font-semibold text-xs uppercase shrink-0">
                                    {{ category.dojang?.name?.charAt(0) ?? '-' }}
                                </div>

                                <span class="text-sm font-semibold text-gray-900">
                                    {{ category.dojang?.name || '-' }}
                                </span>
                            </dd>
                        </div>

                        <div class="md:col-span-2 rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Deskripsi
                            </dt>
                            <dd class="mt-1 whitespace-pre-line text-sm font-medium leading-6 text-gray-900">
                                {{ category.description || '-' }}
                            </dd>
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/60 p-4">
                            <dt class="text-xs font-semibold uppercase tracking-widest text-gray-400">
                                Status
                            </dt>
                            <dd class="mt-2">
                                <span
                                    class="inline-flex items-center gap-1.5 rounded-full border px-2.5 py-1 text-xs font-medium"
                                    :class="category.is_active
                                        ? 'bg-green-50 text-green-700 border-green-100'
                                        : 'bg-gray-50 text-gray-600 border-gray-100'"
                                >
                                    <span
                                        class="w-1.5 h-1.5 rounded-full"
                                        :class="category.is_active ? 'bg-green-500' : 'bg-gray-400'"
                                    ></span>

                                    {{ statusLabel(category.is_active) }}
                                </span>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2">
                <Link
                    :href="route('payment-categories.index')"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white text-gray-700 text-sm font-medium rounded-lg border border-gray-200 hover:bg-gray-50 transition-colors"
                >
                    Kembali
                </Link>

                <Link
                    :href="route('payment-categories.edit', category.id)"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-indigo-700 active:scale-95 transition-all"
                >
                    Edit
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>